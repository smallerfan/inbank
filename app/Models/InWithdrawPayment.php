<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InWithdrawPayment extends Model
{
    protected $table="in_withdraw_payment";
    protected $fillable=['id','pay_type','real_name','account_no','open_bank','code','updated_at'];
    public function bank(){
        return $this->belongsTo('App\Models\InBank','bank_id');
    }
    public function getCodeImgAttribute($value)
    {
        return config('filesystems.disks.qiniu.domain') . ltrim($value, '/');
    }
}
