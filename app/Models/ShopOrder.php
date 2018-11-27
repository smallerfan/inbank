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

class ShopOrder extends Model{
    use SoftDeletes;

    protected $table="market_orders";
    protected $fillable = ['id','code','shoper_id','buy_num','price','express_fee','amount','buy_uid','uname','tel',
        'country','province','city','county','province_name','city_name','county_name','address','express_name',
        'express_sn','pay_method','coin_value','remark','created_at','updated_at','buyer_deleted_at',
        'seller_deleted_at','appeal_deadline','delivered_at','delivered_status'];


    const STATUS = ['close'=>'已关闭','wait_deliver'=>'待发货','wait_collect'=>'待收货','complete'=>'已完成','appeal'=>'申诉'];
    const DELIVER_STATUS = [0=>'待发货',1=>'已发货'];

    public function users(){
        return $this->belongsTo('App\Models\User','buy_uid','id');
    }
    public function order_detail(){
        return $this->hasMany('App\Models\OrderDetail','order_id','id');
    }
    public function shop_shopers(){
        return $this->belongsTo('App\Models\Shoper','shoper_id','id');
    }
    public function order_goods(){
        return $this->belongsToMany('App\Models\Goods','market_order_details','order_id','goods_id');
    }



    public static function orderList($params,$type){

        $order = ShopOrder::query()->with('order_goods')->with('users')->with('order_detail');
        if(isset($params['status'])){
            $order->where('status',$params['status']);
        }else{
            $params['status'] = null;
        }
        if(isset($params['code'])){
            $order->where('code',$params['code']);
        }else{
            $params['code'] = null;
        }
        if(isset($params['user_name'])){
            $order->where('uname','like','%'.$params['user_name'].'%');
        }else{
            $params['user_name'] = null;
        }
        if(isset($params['tel'])){
            $order->where('tel','like','%'.$params['tel'].'%');
        }else{
            $params['tel'] = null;
        }
        if(!isset($type)){
            $type = 0;
        }
        $order->where('order_type','=',$type);

        $list = $order->orderByDesc('created_at')
            ->paginate(10);
//            $a = [];
//            foreach ($list as $k => $v){
//                if(empty($v->users)){
//                    $a[] = $v;
//                }
//            }
//            dd($a);
        $list = $list->appends([
            'status'=>$params['status'],
            'code'=>$params['code'],
            'user_name'=>$params['user_name'],
            'tel'=>$params['tel'],
        ]);
        return $list;
    }
    public static function editShip($params){
        $ship = ShopOrder::find($params['id']);
        $ship -> express_name = $params['express_name'];
        $ship -> express_sn = $params['express_sn'];
        $ship -> remark = $params['remark'];
        $ship -> status = 'wait_collect';
        $res = $ship->save();
        return $res;

    }
    public static function editCreditShip($params){
        $ship = ShopOrder::find($params['id']);
        $ship -> express_name = $params['express_name'];
        $ship -> express_sn = $params['express_sn'];
        $ship -> remark = $params['remark'];
        $ship -> delivered_status = 1;
        $res = $ship->save();
        return $res;

    }
    public static function editExpress($params){
        $ship = ShopOrder::find($params['id']);
        $ship -> address = $params['address'];
        $ship -> uname = $params['uname'];
        $ship -> tel = $params['tel'];
        $res = $ship->save();
        return $res;
    }
    public static function cancelOrder($id){
        $ship = ShopOrder::find($id);
        $ship -> status = 'close';
        $res = $ship->save();
        return $res;
    }
    public static function orderDetail($id){
        $order = ShopOrder::query()
            ->with('order_goods')
            ->with('shop_shopers')
            ->with('users')
            ->find($id);
        return $order;
    }

}