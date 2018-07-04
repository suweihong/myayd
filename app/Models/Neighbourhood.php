<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Neighbourhood extends Model
{
     use SoftDeletes;

    //该小区的店铺
    public function stores()
    {
    	return $this->hasMany('App\Models\Store');
    }
}
