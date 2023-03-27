<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

class LogoutController extends Controller{

	/*
	 * Đăng xuất tài khoản
	 */
	public function logout(){
		if( request()->cookie('auth_private_key_admin') ){
			// Khôi phục phiên đăng nhập TK admin
			$cookie      = cookie('auth_private_key', request()->cookie('auth_private_key_admin'), config('user.login_session_time') );
			$cookieAdmin = cookie('auth_private_key_admin', null, 0);
			return redirect()->route('admin.users.list')->withCookie($cookie)->withCookie($cookieAdmin);
		}else{
			// Lưu lịch sử thao tác
			\App\Services\LogServices::new([
				'user_id'      => null,
				'category'     => 'account',
				'action'       => 'logout_account',
				'action_color' => 'warning', // success, info, warning, danger
				'action_value' => '',
				'old_value'    => [],
				'new_value'    => []
			]);
			
			$cookie = cookie('auth_private_key', null, 0);
			return redirect('/')->withCookie($cookie);
		}
	}

}