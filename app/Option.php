<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'name';
    public $incrementing = false;
    protected $keyType = 'string';
    //protected $fillable = ['*'];
    protected $guarded = [];

}
