<?php
/**
 * Created by PhpStorm.
 * User: xiaofan
 * Date: 2018/10/19
 * Time: 17:24
 */
namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Agent extends Model{

    protected $table="market_agenters";
    protected $fillable = ['id','province','city','county','uid','level','created_at'];


    public function users(){
        return $this->belongsTo('App\Models\User','uid','id');
    }

    public static function addAgent($params){
        $id = Agent::create($params);
        return $id;
    }
    public static function editAgent($params,$id){
        $agent = Agent::find($id);
        $agent->province = $params['province'];
        $agent->city = $params['city'];
        $agent->county = $params['county'];
        $agent->uid = $params['uid'];
        $res = $agent->save();
        return $res;
    }
    public static function agentList($params){
        $list = Agent::query();
        if(isset($params['uid'])){
            $list->where('uid',$params['uid']);
        }else{
            $params['uid'] = null;
        }
        $data =  $list->orderByDesc('created_at')->paginate(10);
        $data = $data->appends([
            'uid'=>$params['uid']
        ]);
        return $data;
    }


}