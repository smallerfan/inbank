<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InPayment extends Model
{
    //
    protected $table='in_payments';
    protected $primaryKey = 'id';
    protected $fillable = ['uid','pay_type','bank_id','is_default','real_name','card_no','open_bank','code_img','created_at','updated_at'];
    const PAY_TYPE = ['bank_card' => '银行卡', 'alipay' => '支付宝', 'wechat' => '微信'];
    public function publish_order(){
        return $this->hasMany('App\Models\C2cPublishedOrder','uid','uid');
    }
}
