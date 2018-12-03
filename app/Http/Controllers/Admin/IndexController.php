<?php

namespace App\Http\Controllers\Admin;

use App\Facades\UploadedFile;
use App\Models\Muser;
use App\Utility\Video;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Storage;
use DB;
use App\Models\Assets;
use App\Models\AssetsLog;
use App\models\ShopOrder;
use App\Models\User;
use Qiniu\Auth;
use Qiniu\Storage\BucketManager;

class IndexController extends Controller
{
    public function __construct()
    {
        $this->auth = new Auth(config('filesystems.disks.qiniu.access_key'),config('filesystems.disks.qiniu.secret_key'));
        $this->backetManager = new BucketManager($this->auth);
    }
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
    public function console(Request $request)
    {
        $type = $request->type;
        if(empty($type)){
            $type=0;
        }
        $times = [
            ['time'=>'day','count'=>0],
            ['time'=>'day','count'=>1],
            ['time'=>'day','count'=>7],
            ['time'=>'month','count'=>1],
        ];
        foreach ($times as $k=>$time){
            $date = date('Y-m-d',strtotime('-'.$time['count'].' '.$time['time'],time()));
            if($time['time'] == 'month'){
                $dayNum = 30;
            }else{
                $dayNum = $time['count'];
            }
            $data[$k]=$this->order_range_data($date,$dayNum,0);
            if($time['time'] == 'day'){
                if($time['count'] == 0){
                    $data[$k]['tip'] = '今日';
                }elseif($time['count'] == 1){
                    $data[$k]['tip'] = '昨日';
                }else{
                    $data[$k]['tip'] = '近'.$time['count'].'日';
                }
            }else{
                $data[$k]['tip'] = '近'.$time['count'].'个月';
            }
        }
        foreach ($times as $k=>$time){
            $date = date('Y-m-d',strtotime('-'.$time['count'].' '.$time['time'],time()));
            if($time['time'] == 'month'){
                $dayNum = 30;
            }else{
                $dayNum = $time['count'];
            }
            $datas[$k]=$this->order_range_data($date,$dayNum,1);
            if($time['time'] == 'day'){
                if($time['count'] == 0){
                    $datas[$k]['tip'] = '今日';
                }elseif($time['count'] == 1){
                    $datas[$k]['tip'] = '昨日';
                }else{
                    $datas[$k]['tip'] = '近'.$time['count'].'日';
                }
            }else{
                $datas[$k]['tip'] = '近'.$time['count'].'个月';
            }
        }
        //七天交易走势图
        $trade_data['num'] = [];
        $trade_data['count'] = [];
        $trade_data['sum'] = [];
        $trade_data['amount'] = [];
        $trade_data1['num'] = [];
        $trade_data1['count'] = [];
        $trade_data1['sum'] = [];
        $trade_data1['amount'] = [];
        for($i=6;$i>=0;$i--){
            $trade_date = date('Y-m-d',strtotime('-'.$i.' day',time()));
            $brokenLine[] = $trade_date;
            $order=$this->order_data($trade_date,0);
            array_push($trade_data['num'],$order['num']);
            array_push($trade_data['count'],$order['count']);
            array_push($trade_data['sum'],$order['sum']);
            array_push($trade_data['amount'],$order['amount']);
        }
        for($i=6;$i>=0;$i--){
            $trade_date1 = date('Y-m-d',strtotime('-'.$i.' day',time()));
            $brokenLine1[] = $trade_date1;
            $order1=$this->order_data($trade_date1,1);
            array_push($trade_data1['num'],$order1['num']);
            array_push($trade_data1['count'],$order1['count']);
            array_push($trade_data1['sum'],$order1['sum']);
            array_push($trade_data1['amount'],$order1['amount']);
        }
        $data1=$data;
        $data2=$datas;
        $types=$brokenLine;
        $type1=$brokenLine1;
        $line=$trade_data;
        $line1=$trade_data1;

        $levels = get_dictionaries_settings('user_level','market');
        $names=[];
        $data3 = [];
        foreach ($levels as $k=>$level){
            $num = Muser::query()->where('level',$level->dic_item)->count('id');
            $data3[$k]['value']=$num;
            $data3[$k]['name']=$level->dic_item_name;
            array_push($names,$level->dic_item_name);
        }
        $data3=json_encode($data3);
        $levels=json_encode($names);


        return view('admin.console')->with(
            ['datas'=>$data1,'data2'=>$data2,'line'=>json_encode($line),'line1'=>json_encode($line1),'types'=>json_encode($types),'type1'=>json_encode($type1),'flag'=>0,'levels'=>$levels,'data3'=>$data3]
        );
//        return view('admin.console')->with('sum',$sum)->with(['datas'=>$data,'levels'=>$levels]);
    }
    private function order_data($date,$type=0,$avg = false){
        if($avg){
            $data['average'] = ShopOrder::query()
                ->where('order_type',$type)
                ->whereDate('created_at',$date)
                ->avg('amount')?:0.0;
        }
        $data['num'] = ShopOrder::query()
            ->where('order_type',$type)
            ->whereDate('created_at',$date)
            ->where('status','!=','close')
            ->count('id');
        $data['count'] = ShopOrder::query()
            ->where('order_type',$type)
            ->whereDate('created_at',$date)
            ->where('status','=','complete')
            ->count('id');
        $data['sum'] =  ShopOrder::query()
            ->where('order_type',$type)
            ->whereDate('created_at',$date)
            ->where('status','!=','close')
            ->sum('amount');
        $data['amount'] = ShopOrder::query()
            ->where('order_type',$type)
            ->whereDate('created_at',$date)
            ->where('status','=','complete')
            ->sum('amount');
        return $data;
    }
    private function order_range_data($date,$dayNum,$type=0){
        if($dayNum <= 1){
            $data = $this->order_data($date,$type,true);
        }else{
            $data['average'] = ShopOrder::query()
                ->where('order_type',$type)
                ->whereDate('created_at','>=',$date)
                ->avg('amount')?:0.0;
            $data['num'] = ShopOrder::query()
                ->where('order_type',$type)
                ->whereDate('created_at','>=',$date)
                ->where('status','!=','close')
                ->count('id');
            $data['count'] = ShopOrder::query()
                ->where('order_type',$type)
                ->whereDate('created_at','>=',$date)
                ->where('status','=','complete')
                ->count('id');
            $data['sum'] =  ShopOrder::query()
                ->where('order_type',$type)
                ->whereDate('created_at','>=',$date)
                ->where('status','!=','close')
                ->sum('amount');
            $data['amount'] = ShopOrder::query()
                ->where('order_type',$type)
                ->whereDate('created_at','>=',$date)
                ->where('status','=','complete')
                ->sum('amount');
        }

        return $data;
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
//                $realPath = $file->getRealPath();   //临时文件的绝对路径
//                // 上传文件
//                $filename = $path.date('Ymd').'/'.uniqid() . '.' . $ext;
                // 使用我们新建的uploads本地存储空间（目录）
                $img_main = UploadedFile::file('image')->store();
//                $bool = Storage::disk('admin')->put($filename, file_get_contents($realPath));
                if($img_main){
                    return $this->json(200,'上传成功',['filename'=>$img_main,'domain' => config('filesystems.disks.qiniu.domain')]);
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
//        dd(1111);
        $file = $request->file('wangEditorH5File');
        if($file){
            if($file->isValid()) {
                // 获取文件相关信息
//                $ext = $file->getClientOriginalExtension();     // 扩展名
//                $realPath = $file->getRealPath();   //临时文件的绝对路径
//                // 上传文件
//                $filename = date('Ymd') . '/' . uniqid() . '.' . $ext;
//                // 使用我们新建的uploads本地存储空间（目录）
                $img_main = UploadedFile::file('wangEditorH5File')->store();
//                $bool = Storage::disk('admin')->put('/wangeditor/'.$filename, file_get_contents($realPath));
                if($img_main){
                    echo asset(config('filesystems.disks.qiniu.domain').$img_main);
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
