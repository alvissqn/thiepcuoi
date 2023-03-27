<?php
namespace App\Services;

use App\Role as RoleModel;
use App\RolePermission;
use Illuminate\Support\Facades\Hash;

class RoleServices{
    /*
     * Thêm hoặc tạo chức vụ
     */
    public static function storeRole($data){
        // Tiến hành lưu dữ liệu
        \DB::beginTransaction();
        try {
            // Lưu lịch sử thao tác
            if( empty($data['id']) ){
                \App\Services\LogServices::new([
                    'category'     => 'permission',
                    'action'       => 'create_role',
                    'action_color' => 'info', // success, info, warning, danger
                    'action_value' => $data['name'],
                    'old_value'    => [],
                    'new_value'    => $data
                ]);
            }else{
                \App\Services\LogServices::new([
                    'category'     => 'permission',
                    'action'       => 'update_role',
                    'action_color' => 'warning', // success, info, warning, danger
                    'action_value' => $data['name'],
                    'old_value'    => RoleModel::find($data['id']),
                    'new_value'    => $data
                ]);
            }
            $data = RoleModel::updateOrCreate(['id' => $data['id'] ?? null ], $data);
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            // Lỗi hệ thống, ajax request sẽ kết nối lại
            throw $e;
        }
        return $data;
    }

    /*
     * Xóa chức vụ
     */
    public static function deleteRole($roleId, $roleIdNew){
        // Tiến hành lưu dữ liệu
        \DB::beginTransaction();
        try {
            $data = RoleModel::find($roleId)->toArray();
            // Lưu lịch sử thao tác
            \App\Services\LogServices::new([
                'category'     => 'permission',
                'action'       => 'delete_role',
                'action_color' => 'danger', // success, info, warning, danger
                'action_value' => $data['name'],
                'old_value'    => array_merge( $data, ['move_to' => RoleModel::find($roleIdNew)->name]),
                'new_value'    => []
            ]);

            // Tiến hành xóa
            \App\User::where('role_id', $roleId)->update(['role_id' => $roleIdNew]);
            RoleModel::find($roleId)->role_permissions()->detach();
            RoleModel::destroy($roleId);
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            // Lỗi hệ thống, ajax request sẽ kết nối lại
            throw $e;
        }
        return true;
    }

    /*
     * Lấy danh sách các quyền của 1 chức vụ
     */
    public static function getPermissionsByRoleId($roleId){
        $data = [];
        foreach(RolePermission::select('permission_name')->where('role_id', $roleId)->get() as $item){
            $data[$item->permission_name] = 1;
        }
        // Gắn quyền admin & member mặc định
        if( $roleId > 0 ){
            $data['member'] = 1;
        }
        if( $roleId == config('user.roles.code.admin') ){
            $data['admin'] = 1;
        }
        return $data;
    }

    /*
     * Set quyền cho 1 chức vụ
     */
    public static function setPermissionsForRole($roleId, $permissions = []){
        // Tiến hành lưu dữ liệu
        \DB::beginTransaction();
        try {
            $getRole = RoleModel::find($roleId);
            $getRole->role_permissions()->sync($permissions);

            // Lưu lịch sử thao tác
            \App\Services\LogServices::new([
                'category'     => 'permission',
                'action'       => 'set_permission_for_role',
                'action_color' => 'warning', // success, info, warning, danger
                'action_value' => $getRole->name,
                'old_value'    => RolePermission::select('permission_name')->where('role_id', $roleId)->get(),
                'new_value'    => $permissions
            ]);
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            // Lỗi hệ thống, ajax request sẽ kết nối lại
            throw $e;
        }
        return true;
    }
}
