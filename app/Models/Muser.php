<?php
/**
 * Created by PhpStorm.
 * User: ytx13
 * Date: 2018/10/28
 * Time: 13:40
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Muser extends Model
{
    protected $table = 'market_users';
    protected $primaryKey = 'id';
    protected $fillable = ['uid','invited_uid','level','pid','path','member_time','member_expire','created_at','updated_at'];

    public function user(){
        return $this->belongsTo('App\Models\User','uid','id');
    }
    public function dictionaries(){
        return $this->belongsTo('App\Models\Dictionaries','level','id');
    }
    public static function changeLevel($id,$level){
        $user = Muser::query()->where('uid',$id)->first();
        $user->level = $level;
        $res = $user->save();
        return $res;
    }

}