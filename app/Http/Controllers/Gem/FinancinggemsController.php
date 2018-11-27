<?php

namespace App\Http\Controllers\Gem;

use App\Models\C2cCoin;
use App\Models\Coin;
use App\Models\Plan;
use App\Models\PlanItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Validator;

class FinancinggemsController extends Controller
{
    public function index()
    {
        $datas = Coin::query()->where('plan_type',2)->with('coin_item')
            ->paginate(10);
        foreach ($datas as $k => $v){
            $v->support_coins = explode(',',$v->support_coins);
        }
//        dd($datas[0]->support_coins [0]);
        return view('gem.financing.index', ['datas'=>$datas]);
    }

    public function create()
    {
        $coin_type = C2cCoin::all();
        return view('gem.financing.create')->with(['coin'=>$coin_type]);
    }

    public function add(Request $request)
    {
        $params = $request->all();
        $validator = \Validator::make($params,
            [
                'name'=>'required|max:40',
                'lock_period'=>'required|integer',
                'plan_label'=>'required|string',
                'plan_rate'=>'required|numeric',
                'support_coins'=>'required',
                'is_hot'=>'required|in:0,1',
                'platform_auth'=>'required|in:0,1',
                'status' =>'required|in:0,1',
            ],[
                'required' => ':attribute必填',
                'integer' => ':attribute为整数项',
                'numeric' => ':attribute为数值',
            ],[
                'name' => '标题',
                'lock_period' => '投资时间',
                'plan_label' => '年利率',
                'support_coins' => '支持币种',
                'is_hot' => '热门',
                'platform_auth' => '平台认证',
                'status' => '状态',
            ]);
        if($validator->fails()){
            return redirect("gem/financing/create")->withErrors($validator->messages()->first());
        }
        foreach ($params['support_coins'] as $k => $v){
            $a[] = $k;
        }
//        $coins = implode(',',$support_coins);
        $params['support_coins'] = implode(',',$a);
//        dd($params['support_coins']);
        DB::beginTransaction();
        try{
            $data = [
                'name' => $params['name'],
                'plan_type' => 2,
                'lock_period' => $params['lock_period'],
                'plan_rate' => $params['plan_rate'],
                'support_coins' => $params['support_coins'],
                'plan_label' => $params['plan_label'],
                'is_hot' => $params['is_hot'],
                'platform_auth' => $params['platform_auth'],
                'status' => $params['status'],
            ];
            $plan_id = Plan::addPlan($data);
            $params['support_coins'] = explode(',',$params['support_coins']);
//            dd($params);
            $coin = C2cCoin::query()->whereIn('short_name',$params['support_coins'])->get();
//            dd($coin);
            foreach($coin as $k => $v)
            {
//                print_r($v);
                $data_item = [
                    'plan_id' => $plan_id,
                    'coin_id' => $v->id,
                    'cast_min_num' => round($params['min'][$v->id][0],2),
                    'cast_max_num' => round($params['max'][$v->id][0],2),
                ];
                $add_item = PlanItem::addPlanItem($data_item);
            }

            DB::commit();
            return redirect()->route("financing.index")->with('flash_message','添加成功');

        }catch (\Exception $exception){
            DB::rollBack();
            return redirect()->route("financing.index")->withErrors('添加失败');
        }
    }

    public function edit($id)
    {
        $data = Coin::query()->with('coin_item')->find($id);
        $coin_type = C2cCoin::all();
        return view('gem.financing.edit',['data'=>$data,'coin'=>$coin_type]);
    }

    public function editPlan(Request $request)
    {
        $params = $request->all();
//        dd($params);
        $plan = Plan::find($params['id']);
        $validator = \Validator::make($params,
            [
                'name'=>'required|max:40',
                'lock_period'=>'required|integer',
                'plan_label'=>'required|string',
                'plan_rate'=>'required|numeric',
                'support_coins'=>'required',
                'is_hot'=>'required|in:0,1',
                'platform_auth'=>'required|in:0,1',
                'status' =>'required|in:0,1',
            ],[
                'required' => ':attribute必填',
                'integer' => ':attribute为整数项',
                'numeric' => ':attribute为数值',
            ],[
                'name' => '标题',
                'lock_period' => '投资时间',
                'plan_label' => '年利率',
                'support_coins' => '支持币种',
                'is_hot' => '热门',
                'platform_auth' => '平台认证',
                'status' => '状态',
            ]);
        if($validator->fails()){
            return view("gem.financing.edit_plan")->withErrors($validator->messages()->first());
        }
        foreach ($params['support_coins'] as $k => $v){
            $a[] = $k;
        }
        $params['support_coins'] = implode(',',$a);
        $update_data  = [
            'name' => $params['name'],
            'plan_type' => 2,
            'lock_period' => $params['lock_period'],
            'plan_rate' => $params['plan_rate'],
            'support_coins' => $params['support_coins'],
            'plan_label' => $params['plan_label'],
            'is_hot' => $params['is_hot'],
            'platform_auth' => $params['platform_auth'],
            'status' => $params['status'],
        ];
        DB::beginTransaction();
        try{
            $edit_res = Plan::editPlan($update_data,$params['id']);
            if($params['support_coins'] !== $plan->support_coins){
                //如果这次支持的货币类型变了，删除旧支持货币记录的，重新插入
                $delete_old = PlanItem::deletePlanItem($params['id']);
                $params['support_coins'] = explode(',',$params['support_coins']);
                $coin = C2cCoin::query()->whereIn('short_name',$params['support_coins'])->get();
                foreach($coin as $k => $v)
                {
                    $data_item = [
                        'plan_id' => $params['id'],
                        'coin_id' => $v->id,
                        'cast_min_num' => round($params['min'][$v->id][0],2),
                        'cast_max_num' => round($params['max'][$v->id][0],2),
                    ];
                    $add_item = PlanItem::addPlanItem($data_item);
                }
            }
            DB::commit();
            return redirect()->route("financing.index")->with('flash_message','修改成功');

        }catch (\Exception $exception){
            DB::rollBack();
            return redirect()->route("financing.index")->withErrors('修改失败');
        }
    }


    public function destroy($id)
    {
        $coin = Coin::query()->find($id);
        if($coin){
            $coin->delete();
            PlanItem::query()->where('plan_id',$id)->delete();
            return redirect()->route("financing.index")->with('flash_message','删除成功');
        }else{
            return redirect()->route("financing.index")->withErrors('删除失败');
        }
    }

    public function set_open($id)
    {
        $coin = Coin::query()->find($id);
        if($coin){
            $coin->status='1';
            $coin->save();
            return redirect()->route("financing.index")->with('flash_message','正常成功');
        }else{
            return redirect()->route("financing.index")->with('flash_message','正常不成功');
        }
    }

    public function set_close($id)
    {
        $coin = Coin::query()->find($id);
        if($coin){
            $coin->status='0';
            $coin->save();
            return redirect()->route("financing.index")->with('flash_message','禁用成功');
        }else{
            return redirect()->route("financing.index")->with('flash_message','禁用失败');
        }
    }
}
