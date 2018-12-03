<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\BasicController;
use App\Http\Controllers\Controller;
use App\Models\Muser;
use App\models\ShopOrder;
use App\Models\User;
use Illuminate\Http\Request;

class DataController extends Controller
{
    public function index(){
        $levels = get_dictionaries_settings('user_level','market');
        $names=[];
        foreach ($levels as $k=>$level){
            $num = Muser::query()->where('level',$level->dic_item)->count('id');
            $data[$k]['value']=$num;
            $data[$k]['name']=$level->dic_item_name;
            array_push($names,$level->dic_item_name);
        }
        $datas=json_encode($data);
        $levels=json_encode($names);
        return view('data.grade_count')->with(['datas'=>$datas,'levels'=>$levels]);
    }
    public function goods_sale(Request $request){
        $type = $request->type;
        if(empty($type)){
            $type=0;
        }
        $times = [
            ['time'=>'day','count'=>0],
            ['time'=>'day','count'=>1],
            ['time'=>'day','count'=>7],
            ['time'=>'month','count'=>1],
        ];
        foreach ($times as $k=>$time){
            $date = date('Y-m-d',strtotime('-'.$time['count'].' '.$time['time'],time()));
            if($time['time'] == 'month'){
                $dayNum = 30;
            }else{
                $dayNum = $time['count'];
            }
            $data[$k]=$this->order_range_data($date,$dayNum,0);
            if($time['time'] == 'day'){
                if($time['count'] == 0){
                    $data[$k]['tip'] = '今日';
                }elseif($time['count'] == 1){
                    $data[$k]['tip'] = '昨日';
                }else{
                    $data[$k]['tip'] = '近'.$time['count'].'日';
                }
            }else{
                $data[$k]['tip'] = '近'.$time['count'].'个月';
            }
        }
        foreach ($times as $k=>$time){
            $date = date('Y-m-d',strtotime('-'.$time['count'].' '.$time['time'],time()));
            if($time['time'] == 'month'){
                $dayNum = 30;
            }else{
                $dayNum = $time['count'];
            }
            $datas[$k]=$this->order_range_data($date,$dayNum,1);
            if($time['time'] == 'day'){
                if($time['count'] == 0){
                    $datas[$k]['tip'] = '今日';
                }elseif($time['count'] == 1){
                    $datas[$k]['tip'] = '昨日';
                }else{
                    $datas[$k]['tip'] = '近'.$time['count'].'日';
                }
            }else{
                $datas[$k]['tip'] = '近'.$time['count'].'个月';
            }
        }
        //七天交易走势图
        $trade_data['num'] = [];
        $trade_data['count'] = [];
        $trade_data['sum'] = [];
        $trade_data['amount'] = [];
        for($i=6;$i>=0;$i--){
            $trade_date = date('Y-m-d',strtotime('-'.$i.' day',time()));
            $brokenLine[] = $trade_date;
            $order=$this->order_data($trade_date,$type);
            array_push($trade_data['num'],$order['num']);
            array_push($trade_data['count'],$order['count']);
            array_push($trade_data['sum'],$order['sum']);
            array_push($trade_data['amount'],$order['amount']);
        }
        $data1=$data;
        $data2=$datas;
        $types=$brokenLine;
        $line=$trade_data;
        return view('admin.console')->with(
            ['datas'=>$data1,'data2'=>$data2,'line'=>json_encode($line),'types'=>json_encode($types),'flag'=>$type]
        );
    }
    private function order_data($date,$type=0,$avg = false){
        if($avg){
            $data['average'] = ShopOrder::query()
                ->where('order_type',$type)
                ->whereDate('created_at',$date)
                ->avg('amount')?:0.0;
        }
        $data['num'] = ShopOrder::query()
            ->where('order_type',$type)
            ->whereDate('created_at',$date)
            ->where('status','!=','close')
            ->count('id');
        $data['count'] = ShopOrder::query()
            ->where('order_type',$type)
            ->whereDate('created_at',$date)
            ->where('status','=','complete')
            ->count('id');
        $data['sum'] =  ShopOrder::query()
            ->where('order_type',$type)
            ->whereDate('created_at',$date)
            ->where('status','!=','close')
            ->sum('amount');
        $data['amount'] = ShopOrder::query()
            ->where('order_type',$type)
            ->whereDate('created_at',$date)
            ->where('status','=','complete')
            ->sum('amount');
        return $data;
    }
    private function order_range_data($date,$dayNum,$type=0){
        if($dayNum <= 1){
            $data = $this->order_data($date,$type,true);
        }else{
            $data['average'] = ShopOrder::query()
                ->where('order_type',$type)
                ->whereDate('created_at','>=',$date)
                ->avg('amount')?:0.0;
            $data['num'] = ShopOrder::query()
                ->where('order_type',$type)
                ->whereDate('created_at','>=',$date)
                ->where('status','!=','close')
                ->count('id');
            $data['count'] = ShopOrder::query()
                ->where('order_type',$type)
                ->whereDate('created_at','>=',$date)
                ->where('status','=','complete')
                ->count('id');
            $data['sum'] =  ShopOrder::query()
                ->where('order_type',$type)
                ->whereDate('created_at','>=',$date)
                ->where('status','!=','close')
                ->sum('amount');
            $data['amount'] = ShopOrder::query()
                ->where('order_type',$type)
                ->whereDate('created_at','>=',$date)
                ->where('status','=','complete')
                ->sum('amount');
        }
        
        return $data;
    }
}
