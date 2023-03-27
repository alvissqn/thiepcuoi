<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class NotificationReaded extends Model{
	protected $table   = 'notification_readed';
    public $timestamps = false;
    //protected $fillable = ['*'];
    protected $guarded = [];
}
