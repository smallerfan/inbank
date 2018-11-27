<?php


namespace App\Http\Controllers\Shop;

use App\Models\Express;
use App\models\OrderDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BasicController;
use App\Http\Requests\SortFormRequest;
use App\Models\ShopOrder;

use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{

    public function index(Request $request)
    {
        $value = $request->all();
        $list = ShopOrder::orderList($value,0);
        return view('shop.order.index')->with(['datas'=>$list,'value'=>$value]);
    }
    public function editStatus(Request $request){
        $value = $request->all();
        return view('shop.order.index');
    }
    public function ship(Request $request,$id){
        $data = $request->all();
        $order = ShopOrder::find($id);
        $ex = Express::select(['ex_code','ex_name'])->get()->toArray();
        foreach ($ex as $k=>$v){
            $a[$v['ex_code']] = $v['ex_name'];
        }
        $order->ex = $a;
        return view('shop.order.ship', compact('order',$order));
    }
    public function updateExpress(Request $request) {
        $params = $request->all();
        $id=$request->id;
        $validator = \Validator::make(
            $params,
            [
                'express_name' => 'required',
                'express_sn' => 'required',
            ],
            [
                'required' => ':attribute必填',
            ],[
                'express_name' => '快递公司名称',
                'express_sn' => '快递单号',
            ]
        );
        if($validator->fails()){
            return redirect("shop/order")->withErrors($validator->messages()->first());
        }
        $params['remark'] = isset($params['remark']) ? $params['remark'] : "";
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
            $buy_num =OrderDetail::query()->where('order_id',$order->id)->where('goods_id',$v->id)->first();
            $v->buy_count = $buy_num->buy_num;
        }
//        echo $order;exit;
        return view('shop.order.detail', compact('order',$order));
    }
    /**
     *  post提交数据
     * @param  string $url 请求Url
     * @param  array $datas 提交的数据
     * @return url响应返回的html
     */
    public function express_kd($id){
        $order = ShopOrder::find($id);
//        dd($url);
        $kd_info = $this->get_kd_info($order);
        $ex_info = Express::where('ex_code',$order->express_name)->first();
        if($ex_info == null){
            return redirect("shop/order")->withErrors('物流信息错误');
        }
        $kd = json_decode($kd_info);
        if($kd->Success == false){
            return view('shop.order.kd')->withErrors($kd->Reason);
        }

        $kd->Traces = array_reverse($kd->Traces);
        return view('shop.order.kd')->with(['order'=>$order,'kd'=>$kd,'ex'=>$ex_info]);

    }
    public function get_kd_info($params){
        $data =[
            'ShipperCode'=>$params['express_name'],
            'LogisticCode'=>$params['express_sn'],
            'OrderCode' => ''
        ];
        $data_sign = kd_encrypt(json_encode($data),env('KD_API_KEY'));
        $datas = [
            'RequestData'=>json_encode($data),
            'EBusinessID'=>env('KD_ID'),
            'RequestType'=>'8001',
            'DataSign'=>$data_sign ,
            'DataType'=> '2' ,
        ];

//        $url = env('KD_URL');
        $url = config('filesystems.disks.kd.url');
        $temps = array();
        foreach ($datas as $key => $value) {
            $temps[] = sprintf('%s=%s', $key, $value);
        }
        $post_data = implode('&', $temps);
        $url_info = parse_url($url);
        if(empty($url_info['port']))
        {
            $url_info['port']=80;
        }
        $httpheader = "POST " . $url_info['path'] . " HTTP/1.0\r\n";
        $httpheader.= "Host:" . $url_info['host'] . "\r\n";
        $httpheader.= "Content-Type:application/x-www-form-urlencoded\r\n";
        $httpheader.= "Content-Length:" . strlen($post_data) . "\r\n";
        $httpheader.= "Connection:close\r\n\r\n";
        $httpheader.= $post_data;
        $fd = fsockopen($url_info['host'], $url_info['port']);
        fwrite($fd, $httpheader);
        $gets = "";
        $headerFlag = true;
        while (!feof($fd)) {
            if (($header = @fgets($fd)) && ($header == "\r\n" || $header == "\n")) {
                break;
            }
        }
        while (!feof($fd)) {
            $gets.= fread($fd, 128);
        }
        fclose($fd);

        return $gets;
    }
}
