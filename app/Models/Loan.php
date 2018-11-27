<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $table="in_loan_limit_settings";
    protected $primaryKey = 'id';
    protected $fillable = ['goods_id','consume','loan_limit','release_period','created_at','updated_at'];

    public static function addLoan($goods_id){
        $goods = Goods::find($goods_id);
        $params = [
            'goods_id' => $goods_id,
            'consume' => $goods->price,
            'loan_limit' => ($goods->price)*6,
            'release_period' => 15
        ];
        $res = Loan::create($params);
        return $res->id;
    }
    public static function updatePrice($goods_id){
        $goods = Goods::find($goods_id);
        $loan = Loan::where('goods_id',$goods_id)->first();
        $loan->consume = $goods->price;
        $loan->loan_limit = ($goods->price)*6;
        $res = $loan->save();
        return $res;
    }

}
