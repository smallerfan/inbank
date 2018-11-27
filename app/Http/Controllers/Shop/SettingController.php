<?php

namespace App\Http\Controllers\Shop;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Setting;
class SettingController extends Controller
{
    public function index() {
        $settings = Setting::query()
            ->where('module','=','market')
            ->get();
        return view("shop.setting.index")->with('settings',$settings);
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
        return redirect()->route("settings.index")->with('flash_message','保存成功');
    }
}
