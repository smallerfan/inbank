<?php

namespace App\Http\Observers;

use App\Models\User;

class UserObserver {

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

    public function creating(User $user)
    {
        $parent_user = $user->parent_user;
        if($parent_user && $parent_user->direct_user_count() >= 5 && !$parent_user->is_svip() && $parent_user->user_rich->dn_num >=1000000 ) {
          $parent_user->user_grade = 'vip';
          $parent_user->save();
        }
    }
    public function updating(User $user)
    {
        $dirty = $user->getDirty();
        $parent_user = $user->parent_user;
        if(empty($dirty['path']) || !$parent_user || !$parent_user->is_svip_user()){
            return;
        }
        $direct_user_count = $parent_user->direct_user_count();
    
        if($parent_user->user_rich->dn_num < 1000){
            $parent_user->user_grade = 'interim';
            $parent_user->save();
        }
            else if($parent_user->user_rich->dn_num>=1000){
            if($direct_user_count >= 5 && $parent_user->user_rich->dn_num >=1000000){
                $parent_user->user_grade = 'vip';
                $parent_user->save();
            }
            else{
                $parent_user->user_grade = 'common';
                $parent_user->save();
            }
        }
    }

}
