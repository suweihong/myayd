<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemType extends Model
{
     //
     protected $table = 'item_type';
     protected $fillable = ['store_id','type_id','item_id','name','rule'];
	use SoftDeletes;
}
