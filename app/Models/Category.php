<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'market_goods_categories';
    protected $hidden = ['created_at','updated_at'];
    protected $fillable = [ 'id','name_cn', 'name_hk', 'name_en','pid','sort','img' ];
    public function goods(){
        return $this->hasMany('App\Models\Goods','category_id');
    }
    
}
