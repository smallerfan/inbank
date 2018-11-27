<?php

namespace App\Http\Controllers;

use App\Authorizable;
use App\Http\Requests\UserRichRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
class UserRichesController extends Controller
{
   #use Authorizable;

   //增加财富
   public function store($user_id, UserRichRequest $request) {
      $user = User::with('user_rich')->find($user_id);
      $opt_type = $request->operate_type;
      $opt_num = $request->operate_num;
      DB::beginTransaction();
      try {
          if ($opt_type == 'send_dk') {
             $record = $user->get_rich_trans_records()->create(['trans_type' => $opt_type]);
             $user->save_and_log($opt_num, 0, $record->id, $opt_type, 1);
          } else if ($opt_type == 'take_back_dk') {
             if ($opt_num > $user->user_rich->live_dk_num) {
                 DB::rollback();
                 flash('操作失败, DK不足')->error();
                 return redirect(route('user_riches.new', $user));
             }
             $record = $user->get_rich_trans_records()->create(['trans_type' => $opt_type]);
             $user->save_and_log(-$opt_num, 0, $record->id, $opt_type, 1);
          } else if ($opt_type == 'send_dn') {
             $record = $user->get_rich_trans_records()->create(['trans_type' => $opt_type]);
             $user->save_and_log(0, $opt_num, $record->id, $opt_type, 1);
          } else if ($opt_type == 'take_back_dn') {
             if ($opt_num > $user->user_rich->dn_num) {
                 DB::rollback();
                 flash('操作失败, DN不足')->error();
                 return redirect(route('user_riches.new', $user));
             }
             $record = $user->get_rich_trans_records()->create(['trans_type' => $opt_type]);
             $user->save_and_log(0, -$opt_num, $record->id, $opt_type, 1);
          }
          DB::commit();
          flash("操作成功")->success();
      } catch(\Exception$e) {
          DB::rollBack();
          flash('操作失败:'. $e->getMessage())->error();
      }
       return redirect(route('user_riches.new', $user));
   }

   //减少财富
   public function destroy() {

   }

   public function new($user_id) {
      $user = User::with('user_rich')->find($user_id);
      return view('user_riches.new', ['user' => $user]);
   }

   public function dk_logs($uid){
       $logs_count = DB::select('select count(1) logs_count from (
            select log.id,log.log_type,log.get_num,log.cur_live_dk cur_dk,log.cur_dn,log.created_at,
            (select u.invite_code from datc_users u where id=(case when log.user_type=0 then record.get_id else record.pay_id end)) other_user from datc_rich_logs log
            left join datc_rich_trans_records record on record.id=log.record_id
            where log.uid=:uid and log.rich_type=0 and log.log_type not in ("buy","sale")
            UNION ALL
            select log.id,log.log_type,log.get_num,log.cur_live_dk cur_dk,log.cur_dn,log.created_at
            ,(select u.invite_code from datc_users u where id=
              (case when orders.trans_type="buy" then (select uid from datc_sale_orders where id=orders.sale_order_id) else orders.uid end)) other_user
            from datc_rich_logs log
            left join datc_buy_orders orders on orders.id=log.record_id
            where log.uid=:uid1 and log.rich_type=0 and log.log_type in ("buy","sale")
                ) tmp', [':uid'=>$uid,':uid1'=>$uid])[0]->logs_count;
//       dd($logs_count[0]->logs_count);
       $page = request("page") ?? 1;
       $page_size = 20;
       $page_total = intval($logs_count/$page_size) + ($logs_count % $page_size == 0 ? 0 : 1);
       $type = 'dk';

       $logs = DB::select('select * from (
            select log.id,log.log_type,log.get_num,log.cur_live_dk cur_dk,log.cur_dn,log.created_at
            ,(select u.invite_code from datc_users u where id=
              (case when log.user_type=0 then record.get_id else record.pay_id end)) other_user
            from datc_rich_logs log
            left join datc_rich_trans_records record on record.id=log.record_id
            where log.uid=:uid and log.rich_type=0 and log.log_type not in ("buy","sale")
            UNION ALL
            select log.id,log.log_type,log.get_num,log.cur_live_dk cur_dk,log.cur_dn,log.created_at
            ,(select u.invite_code from datc_users u where id=
              (case when orders.trans_type="buy" then (select uid from datc_sale_orders where id=orders.sale_order_id) else orders.uid end)) other_user
            from datc_rich_logs log
            left join datc_buy_orders orders on orders.id=log.record_id
            where log.uid=:uid1 and log.rich_type=0 and log.log_type in ("buy","sale")
                ) tmp order by id desc limit :start, :offset', [':uid'=>$uid,':uid1'=>$uid, ':start' => ($page - 1) * $page_size, ':offset' => $page_size]);
       $user = User::with('user_rich')->find($uid);
       return view('user_riches.logs')->with(['page_total' => $page_total, 'logs'=>$logs, 'flag'=>'dk', 'user'=>$user, 'type' => $type ,'page'=>$page]);
   }


