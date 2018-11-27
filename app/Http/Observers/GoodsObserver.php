<?php

namespace App\Http\Observers;

use App\Models\Goods;
use App\models\Loan;

class GoodsObserver {

    /*  可监听的事件
        'retrieved',
        'creating',
        'created',
        'updating',
        'updated',
        'saving',
        'saved',
        'restoring',
        'restored',
        'deleting',
        'deleted',
        'forceDeleted',
    */

    public function creating()
    {

    }
    public function updated(Goods $goods)
    {
        if ($goods->isDirty('price')){
            $is_exist = Loan::where('goods_id',$goods->id)->count();
            if($is_exist > 0){
                $res = Loan::updatePrice($goods->id);
            }
        }
    }

}
