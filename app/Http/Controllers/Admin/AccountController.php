<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\UserServices;
use App\Services\UserDataServices;
use App\Services\NotificationServices;
use App\User;
use Permission, Auth;

class AccountController extends Controller{

	/*
	 * Trang thông tin tài khoản
	 */
	public function profile(){
		Permission::required('member');
		$data = [];
		$data['userData'] = UserDataServices::get( null, Auth::id() );
		return view('pages.admin.account.profile', $data);
	}

	/*
	 * Trang sửa thông tin tài khoản
	 */
	public function editProfile(){
		Permission::required('member');
		$data = [];
		$data['userData'] = UserDataServices::get( null, Auth::id() );
		return view('pages.admin.account.edit-profile', $data);
	}

	/*
	 * Ấn nút cập nhật thông tin tài khoản
	 */
	public function editProfileSubmit(\App\Http\Requests\user\UpdateProfileRequest $request){
		Permission::required('member');
		$response = [
			'errors'      => [],
			'status_code' => 200,
			'data'        => []
		];
		try {
			$data     = $request->only(['id', 'name', 'phone_number', 'email', 'gender']);
			$userData = $request->only(['address', 'birth_day', 'job_title']);
			if( Permission::has('admin') && isset($request->role_id) ){
				$data['role_id'] = $request->role_id;
			}
			// Lưu lịch sử thao tác
			$getUser = User::find($data['id']);
			\App\Services\LogServices::new([
				'user_id'      => null,
				'category'     => 'account',
				'action'       => 'update_profile',
				'action_color' => 'warning', // success, info, warning, danger
				'action_value' => $data['name'].' - '.$data['email'],
				'old_value'    => $getUser->toArray(),
				'new_value'    => array_merge($data, $userData)
			]);

			// Lưu dữ liệu tài khoản
			$response['data'] = UserServices::store($data);
			if( !empty($userData) ){
				UserDataServices::update($userData, $data['id']);
			}

			session()->flash('notify', ['success' => __('general.update_success')]);
			return response()->json($response, $response['status_code']);
		} catch (\Exception $e) {
			$response['errors'] = ['system' => $e->getMessage()];
			$response['status_code'] = 500;
			return response()->json($response, $response['status_code']);
		}
	}

	/*
	 * Trang đổi mật khẩu
	 */
	public function changePassword(){
		Permission::required('member');
		$data = [];
		return view('pages.admin.account.change-password', $data);
	}

	/*
	 * Ấn nút đổi mật khẩu
	 */
	public function changePasswordSubmit(\App\Http\Requests\user\ChangePasswordRequest $request){
		Permission::required('member');
		$response = [
			'errors'      => [],
			'status_code' => 200,
			'data'        => []
		];
		try {
			// Lưu lịch sử thao tác
			\App\Services\LogServices::new([
				'user_id'      => null,
				'category'     => 'account',
				'action'       => 'change_password',
				'action_color' => 'danger', // success, info, warning, danger
				'action_value' => '',
				'old_value'    => [],
				'new_value'    => []
			]);
			User::find( Auth::id() )->update([
				'password' => UserServices::hashPassword($request->password)
			]);
			session()->flash('notify', ['success' => __('general.update_success')]);
			return response()->json($response, $response['status_code']);
		} catch (\Exception $e) {
			$response['errors'] = ['system' => $e->getMessage()];
			$response['status_code'] = 500;
			return response()->json($response, $response['status_code']);
		}
	}

	/*
	 * Trang thay đổi avatar
	 */
	public function changeAvatar(){
		Permission::required('member');
		$data = [];
		return view('pages.admin.account.change-avatar', $data);
	}

	/*
	 * Ấn nút đổi avatar
	 */
	public function changeAvatarSubmit(\App\Http\Requests\user\ChangeAvatarRequest $request){
		Permission::required('member');
		$response = [
			'errors'      => [],
			'status_code' => 200,
			'data'        => []
		];
		try {
			// Lưu lịch sử thao tác
			\App\Services\LogServices::new([
				'user_id'      => null,
				'category'     => 'account',
				'action'       => 'change_avatar',
				'action_color' => 'warning', // success, info, warning, danger
				'action_value' => '',
				'old_value'    => [],
				'new_value'    => []
			]);
			$file = $request->avatar;
			$fileName = md5( Auth::user()->email ).'.png';
			$file->move( public_path().config('user.avatars_path'), $fileName);

			session()->flash('notify', ['success' => __('general.update_success')]);
			return response()->json($response, $response['status_code']);
		} catch (\Exception $e) {
			$response['errors'] = ['system' => $e->getMessage()];
			$response['status_code'] = 500;
			return response()->json($response, $response['status_code']);
		}
	}

	/*
	 * Ấn nút xoay avatar
	 */
	public function changeAvatarRotate(){
		Permission::required('member');
		$response = [
			'errors'      => [],
			'status_code' => 200,
			'data'        => []
		];
		try {
			$avatarPath = public_path().config('user.avatars_path').md5( Auth::user()->email ).'.png';
			if( file_exists($avatarPath) ){
				// Xoay ảnh
				\App\Helpers\Image::rotate($avatarPath);
			}

			return response()->json($response, $response['status_code']);
		} catch (\Exception $e) {
			$response['errors'] = ['system' => $e->getMessage()];
			$response['status_code'] = 500;
			return response()->json($response, $response['status_code']);
		}
	}

	/*
	 * Trang thông báo
	 */
	public function notifications(){
		Permission::required('member');
		$data = [];
		$data['getNotifications'] = NotificationServices::getNotificationsByUserId([
			'userId'       => \Auth::id(),
			'limit'        => 500,
			'replace_name' => true
		]);
		return view('pages.admin.account.notifications', $data);
	}

	/*
	 * Trang lịch sử hoạt động
	 */
	public function logs(){
		Permission::required('member');
		$data = [];
		$data['getLogs'] = \App\Log::where('user_id', Auth::id() )
			->orderBy('id', 'DESC')
			->paginate( paginationLimit() );
		return view('pages.admin.account.logs', $data);
	}

}