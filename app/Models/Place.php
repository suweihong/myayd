<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Place extends Model
{
     use SoftDeletes;

    protected $fillable = ['store_id','type_id','item_id'];

    //该场地所属店铺
    public function store()
    {
    	return $this->belongsTo('App\Models\Store');
    }

    //该场地所属 体育类别
    public function type()
    {
    	return $this->belongsTo('App\Models\Type');
    }

    //该场地 拥有的商品
    public function fields()
    {
    	return $this->hasMany('App\Models\Field');
    }
}
