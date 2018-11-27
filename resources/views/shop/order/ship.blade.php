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
        input.btn.btn-gradient-primary.mb-2 {
            margin-left: 87px;
            margin-top: 25px;
        }
        .form-group.col-lg-12 {
            line-height: 33px;
            padding: 30px;
            border: 3px solid #222d3203;
            border-radius: 25px;
            background: #222d3236;
        }
        .form-control {
            margin-left: 86px;
            width: 50%;
            border-radius: 11px;
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
        select#express_name {
            font-size: 10px;
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
                        <li class="breadcrumb-item active" aria-current="page">订单发货</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <span></span>
                            <h4>
                                <i class='fa fa-circle-o'></i>  订单编号  : {{ $order->code  }}
                                <span class="order_status">{{\App\Models\ShopOrder::STATUS[$order->status]}}</span>
                            </h4>

                            <br>
                            <div class="form-group col-lg-12">
                                <p>订单配送信息:</p>
                                <span class="tittle-name">地址：</span>
                                <span>{{ $order->address }}</span>
                                <br>
                                <span class="tittle-name">收件人：</span>
                                <span>{{ $order->uname }}</span>
                                <br>
                                <span class="tittle-name">收件人手机号：</span>
                                <span>{{ $order->tel }}</span>
                                <span class="btn btn-gradient-primary mb-2" onclick="showEdit()">修改配送信息</span>
                            </div>

                            <div class="form-group col-lg-12" id="edit-express">
                                {{ Form::open(array('url' => route('order.edit_express'))) }}
                                {{ csrf_field() }}
                                {{ method_field('POST') }}

                                {{ Form::hidden('id', $order->id) }}
                                <div class="form-group-input">
                                    {{ Form::label('address', '地址：') }}
                                    {{ Form::text('address', '', array('class' => 'form-control')) }}
                                </div>
                                <div class="form-group-input">
                                    {{ Form::label('uname', '收件人：') }}
                                    {{ Form::text('uname', '', array('class' => 'form-control')) }}
                                </div>
                                <div class="form-group-input">
                                    {{ Form::label('tel', '收件人手机：') }}
                                    {{ Form::text('tel', '', array('class' => 'form-control')) }}
                                </div>

                                {{ Form::submit('确认修改',array('class' => 'btn btn-gradient-primary mb-2')) }}
                                {{ Form::close() }}
                            </div>


                            <div class="form-group col-lg-12">
                                {{ Form::open(array('url' => route('order.update'))) }}
                                {{ csrf_field() }}
                                {{ method_field('POST') }}

                                {{ Form::hidden('id', $order->id) }}
                                <div class="form-group-input">
                                    {{ Form::label('express_name', '快递公司：') }}
                                    {{ Form::select('express_name', $order->ex,null, array('class' => 'form-control-sm'))}}
                                </div>
                                <div class="form-group-input">
                                    {{ Form::label('express_sn', '快递单号：') }}
                                    {{ Form::text('express_sn', '', array('class' => 'form-control')) }}
                                </div>
                                <div class="form-group-input">
                                    {{ Form::label('remark', '备注：') }}
                                    {{ Form::text('remark', '', array('class' => 'form-control')) }}
                                </div>
                                {{ Form::submit('提交',array('class' => 'btn btn-gradient-primary mb-2')) }}
                                {{ Form::close() }}
                            </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class='col-lg-12'>

        {{--@endif--}}
        {{--@if($goods->is_pass() || $goods->is_reject())--}}
            {{--<div class="form-group">--}}
                {{--<p>审核结果:</p>{{ \App\Models\C2cUserAuth::STATUS[$goods->status] }}--}}
            {{--</div>--}}
            {{--@if($goods->is_reject())--}}
                {{--<div class="form-group">--}}
                    {{--<p>操作凭据:</p>{{ $goods->approval_reason }}--}}
                {{--</div>--}}
            {{--@endif--}}
        {{--@endif--}}
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

