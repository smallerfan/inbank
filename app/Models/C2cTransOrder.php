<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class C2cTransOrder extends Model
{
    protected $table='c2c_trans_orders';
    protected $primaryKey = 'id';
    protected $fillable = ['order_sn','published_order_id','trans_type','uid','trans_coin','trans_coin_id','trans_num','payment_type','payment_method','price','amount','dcoin_amount','status','created_at','updated_at','overed_at'];
    const STATUS = ['closed' => '超时关闭', 'cancelled' => '已取消', 'wait_pay' => '待支付', 'wait_confirm' => '待确认收款', 'completed' => '已完成', 'appealing' => '申诉中', 'appeal_fail' => '申诉失败', 'appeal_success' => '申诉成功'];

    public function publish_order(){
        return $this->belongsTo('App\Models\C2cPublishedOrder','published_order_id','id');
    }
    public function user(){
        return $this->belongsTo('App\Models\User','uid','id');
    }
    public static function changeStatus($status,$id){
       $order = C2cTransOrder::find($id);
       $order->status = $status;
       $res = $order->save();
       return $res;
    }
}
