<?php

use \Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Models\Area;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

function validateURL($URL) {
    $pattern = "/^(?:([A-Za-z]+):)?(\/{0,3})([0-9.\-A-Za-z]+)(?::(\d+))?(?:\/([^?#]*))?(?:\?([^#]*))?(?:#(.*))?$/";
    if(preg_match($pattern, $URL)){
        return true;
    } else{
        return false;
    }
}

/**
 * @Desc: 获取配置值
 * @Author: woann <304550409@qq.com>
 * @param $key
 * @return array
 */
function getConfig($key)
{
    if(is_array($key)){
        $res = \DB::table('admin_config')->whereIn('config_key',$key)->get();
        $data = [];
        if($res){
            foreach ($res as $v){
                $data[$v->config_key] = $v->config_value;
            }
        }
        return $data;
    }else{
        $res = \DB::table('admin_config')->where('config_key','=',$key)->first();
        if(!$res){
            return null;
        }
        return $res->config_value;
    }
}
function datetime()
{
    return date('Y-m-d H:i:s', time());
}



function getCode()
{
    return rand(100000, 999999);
}
function get_county($params){
    $data = Area::query()->where('id',$params)->first();
    return $data->name;
}
function get_county_info($params){
    $data = Area::query()->where('id',$params)->first();
    return $data;
}
function get_area_id($params){
    $data = Area::query()->where('code',$params)->first();
    return $data->id;
}
function get_user_name($params){
    $data = User::query()->where('id',$params)->first();
    return $data->username;
}
function get_uid_by_code($params){
    $data = User::query()->where('invite_code',$params)->first();
    return $data->id;
}
function kd_encrypt($data, $appkey) {
    return urlencode(base64_encode(md5($data.$appkey)));
}
function json(int $code = 200,string $msg = '',$data = [])
{
    if ($data == []) {
        $res = [
            'code'  =>$code,
            'msg'   =>$msg,
        ];
    }else{
        $res = [
            'code'  =>$code,
            'msg'   =>$msg,
            'data'  =>$data
        ];
    }
    return response()->json($res);
}
function fen_ye($page,$data){
    $perPage = 10;                                 // 每页显示数量
    if ($page) {                  // 请求是第几页，如果没有传page数据，则默认为1
        $current_page = $page;
        $current_page = $current_page <= 0 ? 1 :$current_page;
    } else {
        $current_page = 1;
    }

    $item = array_slice($data, ($current_page-1)*$perPage, $perPage); // 注释1

    $total = count($data);                                           // 查询总数

    $paginator =new LengthAwarePaginator($item, $total, $perPage, $current_page, [
        'path' => Paginator::resolveCurrentPath(),                // 注释2
        'pageName' => 'page',
    ]);
    return $paginator;
}
