@extends('layouts.app')

@section('title', '| 编辑 公告')

@section('content')
    <style>
        .start-date , .end-date{
            display: none;
        }
    </style>
    @include('UEditor::head')   <!-- 加载编辑器的容器 -->
    <div class='col-lg-10'>

        <h3><i class='fa fa-key'></i> 编辑公告——{{$new->title_cn}}</h3>
        <br>
        {{ Form::model($new,  ['route' => ['news.update', $new], 'method' => 'POST']) }}
        {{ csrf_field() }}
        {{ Form::hidden('id', $new->id) }}
        <div class="form-group">
            {{ Form::label('title_cn', '标题') }}
            {{ Form::text('title_cn', $new->title_cn, array('class' => 'form-control')) }}
        </div>
        {{--<div class="form-group">--}}
            {{--{{ Form::label('title_hk', '标题[hk]') }}--}}
            {{--{{ Form::text('title_hk', $new->title_hk, array('class' => 'form-control')) }}--}}
        {{--</div>--}}
        {{--<div class="form-group">--}}
            {{--{{ Form::label('title_en', '标题[en]') }}--}}
            {{--{{ Form::text('title_en', $new->title_en, array('class' => 'form-control')) }}--}}
        {{--</div>--}}
        <div class="form-group">
            <label>类型</label>&emsp;
            @if($new->news_type == 'site')

                <input type="radio" value="site" id="site" name="news_type" @if($new->news_type == 'site')checked="checked"@endif>网站维护&emsp;

            @else
                <input type="radio" value="common" id="common" name="news_type" @if($new->news_type == 'common')checked="checked"@endif>普通&emsp;
                <input type="radio" value="urgent" id="urgent" name="news_type" @if($new->news_type == 'urgent')checked="checked"@endif>紧急&emsp;
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
            <script id="container" name="content_cn" type="text/plain">
{!! html_entity_decode($new->content_cn) !!}
            </script>
        </div>
        {{--<div class="form-group">--}}
            {{--<label>内容[hk]</label>--}}
            {{--<script id="container1" name="content_hk" type="text/plain">--}}
{{--{!! html_entity_decode($new->content_hk) !!}--}}
            {{--</script>--}}
        {{--</div>--}}
        {{--<div class="form-group">--}}
            {{--<label>内容[en]</label>--}}
            {{--<script id="container2" name="content_en" type="text/plain">--}}
{{--{!! html_entity_decode($new->content_en) !!}--}}
            {{--</script>--}}
        {{--</div>--}}
        <div class="form-group" id="status">
            <label>状态</label>
            @if($new->news_type != 'site')
            <input type="radio" value="open" id="open" name="status" @if($new->status == 'open')checked="checked"@endif>开启&emsp;
            <input type="radio" value="close" id="close" name="status" @if($new->status == 'close')checked="checked"@endif>关闭
            @else
                <input type="radio" value="null" name="status" checked="checked">开启&emsp;
            @endif
        </div>
        {{ Form::submit('保存', array('class' => 'btn btn-primary')) }}

        {{ Form::close() }}

    </div>
    <script type="text/javascript" src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>
    <script type="text/javascript">
        var ue = UE.getEditor('container');
        ue.ready(function () {
            //此处为支持laravel5 csrf ,根据实际情况修改,目的就是设置 _token 值.
            ue.execCommand('serverparam', '_token', _token);
        });
        var ue1 = UE.getEditor('container1');
        ue1.ready(function () {
            //此处为支持laravel5 csrf ,根据实际情况修改,目的就是设置 _token 值.
            ue.execCommand('serverparam', '_token', _token);
        });
        var ue2 = UE.getEditor('container2');
        ue2.ready(function () {
            //此处为支持laravel5 csrf ,根据实际情况修改,目的就是设置 _token 值.
            ue.execCommand('serverparam', '_token', _token);
        });
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
