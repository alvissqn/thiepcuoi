<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \App\Services\NotificationServices;
use Auth, Permission;

class NotificationController extends Controller{

	/*
	 * Đánh dấu là đã đọc
	 */
	public function readedNotification(Request $request){
		Permission::required('member');
		$response = [
			'errors'      => [],
			'status_code' => 200,
			'data'        => []
		];
		try {
			if( empty( $request->get('id') ) ){
				// Đánh dấu đã đọc tất cả
				NotificationServices::maskAsReadedAll( Auth::id() );
			}else{
				// Đánh dấu đã đọc 1 tin
				NotificationServices::maskAsReaded($request->get('id'), Auth::id() );
			}
		} catch (\Exception $e) {
			$response['errors'] = ['system' => $e->getMessage()];
			$response['status_code'] = 500;
		}
		return response()->json($response, $response['status_code']);
	}

	/*
	 * Lấy danh sách thông báo của tài khoản đang đăng nhập
	 */
	public function getMyNotification(){
		$data = NotificationServices::getNotificationsByUserId([
			'userId'       => \Auth::id(),
			'limit'        => 30,
			'replace_name' => true
		]);
		return response()->json($data);
	}

}