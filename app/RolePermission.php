<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    public $timestamps = false;
    //protected $fillable = ['*'];
    protected $guarded = [];

    public function role(){
    	return $this->belongsTo('App\Role');
    }
}
