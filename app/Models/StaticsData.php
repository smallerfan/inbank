<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaticsData extends Model
{
    protected $fillable = ['id','data_type','num','created_at','updated_at'];
    protected $table = 'statics_datas';
    
}
