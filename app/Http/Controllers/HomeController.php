<?php

namespace App\Http\Controllers;


use App\Models\Assets;
use App\Models\AssetsLog;
use App\Models\C2cUserRich;
use App\Models\RichLog;
use App\models\ShopOrder;
use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        $users = User::query()->where('status','=','enable')->count();
        $lock = User::query()->where('status','=','lock')->count();
        $trans_lock = User::query()->where('status','=','trans_lock')->count();
        $sum['users'] = $users;
        $sum['lock'] = $lock;
        $sum['trans_lock'] = $trans_lock;
        //报单产品订单收益总和
        $special_order = ShopOrder::query()->whereHas('order_detail.goods',function ($q){
            $q->where('is_special',1);
        })->sum('amount');
        //其他商品订单收益（已完成）
        $over_order = ShopOrder::query()->whereHas('order_detail.goods',function ($q){
            $q->where('is_special',0);
        })->where('status','=','complete')->sum('amount');
        //已支付未完成的订单收益总和
        $payed_order = ShopOrder::query()->whereHas('order_detail.goods',function ($q){
            $q->where('is_special',0);
        })->where('status','=','wait_collect')->orWhere('status','=','wait_deliver')->sum('amount');
        //总的用户分红
        $user_get = Assets::query()->find(0);
        //报单的用户分红
        $special_user_get = AssetsLog::query()->where('uid',0)->where('award_type',1)->sum('award');
        //普通商品的用户分红
        $over_user_get = AssetsLog::query()->where('uid',0)->where('award_type',0)->sum('award');
        $all_sum = $special_order+$over_order;
        $sum['special'] = [];
        $sum['special']['sum'] = abs($special_order);
        $sum['special']['product_cost'] = abs($special_order*0.1);
        $sum['special']['operating_cost'] = abs($special_order*0.12);
        $sum['special']['other'] = abs($special_order*0.78);
        $sum['special']['user_get'] = abs($special_user_get);
        $sum['special']['system_get'] = abs($sum['special']['other']-$special_user_get);
        $sum['over'] = [];
        $sum['over']['sum'] = abs($over_order);
        $sum['over']['product_cost'] = abs($over_order*0.1);
        $sum['over']['operating_cost'] = abs($over_order*0.12);
        $sum['over']['other'] = abs($over_order*0.78);
        $sum['over']['user_get'] = abs($over_user_get);
        $sum['over']['system_get'] = abs($sum['over']['other']-$over_user_get);
        $sum['all'] = abs($all_sum);
        $sum['payed'] = abs($payed_order);
        $sum['all'] = [];
        $sum['all']['sum'] = abs($all_sum);
        $sum['all']['product_cost'] = abs($all_sum*0.1);
        $sum['all']['operating_cost'] = abs($all_sum*0.12);
        $sum['all']['other'] = abs($all_sum*0.78);
        $sum['all']['user_get'] = abs($user_get->history_award);
        $sum['all']['system_get'] = abs($sum['all']['other']-$user_get->history_award);
//        $sum['all'] = abs($all_sum);
        $sum['payed'] = abs($payed_order);
        return view('home')->with('sum',$sum);
    }
}
