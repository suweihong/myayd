<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bill extends Model
{
    use SoftDeletes;
    protected $fillable = ['store_id','total','collection','balance','check_id','time_start','time_end'];
     /**
     * 该账单所属商店
     */
    public function store()
    {
        return $this->belongsTo('App\Models\Store');
    }

    //该账单的状态
    public function check()
    {
    	return $this->belongsTo('App\Models\Check');
    }
}
