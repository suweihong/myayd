<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MpUser extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'store_id','account','password'
    ];
    // 该店主的 店铺
    public function store()
    {
    	return $this->belongsTo('App\Models\Store');
    }

    //该店主的消息
    public function messages()
    {
    	return $this->hasMany('App\Models\Message');
    }
}
