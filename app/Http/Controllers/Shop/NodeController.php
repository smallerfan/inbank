<?php
/**
 * Created by PhpStorm.
 * User: ytx13
 * Date: 2018/10/28
 * Time: 13:39
 */

namespace App\Http\Controllers\Shop;

use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use App\Models\Muser;
use App\Models\Node;
use App\Models\User;
use Illuminate\Http\Request;

class NodeController extends Controller
{
    public function index(Request $request){
        $params = $request->all();
        $data = Node::query()->select(['uid','invited_uid'])->get()->groupBy('uid');
        foreach ($data as $k => $v){
            $v['count'] = count($v);
        }
        foreach ($data as $k => $v){
            $v['user'] = User::find($k);
        }
//        dd($data);
        return view('shop.node.index')->with(['datas'=>$data]);
    }
    public function create(Request $request){
//        dd(1);
        return view('shop.node.create');
    }
    public function addNode(Request $request){
        $params = $request->all();
        if(!isset($params['uid'])){
            return redirect('shop/node/create')->withErrors('请选择用户');
        }
//        dd($params['uid']);
        $uid = $params['uid'];
        $parent_path = User::find($uid)->path;
        //判断该用户父级是否已经设为点位了
        $parent_code = Node::whereIn('uid',explode(',',$parent_path))->count();
        if($parent_code >0 ){
            return redirect('shop/node/create')->withErrors('该用户上级已经设置了点位');
        }
        $child_uid = User::query()->select('id')->where('path','like','%'.$uid.','.'%')->get();
        //用户子集
        $child = [];
        foreach ($child_uid as $k => $v)
        {
            $child[] = $v->id;
        }
        //判断该用户子集是否已经设为点位了
        $is_child_node = Node::whereIn('uid',$child)->count();
        if($is_child_node > 0){
            return redirect('shop/node/create')->withErrors('该用户下级已经设置了点位');
        }
        //点位上的用户设置为永久会员
        $user = Muser::where('uid',$uid)->first();
        if($user->level == 1){
            return redirect('shop/node/create')->withErrors('该用户目前为注册会员,不能设置为有效点位');
        }
        //
        $node_user = User::query()->whereHas('m_user',function ($q){
            $q->where('member_time','!=',0)->orderByDesc('member_time');
        })
            ->where('path','like','%'.$uid.','.'%')
            ->select(['id'])->limit(98)->get();
//        dd($node_user);
        if(count($node_user)>0){
            foreach ($node_user as $k => $v)
            {
                if($v == null){
                    return 1;
                }
                $invite_node[] = $v->id;
            }
            $add_node = array_unshift($invite_node,$uid);
            $is_exist = Node::query()->where('uid',$uid)->whereIn('invited_uid',$invite_node)->count();
            if($is_exist>0){
                return redirect('shop/node/create')->withErrors('该点位已设置');
            }
            foreach ($invite_node as $k => $v){
                $params = [
                    'uid' => $uid,
                    'invited_uid' => $v,
                ];
                $res = Node::create($params);
                if(!$res){
                    return redirect('shop/node/create')->withErrors('设置失败');
                }
            }
        }else{
            $invite_node = $uid;
            $is_exist = Node::query()->where('uid',$uid)->where('invited_uid',$invite_node)->count();
            if($is_exist>0){
                return redirect('shop/node/create')->withErrors('该点位已设置');
            }
            $res = Node::create(['uid'=>$uid,'invited_uid'=>$uid]);
            if(!$res){
                return redirect('shop/node/create')->withErrors('设置失败');
            }
        }
        return redirect('shop/node/create')->with('flash_message','设置点位成功');

    }
    public function detail($id){
       $node_id = Node::select('invited_uid')->where('uid',$id)->get()->toArray();
       $uid = [];
       foreach ($node_id as $k => $v){
           $uid[] = $v['invited_uid'];
       }
        $user_info = User::query()->with('m_user')->select(['id','invite_code','mobile','username'])->whereIn('id',$uid)->get();
//       dd($user_info);
        return view('shop.node.detail')->with(['data'=>$user_info]);
    }
}
