<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\UserServices;
use App\Services\UserDataServices;
use Auth;

class RegisterController extends Controller{

	/*
	 * Trang đăng ký
	 */
	public function register(){
		if( Auth::check() ){
			return redirect()->route('home');
		}
		$data = [];
		$data['config'] = [
			'search_engine_index' => false, // Cho phép bot lập chỉ mục
			'load_js_language'    => [], // Các gói ngôn ngữ muốn chuyển sang JS, VD: ['user/register', 'user.general']
		];
		return view('pages/user/register', $data);
	}

	/*
	 * API tạo tài khoản mới
	 */
	public function createAccount(\App\Http\Requests\user\CreateAccountRequest $request){
		$response = [
			'errors'      => [],
			'status_code' => 200,
			'data'        => []
		];
		$data = $request->only(['name', 'phone_number', 'email', 'password']);
		try {
			$response['data'] = UserServices::store($data);
			if( !empty( session('user_register_data')['social_type'] ) ){
				UserDataServices::update([
					session('user_register_data')['social_type'] => session('user_register_data')['social_id']
				], $response['data']->id);
			}
			$privateCookie    = cookie('auth_private_key', UserServices::createAuthPrivateKey($response['data']->id, $response['data']->password), config('user.login_session_time') );
			request()->session()->forget('user_register_data');
			return response()->json($response, $response['status_code'])->withCookie( $privateCookie );
		} catch (\Exception $e) {
			$response['errors'] = ['system' => $e->getMessage()];
			$response['status_code'] = 500;
			return response()->json($response, $response['status_code']);
		}
	}

}