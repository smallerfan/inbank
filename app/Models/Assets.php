<?php
/**
 * Created by PhpStorm.
 * User: ytx13
 * Date: 2018/10/28
 * Time: 13:40
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Assets extends Model
{
    protected $table = 'market_assets';
    protected $primaryKey = 'id';
    protected $fillable = ['uid','muid','history_award','live_assets','shopping_assets','created_at','update_at'];

}