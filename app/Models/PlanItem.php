<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanItem extends Model
{
    protected $fillable = ['plan_id','coin_id','cast_min_num','cast_max_num','created_at','updated_at'];
    protected $primaryKey = 'id';
    protected $table = 'in_plan_items';
    public function plan(){
        return $this->belongsTo('App\Models\Plan','plan_id','id');
    }
    public function coin(){
        return $this->belongsTo('App\Models\C2cCoin','coin_id','id');
    }
    public static function addPlanItem($params){
        $res = PlanItem::create($params);
        return $res->id;
    }
    public static function  deletePlanItem($params){
        $data = PlanItem::query()->where('plan_id',$params);
        $res = $data->delete();
        return $res;
    }
}
