<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class C2cUser extends Model
{
    protected $primaryKey = 'id';

    public $incrementing = false;
    protected $fillable = ["id", "nickname", "country_code", "account_code", "account", "password", "salt", "status", "name_auth"];
    protected $table = 'c2c_users';

    const STATUS = ['enabled' => '正常', 'disable' => '禁用'];

    public function c2c_user_riches() {
        return $this->hasMany("App\Models\C2cUserRich", "uuid");
    }

    public function c2c_rich_records() {
        return $this->hasMany("App\Models\C2cRichRecord", "uuid");
    }

    public function user(){
        return $this->hasOne('App\Models\User','uuid','id');
    }

    public static function gen_uuid() {
        return str_replace("-", "", str_replace("}", "", str_replace("{", "", self::guuid())));
    }

    public function c2c_user_auth() {
        return $this->hasOne("App\Models\C2cUserAuth", 'uuid');
    }

    private static function guuid()
    {
        if (function_exists('com_create_guid')) {
            return com_create_guid();
        } else {
            mt_srand((double)microtime() * 10000);//optional for php 4.2.0 and up.
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45);// "-"
            $uuid = chr(123)// "{"
                . substr($charid, 0, 8) . $hyphen
                . substr($charid, 8, 4) . $hyphen
                . substr($charid, 12, 4) . $hyphen
                . substr($charid, 16, 4) . $hyphen
                . substr($charid, 20, 12)
                . chr(125);// "}"
            return $uuid;
        }
    }

}
