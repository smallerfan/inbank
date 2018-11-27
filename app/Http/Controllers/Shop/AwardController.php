<?php

namespace App\Http\Controllers\Shop;

use App\Models\Area;
use App\models\OrderDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\SortFormRequest;
use App\Models\ShopOrder;
use Illuminate\Support\Facades\DB;

class AwardController extends Controller
{
    //
    public function index(Request $request)
    {
        $value = $request->all();
        $list = ShopOrder::orderList($value);
//        foreach ($list as $k =>$v){
//            echo $v->order_goods[0]->name;
//            exit;
//        }
        return view('shop.award.index')->with('datas',$list);
    }
    public function editStatus(Request $request){
        $value = $request->all();
        return view('shop.order.index');
    }
    public function ship(Request $request,$id){
        $data = $request->all();
        $order = ShopOrder::find($id);
        return view('shop.order.ship', compact('order',$order));
    }
    public function updateExpress(Request $request) {
        $params = $request->all();
        $id=$request->id;
        $order = ShopOrder::editShip($params);
        return redirect("shop/order")->with('flash_message','提交成功');
    }
    public function editExpressInfo(Request $request) {
        $params = $request->all();
        $id=$request->id;
        $order = ShopOrder::editExpress($params);
        return redirect("shop/order")->with('flash_message','提交成功');
    }
    public function cancelOrder($id) {
        $order = ShopOrder::cancelOrder($id);
        return redirect("shop/order")->with('flash_message','取消订单成功');
    }
    public function orderDetail($id) {
        $order = ShopOrder::orderDetail($id);
        foreach ($order->order_goods as $k => $v){
            $img= explode(',',$v->imgs);
            $v->img = $img[0];
            $buy_num = OrderDetail::query()->where('order_id',$order->id)->where('goods_id',$v->id)->first();
            $v->buy_count = $buy_num->buy_num;
        }
//        echo $order;exit;
        $province = Area::query()->where('id',$order->shop_shopers->province_code)->first();
        $order->shop_shopers->province = $province->name;
        return view('shop.order.detail', compact('order',$order));
    }
}
