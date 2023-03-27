<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table   = 'users';
    public $timestamps = true;
    //protected $fillable = ['*'];
    protected $guarded = [];

    protected $hidden = [
        'password'
    ];

    /*
     * Lấy chức vụ
     */
    public function role(){
        return $this->hasOne('App\Role', 'id', 'role_id');
    }

    /*
     * Liên kết bảng user_notifications
     */
    public function user_notifications(){
        return $this->belongsToMany('App\UserNotifications', 'user_notifications', 'user_id', 'notification_id');
    }

    /*
     * Liên kết bảng notification_readed
     */
    public function notification_readed(){
        return $this->belongsToMany('App\NotificationReaded', 'notification_readed', 'user_id', 'notification_id');
    }

}
