<?php
namespace App\Services;

use App\User as UserModel;
use Illuminate\Support\Facades\Hash;
use Auth;

class UserServices
{
	private static $items = [];

	/*
	 * Lấy thông tin của tài khoản
	 */
	public static function get($userId = null, $column = null, $password = null){
		if( count(self::$items) > 5 ){
			// Xóa các bản ghi cache cũ
			array_splice(self::$items, 0, -5);
		}
		$userId = $userId ?? Auth::id() ;
		if( empty($userId) ){
			return;
		}
		if( Auth::id() == $userId ){
			self::$items['u'.$userId] = Auth::user();
		}
		self::$items['u'.$userId] = self::$items['u'.$userId] ?? UserModel::select(
				'users.*',
				'roles.name as role_name',
				'roles.color as role_color'
			)
			->where('users.id', $userId)
			->leftJoin('roles', 'roles.id', '=', 'users.role_id')
			->first();
		if( $password && $password != md5(self::$items['u'.$userId]->password) || empty(self::$items['u'.$userId]) ){
			return null;
		}
		if( self::$items['u'.$userId] && !isset(self::$items['u'.$userId]->permissions) ){
    		self::$items['u'.$userId]->permissions = RoleServices::getPermissionsByRoleId(self::$items['u'.$userId]->role_id);
    	}
		switch($column){
			case 'display_name': // Tên hiển thị
				self::$items['u'.$userId]->display_name = '
					<a href="/" target="_blank" style="color: '.self::$items['u'.$userId]->role_color.'; font-weight: bold">
						'.self::$items['u'.$userId]->name.'
					</a>
				';
			break;
		}
    	return self::$items['u'.$userId]->$column ?? self::$items['u'.$userId];
	}

	/*
	 * Thêm hoặc cập nhật tài khoản mới
	 */
	public static function store($data){
    	// Tiến hành lưu dữ liệu
    	\DB::beginTransaction();
    	try {
			if( empty($data['id']) ){
				// Tạo mới
				$data['role_id'] = $data['role_id'] ?? 2;
				$data['password']         = self::hashPassword($data['password']);
	    		$data = UserModel::create($data);
			}else{
				// Cập nhật
				$getUser = UserModel::find($data['id']);
				if( $getUser->email != ($data['email'] ?? null) ){
					// Nếu đổi email

					// Đổi file avatar
					$avatarPath = public_path().config('user.avatars_path');
					$avatarOld = $avatarPath.md5($getUser->email).'.png';
					if( file_exists($avatarOld) ){
						rename($avatarOld, $avatarPath.md5($data['email']).'.png' );
					}
				}
				$data = $getUser->update($data);
			}
    		\DB::commit();
    	} catch (\Exception $e) {
    		\DB::rollBack();
    		// Lỗi hệ thống, ajax request sẽ kết nối lại
    		throw $e;
    	}
    	return $data;
	}


	/*
	 * Tạo mã đăng nhập cho tài khoản
	 */
	public static function createAuthPrivateKey($userId, $password){
		return $userId.'.'.md5($password);
	}

	/*
	 * Mã hóa mật khẩu
	 */
	public static function hashPassword($password){
		return Hash::make( config('user.salt_password').$password );
	}

	/*
	 * Kiểm tra mật khẩu đúng không không
	 */
	public static function verifyPassword($password, $hashedPassword){
		if( Hash::check( config('user.salt_password').$password, $hashedPassword) ) {
		    return true;
		}
		return false;
	}

	/*
	 * Lấy trạng thái online tài khoản
	 */
	public static function activeStatus($userId){
		$lastOnline = UserDataServices::get('last_online', $userId);
		if( $lastOnline > strtotime('-2 minutes') ){
			return 'online';
		}
		return 'offline';
	}

	/*
	 * Lấy ảnh avatar
	 */
	public static function avatar($idOremail = null){
		$idOremail = $idOremail ?? Auth::user()->email ?? null;
		if( is_numeric($idOremail) ){
			$idOremail = self::get($idOremail, 'email');
		}
		$avatarPath = config('user.avatars_path') . md5($idOremail).'.png';
		if( file_exists( public_path( $avatarPath ) ) ){
			return $avatarPath;
		}
		return \Option::get('settings__user_default_avatar', '/files/users/avatars/default.png');
	}

	/*
	 * Hiện ảnh avatar
	 */
	public static function showAvatar($userId, $size = '45px'){
		return '
			<span class="avatar">
				<img src="'.self::avatar($userId).'" style="width: '.$size.'; height: '.$size.'">
				<span class="avatar-status-'.self::activeStatus($userId).'"></span>
			</span>
		';
	}
}
