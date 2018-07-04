<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
     use SoftDeletes;


    //该项目(场地/票卡)拥有的所有类型
    public function types()
    {
    	return $this->hasMany('App\Models\Type');
    }

    //该项目拥有的商品
    public function fields()
    {
    	return $this->hasMany('App\Models\Field');
}
