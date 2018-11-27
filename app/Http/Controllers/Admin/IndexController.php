<?php

namespace App\Http\Controllers\Admin;
use App\Utility\Video;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Storage;
use DB;
use App\Models\Assets;
use App\Models\AssetsLog;
use App\models\ShopOrder;
use App\Models\User;

class IndexController extends Controller
{
    public function index()
    {
        $admin = session('admin');
        /** @noinspection Annotator */
        $user_role = DB::table('admin_user_role')->where('admin_user_id',$admin->id)->first();
        $role_id = $user_role->role_id;
        $menu_list = DB::table('admin_role_menu as rm')
            ->leftJoin('admin_menu as m','m.id','=','rm.menu_id')
            ->where('rm.role_id',$role_id)
            ->where('m.pid',0)
            ->select('m.*')
            ->orderBy('m.sort','DESC')
            ->get();
        foreach ($menu_list as $k=>$v){
            $menu_list[$k]->child = DB::table('admin_role_menu as rm')
                            ->leftJoin('admin_menu as m','m.id','=','rm.menu_id')
                            ->where('rm.role_id',$role_id)
                            ->where('m.pid',$v->id)
                            ->select('m.*')
                            ->orderBy('m.sort','DESC')
                            ->get();
            if(count($menu_list[$k]->child)){
                $menu_list[$k]->has_child = true;
            }else{
                $menu_list[$k]->has_child = false;
            }
        }
        return view('admin.index',['menu'=>$menu_list]);
    }
    public function console()
    {
        $users = User::query()->where('status','=','enable')->count();
        $lock = User::query()->where('status','=','lock')->count();
        $trans_lock = User::query()->where('status','=','trans_lock')->count();
        $sum['users'] = $users;
        $sum['lock'] = $lock;
        $sum['trans_lock'] = $trans_lock;
        //报单产品订单收益总和
        $special_order = ShopOrder::query()->whereHas('order_detail.goods',function ($q){
            $q->where('is_special',1);
        })->sum('amount');
        //其他商品订单收益（已完成）
        $over_order = ShopOrder::query()->whereHas('order_detail.goods',function ($q){
            $q->where('is_special',0);
        })->where('status','=','complete')->sum('amount');
        //已支付未完成的订单收益总和
        $payed_order = ShopOrder::query()->whereHas('order_detail.goods',function ($q){
            $q->where('is_special',0);
        })->where('status','=','wait_collect')->orWhere('status','=','wait_deliver')->sum('amount');
        //总的用户分红
        $user_get = Assets::query()->find(0);
//        dd($user_get);
        //报单的用户分红
        $special_user_get = AssetsLog::query()->where('uid',0)->where('award_type',1)->sum('award');
        //普通商品的用户分红
        $over_user_get = AssetsLog::query()->where('uid',0)->where('award_type',0)->sum('award');
        $all_sum = $special_order+$over_order;
        $sum['special'] = [];
        $sum['special']['sum'] = abs($special_order);
        $sum['special']['product_cost'] = abs($special_order*0.1);
        $sum['special']['operating_cost'] = abs($special_order*0.12);
        $sum['special']['other'] = abs($special_order*0.78);
        $sum['special']['user_get'] = abs($special_user_get);
        $sum['special']['system_get'] = abs($sum['special']['other']-$special_user_get);
        $sum['over'] = [];
        $sum['over']['sum'] = abs($over_order);
        $sum['over']['product_cost'] = abs($over_order*0.1);
        $sum['over']['operating_cost'] = abs($over_order*0.12);
        $sum['over']['other'] = abs($over_order*0.78);
        $sum['over']['user_get'] = abs($over_user_get);
        $sum['over']['system_get'] = abs($sum['over']['other']-$over_user_get);
        $sum['all'] = abs($all_sum);
        $sum['payed'] = abs($payed_order);
        $sum['all'] = [];
        $sum['all']['sum'] = abs($all_sum);
        $sum['all']['product_cost'] = abs($all_sum*0.1);
        $sum['all']['operating_cost'] = abs($all_sum*0.12);
        $sum['all']['other'] = abs($all_sum*0.78);
//        $sum['all']['user_get'] = abs($user_get->history_award);
//        $sum['all']['system_get'] = abs($sum['all']['other']-$user_get->history_award);
//        $sum['all'] = abs($all_sum);
        $sum['payed'] = abs($payed_order);
        return view('admin.console')->with('sum',$sum);
    }

    /**
     * @Desc: 后台图片上传
     * @Author: woann <304550409@qq.com>
     * @param Request $request
     * @return mixed
     */
    public function upload(Request $request)
    {
        $file = $request->file('image');
        $path = $request->input('path').'/';
        if($file){
            if($file->isValid()) {
                $size = $file->getSize();
                if($size > 5000000){
                    return $this->json(500,'图片不能大于5M！');
                }
                // 获取文件相关信息
                $ext = $file->getClientOriginalExtension();     // 扩展名
                if(!in_array($ext,['png','jpg','gif','jpeg','pem']))
                {
                    return $this->json(500,'文件类型不正确！');
                }
                $realPath = $file->getRealPath();   //临时文件的绝对路径
                // 上传文件
                $filename = $path.date('Ymd').'/'.uniqid() . '.' . $ext;
                // 使用我们新建的uploads本地存储空间（目录）
                $bool = Storage::disk('admin')->put($filename, file_get_contents($realPath));
                if($bool){
                    return $this->json(200,'上传成功',['filename'=>'/uploads/'.$filename]);
                }else{
                    return $this->json(500,'上传失败！');
                }
            }else{
                return $this->json(500,'文件类型不正确！');
            }
        }else{
            return $this->json(500,'上传失败！');
        }
    }

    /**
     * @Desc: 富文本上传图片
     * @Author: woann <304550409@qq.com>
     * @param Request $request
     */
    public function wangeditorUpload(Request $request)
    {
        $file = $request->file('wangEditorH5File');
        if($file){
            if($file->isValid()) {
                // 获取文件相关信息
                $ext = $file->getClientOriginalExtension();     // 扩展名
                $realPath = $file->getRealPath();   //临时文件的绝对路径
                // 上传文件
                $filename = date('Ymd') . '/' . uniqid() . '.' . $ext;
                // 使用我们新建的uploads本地存储空间（目录）
                $bool = Storage::disk('admin')->put('/wangeditor/'.$filename, file_get_contents($realPath));
                if($bool){
                    echo asset('/uploads/wangeditor/'.$filename);
                }else{
                    echo 'error|上传失败';
                }
            }else{
                echo 'error|上传失败';
            }
        }else{
            echo 'error|图片类型不正确';
        }
    }

    /**
     * @Desc: 无权限界面
     * @Author: woann <304550409@qq.com>
     * @return \Illuminate\View\View
     */
    public function noPermission()
    {
        return view('base.403');
    }
}
