<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\RoleServices;
use Auth, Permission;

class SettingsController extends Controller{

	/*
	 * Lưu cài đặt
	 */
	public function updateSettings(Request $request){
		Permission::required('admin');
		$response = [
			'errors'      => [],
			'status_code' => 200,
			'data'        => []
		];
		try {
			// Lưu lịch sử thao tác
			$oldValue = [];
			foreach( $request->all() as $key => $value){
				$oldValue[$key] = \Option::get($key);
			}
            \App\Services\LogServices::new([
                'category'     => 'settings',
                'action'       => 'update_setting',
                'action_color' => 'warning', // success, info, warning, danger
                'action_value' => array_key_first( $request->all() ),
                'old_value'    => $oldValue,
                'new_value'    => $request->all()
            ]);
			\Option::update( $request->all() );
			// Cập nhật các file css, js chứ nội dung php
			foreach(glob_recursive( public_path('assets/*.php.*') ) as $file){
				ob_start();
				require $file;
				$data = ob_get_clean();
				file_put_contents( str_replace('.php.', '.', $file), \App\Helpers\Assets::cssMinifier($data) );
			}
		} catch (\Exception $e) {
			$response = [
				'errors'      => ['system' => $e->getMessage()],
				'status_code' => 500,
				'data'        => []
			];
		}
		return response()->json($response, $response['status_code']);
	}

	/*
	 * Trang cài đặt chung
	 */
	public function generalSettings(){
		Permission::required('admin');
		$data = [];
		$data['include'] = 'general';
		return view('pages.admin.settings.settings-layout', $data);
	}

	/*
	 * Trang cài giao diện
	 */
	public function themeSettings(){
		Permission::required('admin');
		$data = [];
		$data['include'] = 'theme';
		return view('pages.admin.settings.settings-layout', $data);
	}

	/*
	 * Trang thiết lập trang quản lý
	 */
	public function adminSettings(){
		Permission::required('admin');
		$data = [];
		$data['include'] = 'admin';
		return view('pages.admin.settings.settings-layout', $data);
	}

	/*
	 * Trang chỉnh sửa ngôn ngữ
	 */
	public function language(){
		Permission::required('admin');
		$data = [];
		return view('pages.admin.settings.language', $data);
	}

	/*
	 * Cập nhật ngôn ngữ
	 */
	public function updateLanguage(Request $request){
		Permission::required('admin');
		$langFilePath = base_path('resources/lang/'.$request->get('lang', config('app.locale') ).'/'.$request->get('path').'.php');
		if( !file_exists($langFilePath) ){
			$error = __('admin/language.update_error');
		}
		if( empty($error) ){
			try {
				// Lưu lịch sử thao tác
	            \App\Services\LogServices::new([
	                'category'     => 'language',
	                'action'       => 'update_language',
	                'action_color' => 'warning', // success, info, warning, danger
	                'action_value' => $request->get('path'),
	                'old_value'    => include $langFilePath,
	                'new_value'    => $request->data ?? []
	            ]);
	            $pathSplit = explode('/', $request->get('path') );
	            $jsFilePath = public_path('assets/lang/'.basename( $request->get('path').'.js' ) );
	        	if( empty($request->data) ){
					unlink($langFilePath);
					if( file_exists($jsFilePath) ){
						unlink($jsFilePath);
					}
				}else{
					putArrayToFile($langFilePath, $request->data);
					if( $pathSplit[0] == 'js' ){
						// Lưu file js
						file_put_contents(
							$jsFilePath,
							"if( typeof _lang == 'undefined' ){ _lang = {}; }
							_lang['{$pathSplit[1]}'] = ".json_encode($request->data)
						);
					}
				}
				return redirect()->back()->with('notify', ['success' => __('admin/language.update_success')]);
			} catch (\Exception $e) {
				$error = __('admin/language.update_error').$e->getMessage();
			}
		}
		return redirect()->back()->with('notify', ['error' => $error]);
	}

