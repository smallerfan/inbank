<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class C2cPublishedOrder extends Model
{
    protected $table='c2c_published_orders';
    protected $primaryKey = 'id';
    protected $fillable = ['uid','trans_sn','trans_type','trans_coin','trans_coin_id','trans_num','trans_price','cur_num','lock_num','min_buy_num','payment_type','payment_method','status','created_at','updated_at','overed_at'];
    public function trans_order(){
        return $this->hasMany('App\Models\C2cTransOrder','published_order_id','id');
    }
    public function user(){
        return $this->belongsTo('App\Models\User','uid','id');
    }
    public function payment(){
        return $this->belongsTo('App\Models\InPayment','uid','uid');
    }
    public static function changeNum($id,$data){
        $order = C2cPublishedOrder::query()->find($id);
        $order->cur_num = $order->cur_num-$data;
        $order->lock_num = $order->lock_num-$data;
        $res = $order->save();
        return $res;
    }

}
