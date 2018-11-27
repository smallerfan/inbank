<?php
/**
 * Created by PhpStorm.
 * User: ytx13
 * Date: 2018/10/28
 * Time: 13:40
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class AssetsLog extends Model
{
    protected $table = 'market_asset_logs';
    protected $primaryKey = 'id';
    protected $fillable = ['id','uid','muid','award','current_award','from_id',
        'order_user','award_type','award_class','award_level','order_ids','is_run',
        'created_at','updated_at','status','payment'];
    const STATUS = [0=>'待打款',1=>'已打款',2=>'打款驳回'];
    public function user(){
        return $this->belongsTo('App\Models\User','uid');
    }
}