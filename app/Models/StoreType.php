<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreType extends Model
{
    // use SoftDeletes;
	
    protected $table = 'store_type';
    protected $fillable = ['store_id','type_id','item_id','hours'];
}
