<?php
/**
 * Created by PhpStorm.
 * User: ytx13
 * Date: 2018/11/8
 * Time: 16:57
 */

namespace App\Http\Controllers\C2C;


use App\Http\Controllers\Controller;
use App\Models\C2cPublishedOrder;
use App\Models\C2cRichLog;
use App\Models\C2cTransOrder;
use App\Models\C2cUserRich;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class CheckController extends Controller
{
    public function index(Request $request){
        $params = $request->all();
        $page = isset($params['page']) ? $params['page']:1;
        if(empty($params['status'])){
            $order = C2cTransOrder::query()->with('user')->with('publish_order.user')->with('publish_order.payment')->orderByDesc('created_at')
                ->get()
                ->filter(function ($item) {
                return substr($item->publish_order->user->mobile,0,2) == 11 || substr($item->publish_order->user->mobile,0,2) == 12;
            })->toArray();
            $res = fen_ye($page,$order);
//            dd($paginator);
//            $a = [];
//            foreach ($order as $k => $v){
//                if(empty($v->publish_order->payment)){
//                    $a[] = $v;
//                }
//            }
//            dd($a);
            return view('check.index')->with('data',$res);
        }else{
            $order = C2cTransOrder::query()->with('user')->with('publish_order.user')->with('publish_order.payment')
                ->where('status','=',$params['status'])
                ->get()
                ->filter(function ($item) {
                    return substr($item->publish_order->user->mobile,0,2) == 11 || substr($item->publish_order->user->mobile,0,2) == 12;
                })->toArray();
            $res = fen_ye($page,$order);
//                ->paginate(10);
//            $order = $order->appends([
//                'status'=>$params['status'],
//            ]);
            return view('check.index')->with('data',$res);
        }
    }
    public function complete(Request $request){
        $params = $request->all();
        $order = C2cTransOrder::query()->with('user')->with('publish_order')->find($params['id']);
//        dd($order);
        DB::beginTransaction();
        //修改挂单订单
        try{
            $change_publish = C2cPublishedOrder::changeNum($order->published_order_id,$order->trans_num);
            //增加买家对应货币可用资产
            $change_buyer_rich = C2cUserRich::changeCoinNum($order->trans_coin_id,$order->trans_num,$order->uid,1);
            //减少卖家对应货币冻结资产
            $change_saler_rich = C2cUserRich::changeCoinNum($order->trans_coin_id,$order->trans_num,$order->publish_order->uid,0);
            //修改订单状态
            $change_res = C2cTransOrder::changeStatus('completed',$params['id']);
            //增加货币操作日志记录
            $buyer_rich = C2cUserRich::userCoinRich($order->trans_coin_id,$order->uid);
            $saler_rich = C2cUserRich::userCoinRich($order->trans_coin_id,$order->publish_order->uid);
            $buyer_uuid = User::query()->find($order->uid)->uuid;
            $saler_uuid = User::query()->find($order->publish_order->uid)->uuid;
            $buyer_log = [
                'uuid' => $buyer_uuid,
                'log_type' => 'c2c_buy',
                'coin_id' => $order->trans_coin_id,
                'rich_num' => $order->trans_num,
                'cur_live_num' => $buyer_rich->live_num,
                'cur_frozen_num' => $buyer_rich->frozen_num
            ];
            $saler_log = [
                'uuid' => $saler_uuid,
                'log_type' => 'c2c_sale',
                'coin_id' => $order->trans_coin_id,
                'rich_num' => 0-$order->trans_num,
                'cur_live_num' => $saler_rich->live_num,
                'cur_frozen_num' => $saler_rich->frozen_num
            ];
            C2cRichLog::addLog($buyer_log);
            C2cRichLog::addLog($saler_log);
            DB::commit();
            return json(200,'修改成功');

        }catch (\Exception $exception){
            DB::rollBack();
            return json(500,$exception->getMessage());
        }
    }
    public function cancel(Request $request){
        $params = $request->all();
        $change_res = C2cTransOrder::changeStatus('cancelled',$params['id']);
        if($change_res == false){
            return json(500,'取消成功');
        }
        return json(200,'取消失败');
    }
}