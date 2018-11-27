<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
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
}
