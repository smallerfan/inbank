<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banner;
use App\Facades\UploadedFile;
class BannerController extends Controller
{
    public function index(){
//        dd(config('filesystems.disks.qiniu.domain'));
        $datas = Banner::query()->orderByDesc('sort')->paginate(5);
        return view('banners.index',['datas'=>$datas]);
    }
    public function create(){
        return view('banners.create');
    }
    public function store(Request $request){
        $this->validate($request,
            [
                'title'=>'required|max:40',
                'href'=>'nullable|max:100',
                'type'=>'required|in:0,1',
                'picture' =>'required|file',
                'status' =>'required|in:open,close|string',
                'sort' =>'required|numeric|min:0',
            ]);
        if ($request->hasFile('picture')) {
            $picture = UploadedFile::file('picture')->store();
        }
        Banner::query()->create([
            'title' => $request->title,
            'href' => $request->href,
            'type' => $request->type,
            'picture' =>$picture,
            'status' =>$request->status,
            'sort' =>$request->sort,
        ]);
        return redirect()->route("banners.index")->with('flash_message','添加成功');
    }
    
    public function edit(Banner $banner){
        $data = Banner::query()->find($banner->id);
        return view('banners.edit',['data'=>$data]);
    }

    public function update(Request $request){
        $this->validate($request,
            [
                'title'=>'required|max:40',
                'href'=>'nullable|max:100',
                'type'=>'required|in:0,1',
                'id'=>'required|numeric',
                'picture' =>'nullable|file',
                'status' =>'required|in:open,close|string',
                'sort' =>'required|numeric|min:0',
            ]);
        $id = $request->id;
    
        $banner = Banner::query()->find($id);
        if ($request->hasFile('picture')) {
            $picture = UploadedFile::file('picture')->store();
            $banner->where('id',$request->id)->update([
                'title' => $request->title,
                'href' => $request->href,
                'type' => $request->type,
                'picture' =>$picture,
                'status' =>$request->status,
                'sort' =>$request->sort,
            ]);
        }else{
            $banner->where('id',$request->id)->update([
                'title' => $request->title,
                'href' => $request->href,
                'type' => $request->type,
                'status' =>$request->status,
                'sort' =>$request->sort,
            ]);
        }
        
        return redirect()->route("banners.index")->with('flash_message','保存成功');
    }
    public function delete(Request $request){
        $id = $request->input('id');
//        dd($id);
        $banner = Banner::query()->find($id);
        if($banner){
            $res = $banner->delete();
            if($res){
                return $this->json(200,'删除成功');
//                return redirect()->route("banners.index")->with('flash_message','删除成功');
            }
            return $this->json(500,'删除失败');
        }else{
            return $this->json(500,'不存在此轮播图');
        }
    }
    public function set_open($id){
        $banner = Banner::query()->find($id);
        if($banner){
            $banner->status='open';
            $banner->save();
            return redirect()->route("banners.index")->with('flash_message','启用成功');
        }else{
            return redirect()->route("banners.index")->with('flash_message','启用失败');
        }
    }
    public function set_close($id){
        $banner = Banner::query()->find($id);
        if($banner){
            $banner->status='close';
            $banner->save();
            return redirect()->route("banners.index")->with('flash_message','禁用成功');
        }else{
            return redirect()->route("banners.index")->with('flash_message','禁用失败');
        }
    }
}
