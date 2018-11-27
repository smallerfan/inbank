<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class GoodsChannel extends Model
{
    protected $table = 'market_goods_channels';
    protected $fillable = ['id','goods_id','channel_type'];
    const CHANNEL = ['hot_sale'=>'热销','choiceness_self'=>'精选'];
    public function goods()
    {
        return $this->belongsTo('App\Models\Goods','goods_id');
    }
   
}
