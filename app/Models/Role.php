<?php
/**
 * Created by PhpStorm.
 * User: ytx13
 * Date: 2018/11/5
 * Time: 10:47
 */

namespace App\Models;


use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{
    protected $primaryKey = 'id';
    protected $fillable = ['name','display_name','description','created_at','updated_at'];
    public static function addRole($params){
        $res = Role::create($params);
        return $res;
    }
}