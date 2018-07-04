<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes;
    //该支付状态的所有订单
    public function orders()
    {
    	return $this->hasMany('App\Models\Order');
    }
}
