<?php

namespace App\Http\Controllers\System;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Setting;
class SystemController extends Controller
{
    public function index(){
        $datas = Setting::query()
//            ->whereNotIn('module',['datc','shop','c2c'])
//            ->group('module')
            ->get()
            ->groupBy('module');

        foreach ($datas as $k=>$v){

            foreach ($v as $key => $value){
                if($value->show_type == 'checkbox'){
                    $value->config_value=explode(',',$value->config_value);
                }
            }
        }
//        dd($datas);
        return view("system.system.index")->with('datas',$datas);
    }
    public function update(Request $request) {
        $value = $request->all();
        unset($value['_token']); //删除数组中的_token
        foreach ($value as $k=>$v){
            if(isset($v)){
                if(is_array($v)){
                    $v = implode(',',$v);
                }
                $update_data = ['config_value'=>$v];
                Setting::query()->where('config_key','=',$k)->update($update_data);
            }
        }
        return redirect()->route("system.index")->with('flash_message','保存成功');
    }
}
