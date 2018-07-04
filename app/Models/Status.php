<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Status extends Model
{
     /**
     * 与模型关联的数据表
     */
    protected $table = 'status';
    
	use SoftDeletes;

	/**
     * 该状态下的订单
     */
    public function orders()
    {
        return $this->hasMany('App\Models\Order')
        			->withTimestamps();
    }
}
