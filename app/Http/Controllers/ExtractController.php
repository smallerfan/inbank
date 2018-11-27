<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExtractFormRequest;
use App\Http\Requests\RechargeFormRequest;
use App\Models\RechargeExtract;
use App\Models\C2cCoin;
use App\Models\C2cUserRich;
use App\Models\C2cRichLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExtractController extends Controller
{
    public function index(Request $request){
        $status = $request->status;
        $key = $request->key;
        $value = $request->value;
        $flag = $request->coin;
        if(empty($flag)){
            $flag = 'ETH';
        }
        $coin = C2cCoin::query()
            ->where(['sys_name'=>$flag])
            ->first();
        $where = ['coin_id'=>$coin->id];
        $data = [];
        if(isset($status)){
            $data = ['status'=>$status];
        }
        if(isset($value)) {
            if ($key == '0') {
                $condition = ['invite_code' => $value];
            } else {
                $condition = ['account' => $value];
            }
            $datas = RechargeExtract::query()
                ->whereHas('user',function ($query) use ($condition){
                    $query->where($condition);
                })
                ->with('c2c_coin')
                ->where(['operate_type'=>'extract'])
                ->where($where)
                ->where($data)
                ->paginate(10);
        }else{
            $datas = RechargeExtract::query()
                ->with(['user','c2c_coin'])
                ->where(['operate_type'=>'extract'])
                ->where($where)
                ->where($data)
                ->paginate(10);
        }
        $coin = C2cCoin::all();
        
        return view('extract.index',['datas'=>$datas,'msg'=>$flag,'status'=>$status,'key'=>$key,'value'=>$value,'coin'=>$coin]);
    }
    public function lists($flag)
    {
        if(empty($flag)){
            $flag = 'BTC';
        }
        $coin = C2cCoin::query()
            ->where(['sys_name'=>$flag])->first();
        $where = ['coin_id'=>$coin->id];
        $datas = RechargeExtract::query()
            ->with(['user','c2c_coin'])
            ->where(['operate_type'=>'extract'])
            ->where($where)
            ->orderByDesc('status')
            ->paginate(10);
        $coin = C2cCoin::all();
        return view('extract.index',['datas'=>$datas,'msg'=>$flag,'coin'=>$coin]);
    }
    public function edit($id){
        $data = RechargeExtract::query()
            ->with(['user','c2c_coin'])
            ->find($id);
        return view('extract.edit',['data'=>$data]);
    }
    public function update(ExtractFormRequest $request){
        $id = $request->id;
        $data = RechargeExtract::query()
            ->with(['user','c2c_coin'])
            ->find($id);
        $status = $request->status;
        DB::beginTransaction();
        try {
            $c2c_user_rich = C2cUserRich::query()
                ->where([
                    'uuid' => $data->user->uuid,
                    'coin_id' => $data->coin_id
                ])->first();
            //冻结账户的提取数量+矿工费  返回到可用资产中
            $sum_num = floatval($data->coin_num) + floatval($data->real_gas_fee);
            if($status == 'reject'){
                $note = $request->note;
                $data->status = 'reject_approval';
                $data->note = $note;
//                $data->approval_uid=Auth()->id;
                $data->save();
                $c2c_user_rich->increment('live_num',$sum_num);
                $c2c_user_rich->decrement('frozen_num',$sum_num);
                $c2c_user_rich->save();
            }
            if ($status == 'pass') {
                $data->status = 'pass_approval';
//                $data->approval_uid=Auth()->id;
                $data->save();
                //提取成功  剔除冻结的提取数量
                $c2c_user_rich->decrement('frozen_num', $sum_num);
                $c2c_user_rich->save();
                
                // 充值日志
                $log = [
                    'uuid' => $data->user->uuid,
                    'log_type' => 'extract',
                    'coin_id' => $data->coin_id,
                    'rich_num' => -$data->coin_num,
                    'cur_live_num' => $c2c_user_rich->live_num,
                    'cur_frozen_num' => $c2c_user_rich->frozen_num
                ];
                C2cRichLog::query()->create($log);
                // 矿工费日志
                $log = [
                    'uuid' => $data->user->uuid,
                    'log_type' => 'gas_fee',
                    'coin_id' => $data->coin_id,
                    'rich_num' => -$data->real_gas_fee,
                    'cur_live_num' => $c2c_user_rich->live_num,
                    'cur_frozen_num' => $c2c_user_rich->frozen_num
                ];
                C2cRichLog::query()->create($log);
                DB::commit();
                
            }
            return view('extract.edit',['data'=>$data,'flag'=>$data->c2c_coin->sys_name,'msg'=>'审核成功']);
        }catch (\Exception $exception){
            DB::rollBack();
            return view('extract.edit',['data'=>$data,'flag'=>$data->c2c_coin->sys_name,'msg'=>'审核失败']);
        }
    }
    public function show($id){
    
    }
}
