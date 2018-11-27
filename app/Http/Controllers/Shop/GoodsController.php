<?php

namespace App\Http\Controllers\Shop;

use App\Facades\UploadedFile;
use App\Http\Controllers\Controller;
use App\Http\Requests\SortFormRequest;
use App\Models\Category;
use App\Models\Goods;
use App\models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Qiniu\Auth;
use Qiniu\Storage\BucketManager;

class GoodsController extends Controller {

    public $auth;

    public function __construct()
    {
        $this->auth = new Auth(config('filesystems.disks.qiniu.access_key'),config('filesystems.disks.qiniu.secret_key'));
        $this->backetManager = new BucketManager($this->auth);
    }

    public function index(Request $request)
    {
        $value = $request->value;
        if(isset($value)){
            $datas = Goods::query()
//                ->with('shoper')
                ->with('category')
                ->with('goods_channel')
                ->where('name','like','%'.$value.'%')
//                ->orderBy('status','asc')
                ->orderByDesc('sort')
                ->orderByDesc('created_at')
                //                ->orderBy('id','asc')
                ->paginate(10);
        }else{
            $datas = Goods::query()
//                ->with('shoper')
                ->with('category')
                ->with('goods_channel')
//                ->orderBy('status','desc')
                ->orderByDesc('sort')
                ->orderByDesc('created_at')
//                ->orderBy('id','asc')
                ->paginate(10);
        }
        
        return view('shop.goods.index')->with('datas',$datas);
    }

    //七牛云上传的token
    public function up_token(){
        $bucket = config('filesystems.disks.qiniu.bucket');
        $accessKey = config('filesystems.disks.qiniu.access_key');
        $secretKey = config('filesystems.disks.qiniu.secret_key');
        $auth = new Auth($accessKey, $secretKey);
        $upToken = $auth->uploadToken($bucket);
        return $upToken;
    }

    public function create(Request $request){
        $up_token = $this->up_token();
        $goods_cate = Category::query()
            ->select(['id','name_cn'])->get()->toArray();
//        dd($goods_cate);
        foreach ($goods_cate as $k => $v){
            $goods_cate[$v['id']] = $v['name_cn'];
        }
        unset($goods_cate[0]);
        return view('shop.goods.add')->with(['cate'=>$goods_cate,'up_token'=>$up_token]);
    }

    public function addGoods(Request $request){
        $params = $request->all();
        $validator = \Validator::make(
            $params,
            [
                'name' => 'required',
                'price' => 'required',
                'intro' => 'required',
                'sort' => 'required',
            ],
            [
                'required' => ':attribute必填',
            ],[
                'name' => '商品名称',
                'price' => '商品单价',
                'intro' => '商品介绍',
                'sort' => '排序',
            ]
        );
        if($validator->fails()){
             return redirect("shop/goods/create")->withErrors($validator->messages()->first());
        }
        $img_main = UploadedFile::file('main_img')->store();
        $img_info = UploadedFile::file('info_img')->store();
        $good_info = [
            'goods_type' => $params['goods_type'],
            'shoper_id' => 1,
            'category_id' => $params['category_id'],
            'name' => $params['name'],
            'price' => $params['price'],
            'is_stock' => $params['is_stock'],
            'stock' => $params['stock'],
            'intro' => $params['intro'],
            'imgs' => implode(',',$img_main),
            'detail_imgs' => implode(',',$img_info),
            'is_usable' => 'enable',
            'status' => 'pass_approval',
            'sort' => $params['sort'],
        ];
        $res = Goods::goodAdd($good_info);
        if($res){
            return redirect("shop/goods")->with('flash_message','新增商品成功');
        }else{
            return redirect("shop/goods/create")->withErrors('新增出错');
        }

    }

    /**
     * 下载七牛云的数据,并添加限时token
     * @param $fileName '文件名'
     * @return string 返回链接字符串
     */
    public function downloadFile($fileName)
    {
        if(strpos($fileName,'http://') === false && strpos($fileName,env('QINIU_DOMAIN')) ===false){
            $baseUrl = config('filesystems.disks.qiniu.domain').'/'.$fileName;
        }else if (strpos($fileName,'http://') === false){
            $baseUrl = $fileName;
        }else{
            $baseUrl = $fileName;
        }
        $authUrl = $this->auth->privateDownloadUrl($baseUrl);
        return $authUrl;
    }

