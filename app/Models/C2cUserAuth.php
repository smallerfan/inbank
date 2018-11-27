<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class C2cUserAuth extends Model
{
    protected $table = "c2c_user_authes";

    protected $primaryKey= 'id';

    protected $fillable = ['remark','status','uuid','uid','count'];

    const STATUS = [ 'wait_approval' => '待审核', 'reject_approval' => '被驳回', 'pass_approval' => '已通过', 'disable_approval' => '被禁止'];

    public function is_wait() {
        return $this->status === 'wait_approval';
    }
    public function is_reject() {
        return $this->status === 'reject_approval';
    }
    public function is_pass() {
        return $this->status === 'pass_approval';
    }
    public function is_disable() {
        return $this->status === 'disable_approval';
    }
    public function user_rich(){
        return $this->hasOne('App\Models\C2cUserRich','uuid','uuid');
    }

    public function user() {
        return $this->belongsTo("App\Models\User", "uid");
    }
    public function c2c_release_plan_record() {
        return $this->hasMany('App\Models\C2cReleasePlanRecord', 'uuid','uuid');
    }
    public static function recallStatus($id){
        $data = C2cUserAuth::find($id);
        if($data->status !== 'reject_approval'){
            return false;
        }
        $data->status = 'wait_approval';
        $data->count = 0;
        $res = $data->save();
        return $res;
    }
}
