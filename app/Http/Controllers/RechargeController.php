<?php

namespace App\Http\Controllers;

use App\Http\Requests\RechargeFormRequest;
use App\Models\C2cCoin;
use App\Models\C2cUserRich;
use App\Models\C2cRichLog;
use App\Models\RechargeExtract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RechargeController extends Controller
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
        if(isset($value)){
            if($key == '0'){
                $condition = ['invite_code'=>$value];
            }else{
                $condition = ['account'=>$value];
            }
            $datas = RechargeExtract::query()
                ->whereHas('user',function ($query) use ($condition){
                    $query->where($condition);
                })
                ->with('c2c_coin')
                ->where(['operate_type'=>'recharge'])
                ->where($where)
                ->where($data)
                ->orderByDesc('status')
                ->orderByDesc('created_at')
                ->paginate(10);
        }else{
            $datas = RechargeExtract::query()
                ->with(['user','c2c_coin'])
                ->where(['operate_type'=>'recharge'])
                ->where($where)
                ->where($data)
                ->orderByDesc('status')
                ->orderByDesc('created_at')
                ->paginate(10);
        }
        $coin = C2cCoin::all();
        return view('recharge.index',['datas'=>$datas,'msg'=>$flag,'status'=>$status,'key'=>$key,'value'=>$value,'coin'=>$coin]);
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
            ->where(['operate_type'=>'recharge'])
            ->where($where)
            ->orderByDesc('status')
            ->orderByDesc('created_at')
            ->paginate(10);
        $coin = C2cCoin::all();
        return view('recharge.index',['datas'=>$datas,'msg'=>$flag,'coin'=>$coin]);
    }
    
    public function edit($id)
    {
        $data = RechargeExtract::query()
            ->with(['user','c2c_coin'])
            ->find($id);
        return view('recharge.edit',['data'=>$data]);
    }
    public function update(RechargeFormRequest $request)
    {
        $id = $request->id;
        $data = RechargeExtract::query()
            ->with(['user','c2c_coin'])
            ->find($id);
        $status = $request->status;
        if($status == 'reject'){
            $note = $request->note;
            $data->status = 'reject_approval';
            $data->note = $note;
            $data->approval_uid=Auth()->user()->id;
            $data->save();
            return view('recharge.edit',['data'=>$data,'flag'=>$data->c2c_coin->sys_name,'msg'=>'审核成功']);
        }

        DB::beginTransaction();
        try {
            if ($status == 'pass') {
                $coin_num = $request->real_coin_num;
                $data->status = 'pass_approval';
                $data->real_coin_num = $coin_num;
                $data->approval_uid=Auth()->user()->id;
                $data->save();
                //充值成功  添加资产数据
                $c2c_user_rich = C2cUserRich::query()
                    ->where([
                        'uuid' => $data->user->uuid,
                        'coin_id' => $data->coin_id
                    ])->first();
                $c2c_user_rich->increment('live_num', $coin_num);
                $c2c_user_rich->save();
                // 充值日志
                $log = [
                    'uuid' => $data->user->uuid,
                    'log_type' => 'recharge',
                    'coin_id' => $data->coin_id,
                    'rich_num' => $coin_num,
                    'cur_live_num' => $c2c_user_rich->live_num,
                    'cur_frozen_num' => $c2c_user_rich->frozen_num
                ];
                C2cRichLog::query()->create($log);
                DB::commit();
                return view('recharge.edit',['data'=>$data,'flag'=>$data->c2c_coin->sys_name,'msg'=>'审核成功']);
            }
        }catch (\Exception $exception){
            DB::rollBack();
            return view('recharge.edit',['data'=>$data,'flag'=>$data->c2c_coin->sys_name,'msg'=>'审核失败']);
        }
        
    }
    public function show($id){
    
    }
}
