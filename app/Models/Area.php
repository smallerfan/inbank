<?php
/**
 * Created by PhpStorm.
 * User: ytx13
 * Date: 2018/11/5
 * Time: 14:05
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $table = 'in_areas';
    protected $primaryKey = 'id';
    protected $fillable = ['code','name','parent_id','first_letter','level','created_at','updated_at'];
}