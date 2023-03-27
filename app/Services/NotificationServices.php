<?php
namespace App\Services;

use App\Notification;
use App\UserNotifications;
use App\RoleNotifications;
use App\NotificationReaded;
use App\Role;
use App\User;

class NotificationServices
{
	/*
	 * Lưu thông báo mới
	 */
	public static function store($data){
    	// Tiến hành lưu dữ liệu
    	\DB::beginTransaction();
    	try {
            $usersId = $data['users'] ?? []; // Lưu id thành viên để gửi mail

            if( empty($data['notification']['expired']) && $data['notification']['type'] != config('notification.type.code.message') ){
                $data['notification']['expired'] = date( 'Y-m-d 23:59:59', strtotime('+ '.config('notification.auto_cleanup').' months') );
            }
            // Lưu thông báo
            $data['store']['notifications'] = Notification::create($data['notification']);
            $notificationId = $data['store']['notifications']->id;

            // Lưu bảng trung gian role_notifications
            if( !empty($data['roles']) ){
                foreach($data['roles'] as $roleId){
                    Role::find($roleId)->role_notifications()->attach([$notificationId]);
                }

                // Lưu id thành viên để gửi mail
                $getUsersId = User::select('id')->whereIn('role_id', $data['roles'])->pluck('id')->toArray();
                if( $getUsersId ){
                    $usersId = array_merge( $usersId, $getUsersId );
                }
            }

            // Lưu bảng trung gian user_notifications
            if( !empty($data['users']) ){
                foreach($data['users'] as $userId){
                    User::find($userId)->user_notifications()->attach([$notificationId]);
                }
            }

            // Gửi email (nếu có)
            if( !empty($data['notification']['send_mail']) && count($usersId) > 0 ){
                $data['emails'] = User::select('email')->whereIn('id', $usersId)->pluck('email')->toArray();
                \Mail::send(
                    'mail.notification',
                    ['data' => $data['notification'] ],
                    function($message) use($data) {
                        $message->to( $data['emails'] )->subject( $data['notification']['title'] );
                    }
                );
            }

    		\DB::commit();
    	} catch (\Exception $e) {
    		\DB::rollBack();
    		// Lỗi hệ thống, ajax request sẽ kết nối lại
    		throw $e;
    	}
    	return $data;
	}

    /*
     * Lấy danh sách thông báo theo id người dùng
     */
    public static function getNotificationsByUserId($params = []){
        extract($params);
        $roleId = \App\Services\UserServices::get($userId, 'role_id');

        // Lấy thông báo cho từng tài khoản
        $getUserNoti = UserNotifications::select(['notifications.*', 'notification_readed.id AS readed'])
            ->leftJoin('notifications', function($query) use ($userId) {
                 $query->on('user_notifications.notification_id', '=', 'notifications.id' );
             })
            ->leftJoin('notification_readed', function($query) use ($userId) {
                $query->on('notification_readed.user_id', '=', \DB::raw($userId) );
                $query->on('notification_readed.notification_id', '=', 'notifications.id' );
             })
            ->where( function($query){
                $query->where( 'notifications.expired', '>', \Carbon\Carbon::now() );
                $query->orWhereNull('notifications.expired');
            } )
            ->where('user_notifications.user_id', $userId)
            ->limit($limit);

        // Lấy thông báo cho từng chức vụ
        $getData = RoleNotifications::select(['notifications.*', 'notification_readed.id AS readed'])
            ->leftJoin('notifications', function($query) use ($userId) {
                 $query->on('role_notifications.notification_id', '=', 'notifications.id' );
             })
            ->leftJoin('notification_readed', function($query) use ($userId) {
                $query->on('notification_readed.user_id', '=', \DB::raw($userId) );
                $query->on('notification_readed.notification_id', '=', 'notifications.id' );
             })
            ->where( function($query){
                $query->where( 'notifications.expired', '>', \Carbon\Carbon::now() );
                $query->orWhereNull('notifications.expired');
            } )
            ->where('role_notifications.role_id', $roleId)
            ->limit($limit)
            ->union($getUserNoti)
            ->orderBy('id', 'DESC')
            ->get();
        
        $data = [
            'items'  => [],
            'unread' => 0
        ];
        foreach($getData as $id => $item){
            if( $replace_name ){
                $item->content = str_replace('@name', \Auth::user()->name, $item->content);
            }
            $item->created_at_date = date( \Option::get('settings__general_time_format'), timestamp($item->created_at) );
            $data['items'][$id] = $item;
            if( !$item->readed ){
                $data['unread']++;
            }
        }
        return $data;
    }

    /*
     * Đánh dấu là đã đọc
     */
    public static function maskAsReaded($notificationId, $userId){
        if( empty($notificationId) || empty($userId) ){
            return false;
        }
        \DB::beginTransaction();
        try {
            // Tiến hành cập nhật
            User::find($userId)->notification_readed()->syncWithoutDetaching([$notificationId]);
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            // Lỗi hệ thống, ajax request sẽ kết nối lại
            throw $e;
        }
    }

    /*
     * Đánh dấu là đã đọc tất cả các tin
     */
    public static function maskAsReadedAll($userId){
        if( empty($userId) ){
            return false;
        }
        \DB::beginTransaction();
        try {
            // Tiến hành cập nhật
            $roleId = \App\Services\UserServices::get($userId, 'role_id');
            $userNotificationsId = UserNotifications::where('user_id', $userId)->pluck('notification_id')->toArray();
            $roleNotificationsId = RoleNotifications::where('role_id', $roleId)->pluck('notification_id')->toArray();
            User::find($userId)->notification_readed()->syncWithoutDetaching( array_merge($userNotificationsId, $roleNotificationsId) );
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            // Lỗi hệ thống, ajax request sẽ kết nối lại
            throw $e;
        }
    }

    /*
     * Xóa thông báo
     */
    public static function delete($id){
        \DB::beginTransaction();
        try {
            // Tiến hành xóa
            UserNotifications::where('notification_id', $id)->delete();
            RoleNotifications::where('notification_id', $id)->delete();
            NotificationReaded::where('notification_id', $id)->delete();
            Notification::where('id', $id)->delete();
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            // Lỗi hệ thống, ajax request sẽ kết nối lại
            throw $e;
        }
    }

    /*
     * Xóa các thông báo đã hết hạn
     */
    public static function cleanup(){
        // Lấy id đã hết hạn
        $notificationsId = Notification::select('id')
            ->where( 'expired', '<', \Carbon\Carbon::now() )
            ->whereNotNull('expired')
            ->pluck('id')
            ->toArray();

        // Tiến hành xóa
        if( !empty($notificationsId) ){
            \DB::beginTransaction();
            try {
                UserNotifications::whereIn('notification_id', $notificationsId)->delete();
                RoleNotifications::whereIn('notification_id', $notificationsId)->delete();
                NotificationReaded::whereIn('notification_id', $notificationsId)->delete();
                Notification::whereIn('id', $notificationsId)->delete();
                \DB::commit();
            } catch (\Exception $e) {
                \DB::rollBack();
                // Lỗi hệ thống, ajax request sẽ kết nối lại
                throw $e;
            }
        }
    }
}
