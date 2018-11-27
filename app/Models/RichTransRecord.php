<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RichTransRecord extends Model
{
    protected $table='rich_trans_records';
    
    protected $fillable = ['trans_type', 'get_id', 'pay_id'];
    
    public function rich_log(){
        return $this->hasMany('App\Models\RichLog','record_id');
    }
    public function trans_pay_user(){
        return $this->belongsTo('App\Models\User','pay_id');
    }
    
    public function trans_get_user(){
        return $this->belongsTo('App\Models\User','get_id');
    }
}
