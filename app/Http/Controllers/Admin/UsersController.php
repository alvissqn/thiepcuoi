<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Services\UserServices;
use App\Services\UserDataServices;
use \App\Services\NotificationServices;
use Permission, Auth;

class UsersController extends Controller{

	/*
	 * Trang danh sách tài khoản
	 */
	public function list(){
		Permission::required('admin');
		$data = [];
		return view('pages.admin.users.list', $data);
	}

	/*
	 * Thêm tài khoản mới
	 */
	public function addUser(\App\Http\Requests\user\AddUserRequest $request){
		$response = [
			'errors'      => [],
			'status_code' => 200,
			'data'        => []
		];
		try {
			$data = $request->only(['name', 'phone_number', 'email', 'role_id', 'password']);
			// Lưu lịch sử thao tác
			\App\Services\LogServices::new([
				'user_id'      => null,
				'category'     => 'user_manager',
				'action'       => 'add_user',
				'action_color' => 'warning', // success, info, warning, danger
				'action_value' => $data['name'].' - '.$data['email'],
				'old_value'    => [],
				'new_value'    => $data
			]);
			$response['data'] = UserServices::store($data);
			return response()->json($response, $response['status_code']);
		} catch (\Exception $e) {
			$response['errors'] = ['system' => $e->getMessage()];
			$response['status_code'] = 500;
			return response()->json($response, $response['status_code']);
		}
	}

	/*
	 * Cập nhật thông tin tài khoản
	 */
	public function updateProfile(\App\Http\Requests\user\UpdateProfileRequest $request){
		$response = [
			'errors'      => [],
			'status_code' => 200,
			'data'        => []
		];
		try {
			$data = $request->only(['id', 'name', 'phone_number', 'email', 'status']);
			if( Permission::has('admin') && isset($request->role_id) ){
				$data['role_id'] = $request->role_id;
			}
			// Lưu lịch sử thao tác
			$getUser = User::find($data['id']);
			\App\Services\LogServices::new([
				'user_id'      => null,
				'category'     => 'user_manager',
				'action'       => 'update_profile',
				'action_color' => 'warning', // success, info, warning, danger
				'action_value' => $data['name'].' - '.$data['email'],
				'old_value'    => $getUser->toArray(),
				'new_value'    => $data
			]);
			$response['data'] = UserServices::store($data);
			return response()->json($response, $response['status_code']);
		} catch (\Exception $e) {
			$response['errors'] = ['system' => $e->getMessage()];
			$response['status_code'] = 500;
			return response()->json($response, $response['status_code']);
		}
	}

	/*
	 * Cập nhật mật khẩu cho 1 tài khoản
	 */
	public function updatePassword(\App\Http\Requests\user\UpdatePasswordRequest $request){
		$response = [
			'errors'      => [],
			'status_code' => 200,
			'data'        => []
		];
		try {
			$data = $request->only(['id', 'password']);
			$data['password'] = UserServices::hashPassword($request->password);
			// Lưu lịch sử thao tác
			$getUser = User::find($data['id']);
			\App\Services\LogServices::new([
				'user_id'      => null,
				'category'     => 'user_manager',
				'action'       => 'update_password',
				'action_color' => 'warning', // success, info, warning, danger
				'action_value' => $getUser->name.' - '.$getUser->email,
				'old_value'    => [],
				'new_value'    => ['new_password' => substr($request->password, 0, 2).'***']
			]);
			$response['data'] = UserServices::store($data);
			return response()->json($response, $response['status_code']);
		} catch (\Exception $e) {
			$response['errors'] = ['system' => $e->getMessage()];
			$response['status_code'] = 500;
			return response()->json($response, $response['status_code']);
		}
	}

	/*
	 * Tranh gửi thông báo đến người dùng
	 */
	public function notifications(){
		Permission::required('admin');
		$data = [];
		return view('pages.admin.users.notifications', $data);
	}

	/*
	/*
	 * Gửi thông báo đến người dùng
	 */
	public function sendNotification(\App\Http\Requests\user\SendNotificationRequest $request){
		Permission::required('user_manager');
		$response = [
			'errors'      => [],
			'status_code' => 200,
			'data'        => []
		];
		try {
			\App\Services\LogServices::new([
				'category'     => 'notification',
				'action'       => 'send_notification',
				'action_color' => 'info', // success, info, warning, danger
				'action_value' => $request->get('title'),
				'old_value'    => [],
				'new_value'    => $request->all()
			]);
			$data = $request->only(['roles', 'users']);
			$data['notification'] = [
				'title'           => $request->get('title'),
				'content'         => $request->get('content'),
				'created_user_id' => Auth::id(),
				'type'            => config('notification.type.code.message'),
				'send_mail'       => $request->get('send_mail', 0)
			];
			if( $request->get('expired') ){
				$data['notification']['expired'] = dateFormat( $request->get('expired'), 'H:i d/m/Y', 'Y-m-d H:i:s' );
			}
			$response['data'] = NotificationServices::store($data);
			return response()->json($response, $response['status_code']);
		} catch (\Exception $e) {
			$response['errors'] = ['system' => $e->getMessage()];
			$response['status_code'] = 500;
			return response()->json($response, $response['status_code']);
		}
	}

	/*
	 * Xóa thông báo
	 */
	public function deleteNotification(Request $request){
		Permission::required('user_manager');
		$response = [
			'errors'      => [],
			'status_code' => 200,
			'data'        => []
		];
		try {
			$getNoti = \App\Notification::find( $request->get('id') );
			\App\Services\LogServices::new([
				'category'     => 'notification',
				'action'       => 'delete_notification',
				'action_color' => 'danger', // success, info, warning, danger
				'action_value' => $getNoti->title,
				'old_value'    => $getNoti,
				'new_value'    => []
			]);
			NotificationServices::delete( $request->get('id') );
		} catch (\Exception $e) {
			$response['errors'] = ['system' => $e->getMessage()];
			$response['status_code'] = 500;
		}
		return response()->json($response, $response['status_code']);
	}

}