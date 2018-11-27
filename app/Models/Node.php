<?php
/**
 * Created by PhpStorm.
 * User: ytx13
 * Date: 2018/10/28
 * Time: 13:40
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Node extends Model
{
    protected $table = 'market_valid_nodes';
    protected $primaryKey = 'id';
    protected $fillable = ['uid','invited_uid','created_at','updated_at'];

}