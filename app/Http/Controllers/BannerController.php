<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banner;
use App\Facades\UploadedFile;
class BannerController extends Controller
{
    public function index(){
        $datas = Banner::query()->orderByDesc('sort')->paginate(5);
        return view('banners.index',['datas'=>$datas]);
    }
    public function create(){
        return view('banners.create');
    }
    public function store(Request $request){
        $params = $request->all();
//        dd($params);
        $validator = \Validator::make($params,
            [
                'title'=>'required|max:40',
                'href'=>'nullable|max:100',
                'type'=>'required|in:0,1',
                'picture' =>'required',
                'status' =>'required',
                'sort' =>'required|numeric|min:0',
            ],[
                'required' => ':attribute必填',
                'numeric' => ':attribute数值',
                'min' => ':attribute最小',
                'max' => ':attribute最大',
                'in' => ':attribute范围',
            ],[
                'title'=>'标题',
                'href'=>'链接',
                'type'=>'类型',
                'picture' =>'图片',
                'status' =>'状态',
                'sort' =>'排序',
            ]);
        if($validator->fails()){
            return $this->json(500,$validator->messages()->first());
        }

        $res = Banner::query()->create([
            'title' => $request->title,
            'href' => $request->href,
            'type' => $request->type,
            'picture' =>$request->picture,
            'status' =>$request->status,
            'sort' =>$request->sort,
        ]);
        if($res){
            return $this->json(200,'添加成功');
        }else{
            return $this->json(500,'添加失败');
        }
    }
    
    public function edit(Banner $banner){
        $data = Banner::query()->find($banner->id);
//        dd($data);
        return view('banners.edit',['data'=>$data]);
    }

    public function update(Request $request){
        $params = $request->all();
//        dd($params);
//        dd($params);
        $validator = \Validator::make($params,
            [
                'title'=>'required|max:40',
                'href'=>'nullable|max:100',
                'type'=>'required|in:0,1',
//                'picture' =>'required',
                'status' =>'required',
                'sort' =>'required|numeric|min:0',
            ],[
                'required' => ':attribute必填',
                'numeric' => ':attribute数值',
                'min' => ':attribute最小',
                'max' => ':attribute最大',
                'in' => ':attribute范围',
            ],[
                'title'=>'标题',
                'href'=>'链接',
                'type'=>'类型',
//                'picture' =>'图片',
                'status' =>'状态',
                'sort' =>'排序',
            ]);
        if($validator->fails()){
            return $this->json(500,$validator->messages()->first());
        }

        $id = $request->id;
        if($params['picture'] != null){
            $update_data = [
                'title' => $request->title,
                'href' => $request->href,
                'type' => $request->type,
                'status' =>$request->status,
                'picture' =>$request->picture,
                'sort' =>$request->sort,
            ];
        }else{
            $update_data = [
                'title' => $request->title,
                'href' => $request->href,
                'type' => $request->type,
                'status' =>$request->status,
                'sort' =>$request->sort,
            ];
        }

//        dd($update_data);
    
        $banner = Banner::query()->find($id);
        $res = $banner->where('id',$request->id)->update($update_data);
        if($res){

            return $this->json(200,'修改成功');
        }else{
            return $this->json(500,'修改失败');
        }
//        return redirect()->route("banners.index")->with('flash_message','保存成功');
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
