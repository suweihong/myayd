<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
     use SoftDeletes;
    protected $dates = ['date'];
     protected $fillable = ['client_id','store_id','status_id','total','collection','balance','payment_id','phone','date','type_id'];

    //该订单的状态
    public function  status()
    {
    	return $this->belongsToMany('App\Models\Status')
                    ->wherePivot('deleted_at', null)
        			->withTimestamps();
    }

    //该订单最新状态
    public function new_status()
    {
    	return $this->status()
    				->orderBy('created_at','desc')
    				->first();
    }

    //获取该订单所属的商店
    public function store()
    {
    	return $this->belongsTo('App\Models\Store');
    }

    //获取该订单包括的商品
    public function fields()
    {
    	return $this->belongsToMany('App\Models\Field','field_order')->withPivot('place_num','time')->withTimestamps();
    }
    //获取该订单包括的 场地
    public function places()
    {
        return $this->belongsToMany('App\Models\Place', 'field_order', 'order_id', 'place_id')->withTimestamps();
    }
    //该订单所属用户
    public function client()
    {
        return $this->belongsTo('App\Models\Client');
    }
    //该订单的支付方式
    public function payment()
    {
    	return $this->belongsTo('App\Models\Payment');
    }
    //该订单的运动品类
    public function type()
    {
    	return $this->belongsTo('App\Models\Type');

    }
}
