<?php

namespace App\Http\Controllers\Shop;

use App\models\Agent;
use App\Models\Muser;
use App\models\OrderDetail;
use App\Models\User;
use DeepCopy\f002\A;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\SortFormRequest;
use App\Models\ShopOrder;
use Illuminate\Support\Facades\DB;

class AgentController extends Controller
{
    //
    public function index(Request $request)
    {
        $value = $request->all();
        $list = Agent::agentList($value);
        foreach ($list as $k =>$v){
            if($v->county != null){
                $v->county = get_county($v->county) ;
            }
            if($v->city != null){
                $v->city = get_county($v->city) ;
            }
            if($v->province != null){
                $v->province = get_county($v->province) ;
            }
            $v->uid = get_user_name($v->uid);
        }

        return view('shop.agent.index')->with('datas',$list);
    }
    public function create(){
        return view('shop.agent.create');
    }
    public function edit(Request $request,$id){
        $params = $request->all();
        if(empty($params)){
            $agent = Agent::find($id);
//            dd($agent);
            $user = User::find($agent->uid);
            $address = [
                'province'=>get_county_info($agent->province),
                'city'=>get_county_info($agent->city),
                'county'=>get_county_info($agent->county),
            ];
            $data = [
                'agent'=>$agent,
                'user'=>$user,
                'address'=>$address,
            ];
//            dd($data);
            return view('shop.agent.edit')->with('data',$data);
        }else{
            $is_exit = Agent::query();
            if(empty($params['city']) && empty($params['county'])){
                $params['level'] = 'province';
                $is_exit->where('province',$params['province'])->where('level','=','province');
                $params['province'] = get_area_id($params['province']);
                $params['city'] = 0;
                $params['county'] = 0;
            }elseif(empty($params['county'])){
                $params['level'] = 'city';
                $is_exit->where('city',$params['city'])->where('level','=','city');
                $params['province'] = get_area_id($params['province']);
                $params['city'] = get_area_id($params['city']);
                $params['county'] = 0;
            }else{
                $params['level'] = 'county';
                $is_exit->where('county',$params['county'])->where('level','=','county');
                $params['province'] = get_area_id($params['province']);
                $params['city'] = get_area_id($params['city']);
                $params['county'] = get_area_id($params['county']);
            }
            $count = $is_exit->count();
            if($count>0){
                return redirect("shop/agent/create")->withErrors('该地区已存在代理');
            }
            $res = Agent::editAgent($params,$id);
            if($res){
                return redirect("shop/agent")->with('flash_message','修改成功');
            }else{
                return redirect("shop/agent")->withErrors('修改失败');
            }
        }
    }
    public function addAgent(Request $request){
        $params = $request -> all();
        $is_exit = Agent::query();
        $validator = \Validator::make(
            $params, [ 'uid' => 'required' ], [ 'required' => ':attribute必填' ],[ 'uid' => '请绑定用户' ]
        );

        if($validator->fails()){
            return redirect("shop/agent/create")->withErrors($validator->messages()->first());
        }

        $user = Muser::where('uid',$params['uid'])->first();
        if($user->level == 1){
            return redirect('shop/agent/create')->withErrors('该用户目前为注册会员,不能设置为区域代理');
        }

        if(empty($params['city']) && empty($params['county'])){
            $params['level'] = 'province';
            $is_exit->where('province',$params['province'])->where('level','=','province');
            $params['province'] = get_area_id($params['province']);
            $params['city'] = 0;
            $params['county'] = 0;
        }elseif(empty($params['county'])){
            $params['level'] = 'city';
            $is_exit->where('city',$params['city'])->where('level','=','city');
            $params['province'] = get_area_id($params['province']);
            $params['city'] = get_area_id($params['city']);
            $params['county'] = 0;
        }else{
            $params['level'] = 'county';
            $is_exit->where('county',$params['county'])->where('level','=','county');
            $params['province'] = get_area_id($params['province']);
            $params['city'] = get_area_id($params['city']);
            $params['county'] = get_area_id($params['county']);
        }
        $count = $is_exit->count();
        if($count>0){
            return redirect("shop/agent/create")->withErrors('该地区已存在代理');
        }
        $res = Agent::addAgent($params);
        if($res){
            return redirect("shop/agent")->with('flash_message','提交成功');
        }else{
            return redirect("shop/agent/create")->withErrors('提交失败');
        }
    }
    public function delete($id){
        $res = Agent::find($id)->delete();
        if($res){
            return redirect("shop/agent")->with('flash_message','删除成功');
        }else{
            return redirect("shop/agent")->with('flash_message','删除失败');
        }
    }
}