   public function dn_logs($uid){
       $logs_count = DB::select('select count(1) logs_count from (
           select log.id,log.log_type,log.get_num,log.cur_live_dk cur_dk,log.cur_dn,log.created_at
           ,(select u.invite_code from datc_users u where id=
             (case when log.user_type=0 then record.get_id else record.pay_id end)) other_user
           from datc_rich_logs log
           left join datc_rich_trans_records record on record.id=log.record_id
           where log.uid=:uid and log.rich_type=1 and log.log_type not in ("buy","sale")
           UNION ALL
           select log.id,log.log_type,log.get_num,log.cur_live_dk cur_dk,log.cur_dn,log.created_at
           ,(select u.invite_code from datc_users u where id=
             (case when orders.trans_type="buy" then (select uid from datc_sale_orders where id=orders.sale_order_id) else orders.uid end)) other_user
           from datc_rich_logs log
           left join datc_buy_orders orders on orders.id=log.record_id
           where log.uid=:uid1 and log.rich_type=1 and log.log_type in ("buy","sale")
               ) tmp', [':uid'=>$uid,':uid1'=>$uid])[0]->logs_count;
       $page = request("page") ?? 1;
       $page_size = 20;
       $page_total = intval($logs_count/$page_size) + ($logs_count % $page_size == 0 ? 0 : 1);
       $type = "dn";

       $logs = DB::select('select * from (
           select log.id,log.log_type,log.get_num,log.cur_live_dk cur_dk,log.cur_dn,log.created_at
           ,(select u.invite_code from datc_users u where id=
             (case when log.user_type=0 then record.get_id else record.pay_id end)) other_user
           from datc_rich_logs log
           left join datc_rich_trans_records record on record.id=log.record_id
           where log.uid=:uid and log.rich_type=1 and log.log_type not in ("buy","sale")
           UNION ALL
           select log.id,log.log_type,log.get_num,log.cur_live_dk cur_dk,log.cur_dn,log.created_at
           ,(select u.invite_code from datc_users u where id=
             (case when orders.trans_type="buy" then (select uid from datc_sale_orders where id=orders.sale_order_id) else orders.uid end)) other_user
           from datc_rich_logs log
           left join datc_buy_orders orders on orders.id=log.record_id
           where log.uid=:uid1 and log.rich_type=1 and log.log_type in ("buy","sale")
               ) tmp order by id desc limit :start, :offset', [':uid'=>$uid,':uid1'=>$uid, ':start' => ($page - 1) * $page_size, ':offset' => $page_size]);
       $user = User::with('user_rich')->find($uid);
       return view('user_riches.logs')->with(['page_total' => $page_total, 'logs'=>$logs, 'flag'=>'dn','user'=>$user, 'type' => $type ,'page'=>$page]);
   }
}
