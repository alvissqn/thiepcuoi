<?php
namespace App\Http\Middleware;

use App\User;
class UserLogin
{
     public function handle($request, \Closure $next)
    {
        $privateKey = explode('.', request()->cookie('auth_private_key') );
    	if( !empty( $privateKey[1] ) ){
    		// Set id tài khoản đã đăng nhập
	    	$request->logged_user_data = \App\Services\UserServices::get( $privateKey[0], null, $privateKey[1] );
	    	if( $request->logged_user_data ){
                \App\Services\UserDataServices::update([
                    'last_online' => time()
                ], $request->logged_user_data->id);
	    	}
    	}
    	return $next($request);
    }
}
