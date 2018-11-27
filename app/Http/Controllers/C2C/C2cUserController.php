<?php

namespace App\Http\Controllers\C2C;

use App\Models\C2cCoin;
use App\Models\C2cRichLog;
use App\Models\C2cUser;
use App\Models\C2cUserRich;
use App\Models\Coin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
class C2cUserController extends Controller
{
    public function index(Request $request){
//        $res = C2cUser::query()->where('name_auth','!=','wait_auth')->update(['name_auth'=>'wait_auth']);
//        return $res;
        $key = $request->key;
        $value = $request->value;
        if(isset($value)){
            if($key == '0'){
                $where = ['invite_code'=>$value];
            }else{
                $where = ['account'=>$value];
            }
            $datas = C2cUser::query()
                ->whereHas("user",function ($query)use ($where){
                    $query->where($where);
                })
                ->with('c2c_user_riches')
                ->orderByDesc('created_at')
                ->paginate(10);
        }else{
            $datas = C2cUser::query()
                ->with('user')
                ->with('c2c_user_riches')
                ->orderByDesc('created_at')
                ->paginate(10);
        }
        $coin = C2cCoin::all();
        return view('c2c_users.index',['datas'=>$datas,'value'=>$value,'key'=>$key,'coin'=>$coin]);
    }
    public function edit(C2cUser $c2cUser){
        $data = C2cUser::query()
            ->with('user')
            ->with('c2c_user_riches')
            ->find($c2cUser->id);
        $coin = C2cCoin::all();
        return view('c2c_users.edit',['data'=>$data,'coin'=>$coin]);
    }
    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'operate' => 'required|in:add,reduce',
            'rich' => 'required|in:1,2,3,4,5',
            'num' => 'required|numeric|min:0.00000001',
            ],
            [
                'required' => ':attribute必填',
                'integer' => ':attribute比为数字项',
            ],[
                'id' => '货币类型',
                'operate' => '操作项',
                'rich' => '财富',
                'num' => '数量',
            ]);
    
        if ($validator->fails()) {
            return redirect()->route('c2c_users.index')->withErrors($validator->errors()->first());
        }
        $id = $request->id;
        $rich = $request->rich;
        
        $data = C2cUserRich::query()
            ->where(['uuid'=>$id,'coin_id'=>$rich])
            ->first();
        if(!$data){
            return redirect()->route('c2c_users.index')->with('flash_message','用户信息异常');
        }
        $num = $request->num;
        $operate = $request->operate;
        if($operate == 'reduce' && $num > $data->live_num){
            return redirect()->route('c2c_users.index')->with('flash_message','数量不足');
        }
        DB::beginTransaction();
        try{
            if($operate == 'reduce'){
                $data->decrement('live_num',$num);
                $log = [
                    'uuid'=>$data->uuid,
                    'log_type'=>'take_back',
                    'coin_id'=>$data->coin_id,
                    'rich_num'=>-$num,
                    'cur_live_num'=>$data->live_num,
                    'cur_frozen_num'=>$data->frozen_num
                ];
            }
            if($operate == 'add'){
                $data->increment('live_num',$num);
                $log = [
                    'uuid'=>$data->uuid,
                    'log_type'=>'send',
                    'coin_id'=>$data->coin_id,
                    'rich_num'=>$num,
                    'cur_live_num'=>$data->live_num,
                    'cur_frozen_num'=>$data->frozen_num
                ];
            }
            $data->save();
            C2cRichLog::query()->create($log);
            DB::commit();
            return redirect()->route('c2c_users.index')->with('flash_message','操作成功');
        }catch (\Exception $exception){
            DB::rollBack();
            return redirect()->route('c2c_users.index')->with('flash_message','操作失败');
        }
    }
}
