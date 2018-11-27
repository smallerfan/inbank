<?php

namespace App\Http\Controllers\Shop;

use App\Models\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Facades\UploadedFile;

class CategoryController extends Controller
{
    public function index() {
       $categories = Category::query()->orderByDesc('sort')->paginate(10);
       return view('shop.categories.index')->with('categories', $categories);
    }

    public function create() {
        $categories=Category::query()->where('pid',0)->select('id','name_cn')->get();
        $arrs=array();
        $arrs[0]="请先选择分类";
        foreach ($categories as $k=>$v){
            $arrs[$k+1]=$v['name_cn'];
        }
        return view('shop.categories.create')->with('categories',$arrs);
    }

    public function add(Request $request) {
        $params = $request->all();
        $validator = \Validator::make(
            $params,
            [
                'name_cn'=>'required|max:40',
                'name_hk'=>'required|max:40',
                'name_en'=>'required|max:40',
//                'img'=>'required|file',
                'pid'=>'required',
                'sort' => 'numeric'
            ],
            [
                'required' => ':attribute必填',
                'max' => ':attribute最大值',
                'numeric' => ':attribute为数值类型',
                'file' => ':attribute为文件类型',
            ],[
                'name_cn'=>'分类中文名',
                'name_hk'=>'分类繁体名',
                'name_en'=>'分类英文名',
//                'img'=>'图片',
                'pid'=>'上级分类',
                'sort' => '排序'
            ]
        );
        if($validator->fails()){
            return redirect()->route("categories.index")->withErrors($validator->messages()->first());
        }

        if ($request->hasFile('img')) {
            $img = UploadedFile::file('img')->store();
        }
        $res = Category::query()->create([
            'name_cn' => $request->name_cn,
            'name_hk' => $request->name_hk,
            'name_en' => $request->name_en,
            'pid'     => $request->pid,
            'img'     => $img,
            'sort'    => $request->sort
        ]);
        if($res){
            return redirect()->route("categories.index")->with('flash_message','添加成功');
        }else{
            return redirect()->route("categories.create")->withErrors('添加失败');

        }
    }

    public function edit(Category $category) {
        $categories=Category::query()->where('pid','=',0)->where('id','!=',$category->id)->select('id','name_cn')->get();
        $arrs=array();
        $arrs[0]="请先选择分类";
        foreach ($categories as $k=>$v){
            $arrs[$k+1]=$v['name_cn'];
        }
//        dd($category);
        return view("shop.categories.edit")->with(['categories'=>$arrs,'category'=>$category]);
    }

    public function update(Request $request) {
        $this->validate($request,
            [
                'name_cn'=>'required|max:40',
                'name_hk'=>'required|max:40',
                'name_en'=>'required|max:40',
                'id'=>'required|numeric',
                'img' =>'required|file',
                'pid'=>'numeric',
                'sort' => 'numeric'
            ]);
        
        $id = $request->id;
        if ($request->hasFile('img')) {
            $img = UploadedFile::file('img')->store();
        }
        $category = Category::query()->find($id);
        $category->where('id',$request->id)->update([
            'name_cn' => $request->name_cn,
            'name_hk' => $request->name_hk,
            'name_en' => $request->name_en,
            'img' =>$img,
            'pid' => $request->pid,
            'sort' => $request->sort
        ]);
        return redirect()->route("categories.index")->with('flash_message','保存成功');
    }

    public function destroy($id)
    {
        $category = Category::query()->find($id);
        if($category){
            $category->delete();
            return redirect()->route("categories.index")->with('flash_message','删除成功');
        }else{
            return redirect()->route("categories.index")->with('flash_message','删除失败');
        }
        
    }
}
