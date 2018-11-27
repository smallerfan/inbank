<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class C2cRichLog extends Model
{
    protected $table = 'c2c_rich_logs';
    protected $fillable = ['uuid','log_type','coin_id','rich_num','cur_live_num','cur_frozen_num'];
    public static function addLog($params){
        $res = C2cRichLog::create($params);
        return $res->id;
    }
}
