<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Field extends Model
{
    	use SoftDeletes;
        protected $fillable = ['type_id','store_id','week','time','place_id','date','price','switch','name','rule','intro'];

    	//该商品所属类型
    	public function type()
    	{
    	return $this->belongsToMany('App\Models\Type');
    	}

        //该商品所属项目 场地 或 票卡
        public function item()
        {
            return $this->belongsToMany('App\Models\Item');
        }

    	//该商品所属的商家
    	public  function store()
    	{
    		return $this->belongsToMany('App\Models\Store');
    		
    	}

        //该商品所属订单
         public function order()
        {
            return $this->belongsToMany('App\Models\Order','field_order');
        }

        //该商品属于哪块地
        public function place()
        {
            return $this->belongsTo('App\Models\Place');

        }
}
