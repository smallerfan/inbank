<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Complaint;

class ComplaintController extends Controller
{
    public function index(){
        $datas = Complaint::query()
            ->with('user')
            ->orderBy('is_reply','asc')
            ->orderBy('reply_read','asc')
            ->orderBy('created_at','asc')
            ->paginate(10);
        return view('complaints.index',['datas'=>$datas]);
    }
    public function edit(Complaint $complaint){
        $complaint = Complaint::query()
            ->with('user')
            ->find($complaint->id);
        $complaint->imgs = explode(',',$complaint->imgs);
            return view('complaints.edit',['data'=>$complaint]);
    }
    
    public function update(Request $request){
        $id = $request->id;
        $advice = Complaint::query()->find($id);
        if(isset($id) && $advice){
            $content = $request->reply_content;
            $advice->reply_content=$content;
            $advice->is_reply='reply';
            $advice->reply_time=datetime();
            $advice->save();
            return redirect()->route("complaints.index")->with('flash_message','回复成功');
        }else{
            return redirect()->route("complaints.index")->with('flash_message','回复失败');
        }
    }
    // 标记回复 为 已读
    public function read(){
    
    }
    
}
