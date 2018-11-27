<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class C2cRichRecord extends Model
{
   public function c2c_rich_logs() {
      return $this->hasMany("App\Models\C2cRichLog", 'record_id');
   }
}
