<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = ['name','plan_type','plan_label','plan_rate','lock_period','support_coins','status','platform_auth','is_hot'];
    protected $table = 'in_plans';
    public function plan_item(){
        return $this->hasMany('App\Models\PlanItem','plan_id');
    }
    public static function addPlan($params){
        $res = Plan::create($params);
        return $res->id;
    }
    public static function editPlan($params,$id){
        $plan = Plan::find($id);
        $plan->name = $params['name'];
        $plan->plan_type = $params['plan_type'];
        $plan->plan_label = $params['plan_label'];
        $plan->plan_rate = $params['plan_rate'];
        $plan->lock_period = $params['lock_period'];
        $plan->support_coins = $params['support_coins'];
        $plan->status = $params['status'];
        $plan->platform_auth = $params['platform_auth'];
        $plan->is_hot = $params['is_hot'];
        $res = $plan->save();
        return $res;
    }
}
