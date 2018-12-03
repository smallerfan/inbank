@extends('base.base')
@section('base')
    <style>
        .start-date , .end-date{
            display: none;
        }
    </style>

    <div class="main-panel">
        <div class="content-wrapper">
            <div class="page-header">
                <h3 class="page-title">
                    <span class="page-title-icon bg-gradient-primary text-white mr-2">
                        <i class="mdi mdi-wrench"></i>
                    </span>
                    商城
                </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{url('/news')}}">公告列表</a></li>
                        <li class="breadcrumb-item active" aria-current="page">编辑公告</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">编辑公告——{{$new->title_cn}}</h4>
                            {{ Form::model($new,  ['route' => ['news.update', $new], 'method' => 'POST','id'=>'form']) }}
                            {{ csrf_field() }}
                            {{ Form::hidden('id', $new->id) }}
                            <div class="form-group">
                                {{ Form::label('title_cn', '标题') }}
                                {{ Form::text('title_cn', $new->title_cn, array('class' => 'form-control')) }}
                            </div>
                            <div class="form-inline">
                                <label>类型</label>&emsp;
                                @if($new->news_type == 'site')
                                    {{ Form::label('start_time', '类型：') }}
                                    <div class="form-check form-check-primary">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" name="news_type" id="site" value="site" @if($new->news_type == 'site')checked="checked"@endif>
                                            网站维护&emsp;
                                            <i class="input-helper"></i></label>
                                    </div>
                                @else
                                    <div class="form-check form-check-primary">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" name="news_type" id="common" @if($new->news_type == 'common')checked="checked" @endif value="common" >
                                            普通&emsp;
                                            <i class="input-helper"></i></label>
                                    </div>
                                    <div class="form-check form-check-danger">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" name="news_type" id="urgent" value="urgent"  @if($new->news_type == 'urgent')checked="checked"@endif>
                                            紧急
                                            <i class="input-helper"></i></label>
                                    </div>

                                @endif
                            </div>
                            <div class="form-group start-date">
                                {{ Form::label('start_time', '开始时间') }}
                                {{ Form::date('start_time',substr($new->start_time,0,10) , array('class' => 'form-control start-date')) }}
                            </div>
                            <div class="form-group end-date">
                                {{ Form::label('end_time', '结束时间') }}
                                {{ Form::date('end_time', substr($new->end_time,0,10), array('class' => 'form-control end-date')) }}
                            </div>
                            <div>
                                <label>内容</label>
                                {{ Form::label('editor', '公告内容：') }}
                                <textarea  placeholder="请在此处编辑内容"  id="editor" name="content_cn" style="height:400px;max-height:400px;">
                                    {!! html_entity_decode($new->content_cn) !!}
                                </textarea >
                            </div>
                            <div class="form-inline" id="status">
                                {{ Form::label('editor', '状态：') }}
                                @if($new->news_type != 'site')
                                <div class="form-check form-check-primary">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="status" id="ExampleRadio1" @if($new->status == 'open')checked="checked"@endif value="open">
                                        开启&emsp;
                                        <i class="input-helper"></i></label>
                                </div>
                                <div class="form-check form-check-primary">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="status" id="ExampleRadio1" @if($new->status == 'close')checked="checked"@endif value="close">
                                        关闭
                                        <i class="input-helper"></i></label>
                                </div>
                                @endif
                            </div>
                            <button type="button" onclick="commit()" class="btn btn-sm btn-gradient-primary btn-icon-text">
                                <i class="mdi mdi-file-check btn-icon-prepend"></i>
                                提交
                            </button>

                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <script type="text/javascript" src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>
    <script type="text/javascript">
        var editor = new wangEditor('editor');
        // 上传图片（举例）
        editor.config.uploadImgUrl = "/admin/wangeditor/upload";
        // 隐藏掉插入网络图片功能。该配置，只有在你正确配置了图片上传功能之后才可用。
        editor.config.hideLinkImg = false;
        editor.create();
        function commit(){
            if(!checkForm()){
                return false;
            }
            var data = $("#form").serializeObject();
//            console.info(data);
            myRequest("/news/update_news","post",data,function(res){
                layer.msg(res.msg)
                setTimeout(function(){
                    window.location="/news";
                },1500)
            },function(){
                layer.msg(res.msg, function(){});
            });
        }
    </script>
    <script type="text/javascript">
        $(document).ready(function(){
            if($('#common').is(':checked')) {
                $('.start-date').css('display','none');
                $('.end-date').css('display','none');
            }

            if($('#urgent').is(':checked')) {
                $('.start-date').css('display','block');
                $('.end-date').css('display','block');
            }
            if($('#site').is(':checked')) {
                $('.start-date').css('display','block');
                $('.end-date').css('display','block');
            }
            $('#common').click(function () {
                $('.start-date').css('display','none');
                $('.end-date').css('display','none');
            });
            $('#urgent').click(function () {
                $('.start-date').css('display','block');
                $('.end-date').css('display','block');
            });
        });
    </script>
@endsection
