<?php

namespace App\Models;;

use Illuminate\Database\Eloquent\Model;

class UserRich extends Model
{
    protected  $table = 'in_user_riches';
    protected $primaryKey = 'id';
    protected $fillable = ['uid','live_dk_num','frozen_dk_num','dn_num','frozen_dn_num'];
    const TYPE = ['natural_release'=>'自然释放','transfer_extra_reward'=>'转账交易奖励','transfer_release'=>'转账加速释放',
        'exchange'=>'兑换消耗','exchange_release'=>'兑换加速释放','exchange_team_reward'=>'团队兑换奖励',
        'transfer_in'=>'转入','transfer_out'=>'转出给','sale'=>'交易卖出','buy'=>'交易买入',
        'shop_order_cancelled'=>'订单取消退款','send_dk'=>'系统下拨','take_back_dk'=>'系统收回',
        'send_dn'=>'系统下拨DN','take_back_dn'=>'系统收回DN','subscribe'=>'认购DATC','game_out'=>'抽奖消耗',
        'game_in'=>'抽奖奖励','shop_ordered'=>'购物消费','appeal_refund'=>'购物申诉退款',
        'transin_from_shop'=>'商城账户转入','publish_sale'=>'挂卖DK','refund_sale'=>'挂卖超时退回'];
    public function user(){
        return $this->belongsTo('App\Models\User','uid');
    }

}
