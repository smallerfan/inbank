<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RichLog extends Model
{
    protected $table = 'in_rich_logs';

    protected $fillable = [ 'record_id', 'log_type', 'rich_type', 'get_num', 'cur_live_dk', 'cur_frozen_dk', 'cur_dn', 'user_type' ];

    //
    public function rich_trans_log(){
        return $this->belongsTo('App\Models\RichTransLog','id');
    }

    public function user(){
        return $this->belongsTo('App\Models\User','uid');
    }
}
