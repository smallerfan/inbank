@extends('base.base')

@section('title', '| 编辑 公告')

@section('base')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="page-header">
                <h3 class="page-title">
                    <span class="page-title-icon bg-gradient-primary text-white mr-2">
                        <i class="mdi mdi-wrench"></i>
                    </span>
                    轮播图
                </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{url('/banners')}}">轮播图列表</a></li>
                        <li class="breadcrumb-item active" aria-current="page">添加轮播图</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">添加轮播图</h4>
                            </p>
                            {{ Form::open(array('id' => 'form','enctype'=>"multipart/form-data")) }}
                            {{ csrf_field() }}
                            <div class="form-group">
                                {{ Form::label('title', '标题') }}
                                {{ Form::text('title', '', array('class' => 'form-control')) }}
                            </div>
                            <div class="form-group">
                                <label>类型</label>&emsp;
                                <div class="form-check col-md-2 col-sm-2" style="display: inline-block;">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="type" value=0 checked="checked">&nbsp;系统
                                        <i class="input-helper"></i>
                                    </label>
                                </div>
                                <div class="form-check col-md-2 col-sm-2" style="display: inline-block;">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="type" value=1>&nbsp;商城
                                        <i class="input-helper"></i>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group" id="image" style="">
                                <label>轮播图</label>&emsp;
                                <input type="file" class="file-upload-default img-file" data-path="avatar">
                                <input type="hidden" class="image-path value-input" name="picture">
                                <div class="input-group col-xs-12">
                                    <input type="text" class="form-control file-upload-info" disabled="" >
                                    <span class="input-group-append">
                                        <button class="file-upload-browse btn btn-gradient-primary" onclick="upload($(this))" type="button">上传</button>
                                        </span>
                                </div>
                                <div class="img-yl">
                                </div>
                            </div>
                            {{--<div class="form-group">--}}
                                {{--<label>图片</label>--}}
                                {{--<label for="file">--}}
                                    {{--<img src="{{ asset('images/default.png') }}" width="80px" height="80px"  id="img">--}}
                                {{--</label>--}}
                                {{--<input type="file" name="picture" style="display: none;" id="file" onchange="show(this.files)">--}}
                            {{--</div>--}}

                            <div class="form-group">
                                {{ Form::label('href', '链接') }}
                                {{ Form::text('href', '', array('class' => 'form-control')) }}
                            </div>

                            <div class="form-group">
                                <label>状态</label>&emsp;
                                <div class="form-check col-md-2 col-sm-2" style="display: inline-block;">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="status" value="open"  checked="checked">&nbsp;启用
                                        <i class="input-helper"></i>
                                    </label>
                                </div>
                                <div class="form-check col-md-2 col-sm-2" style="display: inline-block;">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="status" value="close">&nbsp;禁用
                                        <i class="input-helper"></i>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('sort', '排序(数值越大越靠前)') }}
                                {{ Form::text('sort', 0 , array('class' => 'form-control')) }}
                            </div>
                            <button type="button" onclick="commit()" class="btn btn-sm btn-gradient-primary btn-icon-text">
                                <i class="mdi mdi-file-check btn-icon-prepend"></i>
                                提交
                            </button>
                            {{--{{ Form::submit('保存', array('class' => 'btn btn-primary')) }}--}}
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <script type="text/javascript">
        function commit(){
            if(!checkForm()){
                return false;
            }
            var data = $("#form").serializeObject();
            myRequest("{{ route('banners.store') }}","post",data,function(res){
                layer.msg(res.msg);
                if(res.code === 200){
                    setTimeout(function(){
                        window.location="/banners";
                    },1500)
                }
            },function(){
                layer.msg(res.msg, function(){});
            });
        }
        function show(f) {
            var str = "";
            for (var i = 0; i < f.length; i++) {
                var reader = new FileReader();
                reader.readAsDataURL(f[i]);
                reader.onload = function (e) {
                    str += "<img  height='80px' width='140px' id='img' src='" + e.target.result + "'/>";
                    $("#img")[0].outerHTML = str;
                }
            }
        }
    </script>

@endsection
