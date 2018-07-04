<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Store_img extends Model
{
    protected $table = 'store_imgs'; 
    protected $fillabled = ['store_id','img'];
     use SoftDeletes;
      // 图片所属的店铺
    public function store ()
    {
    	 return $this->belongsTo('App\Models\store');
    }
}
