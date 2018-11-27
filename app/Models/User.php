<?php

namespace App\Models;;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class User extends Model
{
    use Notifiable;
    protected $table="in_users";
    protected $primaryKey = 'id';


    const GRADE = ['interim' => '普通', 'common' => '合格', 'vip' => 'VIP', 'svip' => 'SVIP'];

    const STATUS = [ 'deleted' => '删除', 'lock' => '锁定', 'enable' => '正常', 'trans_lock' => '交易锁定'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'country_code', 'account', 'password','uuid','invite_code','mobile','is_saler',
       'province', 'city', 'county', 'mobile', 'reg_ip',
       'user_sn', 'username', 'salt', 'password', 'pay_salt', 'pay_pwd', 'status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'safety_password', 'pay_pwd', 'password_confirmation', 'old_password'
    ];

    public function user_rich(){
        return $this->hasOne('App\Models\UserRich','uid');
    }
    public function rich_logs(){
        return $this->hasMany('App\Models\RichLog','uid');
    }

    public function shop_orders(){
        return $this->hasMany('App\Models\ShopOrder','buy_uid','id');
    }

    public function pay_rich_trans_records(){
        return $this->hasMany('App\Models\RichTransRecord','pay_id');
    }

    public function get_rich_trans_records() {
        return $this->hasMany('App\Models\RichTransRecord','get_id');
    }

    public function trans_record(){
       return $this->belongsTo('App\Models\RichTransRecord','pay_id');
    }
    public function shoper(){
        return $this->hasOne('App\Models\Shoper','uid','id');
    }
    public function parent_user() {
       return $this->belongsTo('App\Models\User',  'pid');
    }

    public function children() {
       return $this->hasMany('App\Models\User',  'pid');
    }
    public function c2c_release_plan_record() {
        return $this->hasMany('App\Models\C2cReleasePlanRecord', 'uuid','uuid');
    }
    public function c2c_user_rich() {
        return $this->hasOne('App\Models\C2cUserRich', 'uuid','uuid');
    }
    public function direct_user_count() {
        return $this->children()->notDeleted()->notInterim()->count();
    }

    public function c2c_user_auth()
    {
        return $this->hasOne('App\Models\C2cUserAuth', 'uid');
    }
    public function c2c_user()
    {
        return $this->belongsTo('App\Models\C2cUser', 'uuid','uuid');
    }
    public function complaint()
    {
        return $this->hasMany('App\Models\Complaint', 'uid','uid');
    }
    public function recharge_extract()
    {
        return $this->hasMany('App\Models\RechargeExtract', 'uuid','uuid');
    }
    public function m_user(){
        return $this->hasOne('App\Models\Muser','uid','id');
    }

    public function c2c_published_orders() {
        return $this->hasMany("App\Models\C2cPublishedOrder", 'uid');
    }

    public function payments() {
        return $this->hasMany('App\Models\InPayment', 'uid');
    }

    public static function encrypt_password($pwd, $salt) {
       return md5(md5($pwd).$salt);
    }

    public function checkPayPwd($pwd){
        if(!$this->pay_pwd || !$this->pay_salt){
            return 'auth.pay_pwd_unset';
        }
        $new=self::encrypt_password($pwd,$this->pay_salt);
        if($new == $this->pay_pwd){
            return true;
        }else{
            return 'auth.transfer_pay_password_error';
        }
    }

    public function is_common_user() {
       return $this->user_grade === "common";
    }

    public function is_vip_user() {
        return $this->user_grade === "vip";
    }

    public function is_svip_user() {
        return $this->user_grade === 'svip';
    }

    public function is_interim_user() {
        return $this->user_grade === 'interim';
    }

    public function scopeNotDeleted($query)
    {
        return $query->where('status', '!=', 'deleted');
    }

    public function scopeNotInterim($query)
    {
        return $query->where('user_grade', '!=', 'interim');
    }

    public function is_deleted() {
       return $this->status === 'deleted';
    }
    public function is_enabled(){
        return $this->status === 'enable';
    }

    public function is_locked() {
        return $this->status === 'lock';
    }

