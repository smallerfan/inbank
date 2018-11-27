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
            margin-bottom: 4rem;
        }
        .add-button {
            float: right;
            margin: 8.5px;
            display: inline-block;
        }
        a.add-button {
            text-decoration: none;
            background: #222d32;
            color: #f2f2f2;
            padding: 10px 30px 10px 30px;
            font-size: 16px;
            font-family: 微软雅黑,宋体,Arial,Helvetica,Verdana,sans-serif;
            font-weight: bold;
            border-radius: 3px;
            -webkit-transition: all linear 0.30s;
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
        span.find{
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
        }
        a.find {
            color: #fff;
        }
        div#edit-express {
            margin-top: 2%;
        }
        /*#edit-express{*/
            /*display: none;*/
        /*}*/
    </style>

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
                        <li class="breadcrumb-item"><a href="{{url('/shop/node')}}">点位列表</a></li>
                        <li class="breadcrumb-item active" aria-current="page">新增点位</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">新增点位</h4>
                            <p class="card-description">
                            </p>
                            <div class="form-group col-lg-12" id="edit-express">
                                {{ Form::open(array('url' => route('node.add'))) }}
                                {{ csrf_field() }}
                                {{ method_field('POST') }}
                                <div class="form-group-input">
                                    <label class="title-find">点位用户绑定</label>
                                    <br>

                                    <select name="type" id="type" class="form-control" style="margin-top: 10px;width: 50%;">
                                        <option value=" ">——请选择搜索项——</option>
                                        <option value="tel">用户手机号</option>
                                        <option value="uid">用户UID</option>
                                    </select>
                                    <input class="form-control" name="key" placeholder="请输入...." id="key" style="margin-top: 10px;width: 50%;">
                                    <span class="btn btn-sm btn-gradient-danger btn-icon-text" id="find-user" style="    margin-bottom: 10px;">查找用户</span>
                                    <br>
                                    <div id="user_info">
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
                    str4 = '<input id="uid-input" style="display: none" name="uid" '+'value="'+data.id+'">';
                    $("#uid-input").remove();
                    $("#user_name").remove();
                    $("#user_info").append(str1+str2+str3+str4);
                }
            })
        })
    </script>
@endsection