    public function edit(Request $request,$id) {
        $up_token = $this->up_token();
        $params = $request->all();

        if(empty($params)){
            $goods_cate = Category::query()
                ->select(['id','name_cn'])->get()->toArray();
            foreach ($goods_cate as $k => $v){
                $goods_cate[$v['id']] = $v['name_cn'];
            }
            unset($goods_cate[0]);
            $goods = Goods::find($id);
            $imgs =  $goods->imgs =explode(',',$goods->imgs);
            $detail = $goods->detail_imgs = explode(',',$goods->detail_imgs);

            foreach ($imgs as $k => $v){
                $imgs[$k] = $this->downloadFile($v);
            }
            foreach ($detail as $k =>$v){
                $detail[$k] = $this->downloadFile($v);
            }
            $goods->imgs = $imgs;
            $goods->detail_imgs = $detail;
            return view("shop.goods.edit")->with(['goods'=>$goods,'up_token'=>$up_token,'cate'=>$goods_cate]);
        }else{
            $validator = \Validator::make(
                $params,
                [
                    'name' => 'required',
                    'price' => 'required',
                    'intro' => 'required',
                    'sort' => 'required|integer',
                ],
                [
                    'required' => ':attribute必填',
                    'integer' => ':attribute比为数字项',
                ],[
                    'name' => '商品名称',
                    'price' => '商品单价',
                    'intro' => '商品介绍',
                    'sort' => '排序',
                ]
            );
            if($validator->fails()){
                return redirect("shop/goods/create")->withErrors($validator->messages()->first());
            }
            $goods = Goods::find($id);
            $files_main_img = $request->file('main_img');
            $files_info_img = $request->file('info_img');
            if($files_main_img == null){
                $params['imgs'] = $goods->imgs ;
            }else{
                $img_main = UploadedFile::file('main_img')->store();
                $params['imgs'] = implode(',',$img_main);
            }
            if($files_info_img == null){
                $params['detail_imgs'] = $goods->detail_imgs ;
            }else{
                $img_info = UploadedFile::file('info_img')->store();
                $params['detail_imgs'] = implode(',',$img_info);
            }
            $update_goods = Goods::updateGoods($params,$id);
            if($update_goods){
                return redirect("shop/goods")->with('flash_message','修改成功');
            }else{
                return redirect("shop/goods")->withErrors('修改失败');
            }
        }

    }

    
    public function update(Request $request) {
        $remark = $request->reason;
        $choice = $request->choice;
        if($choice == 'pass_approval'){
            $this->validate($request, ['reason'=>'nullable|max:50|string','id' =>'required|numeric', 'choice' => 'required|string']);
        }else{
            $this->validate($request, ['reason'=>'required|max:50|string','id' =>'required|numeric', 'choice' => 'required|string']);
        }
        $id=$request->id;
        $goods = Goods::query()->find($id);
        $goods->fill(['approval_reason' => $remark, 'status' => $choice,'approval_time'=>datetime()])->save();
        return redirect("shop/goods")->with('flash_message','提交成功');
    }
    //删除
    public function destroy($id) {
        $goods = Goods::deleteGoods($id);
//        $goods->goods_channel->delete();
        if($goods){
            return redirect("shop/goods")->with('flash_message','删除成功');
        }else{
            return redirect("shop/goods")->withErrors('删除失败');
        }
    }
   
    //    设为正常
    public function set_enable($goods_id) {
        $goods = Goods::query()->find($goods_id);
        if(!$goods->exists()){
            flash('商品不存在')->success();
        }
        $goods->is_usable='enable';
        $goods->save();
        flash('设置成功')->success();
        return redirect('shop/goods');
    }
    //    设为禁止
    public function set_disable($goods_id) {
        $goods = Goods::query()->find($goods_id);
        if(!$goods->exists()){
            flash('商品不存在')->success();
        }
        $goods->is_usable='disable';
        $goods->save();
        flash('禁止成功')->success();
        return redirect('shop/goods');
    }
    //    成为热销
    public function set_hot_sale($goods_id) {
        $goods = Goods::query()->find($goods_id);
        $data=['channel_type'=>'hot_sale'];
        if($goods->has_no_channel->count() == 0){
            $goods->has_no_channel()->create($data);
        }elseif($goods->has_no_channel->count() > 0){
            $goods->has_no_channel[0]->channel_type='hot_sale';
            $goods->has_no_channel[0]->save();
        }
        
        flash('设置成功')->success();
        return redirect('shop/goods');
    }
    //    取消热销
    public function unset_hot_sale($goods_id) {
        $goods = Goods::query()->find($goods_id);
        
        if(!$goods->exists()){
            flash('商品不存在')->success();
        }
        if($goods->has_hot_sale->count() > 0){
            $goods->has_channel[0]->channel_type=null;
            $goods->has_channel[0]->save();
        }
        flash('取消成功')->success();
        return redirect('shop/goods');
    }
    //设为报单产品
    public function updateSpecial($id){
        $goods = Goods::find($id);
        if($goods->is_special == 0){
            $goods->is_special = 1;
        }else{
            $goods->is_special = 0;
        }
        $res = $goods->save();
        if(!$res){
            return redirect("shop/goods")->withErrors('设置失败');
        }
        $is_loan = Loan::where('goods_id',$id)->get();
        if(count($is_loan) > 0){
            $delete_loan = Loan::where('goods_id',$id)->delete();
//            dd($delete_loan);
        }else{
            $loan = Loan::addLoan($id);
            if(!$loan){
                return redirect("shop/goods")->withErrors('设置失败');
            }
        }

        flash('设置成功')->success();
        return redirect('shop/goods');
    }
    
    //    成为自营
    public function set_choiceness_self($goods_id) {
        $goods = Goods::query()->find($goods_id);
        $data=['channel_type'=>'choiceness_self'];
        if($goods->has_choiceness_self->count() == 0){
            $goods->has_choiceness_self()->create($data);
        }elseif($goods->has_no_channel->count() > 0){
            $goods->has_no_channel[0]->channel_type='choiceness_self';
            $goods->has_no_channel[0]->save();
        }
        
        flash('设置成功')->success();
        return redirect('shop/goods');
    }
    //    取消自营
    public function unset_choiceness_self($goods_id) {
        $goods = Goods::query()->find($goods_id);
        if(!$goods->exists()){
            flash('商品不存在')->success();
        }
        if($goods->has_choiceness_self->count() > 0){
            $goods->has_choiceness_self[0]->channel_type=null;
            $goods->has_choiceness_self[0]->save();
        }
        flash('取消成功')->success();
        return redirect('shop/goods');
    }
    //    设置排序
    public function set_sort(SortFormRequest $request) {
        $id=$request->id;
        $sort=$request->sort;
        $goods = Goods::query()->find($id);
        if(!$goods->exists()){
            return 0;
        }
        $goods->sort=$sort;
        $goods->save();
        return 200;
    }
}
