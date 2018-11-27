<?php

namespace App\Http\Controllers\Statics;

use App\Http\Controllers\Controller;
use App\Models\RichLog;
use App\Models\StaticsData;
use App\Models\User;
use App\Models\UserRich;
use Illuminate\Http\Request;
use DB;
class StaticsController extends Controller
{
//    dk下拨列表
    public function index(Request $request)
    {
        $key = $request->key;
        $value = $request->value;
        if(isset($value)){
            $datas = RichLog::query()
                ->whereHas('user' ,function($query) use ($value){
                        $query->where('invite_code',$value)->orWhere('mobile',$value);
                    }
                )
                ->where('log_type','=','send_dk')
                ->orderByDesc('created_at')
                ->paginate(10);
        }else{
            $datas = RichLog::query()
                ->with('user')
                ->where('log_type','=','send_dk')
                ->orderByDesc('created_at')
                ->paginate(10);
        }
        return view('statics.dk_stir',['datas'=>$datas,'value'=>$value,'key'=>$key]);
    }
    public function money()
    {
        //平台DK总计    平台DN总计    （可用+冻结）
        $data=UserRich::query()
            ->whereHas('user',function ($query){
                $query->where('status','=','enable');
            })
            ->selectRaw('SUM(live_dk_num+frozen_dk_num) as sum_dk')
            ->selectRaw('SUM(dn_num+frozen_dk_num) as sum_dn')
            ->first('sum_dk','sum_dn');
        $lock_data=UserRich::query()
            ->whereHas('user' , function($query) {
                $query->where('status','=','lock')->orWhere('status','=','trans_lock');
            })
            ->selectRaw('SUM(live_dk_num+frozen_dk_num) as sum_dk')
            ->selectRaw('SUM(dn_num+frozen_dk_num) as sum_dn')
            ->first('lock_sum_dk','lock_sum_dn');
        $datas=RichLog::query()
            ->whereIn('log_type',['natural_release','transfer_release','exchange_release','exchange_team_reward'])
            ->whereDate('created_at','=',date('Y-m-d',strtotime('-1 day',time())))
            ->sum('get_num');
        $data['lock_sum_dk'] = empty($lock_data['lock_sum_dk'])?0.00:$lock_data['lock_sum_dk'];
        $data['lock_sum_dn'] = empty($lock_data['lock_sum_dn'])?0.00:$lock_data['lock_sum_dn'];
        $data['yestoday_sum_dn_releases']=abs($datas);
        $output = (abs($datas)/floatval($data['sum_dn']))*100;
        $data['yestoday_dn_releases_rate']=substr($output,0,4);
        //7天dn 增加数量趋势
        $date = date('Y-m-d',strtotime('-7 day',time()));
        $increase = StaticsData::query()
            ->where('data_type','=','dn_increase')
            ->whereDate('created_at','>=',$date)
            ->select('num','created_at')
            ->get();
        $release = StaticsData::query()
                ->where('data_type','=','dn_release')
                ->whereDate('created_at','>=',$date)
                ->select('num','created_at')
                ->get();
        $exchange = StaticsData::query()
                ->where('data_type','=','exchange')
                ->whereDate('created_at','>=',$date)
                ->select('num','created_at')
                ->get();
        $transfer = StaticsData::query()
                ->where('data_type','=','transfer')
                ->whereDate('created_at','=',$date)
                ->select('num','created_at')
                ->get();

        
        $increase = json_encode($increase);
        $release = json_encode($release);
        $exchange = json_encode($exchange);
        $transfer = json_encode($transfer);
        return view('statics.money',[
            'data'=>$data,
            'increase'=>$increase,
            'release'=>$release,
            'exchange'=>$exchange,
            'transfer'=>$transfer
        ]);
    }
    public function addUsers()
    {
        //30天统计
//        for ($i=30; $i>0; $i--){
//            $date=date('Y-m-d',strtotime('-'.$i.' day',time()));
//            $month=date('m-d',strtotime('-'.$i.' day',time()));
//            $data[30-$i]['date'] = $month;
//            $data[30-$i]['count'] = User::query()->whereDate('created_at','=',$date)->count('id');
//    }
        for ($i=15; $i>0; $i--){
            $date=date('Y-m-d',strtotime('-'.$i.' day',time()));
            $month=date('m-d',strtotime('-'.$i.' day',time()));
            $data[15-$i]['date'] = $month;
            $data[15-$i]['count'] = User::query()->whereDate('created_at','=',$date)->count();
        }
        $data=json_encode($data);
        return view('statics.addUsers',['datas'=>$data]);
    }

    
    public function results(Request $request)
    {
        $value = $request->value;
        $datas=array();
        if(isset($value)){
            $user = User::query()
                ->where('invite_code',$value)
                ->orWhere('mobile',$value)
                ->first();
            if(empty($user)){
                return view('statics.results',['datas'=>-1,'value'=>$value]);
            }
            if(empty($user->user_rich)){
                return view('statics.results',['datas'=>0,'value'=>$value]);
            }
            //当前可用dn
            $live_dn=$user->user_rich->dn_num;
            //当前冻结dn
            $frozen_dn=$user->user_rich->frozen_dn_num;
            //当前可用dk
            $live_dk=$user->user_rich->live_dk_num;
            //当前冻结dk
            $frozen_dk=$user->user_rich->frozen_dk_num;
            //转账总额   DK
            $transfer_num=RichLog::query()
                ->where(['uid'=>$user->id,'log_type'=>'transfer_out','user_type'=>0,'rich_type'=>0])
                ->sum('get_num');
            //兑换总额   DK
            $exchange_num=RichLog::query()
                ->where(['uid'=>$user->id,'log_type'=>'exchange','rich_type'=>0])
                ->sum('get_num');
            //自然挖矿总额
            $release = RichLog::query()
                ->where(['uid'=>$user->id,'log_type'=>'natural_release','rich_type'=>0])
                ->sum('get_num');
            //加速挖矿总额  DK
            $speedup = RichLog::query()
                ->where(['uid'=>$user->id,'rich_type'=>0])
                ->whereIn('log_type',['transfer_release','exchange_release','exchange_team_reward'])
                ->sum('get_num');
            //矿池拓展  DN
            $pool_expand = RichLog::query()
                ->where(['uid'=>$user->id,'rich_type'=>1,'log_type'=>'transfer_extra_reward'])
                ->sum('get_num');
            
            //直推人数
            $dire_num = $user->direct_user_count();
            
            $path = $user->path.','.$user->id.',';
            $cur_team_num = User::query()
                ->where('path','like',$path.'%')
                ->count('id');
            $datas['cur_live_dn'] = $live_dn;
            $datas['cur_frozen_dn'] = $frozen_dn;
            $datas['cur_live_dk'] = $live_dk;
            $datas['cur_frozen_dk'] = $frozen_dk;
            $datas['transfer_num'] = abs($transfer_num);
            $datas['exchange_num'] = abs($exchange_num);
            $datas['natural_release_num'] = $release;
            $datas['speedup_release_num'] = $speedup;
            $datas['pool_expand_num'] = $pool_expand;
    
            $datas['dire_num'] = $dire_num;
            $datas['cur_team_num'] = $cur_team_num;
        }
        
        return view('statics.results',['datas'=>$datas,'value'=>$value]);
    }

    
}
