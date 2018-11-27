<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class C2cReleasePlanRecord extends Model
{
    protected $fillable = ['id','uuid','c2c_release_plan_id','exchange_rate','exchange_num','updated_at'];
    protected $table = 'c2c_release_plan_records';
    public function c2c_release_plan(){
        return $this->belongsTo('App\Models\C2cReleasePlan','c2c_release_plan_id');
    }
    public function user(){
        return $this->belongsTo('App\Models\User','uuid','uuid');
    }
    public function c2c_user_auth(){
        return $this->belongsTo('App\Models\C2cUserAuth','uuid','uuid');
    }
}
