<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    public $timestamps = true;
    protected $dates   = ['expired'];
    //protected $fillable = ['*'];
    protected $guarded = [];
}
