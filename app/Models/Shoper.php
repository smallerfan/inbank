<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Shoper extends Model
{
    protected $table="market_shopers";
    protected $fillable = ['id','name','shop_type','shop_grade','uid','status','approval_reason','approval_time','self_support','updated_at'];
    const STATUS = ['pass_approval'=>'通过','reject_approval'=>'驳回','wait_approval'=>'待审核'];
    const TYPE = ['real_goods'=>'实体商品','virtual_goods'=>'虚拟商品'];
    const USE = ['enable'=>'正常','disable'=>'禁止'];
    const SUPPORT = ['self'=>'自营','no_self'=>'非自营'];

    public function shop_orders(){
        return $this->hasMany('App\Models\ShopOrder','shoper_id','id');
    }
    public function goods(){
        return $this->hasMany('App\Models\Goods','shoper_id','id');
    }
    public function user(){
        return $this->belongsTo('App\Models\User','uid');
    }
    public function is_wait(){
        return $this->status==='wait_approval';
    }
    public function is_pass(){
        return $this->status==='pass_approval';
    }
    public function is_reject(){
        return $this->status==='reject_approval';
    }
    
    public function hotSale(){
        return $this->hasManyThrough('App\Models\GoodsChannel','App\Models\Goods','shoper_id','goods_id')
            ->where('channel_type','hot_sale');
    }
    public function choicenessSelf(){
        return $this->hasManyThrough('App\Models\GoodsChannel','App\Models\Goods','shoper_id','goods_id')
            ->where('channel_type','choiceness_self');
    }

}
