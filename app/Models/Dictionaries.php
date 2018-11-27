<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

   class Dictionaries extends Model
{
    protected $table="in_dictionaries";
    protected $primaryKey="id";
    protected $fillable = ['module','dic_type','dic_type_name','dic_item','dic_item_name','dic_value','created_at','updated_at'];
    public function m_user(){
        return $this->hasMany('App\Models\Muser','level','id');
    }

    public static function dicAdd($param){
        $res = Dictionaries::create($param);
        return $res->id;
    }
    public static function dicEdit($param,$id){
        $dic = Dictionaries::find($id);
        $dic -> module = $param['module'];
        $dic -> dic_type = $param['dic_type'];
        $dic -> dic_type_name = $param['dic_type_name'];
        $dic -> dic_item = $param['dic_item'];
        $dic -> dic_item_name = $param['dic_item_name'];
        $dic -> dic_value = $param['dic_value'];
        $res = $dic->save();
        return $res;
    }
   public static function dicDelete($id){
       $res = Dictionaries::find($id)->delete();
       return $res;
   }

}
