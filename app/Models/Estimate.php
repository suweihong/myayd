<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Estimate extends Model
{
    use SoftDeletes;
    protected $fillable = ['id','store_id','client_id','content','enviroment','service','average','check_id'];

    //该评论所属店铺
    public function store()
    {
    	return $this->belongsTo('App\Models\Store');
    }

    //该评论所属用户
    public function client()
    {
    	return $this->belongsTo('App\Models\Client');
    }
}
