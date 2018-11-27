{{-- \resources\views\permissions\create.blade.php --}}
@extends('base.base')

@section('title', '| Create Permission')

@section('base')
    <style>
        .start-date , .end-date{
            display: none;
        }
    </style>
    <div class='col-lg-10'>

        @include('UEditor::head');    <!-- 加载编辑器的容器 -->
        <h3><i class='fa fa-key'></i>添加公告</h3>
        <br>
        {{ Form::open(array('url' => route('news.store'))) }}
        {{ csrf_field() }}
        {{ method_field('POST') }}
        <div class="form-group">
            {{ Form::label('title_cn', '名称[cn]') }}
            {{ Form::text('title_cn', null , array('class' => 'form-control')) }}
        </div>
        {{--<div class="form-group">--}}
            {{--{{ Form::label('title_hk', '名称[hk]') }}--}}
            {{--{{ Form::text('title_hk', null , array('class' => 'form-control')) }}--}}
        {{--</div>--}}
        {{--<div class="form-group">--}}
            {{--{{ Form::label('title_en', '名称[en]') }}--}}
            {{--{{ Form::text('title_en', null , array('class' => 'form-control')) }}--}}
        {{--</div>--}}
        <div class="form-group">
            {{ Form::label('news_type', '类型') }}&emsp;
            <input type="radio" value="common" class="common" name="news_type" onclick="commons()" checked>普通&emsp;
            <input type="radio" value="urgent" class="urgent" name="news_type" onclick="urgent()">紧急
        </div>
        <div class="form-group start-date">
            {{ Form::label('start_time', '开始时间') }}
            {{ Form::date('start_time',\Carbon\Carbon::now() , array('class' => 'form-control start-date')) }}
        </div>
        <div class="form-group end-date">
            {{ Form::label('end_time', '结束时间') }}
            {{ Form::date('end_time', \Carbon\Carbon::tomorrow(), array('class' => 'form-control end-date')) }}
        </div>
        <div>
            <label>内容[cn]</label>
            <script id="container" name="content_cn" type="text/plain">
            </script>
        </div>
        {{--<div class="form-group">--}}
            {{--<label>内容[hk]</label>--}}
            {{--<script id="container1" name="content_hk" type="text/plain">--}}
            {{--</script>--}}
        {{--</div>--}}
        {{--<div class="form-group">--}}
            {{--<label>内容[en]</label>--}}
            {{--<script id="container2" name="content_en" type="text/plain">--}}
            {{--</script>--}}
        {{--</div>--}}
        <div class="form-group">
            {{ Form::label('status', '状态') }}
            {{ Form::radio('status', 'open', 'checked') }}开启&emsp;
            {{ Form::radio('status', 'close') }}关闭
        </div>

        {{ Form::submit('提交', array('class' => 'btn btn-primary')) }}

        {{ Form::close() }}

    </div>
    <script type="text/javascript">
        var ue = UE.getEditor('container');
        ue.ready(function () {
            //此处为支持laravel5 csrf ,根据实际情况修改,目的就是设置 _token 值.
            ue.execCommand('serverparam', '_token', TOKEN);
        });
        var ue1 = UE.getEditor('container1');
        ue1.ready(function () {
            //此处为支持laravel5 csrf ,根据实际情况修改,目的就是设置 _token 值.
            ue.execCommand('serverparam', '_token', TOKEN);
        });
        var ue2 = UE.getEditor('container2');
        ue2.ready(function () {
            //此处为支持laravel5 csrf ,根据实际情况修改,目的就是设置 _token 值.
            ue.execCommand('serverparam', '_token', TOKEN);
        });
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
