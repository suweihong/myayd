<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Staff extends Model
{
    protected $table = 'staff';
	use SoftDeletes;

	//该员工属于哪个店铺
	 public function store()
    {
        return $this->belongsTo('App\Models\Store');
    }
}
