<?php

namespace App\Http\Controllers\C2C;

use App\Http\Controllers\Controller;
use App\Models\C2cCoin;
use App\Models\C2cUser;
use App\Models\C2cUserAuth;
use App\Models\C2cUserRich;
use Illuminate\Http\Request;
use DB;

class C2cUserAuthController extends Controller
{
    public function index(Request $request)
    {
//        dd(1);
        $status = $request->status;
        $key = $request->key;
        $value = $request->value;
        $data = [];
        $where = [];
        if(isset($status)){
            $data['status'] = $status;
        }
        if(isset($value)){
            if($key == '0'){
                $where = ['invite_code'=>$value];
            }
            if($key == '1'){
                $where = ['mobile'=>$value];
            }
            $datas = C2cUserAuth::query()
                ->whereHas("user",function ($query)use ($where){
                    $query->where($where);
                })
                ->where($data)
                ->orderBy('status', 'desc')
                ->orderBy('id', 'asc')
                ->paginate(10);
        }else{
            //实名认证列表
            $datas = C2cUserAuth::query()->with("user")->where($data)->orderBy('status', 'desc')->orderBy('id', 'asc')->paginate(10);
//            dd($datas);
        }
        $datas = $datas->appends([
            'status'=>$status,
            'key'=>$key,
            'value'=>$value,
        ]);
        return view('c2c_user_authes.index', ['datas' => $datas,'value'=>$value,'key'=>$key,'status'=>$status]);
    }

    public function edit(C2cUserAuth $auth)
    {
        return view("c2c_user_authes.edit", compact('auth'));
    }

    public function update(Request $request)
    {

        $remark = $request->remark;
        $choice = $request->choice;
        if ($choice != 'pass_approval') {
            $this->validate($request, [
                'remark' => 'required|max:50|string',
                'id'     => 'required|numeric',
                'choice' => 'required|string|in:pass_approval,reject_approval,disable_approval'
            ]);
        } else {
            $this->validate($request, [
                'remark' => 'nullable|max:50|string',
                'id'     => 'required|numeric',
                'choice' => 'required|string|in:pass_approval,reject_approval,disable_approval'
            ]);
        }
        $id = $request->id;
        DB::beginTransaction();
        try {
            $auth = C2cUserAuth::query()->find($id);

            $data = ['remark' => $remark, 'status' => $choice];

            $auth->fill($data)->save();
            if ($auth->status === 'reject_approval') {
                $auth->count += 1;
                $auth->save();
            }
            if ($auth->count == 3) {
                $auth->status = 'disable_approval';
                $auth->save();
            }

            if ($auth->status === 'pass_approval') {
                $c2c_users = C2cUser::query()
                    ->where(['country_code' => $auth->user->country_code, 'account' => $auth->user->account])
                    ->first();

                if ($c2c_users) {
                    if ($c2c_users->name_auth != 'pass_auth') {
                        $c2c_users->name_auth = 'pass_auth';
                        $c2c_users->save();
                    }
                    if (empty($auth->uuid)) {
                        $auth->uuid = $c2c_users->id;
                        $auth->save();
                    }
                    if (empty($auth->user->uuid)) {
                        $auth->user->uuid = $c2c_users->id;
                        $auth->user->save();
                    }
                    $c2c_user_rich = C2cUserRich::query()
                        ->where('uuid', '=', $c2c_users->id)
                        ->count('id');
                    if ($c2c_user_rich == 0) {
                        //创建C2C coin账号
                        $coins = C2cCoin::all();
                        foreach ($coins as $coin) {
                            $c2c_users->c2c_user_riches()->create(['coin_id' => $coin->id]);
                        }
                    }
                } else {

                    //创建C2C用户
                    $c2c_user   = C2cUser::query()->create([
                        'id'           => C2cUser::gen_uuid(),
                        'country_code' => $auth->user->country_code,
                        'account'      => $auth->user->account,
                        'password'     => $auth->user->password,
                        'salt'         => $auth->user->salt,
                        'status'       => 'enabled',
                        'name_auth'    => 'pass_auth'
                    ]);
                    $auth->uuid = $c2c_user->id;
                    $auth->save();

                    $auth->user->uuid = $c2c_user->id;
                    $auth->user->save();

                    //创建C2C coin账号
                    $coins = C2cCoin::all();
                    foreach ($coins as $coin) {
                        $c2c_user->c2c_user_riches()->create(['coin_id' => $coin->id]);
                    }
                }
            }

            DB::commit();
            return redirect()->route("auth.index")->with('flash_message', '提交成功');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route("auth.index")->with('flash_message', '提交失败:' . $e->getMessage());
        }
    }
    public function recall(Request $request){
        $params = $request->all();
        $change_res = C2cUserAuth::recallStatus($params['id']);
        if($change_res == false){
            return json(500,'修改失败');
        }
        return json(200,'修改成功');

//        return $params;
    }

}
