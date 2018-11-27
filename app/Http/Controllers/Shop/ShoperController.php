<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Shoper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class ShoperController extends Controller
{

    public function index(Request $request)
    {
        $value = $request->value;
        if(isset($value)){
            $shopers=Shoper::query()
                ->whereHas('user',function ($query) use($value){
                    $query->where('invite_code','=',$value);
                })
                ->orWhere('name','like','%'.$value.'%')
                ->orderBy('status','desc')
                ->orderBy('id','asc')
                ->paginate(10);
        }else{
            $shopers=Shoper::with('user')->orderBy('status','desc')->orderBy('id','asc')->paginate(10);
        }
        return view('shop.shoper.index')->with(['shopers'=>$shopers,'value'=>$value]);
    }
    
    public function edit(Shoper $shoper)
    {
        $shop = Shoper::query()->find($shoper->id);
        return view("shop.shoper.edit", compact('shop'));
    }

    public function update(Request $request)
    {
        $remark = $request->reason;
        $choice = $request->choice;
        if($choice == 'pass_approval'){
            $this->validate($request, ['reason'=>'nullable|max:50|string','id' =>'required|numeric', 'choice' => 'required|string']);
    
        }else{
            $this->validate($request, ['reason'=>'required|max:50|string','id' =>'required|numeric', 'choice' => 'required|string']);
        }
        $id=$request->id;
        $shoper = Shoper::query()->find($id);
        
        if(!$shoper){
            return redirect("shop/shopers")->with('flash_message','商家信息异常');
        }
        $user = User::query()->where('id','=',$shoper->uid)->first();
        DB::beginTransaction();
        try{
            $shoper->fill(['approval_reason' => $remark, 'status' => $choice,'approval_time'=>datetime()])->save();
            $user->fill(['is_saler' => 'shop'])->save();
            DB::commit();
            return redirect("shop/shopers")->with('flash_message','提交成功');
        }catch (\Exception $exception){
            DB::rollBack();
            return redirect("shop/shopers")->with('flash_message','提交成功');
        }
        
    }
    
    //    设为正常
    public function set_enable($shoper_id) {
        $shoper = Shoper::query()->find($shoper_id);
        $shoper->is_usable='enable';
        $shoper->save();
        flash('设置成功')->success();
        return redirect('shop/shopers');
    }
    //    设为禁止
    public function set_disable($shoper_id) {
        $shoper = Shoper::query()->find($shoper_id);
        $shoper->is_usable='disable';
        $shoper->save();
        flash('禁止成功')->success();
        return redirect('shop/shopers');
    }
    //    设为自营
    public function set_self($shoper_id) {
        $shoper = Shoper::query()->find($shoper_id);
        $data=['self_support'=>'self'];
        $shoper->update($data);
        flash('设置成功')->success();
        return redirect('shop/shopers');
    }
    //    设为非自营
    public function set_no_self($shoper_id) {
        $shoper = Shoper::query()->find($shoper_id);
        $data=['self_support'=>'no_self'];
        $shoper->update($data);
        //方法一
        $shoper->choicenessSelf()->delete();
        //方法二
//        $goodsChannels = GoodsChannel::query()
//            ->where('channel_type','hot_sale')
//            ->whereHas('goods',function ($query){
//                return $query->has('shoper');
//            })->get();
        flash('设置成功')->success();
        return redirect('shop/shopers');
    }
}
