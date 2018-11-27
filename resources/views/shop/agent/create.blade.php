@extends('base.base')

@section('title', '| 编辑 权限')


@section('base')
    <style>
        span.show_info {
            margin-right: 88px;
        }
        div#user_name {
            margin-top: 11px;
        }
        img{width: 300px;}
        p{font-weight: bold;font-size: 18px;}
        .remark{display: none;}
        span.order_status {
            color: red;
            margin: 46px;
            font-size: 20px;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .form-group.col-lg-12 {
            line-height: 33px;
            padding: 30px;
            border: 3px solid #222d3203;
            border-radius: 25px;
            background: #222d3236;
        }
        .form-control {
            width: 50%;
            margin-top: 15px;
        }
       span.edit-express {
            margin-top: 24px;
            border: none;
            text-decoration: none;
            background: #222d32;
            color: #f2f2f2;
            padding: 10px 30px 10px 30px;
            font-size: 16px;
            font-family: 微软雅黑,宋体,Arial,Helvetica,Verdana,sans-serif;
            font-weight: bold;
            border-radius: 3px;
            -webkit-transition: all linear 0.30s;
            float: right;
        }
        input.form-control {
            margin-bottom: 10PX;
        }
        a.find {
            color: #fff;
        }
        /*#edit-express{*/
            /*display: none;*/
        /*}*/
    </style>
    {{--<link href="http://www.jq22.com/jquery/bootstrap-3.3.4.css" rel="stylesheet">--}}
    <!--[if IE]>
    <![endif]-->

    <script src="http://www.jq22.com/jquery/1.11.1/jquery.min.js"></script>
    <script src="http://www.jq22.com/jquery/bootstrap-3.3.4.js"></script>
    <script src="{{ url('js/distpicker.data.js') }}"></script>
    <script src="{{ url('js/distpicker.js') }}"></script>
    <script src="{{ url('js/main.js') }}"></script>



    <div class="main-panel">
        <div class="content-wrapper">
            <div class="page-header">
                <h3 class="page-title">
                    <span class="page-title-icon bg-gradient-primary text-white mr-2">
                        <i class="mdi mdi-wrench"></i>
                    </span>
                    系统
                </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{url('/shop/agent')}}">区域代理列表</a></li>
                        <li class="breadcrumb-item active" aria-current="page">新增代理</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">新增代理</h4>
                            <p class="card-description">
                            <div class="add-button">
                                <a href="{{ route('agent.create') }}" class="btn btn-sm btn-gradient-success btn-icon-text">
                                    <i class="mdi mdi-plus btn-icon-prepend"></i>新增代理
                                </a>
                            </div>
                            </p>
                            {{ Form::open(array('url' => route('agent.add'))) }}
                            {{ csrf_field() }}
                            {{ method_field('POST') }}


                            <div class="form-group-input">
                                <label class="title-find">绑定用户</label>
                                <br>

                                <select name="type" id="type" class="form-control">
                                    <option value=" ">——请选择搜索项——</option>
                                    <option value="tel">用户手机号</option>
                                    <option value="uid">用户UID</option>
                                </select>
                                <input class="form-control" name="key" placeholder="请输入...." id="key">
                                {{--{{ Form::label('uid', '用户id：') }}--}}
                                {{--<input class="form-control" name="uid" placeholder="请输入用户id" id="uid">--}}
                                <br>
                                <span class="btn btn-sm btn-gradient-danger btn-icon-text" id="find-user">查找用户</span>
                                <br>
                                <div id="user_info" class="table-dark">

                                </div>

                            </div>
                            <br>
                            {{ Form::label('address', '地址选择：') }}
                            <div data-toggle="distpicker">
                                <div class="form-group">
                                    <label class="sr-only" for="province1" >Province</label>
                                    <select class="form-control" id="province1" name="province"></select>
                                </div>
                                <div class="form-group">
                                    <label class="sr-only" for="city1">City</label>
                                    <select class="form-control" id="city1" name="city"></select>
                                </div>
                                <div class="form-group">
                                    <label class="sr-only" for="district1">District</label>
                                    <select class="form-control" id="district1" name="county"></select>
                                </div>
                            </div>

                            {{ Form::submit('提交',array('class'=>'btn btn-gradient-primary mb-2')) }}
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script type="text/javascript">
        function clickItShow(){
            $("#remark").show();
        }
        function clickItHide(){
            $("#remark").hide();
        }


        $("#find-user").click(function () {
            $.ajax({
                url : '{{ route('user.user_find') }}',
                type: 'POST',
                data: {
                    type : $("#type").val(),
                    key : $("#key").val()
                },
                dataType: 'json',
                success:function (data) {
//                    var str1 = '';
//                    var str2 = '';
//                    var str3 = '';
                    str1 = '<div id="user_name" >'+'用户名：'+'<span class="show_info">'+data.username+'</span>'+'  ';
                    str2 = '用户ID：'+'<span class="show_info">'+data.invite_code+'</span>'+'  ';
                    str3 = '用户手机号：'+'<span class="show_info">'+data.mobile+'</span>'+'</div>';
                    str4 = '<input id="uid-input" type="hidden" style="display: none" name="uid" '+'value="'+data.id+'">';
                    $("#uid-input").remove();
                    $("#user_name").remove();
                    $("#user_info").append(str1+str2+str3+str4);
                }
            })
        })
    </script>
@endsection

