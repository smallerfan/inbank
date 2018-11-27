<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class C2cReleasePlan extends Model
{
    //
    protected $fillable = ['id','name','exchange_rate','release_num','released_num','status','next_plan_id','updated_at'];
    protected $table = 'c2c_release_plans';
    public function c2c_release_plan_record(){
        return $this->hasOne('App\Models\C2cReleasePlanRecord','c2c_release_plan_id');
    }
}
