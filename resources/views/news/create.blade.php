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
                        <li class="breadcrumb-item active" aria-current="page">添加公告</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">添加公告</h4>
                            <br>
                            {{ Form::open(array('url' => route('news.store'),'id'=>'form')) }}
                            {{ csrf_field() }}
                            {{ method_field('POST') }}
                            <div class="form-group">
                                {{ Form::label('title_cn', '标题') }}
                                {{ Form::text('title_cn', null , array('class' => 'form-control')) }}
                            </div>
                            <div class="form-inline">
                                {{ Form::label('start_time', '类型：') }}
                                <div class="form-check form-check-primary">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="news_type" id="ExampleRadio1" checked value="common" onclick="commons()">
                                        普通&emsp;
                                        <i class="input-helper"></i></label>
                                </div>
                                <div class="form-check form-check-danger">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="news_type" id="ExampleRadio1" value="urgent" onclick="urgent()">
                                        紧急
                                        <i class="input-helper"></i></label>
                                </div>
                            </div>
                            <div class="form-group start-date">
                                {{ Form::label('start_time', '开始时间：') }}
                                {{ Form::date('start_time',\Carbon\Carbon::now() , array('class' => 'form-control start-date')) }}
                            </div>
                            <div class="form-group end-date">
                                {{ Form::label('end_time', '结束时间：') }}
                                {{ Form::date('end_time', \Carbon\Carbon::tomorrow(), array('class' => 'form-control end-date')) }}
                            </div>
                            <div>
                                {{ Form::label('editor', '公告内容：') }}
                                <textarea  placeholder="请在此处编辑内容"  id="editor" name="content_cn" style="height:400px;max-height:400px;"></textarea >
                            </div>
                            <div class="form-inline">
                                {{ Form::label('editor', '状态：') }}
                                <div class="form-check form-check-primary">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="status" id="ExampleRadio1" checked value="open">
                                        开启&emsp;
                                        <i class="input-helper"></i></label>
                                </div>
                                <div class="form-check form-check-primary">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="status" id="ExampleRadio1" value="close">
                                        关闭
                                        <i class="input-helper"></i></label>
                                </div>
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
            myRequest("/news/add","post",data,function(res){
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
        function commons() {
            $('.start-date').css('display','none');
            $('.end-date').css('display','none');
        }
        function urgent() {
            $('.start-date').css('display','block');
            $('.end-date').css('display','block');
        }

    </script>

    </div>
@endsection
