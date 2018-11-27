<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = ['title','picture','href','type'];
    protected $table = 'in_banners';
    const TYPE = [0=>'系统',1=>'商城'];
    const STATUS = ['open'=>'启用','close'=>'禁用'];
    public function getPictureAttribute($value)
    {
        return config('filesystems.disks.qiniu.domain') .''. ltrim($value);
    }
}