	/*
	 * Trang chỉnh sửa thông tin các trang
	 */
	public function pageInfo(){
		Permission::required('admin');
		$data = [];
		return view('pages.admin.settings.page-info', $data);
	}

	/*
	 * Cập nhật thông tin các trang
	 */
	public function updatePageInfo(Request $request){
		Permission::required('admin');
		try {
			$oldValue = $newValue = [];
			foreach($request->data as $name => $item){
				$name = str_replace('___', '/', $name);
				$path = config_path('pages/'.$name.'.php');
				$oldValue[$name] = include $path;
				$newValue[$name] = $item;
				putArrayToFile($path, $item);
			}
			// Lưu lịch sử thao tác
            \App\Services\LogServices::new([
                'category'     => 'pages_info',
                'action'       => 'update_pages_info',
                'action_color' => 'warning', // success, info, warning, danger
                'action_value' => '',
                'old_value'    => $oldValue,
                'new_value'    => $newValue
            ]);
			return response()->json(['errors' => [], 'status_code' => 200, 'data' => [] ]);
		} catch (\Exception $e) {
			return response()->json(['errors' => ['system' => [$e->getMessage()]], 'status_code' => 200, 'data' => [] ]);	
		}
	}

	/*
	 * Trang phân quyền & chức vụ
	 */
	public function permission(){
		Permission::required('admin');
		$data = [];
		return view('pages.admin.settings.permission', $data);
	}

	/*
	 * Cập nhật tên quyền
	 */
	public function updatePermissionsName(Request $request){
		Permission::required('admin');
		$path = config_path('storage/permissions_name.php');
		$oldValue = include $path;
		putArrayToFile($path, $request->all());
		// Lưu lịch sử thao tác
        \App\Services\LogServices::new([
        	'category'     => 'permission',
			'action'       => 'update_permission_name',
			'action_color' => 'warning', // success, info, warning, danger
			'action_value' => '',
			'old_value'    => $oldValue,
			'new_value'    => $request->all()
        ]);
		return redirect()->back()->with('notify', ['success' => __('general.update_success')]);
	}

	/*
	 * Thêm hoặc sửa chức vụ
	 */
	public function updateRole(\App\Http\Requests\admin\UpdateRoleRequest $request){
		Permission::required('admin');
		$response = [
			'errors'      => [],
			'status_code' => 200,
			'data'        => []
		];
		try {
			$response['data'] = RoleServices::storeRole( $request->all() );
			return response()->json($response, $response['status_code']);
		} catch (\Exception $e) {
			$response['errors'] = ['system' => $e->getMessage()];
			$response['status_code'] = 500;
			return response()->json($response, $response['status_code']);
		}
	}

	/*
	 * Xóa chức vụ
	 */
	public function deleteRole(\App\Http\Requests\admin\DeleteRoleRequest $request){
		Permission::required('admin');
		$response = [
			'errors'      => [],
			'status_code' => 200,
			'data'        => []
		];
		try {
			RoleServices::deleteRole($request->id, $request->move_to);
			return response()->json($response, $response['status_code']);
		} catch (\Exception $e) {
			$response['errors'] = ['system' => $e->getMessage()];
			$response['status_code'] = 500;
			return response()->json($response, $response['status_code']);
		}
	}

	/*
	 * Thiết lập quyền cho chức vụ
	 */
	public function setPermissionsForRole(\App\Http\Requests\admin\SetPermissionsForRoleRequest $request){
		Permission::required('admin');
		$response = [
			'errors'      => [],
			'status_code' => 200,
			'data'        => []
		];
		try {
			RoleServices::setPermissionsForRole($request->id, $request->permissions);
			return response()->json($response, $response['status_code']);
		} catch (\Exception $e) {
			$response['errors'] = ['system' => $e->getMessage()];
			$response['status_code'] = 500;
			return response()->json($response, $response['status_code']);
		}
	}
}