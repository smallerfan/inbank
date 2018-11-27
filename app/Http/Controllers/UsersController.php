<?php

namespace App\Http\Controllers;

use App\Authorizable;
use App\Models\Dictionaries;
use App\Models\Muser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{
    #use Authorizable;

    public function index(Request $request) {
        $key = $request->key;
        $value = $request->value;
        $grade = $request->grade;
        $status = $request->status;
        $data=[];
        if(isset($value)){
            if($key == '0'){
                $data['invite_code'] = $value;
            }
            if($key == '1'){
                $data['mobile'] = $value;
            }
        }
        if(isset($grade)){
            $data['user_grade'] = $grade;
        }
        if(isset($status)){
            $data['status'] = $status;
        }
        $users = User::with(["parent_user", "user_rich", 'children','m_user.dictionaries'])
            ->where('mobile','not like','11'.'%')
            ->where('id','>','100')
            ->where('mobile','!=','13000000000')
            ->where($data)
            ->orderByDesc('id')
            ->paginate(10);
//        $a =[];
//        foreach ($users as $k => $v){
//            if(empty($v->m_user->dictionaries)){
//                $a[] = $v;
//            }
//        }
//        dd($a);
        return view('users.index', ['users' => $users,'key'=>$key,'value'=>$value,'grade'=>$grade,'status'=>$status]);
    }
    public function destroy($user_id) {
        $user = User::find($user_id);
        if(!$user->is_deleted()) {
            $user->status = 'deleted';
            $user->auto_update_user_grade();
            $user->save();
        }
        flash('删除用户成功')->success();
        return redirect('users');
    }

    public function lockTrans($user_id) {
        $user = User::find($user_id);
        if(!$user->is_trans_locked()) {
            $user->status = 'trans_lock';
            $user->save();
        }
        flash('交易锁定成功')->success();
        return redirect('users');
    }

    public function unlockTrans($user_id) {
        $user = User::find($user_id);
        if($user->is_trans_locked()) {
            $user->status = 'enable';
            $user->save();
        }
        flash('解锁交易成功')->success();
        return redirect('users');
    }

    public function lock($user_id) {
        $user = User::find($user_id);
        if(!$user->is_locked()) {
            $user->status = 'lock';
            $user->save();
        }
        flash('锁定用户成功')->success();
        return redirect('users');
    }

    public function unlock($user_id) {
        $user = User::find($user_id);
        if($user->is_locked()) {
            $user->status = 'enable';
            $user->save();
        }
        flash('解锁用户成功')->success();
        return redirect('users');
    }

    public function upgrade($user_id) {
        $user = User::find($user_id);
        if(!$user->is_svip_user()) {
            $user->user_grade = 'svip';
            $user->save();
        }
        flash('升级用户成功')->success();
        return redirect('users');
    }

    public function downgrade($user_id) {
        $user = User::find($user_id);
        if($user->is_svip_user()) {
            $user->user_grade = 'interim';
            $user->auto_update_user_grade();
            $user->save();
        }
        flash('取消SVIP成功, 当前用户等级降为:'.User::GRADE[$user->user_grade])->success();
        return redirect('users');
    }
    public function edit(User $user) {
        $user = User::query()
            ->with('user_rich')
            ->with('m_user.dictionaries')
            ->find($user->id);
        $path = $user->path.','.$user->id.',';
        $all = User::query()
            ->where('id','=',$user->id)
            ->orWhere('path','like',$path)
            ->orWhere('pid','=',$user->id)
            ->count();
        $lock = User::query()
            ->where(['id'=>$user->id,'status'=>'lock'])
            ->orWhere('path','like',$path)
            ->orWhere('pid','=',$user->id)
            ->count();
        $unlock = User::query()
            ->where(['id'=>$user->id])
            ->orWhere('path','like',$path)
            ->orWhere('pid','=',$user->id)
            ->whereIn('status',['enable','trans_lock'])
            ->count();
        $level = Dictionaries::all();
        return view('users.edit',['user'=>$user,'all'=>$all,'lock'=>$lock,'unlock'=>$unlock,'level'=>$level]);
    }
    public function update(Request $request) {
        $id = $request->id;
        $auth_id = $request->auth_id;
        $level = $request->level;
        $user = User::query()->find($id);
        $this->validate($request,
            [
                'account'=>'required|regex:/^1\d{10}$/',
                'status'=>'required|in:enable,deleted,lock,trans_lock',
                'level'=>'required',
            ]);
        $account = $request->account;
        $mobile = User::query()
            ->where('mobile','=',$account)
            ->where('id','!=',$id)
            ->count();
        if($mobile > 0){
            return view('users.edit')->with(['user'=>$user,'account'=>$account,'msg'=>'新手机号已被使用']);
        }
        $status = $request->status;

        $user->account=$account;
        $user->mobile=$account;
        $user->status=$status;
//        if($auth_id  == 1){
//            $grade = $request->grade;
//            $user->user_grade=$grade;
//        }
        $user->save();
//        dd($user->id);
        $level_save = Muser::changeLevel($id,$level);
        return redirect('users')->with('flash_message','修改成功');
    }
    public function userRelation(Request $request){
//        dd($request->segment(2));
        return view('users.relation');
    }

    public function lock_line(Request $request){
        $id = $request->id;
        $user = User::query()->find($id);
        $path = $user->path.','.$user->id.',';
        $unlock = User::query()
            ->where(['id'=>$user->id])
            ->orWhere('path','like',$path)
            ->orWhere('pid','=',$user->id)
            ->whereIn('status',['enable','trans_lock'])
            ->update(['status'=>'lock']);
        if($unlock > 0){
            return json_encode($unlock);
        }else{
            return json_encode(-1);
        }
    }

    public function show(User $user){
        $info = User::query()->find($user->id);
        return view('users.move',['user'=>$info]);
    }
    public function searchUser(Request $request){
        $uid = $request->uid;
        $id = $request->id;
        if(empty($uid) || empty($id)){
            return json_encode(0);
        }
        $new_user = User::query()->where('invite_code','=',$uid)->first();
        $user = User::query()->find($id);
        if(!$user || !$new_user){
            return json_encode(-2);
        }
        $path = $user->path.','.$user->id.',';
        //新推荐人是否是该用户的下线
        $unlock = User::query()
            ->where(['id'=>$user->id])
            ->orWhere('path','like',$path)
            ->orWhere('pid','=',$user->id)
            ->get();
        $id_str = array();
        foreach ($unlock as $key=>$item){
            $id_str[$key] = $item->id;
        }
        if(in_array($new_user->id,$id_str)){
            return json_encode(-1);
        }
        $data = ['uid'=>$new_user->id,'username'=>$new_user->username,'mobile'=>$new_user->mobile];
        return json_encode($data);

    }

    public function move_line(Request $request){
        $id = $request->id;
        $uid = $request->uid;
        $user = User::query()->find($id);
        if(empty($uid)){
            return view('users.move',['user'=>$user,'msg'=>'新推荐人UID不能为空']);
        }
        $new_user = User::query()->where('invite_code','=',$uid)->first();
        if(!$user || !$new_user){
            return view('users.move',['user'=>$user,'msg'=>'用户/新推荐人不存在']);
        }
        // 1.判断当前用户是否是根节点用户
        if($user->pid == 0){
            return view('users.move',['user'=>$user,'msg'=>'顶级用户不能移线']);
        }
        // 2.判断新推荐人是否在当前用户下级网络中
        $path = $user->path.','.$user->id.',';
        //新推荐人是否是该用户的下线
        $unlock = User::query()
            ->where(['id'=>$user->id])
            ->orWhere('path','like',$path.'%')
            ->orWhere('pid','=',$user->id)
            ->get();
        $id_str = array();
        foreach ($unlock as $key=>$item){
            $id_str[$key] = $item->id;
        }
        if(in_array($new_user->id,$id_str)){
            return view('users.move',['user'=>$user,'msg'=>'新推荐人是你的下线,不能移线']);
        }

        DB::beginTransaction();
        try{
            // 3.修改当前用户的pid值
            $user->pid = $new_user->id;
            $user->save();
            // 4.修改当前用户及其所有下级的path值

            //所有下级用户path更新  新推荐人path后加.
            DB::update(
                'update datc_users set path=REPLACE(path,:old_Path,:new_path) where id = :id or pid = :pid or path like :self_path',
                [':old_Path'=>$user->path,':new_path'=>$new_user->path.','.$new_user->id,':id'=>$user->id,':pid'=>$user->id,':self_path'=>$path.'%']
            );
            DB::commit();
            return view('users.move',['user'=>$user,'msg'=>'移线成功']);
            // 5 原推荐人 与 新推荐人 的用户等级 更新  observe跟踪更新
        }catch (\Exception $exception){
            DB::rollBack();
            return view('users.move',['user'=>$user,'msg'=>'移线失败']);

        }

    }

    //用户等级设置
    public function userLevel(Request $request){
        $params = $request->all();
        $data = Dictionaries::query()->paginate(10);
        return view('users.level.index',['setting'=>$data]);
    }

    public function userLevelAdd(Request $request){
        $params = $request->all();
        if(empty($params)){
            return view('users.level.create');
        }
        $data = [
            'module' => $params['module'],
            'dic_type' => $params['dic_type'],
            'dic_type_name' => $params['dic_type_name'],
            'dic_item' => $params['dic_item'],
            'dic_item_name' => $params['dic_item_name'],
            'dic_value' => $params['dic_value'],
        ];
        $res = Dictionaries::dicAdd($data);
        if($res){
            return redirect("user/user_level/index")->with('flash_message','添加成功');
        }else{
            return redirect("user/user_level/index")->withErrors('添加失败');
        }
    }

    public function userLevelEdit(Request $request,$id){
        $params = $request->all();
        if(empty($params)){
            $data = Dictionaries::find($id);
            return view('users.level.edit',['data'=>$data]);
        }
        $res = Dictionaries::dicEdit($params,$id);
        if($res){
            return redirect("user/user_level/index")->with('flash_message','修改成功');
        }else{
            return redirect("user/user_level/index")->withErrors('修改失败');
        }
    }

    public function userLevelDelete($id){
        $res = Dictionaries::dicDelete($id);
        if($res){
            return redirect("user/user_level/index")->with('flash_message','删除成功');
        }else{
            return redirect("user/user_level/index")->withErrors('删除失败');
        }
    }

    public function userFind(Request $request){
        $params = $request->all();
        $user = User::query();
        if($params['type'] == 'uid'){
            $user->where('invite_code',$params['key']);
        }
        if($params['type'] =='tel'){

            $user->where('mobile',$params['key']);
        }

        $data = $user->first();

//        dd($data);
        return $data;
    }

    public function treeList(Request $request){
        $params = $request->all();
        if(!isset($params['id'])){
            $user = User::query()
                ->select('id as nodeId','mobile as text','username')
                //
                ->where('pid','<',100)
                //去掉前100用户
                ->where('id','>=',100)
                //去掉机器人
                ->where('mobile','not like','11'.'%')
                ->where('mobile','not like','12'.'%')
                ->get()->toArray();
//            foreach ()
            foreach ($user as $k => $v){
                $child_count = User::query()
                    ->where('mobile','not like','11'.'%')
                    ->where('mobile','not like','12'.'%')
                    ->where('path','like','%'.','.$v['nodeId'].','.'%')
                    ->where('id','>=',0)
                    ->count();
                $user[$k]['text'] = $v['text'].'（'.$v['username'].'）'.'---'.'  '.'[人数：'.$child_count.']';
            }
//            dd($user);
        }else{
            $user = User::query()
                ->where('mobile','not like','11'.'%')
                ->where('mobile','not like','12'.'%')
                ->where('id','>=',100)
                ->where('pid',$params['id'])->get()->toArray();
            foreach ($user as $k => $v){
                $user[$k]['nodeId'] = $v['id'];
                $child_count = User::query()->where('path','like','%'.','.$v['id'].','.'%')->count();
                $user[$k]['text'] = $v['mobile'].'（'.$v['username'].'）'.'---'.' '.'[人数：'.$child_count.']';
            }
        }
        return json(200,'查询成功',$user);
    }

}

