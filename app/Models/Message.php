<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use SoftDeletes;

     protected $fillable =['mp_user_id','title','content','read','complaint_id'];

    //私信所属商户
    public function mp_user()
    {
    	return $this->belongsTo('App\Models\MpUser');
    }

    //私信所属的举报信息， 反馈信息
    public function complaint()
    {
    	return $this->belongsTo('App\Models\Complaint');
    }
