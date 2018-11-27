<?php

namespace App\Http\Controllers;

use App\Models\Assets;
use App\Models\AssetsLog;
use App\Models\InPayment;
use App\Http\Controllers\BasicController;
use App\Models\InWithdrawPayment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WithdrawController extends Controller
{
    public function index(Request $request)
    {
        $value = $request->value;
        $status = $request->status;
        if(isset($status)){
            $where['status'] = $status;
        }
        $where['award_class'] = 5;
        $log = $datas = AssetsLog::query()
            ->with('user')
            ->where('status','>=',0)
            ->where('payment','>',0)
            ->where($where)
            ->orderBy('status')
            ->orderByDesc('created_at');
        if(isset($value)){
            $datas = $log
                ->whereHas('user',function ($query) use ($value){
                    $query->where('invite_code',$value)->orWhere('mobile',$value);
                })
                ->paginate(10);
            $info['value'] = $value;
        }else{
            $datas = $log->paginate(10);
        }
        $datas = $datas->appends([
            'status'=>$status,
            'value'=>$value,
        ]);
        return view('c2c_withdraw.index')->with(['datas'=>$datas,'status'=>$status,'value'=>$value]);
        
    }
    public function edit($id){
        $data = AssetsLog::query()
        ->with(['user'=>function($query){
            $query->select('id','invite_code','mobile','username');
        }])
        ->find($id);
        if($data){
            $fee = AssetsLog::query()->where(['uid'=>$data->user->id,'from_id'=>$data->id])->first(['award']);
            $data['fee'] = $fee->award;
            $payment = InPayment::query()
                ->with(['bank'=>function($query){
                    $query->select('name_cn','id');
                }])
                ->where(['id'=>$data->payment,'uid'=>$data->user->id])
                ->select('id','pay_type','bank_id','real_name','card_no','open_bank','code_img')
                ->first();
            if(empty($payment)){
                $data['payment'] = InWithdrawPayment::query()
                    ->with(['bank'=>function($query){
                        $query->select('name_cn','id');
                    }])
                    ->where(['id'=>$data->payment,'uid'=>$data->user->id])
                    ->select('id','pay_type','bank_id','real_name','card_no','open_bank','code_img')
                    ->first();
            }else{
                $data['payment'] = $payment;
            }
        }
        
        
        return view('c2c_withdraw.edit')->with('data',$data);
    }

    public function update(Request $request){
        $id = $request->id;
        $status = $request->status;
        if(empty($id) || $id < 0){
            return view('c2c_withdraw.edit')->with('msg','ID信息异常');
        }
        DB::beginTransaction();
        try{
            $log = AssetsLog::query()->find($id);
            if($status == '2'){
                $withdraw_rate = get_setting_value('award_extract_charge_rate','market',0.05);
                $amount = $log->award * (1+$withdraw_rate); //负值
                $asset_data = Assets::query()->where('uid', $log->uid)
                    ->first(['live_assets', 'history_award', 'id', 'shopping_assets']);
                $asset_data->fill(['live_assets'=>$asset_data->live_assets - $amount])->save();
                $update['current_award'] = $asset_data->shopping_assets + $asset_data->live_assets;
            }
            $update['status']=intval($status);
            $log->fill($update)->save();
            $fee = AssetsLog::query()->where(['uid'=>$log->uid,'from_id'=>$log->id])->first();
            $fee->fill($update)->save();
            DB::commit();
            return redirect('withdraw')->with('flash_message','审核成功');
        }catch (\Exception $e){
            DB::rollBack();
            return redirect('withdraw')->with('flash_message','审核失败');
        }
    }
}
