@extends('base.base')

@section('title', '| 编辑 用户信息')

@section('base')
    <style>
        .head{
            width: 20%;
            text-align: right;
        }

        .tip-title{
            float: left;
        }
        .tip{
            margin-top: 10px;
            color: #fe7c96;
        }
        span.tip-title {
            margin-top: 20px;
        }
        .ajax-btn{
            color: #139ff7;
            background-color: #ff7496;
        }
    </style>
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="page-header">
                <h3 class="page-title">
                    <span class="page-title-icon bg-gradient-primary text-white mr-2">
                        <i class="mdi mdi-wrench"></i>
                    </span>
                    用户
                </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{url('/users')}}">用户列表</a></li>
                        <li class="breadcrumb-item active" aria-current="page">编辑用户</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">编辑用户</h4>
                            @if(isset($msg))
                                <div class="note note-info">
                                    <div class="alert alert-info">{{ $msg }}</div>
                                </div>
                            @endif
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th class="head">UID: </th>
                                    <th style="text-align: left;">{{ $user->invite_code }}</th>
                                </tr>
                                <tr>
                                    <th class="head">操作: </th>
                                    <th>
                                        @if($all == $lock)
                                            <span class="tip-title">提示: <p class="tip">该线已锁定！锁定人数{{ $all }}</p></span>
                                        @elseif($unlock >0)
                                            <button class="btn btn-gradient-danger btn-md" onclick="lock_line()">锁线</button>
                                            <br>
                                            <span class="tip-title">提示: <p class="tip">该线已锁定人数{{ $lock }}，未锁定人数{{ $unlock }}</p></span>
                                        @endif
                                    </th>
                                <tr>
                                {{ Form::open(array('url' => route('users.update'))) }}
                                {{ Form::hidden('id', $user->id,array('class'=>'id_text')) }}
                                {{ Form::hidden('auth_id', session('admin')->id,array('class'=>'auth_text')) }}
                                <tr>
                                    <th class="head">手机号: </th>
                                    <th>
                                        <input type="text" name="account" @if(isset($account)) value="{{ $account }}" @else value="{{ $user->account }}" @endif class="form-control">
                                    </th>
                                </tr>


                                <tr>
                                    <th class="head">状态: </th>
                                    <th>
                                        <div class="form-check col-md-2 col-sm-2" style="display: inline-block;">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input" name="status" @if($user->status == 'enable') checked="checked" @endif value="enable">&nbsp;正常
                                                <i class="input-helper"></i>
                                            </label>
                                        </div>
                                        <div class="form-check col-md-2 col-sm-2" style="display: inline-block;">
                                            <label class="form-check-label">
                                                <input type="radio"  class="form-check-input" name="status" @if($user->status == 'lock') checked="checked" @endif value="lock">&nbsp;锁定&emsp;
                                                <i class="input-helper"></i>
                                            </label>
                                        </div>
                                        <div class="form-check col-md-2 col-sm-2" style="display: inline-block;">
                                            <label class="form-check-label">
                                                <input type="radio"  class="form-check-input" name="status" @if($user->status == 'trans_lock') checked="checked" @endif value="trans_lock">&nbsp;交易锁定&emsp;
                                            </label>
                                        </div>
                                        <div class="form-check col-md-2 col-sm-2" style="display: inline-block;">
                                            <label class="form-check-label">
                                                <input type="radio"  class="form-check-input" name="status" @if($user->status == 'deleted') checked="checked" @endif value="deleted">&nbsp;删除
                                                <i class="input-helper"></i>
                                            </label>
                                        </div>
                                    </th>
                                </tr>

                                <tr>
                                    <th class="head">等级: </th>
                                    <th>
                                        @foreach($level as $l)
                                            <div class="form-check col-md-2 col-sm-2" style="display: inline-block;">
                                                <label class="form-check-label">
                                                    <input type="radio" name="level"  class="form-check-input" @if($user->m_user->level == $l->id) checked="checked" @endif value="{{$l->id}}">&nbsp;{{$l->dic_item_name}}&emsp;
                                                    <i class="input-helper"></i>
                                                </label>
                                            </div>
                                        @endforeach
                                    </th>
                                </tr>
                                <tr>
                                    <th class="head">推荐人: </th>
                                    <th>
                                        @if($user->pid > 0)
                                        {{ $user->parent_user->invite_code }}&emsp;&emsp;
                                        <a class="btn btn-link" href="{{ route('users.show', $user) }}">修改推荐人</a>
                                        @else
                                        &emsp;无
                                        @endif
                                    </th>
                                </tr>
                                <tr>
                                    <th class="head">注册地址: </th>
                                    <th>
                                        <div class="form-inline">
                                        <select name="province" id="province" onchange="getChildArea(1)" class="form-control">
                                            @foreach($provinces as $province)
                                                <option value="{{ $province->id }}"
                                                        @if($province->id == 0)
                                                        selected="selected" disabled="disabled"
                                                        @elseif($province->id == $user->province)
                                                        selected="selected"
                                                        @endif>
                                                    {{ $province->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <select name="city" id="city" onchange="getChildArea(2)" class="form-control">
                                            @foreach($cities as $city)
                                                <option value="{{ $city->id }}" @if($city->id == $user->city)selected="selected"@endif>
                                                    {{ $city->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <select name="area" id="area" class="form-control">
                                            @foreach($areas as $area)
                                                <option value="{{ $area->id }}" @if($area->id == $user->county)selected="selected"@endif>
                                                    {{ $area->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        </div>
                                    </th>
                                </tr>
                                <tr>
                                    <th colspan="2" style="text-align: center;">
                                        {{ Form::submit('保存', array('class' => 'btn btn-primary')) }}

                                        {{ Form::close() }}
                                    </th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <script type="text/javascript" src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>
    <script type="text/javascript">
        function lock_line(){
            var oper = confirm('确认进行此操作？');
            if(oper){
                var id = $('.id_text').val();
                $.ajax({
                    type: "post",
                    url: "{{ route('users.lock_line') }}",
                    data: {id:id,_token:_token},
                    dataType: "text",
                    success: function(data){
                        if(data > 0){
                            alert(data+' 个用户已锁定！');
                            location.reload(true);
                        }else{
                            alert('没有要锁定的用户！');
                        }
                    },
                    error:function (error) {
                        console.log(error);
                    }
                });
            }else{
                return false;
            }
        }
        function getChildArea(type) {
            if(type == 1){
                var id = $('#province').val();
            }
            if(type == 2){
                var id = $('#city').val();
            }
            $.ajax({
                type: "get",
                url: "{{ route('users.getChildArea') }}",
                data: {id:id,level:type},
                dataType: "json",
                success: function(data){
                    optionstring = '';
                    $.each(data,function(key,value){  //循环遍历后台传过来的json数据
                        optionstring += "<option value=\"" + value.id + "\" >" + value.name + "</option>";
                    });
                    if(type == 1){
                        $("#city").html("<option selected disabled>请选择市</option> "+optionstring);
                    }
                    if(type == 2){
                        $("#area").html("<option selected disabled>请选择区/县</option> "+optionstring);
                    }
                },
                error:function (error) {
                    console.log(error);
                }
            });

        }
    </script>
@endsection
