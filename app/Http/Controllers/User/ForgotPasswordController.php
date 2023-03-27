<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\UserServices;
use App\Services\UserDataServices;
use App\User;
use Auth;

class ForgotPasswordController extends Controller{

	/*
	 * Trang lấy lại mật khẩu
	 */
	public function forgotPassword(){
		if( Auth::check() ){
			return redirect()->route('home');
		}
		$data = [];
		$data['config'] = [
			'search_engine_index' => false, // Thẻ robots
			'load_js_language'    => [], // Các gói ngôn ngữ muốn chuyển sang JS, VD: ['user/register', 'user.general']
		];
		return view('pages/user/forgot-password', $data);
	}

	/*
	 * Ấn nút lấy lại mật khẩu
	 */
	public function forgotPasswordSubmit(\App\Http\Requests\user\ForgotPasswordRequest $request){
		$response = [
			'errors'      => [],
			'data'        => [
				'message' => __('user/forgot-password.email_sent')
			],
			'status_code' => 200
		];
		$getUser = User::where('email', $request->email)->first();
		$resetPasswordKey = UserDataServices::get('reset_password_key', $getUser->id);
		if( empty( $resetPasswordKey ) ){
			try {
				$getUser->reset_password_key = hash('sha256', $getUser->email.randomString(60) );
				\Mail::send('mail.forgot-password', ['data' => $getUser], function($message) use($getUser) {
					$message->to([$getUser->email])
						->subject( __('user/forgot-password.send_mail_subject', ['name' => $getUser->name]) );
				});
				UserDataServices::update(['reset_password_key' => $getUser->reset_password_key], $getUser->id);
			} catch (\Exception $e) {
				$response['errors']['send_mail'] = [__('user/forgot-password.send_mail_failed').': '.$e->getMessage()];
			}
		}
		return response()->json($response, $response['status_code']);
	}
	
	/*
	 * Trang đổi lại mật khẩu
	 */
	public function resetPassword($key){
		if( Auth::check() ){
			return redirect()->route('home');
		}
		if( !\App\UserData::where('key', 'reset_password_key')->where('value', $key)->exists() ){
			return redirect()->route('user.login')->with('notify', ['error' => __('user/reset-password.private_key_incorrect')]);
		}
		$data = [];
		$data['config'] = [
			'search_engine_index' => false, // Thẻ robots
			'load_js_language'    => [], // Các gói ngôn ngữ muốn chuyển sang JS, VD: ['user/register', 'user.general']
		];
		return view('pages/user/reset-password', $data);
	}

	/*
	 * Ấn nút đổi lại mật khẩu
	 */
	public function resetPasswordSubmit(\App\Http\Requests\user\ResetPasswordRequest $request){
		$response = [
			'errors'      => [],
			'data'        => [
				'redirect_url' => route('home')
			],
			'status_code' => 200
		];
		$userId = \App\UserData::where('key', 'reset_password_key')
			->where('value', $request->reset_password_key)
			->value('user_id');
		$getUser = User::find($userId);
		if( $getUser ){
			try {
				$getUser->update([
					'password' => UserServices::hashPassword($request->password),
				]);
				UserDataServices::update([
					'login_failed_count' => 0,
					'reset_password_key' => null
				], $userId);
				$privateCookie = cookie('auth_private_key', UserServices::createAuthPrivateKey($getUser->id, $getUser->password), config('user.login_session_time') );
				return response()->json($response, $response['status_code'])->withCookie( $privateCookie );
			} catch (\Exception $e) {
				$response = [
					'errors'      => [
						'system' => $e->getMessage()
					],
					'data'        => [],
					'status_code' => 500
				];
			}
		}
		return response()->json($response, $response['status_code']);
	}

}