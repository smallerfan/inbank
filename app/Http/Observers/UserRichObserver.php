<?php

namespace App\Http\Observers;

use App\Models\User;
use App\Models\UserRich;

class UserRichObserver
{
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
    public function updating(UserRich $userRich)
    {
        if($userRich->getOriginal("dn_num") != $userRich->dn_num ) {
            $this->update_user_grade($userRich);
        }
    }

    private function update_user_grade($user_rich) {
        if(!$user_rich->user->is_svip_user()) {
            $direct_user_count = $user_rich->user->direct_user_count();

            if($direct_user_count >= 5 && $user_rich->dn_num >=1000000) {
                $user_rich->user->user_grade = 'vip';
                $user_rich->user->save();
            } else if($user_rich->dn_num >= 1000 && $user_rich->dn_num < 1000000) {
                $user_rich->user->user_grade = 'common';
                $user_rich->user->save();
            } else if($user_rich->dn_num < 1000) {
                $user_rich->user->user_grade = 'interim';
                $user_rich->user->save();
            }
        }
        if($user_rich->user->parent_user)  {
            $this->update_user_grade($user_rich->user->parent_user->user_rich);
        }
    }
}
