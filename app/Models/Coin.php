<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coin extends Model
{
    protected $fillable = ['name','plan_type','lock_period','plan_rate','support_coins',
        'plan_label','is_hot','platform_auth','status'];
    protected $table = 'in_plans';
    const IS_HOT = [0=>'否',1=>'是'];
    const PLATFORM_AUTH = [0=>'否',1=>'是'];
    const STATUS = ['0'=>'禁用','1'=>'正常'];

    public function coin_item(){
        return $this->hasMany('App\Models\PlanItem','plan_id','id');
    }
}
