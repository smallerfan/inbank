<?php

namespace App\Http\Observers;

use App\Models\RichLog;

class RichLogObserver
{
  public function creating(RichLog $rich_log) {
     $rich_log->cur_live_dk = $rich_log->user->user_rich->live_dk_num;
     $rich_log->cur_frozen_dk = $rich_log->user->user_rich->frozen_dk_num;
     $rich_log->cur_dn = $rich_log->user->user_rich->dn_num;
  }
}
