@extends('base.base')

@section('title', '| 编辑 权限')


@section('base')
    <style>
        img{width: 300px;}
        p{font-weight: bold;font-size: 18px;}
        .remark{display: none;}
        span.order_status {
            color: red;
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
        .info {
            margin-top: 20px;
            margin-left: 30px;
            font-size: 16px;
        }
        i.fa.fa-circle-o {
            margin-right: 15px;
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
                        <li class="breadcrumb-item active" aria-current="page">订单详情</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">订单编号  : {{ $order->code  }}</h4>
                            </p>
                            <br>
                            <h3>
                                <div class="info">

                                    <i class='mdi mdi-bookmark-check'></i>  订单编号  : {{ $order->code  }}


                                </div>
                                <div class="info">
                                    <i class='mdi mdi-bookmark-check'></i>
                                    <span class="tittle-name">订单创建时间：</span>
                                    <span>{{ $order->created_at}}</span>
                                </div>
                                <div class="info">
                                    <i class='mdi mdi-bookmark-check'></i>
                                    <span class="tittle-name">订单状态：</span>
                                    <span class="order_status">{{\App\Models\ShopOrder::STATUS[$order->status]}}</span>
                                </div>
                                <div class="info">
                                    <i class='mdi mdi-bookmark-check'></i>
                                    <span class="tittle-name">订单总额：</span>
                                    <span>{{ $order->amount }}</span>
                                </div>
                                <div class="info">
                                    <i class='mdi mdi-bookmark-check'></i>
                                    <span class="tittle-name">订单商品总数：</span>
                                    <span>{{ $order->buy_num}}</span>
                                </div>
                            </h3>
                            <br>
                            <div class="form-group col-lg-12">
                                <p>订单商品信息:</p>
                                @foreach($order->order_goods as $goods)
                                    <span class="tittle-name">商品名：</span>
                                    <span>{{ $goods->name }}</span>
                                    <br>
                                    <span class="tittle-name">商品单价：</span>
                                    <span>{{ $goods->price }}</span>
                                    <br>
                                    <span class="tittle-name">购买数量：</span>
                                    <span>{{ $goods->buy_count }}</span>
                                    <br>
                                    <span class="tittle-name">商品缩略图：</span>
                                    <span><img src="{{ $goods->img }}"> </span>
                                    <hr>
                                @endforeach

                            </div>
                            <div class="form-group col-lg-12">
                                <p>订单配送信息:</p>
                                <span class="tittle-name">地址：</span>
                                <span>{{ $order->province_name.$order->city_name.$order->county_name.$order->address }}</span>
                                <br>
                                <span class="tittle-name">收件人：</span>
                                <span>{{ $order->uname }}</span>
                                <br>
                                <span class="tittle-name">收件人手机号：</span>
                                <span>{{ $order->tel }}</span>
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
        function showEdit() {
            $('#edit-express').show()
        }
    </script>
@endsection

