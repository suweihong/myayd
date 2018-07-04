<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Store extends Model
{
    use SoftDeletes;

        protected $fillable = ['id', 'neighbourhood_id','mp_user_id','balance','title','name','address','map_url','phone','switch','check_id','logo','introduction'];


    //该商店拥有的所有       商品
    public function fields()
    {
    	return $this->hasMany('App\Models\Field');
    }

    //该店拥有的员工
    public function staffs()
    {
    	return $this->hasMany('App\Models\Staff');
    }

    //该店的 图片
    public function imgs()
    {
    	return $this->hasMany('App\Models\Store_img');
    }

    //该店的订单
    public function orders()
    {
    	return $this->hasMany('App\Models\Order');
    }

    //该店所属小区
    public function neighbourhood()
    {
    	return $this->belongsToMany('App\Models\Neighbourhood');
    }


    // 该店的管理员

    public function mp_user()
    {
        return $this->hasOne('App\Models\MpUser');
    }

    //该店的账单
    public function bills()
    {
    	return  $this->hasMany('App\Models\Bill');
    }


    //属于该店的消息
    public  function messages()
    {
        return $this->hasMany('App\Models\Message');
    }

    //该店拥有的体育品类
    public function types()
    {
        return $this->belongsToMany('App\Models\Type')
                    ->withTimestamps();
    }

    //该商店拥有的 场地
    public function places()
    {
        return $this->hasMany('App\Models\Place');
    }

    //该店铺拥有的评论
    public function estimates()
    {
        return $this->hasMany('App\Models\Estimate');
    }
}
