<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Services\UserServices;
use App\Services\UserDataServices;
use App\Services\NotificationServices;
use Auth, Permission;

class LoginController extends Controller{

	/*
	 * Trang đăng nhập
	 */
	public function login(){
		if( \Auth::check() ){
			return redirect()->route('home');
		}
		$data = [];
		$data['config'] = [
			'search_engine_index' => false, // Thẻ robots
			'load_js_language'    => [], // Các gói ngôn ngữ muốn chuyển sang JS, VD: ['user/register', 'user.general']
		];
		return view('pages/user/login', $data);
	}

	/*
	 * Ấn nút đăng nhập
	 */
	public function loginSubmit(Request $request){
		$redirectUrl = '/admin';
		$response = [
			'errors'      => [],
			'data'        => [
				'redirect_url' => $redirectUrl
			],
			'status_code' => 200
		];
		$getUser = User::where('email', $request->email ?? null)->first();
		if( $getUser ){
			$userData = UserDataServices::get(null, $getUser->id);
			$userBlockTime = ($userData->login_failed_time ?? 0) - time() + (config('user.block_login_failed_seconds') * 60);
			if( ($userData->login_failed_count ?? 0) >= config('user.block_login_failed_times') && $userBlockTime > 0 ){
				// Đăng nhập bị khóa
				$response['errors']['password'] = [__('user/login.login_blocked', ['seconds' => $userBlockTime])];
			}else if( $getUser->status == config('user.status.blocked') ){
				// Tài khoản bị khóa
				$response['errors']['blocked'] = ['Tài khoản đang bị tạm khóa'];
			}else{
				if( UserServices::verifyPassword($request->password ?? null, $getUser->password) ){
					// Mật khẩu chính xác
					$privateCookie = cookie('auth_private_key', UserServices::createAuthPrivateKey($getUser->id, $getUser->password), config('user.login_session_time') );
					UserDataServices::update(['login_failed_count' => 0], $getUser->id);
				}else{
					// Mật khẩu sai
					$response['errors']['password'] = [__('user/login.login_failed')];
					UserDataServices::update([
						'login_failed_count' => ($userData->login_failed_count ?? 0) + 1,
						'login_failed_time'  => time()
					], $getUser->id);
					// Lưu lịch sử thao tác
					\App\Services\LogServices::new([
						'user_id'      => $getUser->id,
						'category'     => 'account',
						'action'       => 'login_failed',
						'action_color' => 'danger', // success, info, warning, danger
						'action_value' => ($userData->login_failed_count ?? 0),
						'old_value'    => [],
						'new_value'    => [
							'ip'      => $_SERVER['REMOTE_ADDR']  ?? null,
							'browser' => $_SERVER['HTTP_USER_AGENT'] ?? null
						]
					]);
				}
			}
		}else{
			$response['errors']['password'] = [__('user/login.login_failed')];
		}
		if( empty($response['errors']) ){
			// Đăng nhập thành công

			// Lưu lịch sử thao tác
			\App\Services\LogServices::new([
				'user_id'      => $getUser->id,
				'category'     => 'account',
				'action'       => 'login_with_password',
				'action_color' => 'warning', // success, info, warning, danger
				'action_value' => $getUser->email,
				'old_value'    => [],
				'new_value'    => [
					'ip'      => $_SERVER['REMOTE_ADDR']  ?? null,
					'browser' => $_SERVER['HTTP_USER_AGENT'] ?? null
				]
			]);
			return response()->json($response, $response['status_code'])->withCookie( $privateCookie );
		}else{
			// Đăng nhập thất bại
			return response()->json($response, $response['status_code']);
		}
	}

	/*
	 * Click đăng nhập qua FB,GG
	 */
	public function loginWithSocial($service){
		return \Socialite::driver($service)->redirect();
	}

	/*
	 * Lấy dữ liệu FB, GG trả về
	 */
	public function loginWithSocialCallback($service){
		$redirectUrl = '/admin';
		try {
			$data = \Socialite::with($service)->user();
			if( empty($data->name) ){
				throw new \Exception("Error Processing Request", 1);
			}else{
				$social_id        = $data->id;
				$social_id_prefix = 'social_'.$service.'_id';
				$data->email = $data->email ?? $data->id;
				$getUser = User::where('email', $data->email)->first();
				$dataToStore = [
					'name'            => $data->name,
					'email'           => $data->email,
					'gender'          => config('user.gender.code')[$data->user['gender'] ?? 'unknown'],
					'password'        => randomString(30)
				];
				// Cập nhật lại avatar
				$avatarContent = file_get_contents_curl($data->avatar);
				if( strlen($avatarContent) > 500 ){
					$avatarFile = config('user.avatars_path').md5($data->email).'.png';
					file_put_contents( public_path($avatarFile), $avatarContent);
				}

				if( $getUser ){
					// Đã tồn tại tài khoản
					unset($dataToStore['password']);
					$getUser->update($dataToStore);
					UserDataServices::update([
						$social_id_prefix => $social_id
					], $getUser->id);
					// Lưu cookie đăng nhập
					$privateCookie = cookie('auth_private_key', UserServices::createAuthPrivateKey($getUser->id, $getUser->password), config('user.login_session_time') );

					// Lưu lịch sử thao tác
					\App\Services\LogServices::new([
						'user_id'      => $getUser->id,
						'category'     => 'account',
						'action'       => 'login_with_social_'.$service,
		                'action_color' => 'warning', // success, info, warning, danger
		                'action_value' => $getUser->email,
		                'old_value'    => [],
		                'new_value'    => [
							'ip'      => $_SERVER['REMOTE_ADDR']  ?? null,
							'browser' => $_SERVER['HTTP_USER_AGENT'] ?? null
						]
		            ]);
					return redirect($redirectUrl)->withCookie($privateCookie);
				}else{
					// Đăng ký mới
					if( isset($avatarFile) ){
						$dataToStore['avatar'] = $avatarFile;
					}
					$dataToStore['social_type'] = $social_id_prefix;
					$dataToStore['social_id']   = $social_id;
					session(['user_register_data' => $dataToStore]);
					return redirect()->route('user.register');
				}
			}
		} catch (\Exception $e) {
			return redirect('/')->with('notify', ['error' => __('user/register.login_with_social_failed').': '.$e->getMessage()] );
		}
	}
}