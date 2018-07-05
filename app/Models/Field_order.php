<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Field_order extends Model
{
    protected $table = 'field_order'; 
    protected $fillabled = ['order_id','field_id','place_num'];
     use SoftDeletes;
      // 该商品所属的订单
    public function order ()
    {
    	 return $this->belongsTo('App\Models\order');
    }
}
