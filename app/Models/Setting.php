<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'in_settings';
    protected $fillable = ['id','config_value','module','title','sort','show_type','created_at','updated_at'];
}
