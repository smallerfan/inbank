<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class C2cCoin extends Model
{
    //
    protected $table = 'c2c_coins';

    public function c2c_user_rich(){
        return $this->hasMany('App\Models\C2cUserRich', 'coin_id', 'coin_id');
    }
    public function recharge_extract(){
        return $this->hasMany('App\Models\RechargeExtract','coin_id','coin_id');
    }
    public function plan_item(){
        return $this->hasOne('App\Models\PlanItem','coin_id','id');
    }
}
