<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Goods extends Model
{
    use SoftDeletes;
    protected $table = 'market_goods';

    protected $fillable = [ 'id','shoper_id','imgs','detail_imgs','price','is_stock','is_special','stock','sell_num','intro','is_usable','on_shelf','name','custom_attribute','sort','goods_type', 'category_id','approval_reason','status','approval_time','created_at','updated_at'];
    const STATUS = ['pass_approval'=>'通过','reject_approval'=>'驳回','wait_approval'=>'待审核'];
    const TYPE = ['real_goods'=>'实体商品','virtual_goods'=>'虚拟商品'];
    const USE = ['enable'=>'正常','disable'=>'禁止'];
    const CHANNEL = ['hot_sale'=>'热销','choiceness_self'=>'精选自营'];

    public function order_detail(){
        return $this->hasMany('App\Models\OrderDetail','goods_id','id');
    }

    public function shoper(){
        return $this->belongsTo('App\Models\Shoper','shoper_id');
    }
    public function category(){
        return $this->belongsTo('App\Models\Category','category_id');
    }
    public function goods_channel(){
        return $this->hasMany('App\Models\GoodsChannel','goods_id');
    }
    
    public function is_wait() {
        return $this->status === 'wait_approval';
    }
    public function is_pass() {
        return $this->status === 'pass_approval';
    }
    public function is_reject() {
        return $this->status === 'reject_approval';
    }
    public function has_hot_sale(){
        return $this->hasMany('App\Models\GoodsChannel','goods_id')
            ->where('channel_type','hot_sale');
    }
    public function has_choiceness_self(){
        return $this->hasMany('App\Models\GoodsChannel','goods_id')
            ->where('channel_type','choiceness_self');
    }
    public function has_no_channel(){
        return $this->hasMany('App\Models\GoodsChannel','goods_id')
            ->whereNull('channel_type');
    }
    public function has_channel(){
        return $this->hasMany('App\Models\GoodsChannel','goods_id')
            ->whereNotNull('channel_type');
    }
    public static function goodAdd($params){
        $id = Goods::create($params);
        return $id;
    }
    public static function deleteGoods($id){
        $res = Goods::find($id)->delete();
        return $res;
    }
    public static function updateGoods($params,$id){
        $goods = Goods::find($id);
        $goods->goods_type = $params['goods_type'];
        $goods->category_id = $params['category_id'];
        $goods->imgs = $params['imgs'];
        $goods->detail_imgs = $params['detail_imgs'];
        $goods->name = $params['name'];
        $goods->price = $params['price'];
        $goods->is_stock = $params['is_stock'];
        $res = $goods->save();
        return $res;
    }
    
}
