<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

class LoginWithPrivateKeyController extends Controller{

	/*
	 * Đăng nhập nhanh qua private key
	 */
	public function loginWithPrivateKey($key){
		$privateKey = explode('.', $key);
    	if( !empty( $privateKey[1] ) ){
	    	$getUser = \App\Services\UserServices::get( $privateKey[0], null, $privateKey[1] );
	    	if( $getUser ){

	    		// Thông tin đăng nhập chính xác
				$cookieAdmin = cookie('auth_private_key_admin', request()->cookie('auth_private_key'), 1000);
				$cookie      = cookie('auth_private_key', $key, 1000);
				return redirect('/admin')
					->with('notify', ['success' => 'Logged as: '.$getUser->name])
					->withCookie($cookie)
					->withCookie($cookieAdmin);	
	    	}
    	}
    	return redirect('/');
	}

}