    public function is_trans_locked() {
        return $this->status === 'trans_lock';
    }
    public function trans_order(){
        return $this->hasMany('App\Models\C2cTransOrder','uid','id');
    }
    public function publish_order(){
        return $this->hasMany('App\Models\C2cPublishedOrder','uid','id');
    }

    /***
     * dn, dk下拨与收回并记录
     * @param $release_dk_num
     * @param $release_dn_num
     * @param $record_id
     */
    public function save_and_log($release_dk_num, $release_dn_num, $record_id,$log_type,$user_type=1) {
        //如果释放记录为零，则不记录;
        if($release_dk_num == 0 && $release_dn_num == 0) { return; }

        $dn_num=$this->user_rich->dn_num;
        $dk_num=$this->user_rich->live_dk_num;

        //用户的dn账户的值不能为负数;
        if($release_dn_num < 0 && $dn_num < abs($release_dn_num)){return;}
        if($release_dk_num < 0 && $dk_num < abs($release_dk_num)){return;}

        $this->user_rich->dn_num += $release_dn_num;
        $this->user_rich->live_dk_num += $release_dk_num;


        if($this->user_rich->live_dk_num < 0) {
           throw new Exception('DK余额不足');
        }

        if($this->user_rich->dn_num < 0) {
            throw new Exception('DN余额不足');
        }

        $this->user_rich->save();

        if($release_dk_num != 0){
            $this->rich_logs()->create([ 'record_id'=> $record_id, 'user_type'=>$user_type , 'log_type' => $log_type, 'rich_type' => 0, 'get_num' => $release_dk_num ]);
        }
        if($release_dn_num != 0){
            $this->rich_logs()->create([ 'record_id'=> $record_id, 'user_type'=>$user_type , 'log_type' => $log_type, 'rich_type' => 1, 'get_num' => $release_dn_num ]);
        }
    }

    /***
     * @param int $to_parent_level 获取当前用户推荐链的前 $to_parent_level 个推荐人
     * @return mixed
     */
    public function get_parent_users($to_parent_level = 30) {
        $ids = array_slice(explode(',', $this->path), -$to_parent_level);
        #保持推荐顺序 FIND_IN_SET("id", "1,3,2,4,5");
        $parent_users = User::with('user_rich')->whereIn('id', $ids) ->notDeleted()->orderByRaw(DB::raw("FIND_IN_SET(id, '" . implode(',', $ids) . "'" . ')'))->get();
        return $parent_users;
    }

    public function auto_update_user_grade() {
//        dd($this->is_svip_user())
        if(!$this->is_svip_user()) {
            $direct_user_count = $this->direct_user_count();

            dd($this->user_rich);
            if($this->user_rich->dn_num < 1000){
                $this->user_grade = 'interim';
                $this->save();
            }
            else if($this->user_rich->dn_num>=1000){
                if($direct_user_count >= 5 && $this->user_rich->dn_num >=1000000){
                    $this->user_grade = 'vip';
                    $this->save();
                }
                else{
                    $this->user_grade = 'common';
                    $this->save();
                }
            }

         #if($direct_user_count >= 5 && $this->user_rich->dn_num >=1000000) {
         #    $this->user_grade = 'vip';
         #    $this->save();
         #} else if($this->user_rich->dn_num >= 1000 && $this->user_rich->dn_num < 1000000) {
         #    info("common");
         #    $this->user_grade = 'common';
         #    $this->save();
         #} else if($this->user_rich->dn_num < 1000) {
         #    $#this->user_grade = 'interim';
         #    $this->save();
         #}
        }
        if($this->parent_user)  {
            $this->parent_user->auto_update_user_grade();
        }
    }


    public static function gen_invite_code($length = 8)
    {
        $pattern = '0123456789';
        $code = self::gen_comm($pattern, $length);
        $users = User::query()->where('invite_code', $code)->first();
        if ($users) {
            return self::gen_invite_code($length);
        } else {
            return $code;
        }
    }

    public static function gen_salt($length = 6)
    {
        $pattern = '01234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        return self::gen_comm($pattern, $length);
    }

    private static function gen_comm($content, $length)
    {
        $key = '';
        for ($i = 0; $i < $length; $i++) {
            $key .= $content{mt_rand(0, strlen($content) - 1)};    //生成php随机数
        }

        return $key;
    }

}
