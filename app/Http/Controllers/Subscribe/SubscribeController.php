<?php

namespace App\Http\Controllers\Subscribe;

use App\Http\Controllers\Controller;
use App\Models\C2cCoin;
use App\Models\C2cReleasePlan;
use App\Models\C2cReleasePlanRecord;
use App\Models\C2cUserRich;
use Illuminate\Http\Request;

class SubscribeController extends Controller
{
    public function index(Request $request)
    {
        $key = $request->key;
        $value = $request->value;
        $peroid = $request->peroid;
        if(isset($value) && isset($peroid)) {
            $datas = C2cReleasePlanRecord::query()
                ->whereHas('user', function ($query) use ($value) {
                    $query->where('invite_code', $value)->orWhere('mobile', $value);
                })
                ->with('c2c_user_auth')
                ->whereHas('c2c_release_plan', function ($query) use ($peroid) {
                    $query->where('name', $peroid);
                })
                ->paginate(10);
        }elseif (isset($value) && empty($peroid)) {
            $datas = C2cReleasePlanRecord::query()
                ->whereHas('user', function ($query) use ($value) {
                    $query->where('invite_code', $value)->orWhere('mobile', $value);
                })
                ->with('c2c_user_auth')
                ->with('c2c_release_plan')
                ->paginate(10);
        }elseif(empty($value) && isset($peroid)){
            $datas = C2cReleasePlanRecord::query()
                ->with('user')
                ->with('c2c_user_auth')
                ->whereHas('c2c_release_plan', function ($query) use ($peroid) {
                    $query->where('name', $peroid);
                })
                ->paginate(10);
        }else {
            $datas = C2cReleasePlanRecord::query()
                ->with('user')
                ->with('c2c_user_auth')
                ->with('c2c_release_plan')
                ->paginate(10);
        }
//        dd($datas);
        return view('subscribe.index',['datas'=>$datas,'key'=>$key,'value'=>$value,'peroid'=>$peroid]);
    }

    
    public function statics()
    {
        $data = C2cReleasePlan::query()
            ->where('status','enabled')
            ->select('name','release_num','released_num')
            ->first();
        $coin = C2cCoin::query()->where('sys_name','=','DATC')->select('id')->first();
        $datas = C2cUserRich::query()
            ->with('user')
            ->where('coin_id','=',$coin->id)
            ->orderByDesc('live_num')
            ->limit(10)
            ->get();
        return view('subscribe.statics',['data'=>$data,'datas'=>$datas]);
    }
}
