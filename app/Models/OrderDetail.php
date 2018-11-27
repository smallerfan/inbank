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

class OrderDetail extends Model{

    protected $table="market_order_details";

    protected $fillable = ['id','order_id','buy_num','goods_id','buy_num','buy_price','goods_name','goods_pic','created_at'];

    public function shop_order(){
        return $this->belongsTo('App\Models\ShopOrder','order_id','id');
    }
    public function goods(){
        return $this->belongsTo('App\Models\Goods','goods_id','id');
    }
    public static function orderList($params){
        $query = OrderDetail::query()
            ->with('shop_order')
            ->with('goods');
        if(isset($params['status'])){
            $query->where('status',$params['status']);
        }else{
            $params['status'] = null;
        }
        if(isset($params['code'])){
            $query->where('code',$params['code']);
        }else{
            $params['code'] = null;
        }
        if(isset($params['user_name'])){
            $query->where('uname','like','%'.$params['user_name'].'%');
        }else{
            $params['user_name'] = null;
        }
        if(isset($params['tel'])){
            $query->where('tel',$params['tel']);
        }else{
            $params['tel'] = null;
        }
        $list = $query->orderByDesc('created_at')
            ->paginate(10);

        $list = $list->appends([
            'status'=>$params['status'],
            'code'=>$params['code'],
            'user_name'=>$params['user_name'],
            'tel'=>$params['tel'],
        ]);
        return $list;
    }

}