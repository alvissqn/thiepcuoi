<?php
namespace App\Http\Middleware;
use Illuminate\Support\Facades\Cookie;
/*
 * Kiểm tra quyền đăng nhập
 */
use Closure;
use App\User;

class Auth
{
     public function handle($request, Closure $next)
    {
    	if( \Auth::check() ){
    		if( \Auth::user()->status == config('user.status.blocked') ){
    			// Tài khoản bị khóa
    			$cookie = cookie('auth_private_key', null, 0);
    			return redirect()->route('user.login')->with('notify', ['error' => 'Tài khoản này đang bị tạm khóa'])->withCookie($cookie);
    		}
	    	return $next($request);
    	}
    	return redirect()->route('user.login')->with('notify', ['error' => __('user/general.please_login')]);
    }
}
