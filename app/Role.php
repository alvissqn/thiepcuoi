<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public $timestamps = false;
    //protected $fillable = ['*'];
    protected $guarded = [];

     /*
	 * Danh sách quyền của 1 chức vụ
     */
    public function permissions(){
    	return $this->hasMany('App\RolePermission');
    }
    
    /*
	 * Liên kết bảng role_permissions
     */
    public function role_permissions(){
    	return $this->belongsToMany('App\RolePermission', 'role_permissions', 'role_id', 'permission_name');
    }

    /*
     * Liên kết bảng role_notifications
     */
    public function role_notifications(){
        return $this->belongsToMany('App\RoleNotifications', 'role_notifications', 'role_id', 'notification_id');
    }
}
