<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Express extends Model
{
    protected $table = 'in_express_list';
    protected $primaryKey = 'id';
    protected $fillable = ['ex_name','ex_code'];
}
