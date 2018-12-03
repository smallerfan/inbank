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
    public function store(Request $request){
        $this->validate($request,
            [
                'title_cn'=>'required|max:40',
                'title_hk'=>'nullable|max:40',
                'title_en'=>'nullable|max:80',
                'news_type'=>'required|in:common,urgent',
                'content_cn'=>'required',
                'content_hk'=>'nullable',
                'content_en' => 'nullable',
                'status' => 'required|string|in:open,close',
                'start_time' => 'nullable|date|required_if:news_type,urgent',
                'end_time' => 'nullable|date|after_or_equal:start_time|required_if:news_type,urgent',
            ]);
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
        
        return redirect()->route("news.index")->with('flash_message','添加成功');
    }
    public function edit(News $news){
        $new = News::query()->find($news->id);
        return view('news.edit')->with('new',$new);
    }
    public function update(Request $request){
        $this->validate($request,
            [
                'title_cn'=>'required|max:40',
                'title_hk'=>'nullable|max:40',
                'title_en'=>'nullable|max:80',
                'news_type'=>'required|in:common,urgent,site',
                'content_cn'=>'required',
                'content_hk'=>'nullable',
                'content_en' => 'nullable',
                'status' => 'nullable|string|in:open,close,null',
                'start_time' => 'nullable|date|required_if:news_type,urgent,site',
                'end_time' => 'nullable|date|after_or_equal:start_time|required_if:news_type,urgent,site',
            ]);
        $id = $request->id;
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
        
        return redirect()->route("news.index")->with('flash_message','保存成功');
    
    }
    public function show(News $news){
        $new = News::query()->find($news->id);
        return view('news.show')->with('new',$new);
    }
    public function destroy($id){
        $news = News::query()->find($id);
        if($news){
            $news -> delete();
            return redirect()->route("news.index")->with('flash_message','删除成功');
        }else{
            return redirect()->route("news.index")->with('flash_message','删除失败');
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
