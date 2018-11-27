@extends('base.base')

@section('title', '| 编辑 权限')


@section('base')
    <style>
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
        .form-group.col-lg-12 {
            line-height: 33px;
            padding: 30px;
            border: 3px solid #222d3203;
            border-radius: 25px;
            background: #222d3236;
        }
        .form-control {
            width: 50%;
            border-radius: 11px;
        }
        input[type="submit"] {
            margin-left: 25px;
            margin-top: 24px;
            border: none;
            text-decoration: none;
            background: #222d32;
            color: #f2f2f2;
            padding: 10px 30px 10px 30px;
            font-size: 16px;
            font-family: 微软雅黑,宋体,Arial,Helvetica,Verdana,sans-serif;
            font-weight: bold;
            border-radius: 11px;
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
        #edit-express{
            display: none;
        }
        label {
            margin-left: 25px;
        }
        .op_express_delivery_timeline_notice, .op_express_delivery_timeline_title {
            padding: 10px;
            margin-left: -15px;
            /*float: left;*/
            border-left: solid 2px #ececec;
        }
        .op_express_delivery_timeline_new .op_express_delivery_timeline_circle {
            left: -18px;
            margin-top: -10px;
            padding-top: 20px;
        }
        .op_express_delivery_timeline_circle {
            width: 55px;
            float: left;
            display: inline-block;
            position: relative;
            overflow: hidden;
            /*background-color: #fff;*/
            left: -18px;
            margin-top: 17px;
        }
        .op_express_delivery_timeline_circle_blue, .op_express_delivery_timeline_circle_red {
            background-image: url(//www.baidu.com/aladdin/img/express_delivery/dout.png);
        }
        .c-icon {
            display: inline-block;
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
                        <li class="breadcrumb-item"><a href="{{url('shop/order')}}">普通订单列表</a></li>
                        <li class="breadcrumb-item active" aria-current="page">物流详情</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4>
                                <i class='mdi mdi-car-wash'></i>  物流公司  : {{ $ex->ex_name  }}
                                <br>
                                <br>
                                <i class='mdi mdi-fast-forward'></i>  快递单号  : {{ $order->express_sn  }}
                            </h4>

                            <br>
                            <div class="form-group col-lg-12">
                                <ul class="op_express_delivery_timeline_box">
                                    <li class="op_express_delivery_timeline_label c-clearfix">
                                        <div class="op_express_delivery_timeline_notice"
                                        ><div class="op_express_delivery_timeline_circle"></div>
                                            <div class="op_express_delivery_timeline_info">物流信息与官网实时同步</div>
                                        </div>
                                    </li>
                                    @foreach($kd->Traces as $t)
                                        <li class="op_express_delivery_timeline_new c-clearfix">
                                            <div class="op_express_delivery_timeline_title">
                                                <div class="op_express_delivery_timeline_circle op_express_delivery_timeline_circle">
                                                    <i class="c-icon op_express_delivery_timeline_circle_red"></i>
                                                    {{--<i class="c-text c-text-danger c-text-mult c-gap-left-small">最新</i>--}}
                                                </div>
                                                <div class="op_express_delivery_timeline_info">{{$t->AcceptTime}} <br>【{{$t->Location}}】 {{$t->AcceptStation}}</div>
                                            </div>
                                        </li>
                                    @endforeach
                                    {{--<li class="op_express_delivery_timeline_label c-clearfix">--}}
                                    {{--<div class="op_express_delivery_timeline_title">--}}
                                    {{--<div class="op_express_delivery_timeline_circle">--}}
                                    {{--<i class="c-icon op_express_delivery_timeline_circle_blue"></i>--}}
                                    {{--</div>--}}
                                    {{--<div class="op_express_delivery_timeline_info">2018年10月27日 下午2:43:05<br>【沈阳市】 快件到达 【沈阳中转】</div>--}}
                                    {{--</div>--}}
                                    {{--</li>--}}
                                    {{--<li class="op_express_delivery_timeline_label c-clearfix">--}}
                                    {{--<div class="op_express_delivery_timeline_title">--}}
                                    {{--<div class="op_express_delivery_timeline_circle">--}}
                                    {{--<i class="c-icon op_express_delivery_timeline_circle_blue"></i>--}}
                                    {{--</div><div class="op_express_delivery_timeline_info">2018年10月27日 上午8:08:05<br>【盘锦市】 快件离开 【盘锦中转】 发往 【沈阳中转】</div>--}}
                                    {{--</div>--}}
                                    {{--</li>--}}
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class='col-lg-12'>
        <span></span>

    </div>

    <script type="text/javascript">
        function clickItShow(){
            $("#remark").show();
        }
        function clickItHide(){
            $("#remark").hide();
        }
        function showEdit() {
            $('#edit-express').show()
        }
    </script>
@endsection

