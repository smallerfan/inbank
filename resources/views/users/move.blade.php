@extends('base.base')

@section('title', '| 编辑 用户推荐人')

@section('base')
    <style>
        .head{
            width: 20%;
            text-align: right;
        }
        .new-info{
            display: none;
            /*display: block;*/
            float: left;
            width: 300px;
            height: 50px;
            margin-left: 20px;
        }
        .new-tips{
            display: none;
            /*display: block;*/
            float: left;
            width: 300px;
            height: 50px;
            margin-left: 20px;
        }
        .tips{
            color: red;
            font-size: 16px;
        }
        .btn-primary{
            font-size: 16px;
            width: 60px;
            float: left;
            height: 42px;
            margin:18px 0 20px 20px;
            display: inline;
        }
        .btn-save{
            font-size: 16px;
            width: 100px;
            height: 42px;
            color: #222d32;
            background-color: #9e88f7;
            border-color: #9e88f7;
        }
        table tbody tr td{text-align: center;}
        .input_control{
            width:360px;
            margin:20px 0 20px 20px;
            float: left;
        }
        input[type="text"]{
            box-sizing: border-box;
            text-align:left;
            font-size:1em;
            height:2.7em;
            border-radius:4px;
            border:1px solid #c8cccf;
            color:#6a6f77;
            -web-kit-appearance:none;
            -moz-appearance: none;
            display:block;
            outline:0;
            padding:0 1em;
            text-decoration:none;
            width:100%;
        }
        input[type="text"]:focus{
            border:1px solid #ff7496;
        }
    </style>
    <div class='col-lg-8'>

        <h3><i class='fa fa-key'></i> 编辑用户-推荐人</h3>
        <br>
        @if(isset($msg))
        <div class="note note-info">
            <div class="alert alert-info" role="alert">{{ $msg }}</div>
        </div>
        @endif
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th class="head">账户: </th>
                    <th style="text-align: left;">
                        UID: {{ $user->invite_code }}<br/>
                        昵称: {{ $user->username }}<br/>
                        手机号: {{ $user->mobile }}
                    </th>
                </tr>
                <tr>
                    <th class="head">原推荐人: </th>
                    <th>{{ $user->parent_user->invite_code }}</th>
                <tr>
                {{ Form::open(array('url' => route('users.move_line'))) }}
                {{ csrf_field() }}
                {{ Form::hidden('id', $user->id,array('class'=>'id_text')) }}
                <tr>
                    <th class="head" rowspan="2">新推荐人: </th>
                    <th>
                        <div class="input_control">
                            <input type="text" name="uid" id="uid" class="form_input">
                        </div>
                        <input type="button" value="搜索" class="btn btn-primary" onclick="search()">
                        <br/>
                    </th>
                </tr>
                <tr>
                    <th>
                        <div class="input_control info">
                            <div class="new-info">
                                UID: <label class="uid">:</label><br/>
                                昵称: <label class="username"></label><br/>
                                手机号: <label class="mobile"></label>
                            </div>
                            <div class="new-tips">
                                <p class="tips"></p>
                                @if(isset($msg))
                                    <p class="msg">{{ $msg }}</p>
                                @endif
                            </div>
                        </div>
                    </th>
                </tr>
                <tr>
                    <th colspan="2" style="text-align: center;">
                        {{ Form::submit('确认移线', array('class' => 'btn btn-save')) }}
                        {{ Form::close() }}
                    </th>
                </tr>
                </thead>
            </table>
        </div>


    </div>
    <script type="text/javascript" src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $("#uid").focus(function(){
                $('.new-info').css('display','none');
                $('.new-tips').css('display','none');
            });
        })
        function search(){
            var uid = $('#uid').val();
            var id = $('.id_text').val();
            if(uid == '' || id == ''){
                $('.new-tips').css('display','block');
                $('.tips').text('用户信息异常/搜索内容不能为空');
            }
            $.ajax({
                type: "post",
                url: "{{ route('users.searchUser') }}",
                data: {id:id,uid:uid,_token:_token},
                dataType: "text",
                success: function(data){
                    var info = jQuery.parseJSON(data);

                    console.log(info);
                    if(info == 0){
                        $('.new-tips').css('display','block');
                        $('.tips').text('用户信息异常/搜索内容不能为空');
                    }else if(info == -1){
                        $('.new-tips').css('display','block');
                        $('.tips').text('新推荐人是你的下线');
                    }else if(info == -2){
                        $('.new-tips').css('display','block');
                        $('.tips').text('用户/新推荐人信息异常');
                    }else{
                        console.log(info.mobile);
                        $('.new-info').css('display','block');
                        $('.info').css('height','80px');
                        $('.uid').text(info.uid);
                        $('.username').text(info.username);
                        $('.mobile').text(info.mobile);
                    }
                },
                error:function (error) {
                    console.log(error);
                }
            });
        }
    </script>
@endsection
