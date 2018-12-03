<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;
    class NewsController extends Controller
{
    //
    public function index(){
        $datas = News::query()->orderByDesc('id')->paginate(10);
        return view('news.index')->with('datas',$datas);
    }
    public function create(){
        $status['open'] = '开启';
        $status['close'] = '关闭';
        return view('news.create')->with('status',$status);
    }
    public function add(Request $request){
        $params = $request->all();
        $validator = \Validator::make(
            $params,
            [
                'title_cn'=>'required|max:40',
                'news_type'=>'required',
                'content_cn'=>'required',
            ],
            [
                'required' => ':attribute必填',
                'max' => ':attribute最大值',
            ],[
                'title_cn' => '标题',
                'news_type' => '公告类型',
                'content_cn' => '内容',
            ]
        );
        if($validator->fails()){
            return $this->json(500,$validator->messages()->first());
        }
        if($request->news_type == 'common'){
            News::query()->create([
                'title_cn' => $request->title_cn,
                'title_hk' => $request->title_hk,
                'title_en' => $request->title_en,
                'news_type' => 'common',
                'content_cn'=> $request->content_cn,
                'content_hk'=> $request->content_hk,
                'content_en' =>  $request->content_en,
                'status' => $request->status,
                
            ]);
        }else{
            News::query()->create([
                'title_cn' => $request->title_cn,
                'title_hk' => $request->title_hk,
                'title_en' => $request->title_en,
                'news_type' => 'urgent',
                'content_cn'=> $request->content_cn,
                'content_hk'=> $request->content_hk,
                'content_en' =>  $request->content_en,
                'status' => $request->status,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time
            ]);
        }
        return $this->json(200,'添加成功');
    }
    public function edit(News $news){
        $new = News::query()->find($news->id);
        return view('news.edit')->with('new',$new);
    }
    public function updateNews(Request $request){
        $params = $request->all();
        $validator = \Validator::make(
            $params,
            [
                'title_cn'=>'required|max:40',
                'news_type'=>'required',
                'content_cn'=>'required',
            ],
            [
                'required' => ':attribute必填',
                'max' => ':attribute最大值',
            ],[
                'title_cn' => '标题',
                'news_type' => '公告类型',
                'content_cn' => '内容',
            ]
        );
        if($validator->fails()){
            return $this->json(500,$validator->messages()->first());
        }
        $this->validate($request,
            [

            ]);
        $id = $request->id;
//        dd($id);
        $new = News::query()->find($id);
        if($request->news_type == 'common'){
            $new->where('id',$request->id)->update([
                'title_cn' => $request->title_cn,
                'title_hk' => $request->title_hk,
                'title_en' => $request->title_en,
                'news_type' => $request->news_type,
                'content_cn'=> $request->content_cn,
                'content_hk'=> $request->content_hk,
                'content_en' =>  $request->content_en,
                'status' => $request->status,
            ]);
        }else{
            $new->where('id',$request->id)->update([
                'title_cn' => $request->title_cn,
                'title_hk' => $request->title_hk,
                'title_en' => $request->title_en,
                'news_type' => $request->news_type,
                'content_cn'=> $request->content_cn,
                'content_hk'=> $request->content_hk,
                'content_en' =>  $request->content_en,
                'status' => $request->status,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time
            ]);
        }
        return $this->json(200,'修改成功');
//        return redirect()->route("news.index")->with('flash_message','保存成功');
    
    }
    public function show(News $news){
        $new = News::query()->find($news->id);
        return view('news.show')->with('new',$new);
    }

    public function delete(Request $request){
        $id = $request->input('id');
//        dd($id);
        $news = News::query()->find($id);
        if($news){
            $res = $news->delete();
            if($res){
                return $this->json(200,'删除成功');
//                return redirect()->route("banners.index")->with('flash_message','删除成功');
            }
            return $this->json(500,'删除失败');
        }else{
            return $this->json(500,'不存在此轮播图');
        }
    }
    public function set_open($news_id) {
        $news = News::query()->find($news_id);
        if(!$news->is_open()) {
            $news->status = 'open';
            $news->save();
        }
        return redirect()->route('news.index')->with('flash_message','启用成功');
    }
    public function set_close($news_id) {
        $news = News::query()->find($news_id);
        if(!$news->is_close()) {
            $news->status = 'close';
            $news->save();
        }
        return redirect()->route('news.index')->with('flash_message','禁用成功');
    }
}
