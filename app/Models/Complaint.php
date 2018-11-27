<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    protected $table = 'in_advices';
//建议类型:develop_suggest研发建议，function_problem功能问题，trans_complaint交易投诉，other其它
    const TYPE = ['develop_suggest'=>'研发建议','function_problem'=>'功能问题','trans_complaint'=>'交易投诉','other'=>'其他'];
    const STATUS = ['no_reply'=>'未回复','reply'=>'已回复'];
    public function user(){
        return $this->belongsTo('App\Models\User','uid','id');
    }
}
