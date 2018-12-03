<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RechargeExtract extends Model
{
    protected $table = 'c2c_rich_records';
    protected $primaryKey = 'id';
    protected $fillable = ['id','uid','coin_id','coin_num','wallet','gas_fee_rate',
        'real_gas_fee','operate_type','status','real_coin_num','approval_uid',
        'note','created_at','updated_at'];
    const STATUS = ['wait_approval'=>'待审核','pass_approval'=>'已通过' ,'reject_approval'=>'审核驳回'];
    public function user(){
        return $this->belongsTo('App\Models\User','uuid','uuid');
    }
    public function c2c_coin(){
        return $this->belongsTo('App\Models\C2cCoin','coin_id','id');
    }
    
}
