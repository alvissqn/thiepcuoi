<?php
/*
 * Quyền truy cập của tài khoản
 */

use Illuminate\Http\Exceptions\HttpResponseException;

class Permission{
	
	/*
	 * Kiểm tra quyền của tài khoản
	 */
	public static function has($name, $userId = null){
		$permissionsForUser = \App\Services\UserServices::get($userId, 'permissions');
		foreach( explode('|', $name) as $pName ){
			if( !in_array($pName, ['member', 'admin']) && empty( config('storage.permissions_name.'.$pName) ) ){
				// Thêm tên quyền nếu chưa tồn tại
				$data = config('storage.permissions_name');
				$data[$pName] = ucfirst($pName);
				$configPath = config_path('storage/permissions_name.php');
				putArrayToFile($configPath, $data);
			}
			if( isset($permissionsForUser[$pName]) ){
				return true;
			}
		}
		return false;
	}

	/*
	 * Chuyển hướng nếu không có quyền
	 */
	public static function required($name, $redirectUrl = null){
		if( !self::has($name) ){
			if( $redirectUrl ){
				echo '
					<script>
						alert("'.__('admin/general.your_account_is_incompetence').'");
						location.href = "'.$redirectUrl.'";
					</script>
				';
			}else{
				throw new HttpResponseException(
		            response()->json([
		            	'errors' => [
		            		'auth' => [__('admin/general.your_account_is_incompetence')]
		            	]
		            ], 200)
		        );
			}
			die;
		}
	}
}