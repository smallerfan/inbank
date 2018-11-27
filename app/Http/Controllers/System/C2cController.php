<?php

namespace App\Http\Controllers\System;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Setting;
class C2cController extends Controller
{
    public function index(){
        $datas = Setting::query()
            ->where('module','=','c2c')
            ->get();
        return view("system.c2c.index")->with('datas',$datas);
    }
    public function update(Request $request) {
        $value = $request->all();
        unset($value['_token']); //删除数组中的_token
        foreach ($value as $k=>$v){
            if(isset($v)){
                $update_data = ['config_value'=>$v];
                Setting::query()->where('config_key','=',$k)->update($update_data);
            }
        }
        return redirect()->route("c2c.index")->with('flash_message','保存成功');
    }
}
