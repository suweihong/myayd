<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Complaint extends Model
{
    use SoftDeletes;
        protected $fillable = ['kind_id','store_id','client_id','mp_user_id','check_id','content'];

    /**
     * 该投诉所属用户。（反向关联）
     */
    public function client()
    {
        return $this->belongsTo('App\Models\Client');
    }

      /**
     * 该投诉所属商户。（反向关联）
     */
    public function mp_user()
    {
        return $this->belongsTo('App\Models\MpUser');
    }

    /**
     * 该投诉所属商家。（反向关联）
     */
    public function store()
    {
        return $this->belongsTo('App\Models\Store');
    }

    /**
     * 该投诉所属类型。（反向关联）
     */
    public function kind()
    {
        return $this->belongsTo('App\Models\Kind');
    }

    /**
     * 该投诉所属状态。（反向关联）
     */
    public function check()
    {
        return $this->belongsTo('App\Models\Check');
    }

    //该投诉拥有的回复
    public function messages()
    {
        return $this->hasMany('App\Models\Message');
    }
}
