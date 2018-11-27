<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $table = 'in_news';
    protected $fillable = ['id','news_type','title_cn','title_hk','title_en','status','content_cn','content_hk','content_en','start_time','end_time'];
    const STATUS = ['open'=>'开启','close'=>'关闭'];
    const TYPE = ['common'=>'普通','urgent'=>'紧急','site'=>'网站维护'];
    public function is_open(){
        return $this->status === 'open';
    }
    public function is_close(){
        return $this->status === 'close';
    }
}
