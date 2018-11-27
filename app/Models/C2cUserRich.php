<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class C2cUserRich extends Model
{
    protected $fillable = ['uuid', 'coin_id'];
    protected $table = "c2c_user_riches";

    public function c2c_user_auth()
    {
        return $this->belongsTo('App\Models\C2cUserAuth', 'uuid', 'uuid');
    }
    public function user(){
        return $this->belongsTo('App\Models\User', 'uuid', 'uuid');
    }
    public function c2c_coin(){
        return $this->belongsTo('App\Models\C2cCoin', 'coin_id','id');
    }
    public static function userCoinRich($coin_id,$uid){
        $uuid = User::find($uid)->uuid;
        $res = C2cUserRich::query()->where('uuid',$uuid)->where('coin_id',$coin_id)->first();
        return $res;
    }
    public static function changeCoinNum($coin_id,$num,$uid,$type = 0){
        //type = 1 增加  type = 0 减少
        $uuid = User::find($uid)->uuid;
        $rich = C2cUserRich::query()->where('uuid',$uuid)
            ->where('coin_id',$coin_id)->first();
        if($type == 1){
            $rich->live_num = abs($num+$rich->live_num);
        }else{
            $rich->frozen_num = abs($rich->frozen_num-$num);
        }
        $res = $rich->save();
        return $res;
    }
}
