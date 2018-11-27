@extends('base.base')

@section('title', '| order')

@section('base')
    <style>
        td,th{
            text-align: center
        }
        span.good_name {
            font-size: 12px;
        }
        a.btn.btn-inverse-info.btn-sm {
            margin-bottom: 10px;
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
                        <li class="breadcrumb-item"><a href="#">订单管理</a></li>
                        <li class="breadcrumb-item active" aria-current="page">订单列表</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">授信商品订单</h4>
                            <form action="{{ route('creditOrder.index') }}" method="get" class="form-inline">
                                <div class="input_control">
                                    <input type="text" class="form-control mb-2 mr-sm-2" id="user_name" name="user_name" class="form_input" placeholder="输入用户名..." @if(isset($value['user_name'])) value="{{ $value['user_name']}}" @endif>
                                </div>
                                <div class="input_control">
                                    <input type="text" class="form-control mb-2 mr-sm-2" id="code" name="code" class="form_input" placeholder="输入订单编号..." @if(isset($value['code'])) value="{{ $value['code'] }}" @endif>
                                </div>
                                <div class="input_control">
                                    <input type="text" class="form-control mb-2 mr-sm-2" id="tel" name="tel" class="form_input" placeholder="输入手机号..." @if(isset($value['tel'])) value="{{ $value['tel'] }}" @endif>
                                </div>
                                <div class="input_control">
                                    <select class="form-control mb-2 mr-sm-2" name="status" id="status">
                                        @if(isset($value['status']))
                                            <option value="{{$value['status']}}" selected>{{ \App\Models\ShopOrder::STATUS[$value['status']] }}</option>
                                        @endif
                                            <option value=" ">---请选择发货状态---</option>
                                            <option value="0">待发货</option>
                                            <option value="1">已收货</option>

                                    </select>
                                </div>
                                <input type="submit" value="搜索" class="btn btn-gradient-primary mb-2" id="form-submit">
                            </form>
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th width="2%">ID</th>
                                    <th width="2%">UID</th>
                                    <th width="15%">订单编号</th>
                                    <th width="12%">用户名称</th>
                                    <th width="10%">用户手机号</th>
                                    <th width="20%">商品名称</th>
                                    <th width="5%">数量</th>
                                    <th width="5%">订单总额</th>
                                    <th width="12%">下单时间</th>
                                    <th width="12%">订单状态</th>
                                    <th width="5%">操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($datas as $data)
                                    <tr>
                                        <td>{{ $data->id }}</td>
                                        <td>{{ $data->users->invite_code }}</td>
                                        <td>{{ $data->code }}</td>
                                        <td>{{ $data->uname }}</td>
                                        <td>{{ $data->tel }}</td>
                                        <td>
                                            @foreach($data->order_goods as $goods )
                                                <span class="good_name"> {{ $goods->name }}</span>
                                                <hr>
                                            @endforeach
                                        </td>
                                        <td>x{{ $data->buy_num }}</td>
                                        <td>{{ $data->amount }}</td>
                                        <td>{{ $data->created_at }}</td>
                                        <td>{{ \App\Models\ShopOrder::STATUS[$data->status] }}</td>
                                        <td>

                                            <a href="{{ route('creditOrder.detail',$data->id) }}" class="btn btn-inverse-info btn-sm" style="margin-right: 3px;">查看详情</a>
                                            <br>
                                            @if($data->delivered_status == 0)
                                                <a href="{{ route('creditOrder.ship',$data->id) }}" class="btn btn-inverse-danger btn-sm" style="margin-right: 3px;">订单发货</a>
                                                <br>
                                            @endif
                                            @if($data->delivered_status == 1)
                                                <a href="{{ route('creditOrder.express_kd',$data->id) }}" class="btn btn-inverse-success btn-sm" style="margin-right: 3px;">物流详情</a>
                                                <br>
                                            @endif

                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <br>
                            {{ $datas->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>





    {{--<div class="col-lg-12">--}}
        {{--<h3><i class="fa fa-key"></i>订单列表</h3>--}}
        {{--<hr>--}}
        {{--<form action="{{ route('creditOrder.index') }}" method="get">--}}
            {{--<div class="input_control">--}}
                {{--<label class="find_key">按用户名查找：</label>--}}
                {{--<input type="text" name="user_name" class="form_input" placeholder="输入用户名..." @if(isset($value['user_name'])) value="{{ $value['user_name']}}" @endif>--}}
            {{--</div>--}}
            {{--<div class="input_control">--}}
                {{--<label class="find_key">按订单编号查找：</label>--}}
                {{--<input type="text" name="code" class="form_input" placeholder="输入订单编号..." @if(isset($value['code'])) value="{{ $value['code'] }}" @endif>--}}
            {{--</div>--}}
            {{--<div class="input_control">--}}
                {{--<label class="find_key">按手机号查找：</label>--}}
                {{--<input type="text" name="tel" class="form_input" placeholder="输入手机号..." @if(isset($value['tel'])) value="{{ $value['tel'] }}" @endif>--}}
            {{--</div>--}}
            {{--<div class="input_control">--}}
                {{--<label class="find_key">按订单发货状态查找：</label>--}}
                {{--<select class="form-control" name="status">--}}
                    {{--@if(isset($value['status']))--}}
                        {{--<option value="{{$value['status']}}" selected>{{ \App\Models\ShopOrder::DELIVER_STATUS[$value['delivered_status']] }}</option>--}}
                    {{--@endif--}}
                    {{--<option value=" ">---请选择发货状态---</option>--}}
                    {{--<option value="0">待发货</option>--}}
                    {{--<option value="1">已收货</option>--}}

                {{--</select>--}}
                {{--<input type="text" name="value" class="form_input" placeholder="输入搜索值..." @if(isset($value)) value="{{ $value }}" @endif>--}}
            {{--</div>--}}
            {{--<input type="submit" value="搜索" class="btn btn-primary" id="form-submit">--}}
            {{--<div class="add-button">--}}
            {{--<a href="{{ route('goods.create') }}" class="add-button">添加商品</a>--}}
            {{--</div>      <p>商品名称：</p>--}}
            {{----}}
        {{--</form>--}}
        {{--<div class="table-responsive">--}}
            {{--<table class="table table-bordered table-striped">--}}
                {{--<thead>--}}
                {{--<tr>--}}
                    {{--<th width="2%">ID</th>--}}
                    {{--<th width="2%">UID</th>--}}
                    {{--<th width="10%">商户</th>--}}
                    {{--<th width="20%">订单编号</th>--}}
                    {{--<th width="10%">用户名称</th>--}}
                    {{--<th width="10%">用户手机号</th>--}}
                    {{--<th width="20%">商品名称</th>--}}
                    {{--<th width="5%">价格</th>--}}
                    {{--<th width="5%">数量</th>--}}
                    {{--<th width="5%">订单总额</th>--}}
                    {{--<th width="12%">下单时间</th>--}}
                    {{--<th width="8%">发货状态</th>--}}
                    {{--<th width="8%">是否精品</th>--}}
                    {{--<th class="weight" width="8%">综合排序</th>--}}
                    {{--<th width="8%">状态</th>--}}
                    {{--<th width="8%">操作</th>--}}
                {{--</tr>--}}
                {{--</thead>--}}
                {{--<tbody>--}}
                {{--@foreach ($datas as $data)--}}
                    {{--<tr>--}}
                        {{--<td>{{ $data->id }}</td>--}}
                        {{--<td>{{ $data->users->invite_code }}</td>--}}
                        {{--<td>{{ $data->shop_shopers->name }}</td>--}}
                        {{--<td>{{ $data->code }}</td>--}}
                        {{--<td>{{ $data->uname }}</td>--}}
                        {{--<td>{{ $data->tel }}</td>--}}
                        {{--<td>--}}
                            {{--@foreach($data->order_goods as $goods )--}}
                                {{--<span class="good_name"> {{ $goods->name }}</span>--}}
                                {{--<hr>--}}
                            {{--@endforeach--}}
                        {{--</td>--}}
                        {{--<td>{{ $data->price }}</td>--}}
                        {{--<td>x{{ $data->buy_num }}</td>--}}
                        {{--<td>{{ $data->amount }}</td>--}}
                        {{--<td>{{ $data->created_at }}</td>--}}
                        {{--<td>{{ \App\Models\ShopOrder::DELIVER_STATUS[$data->delivered_status] }}</td>--}}
                        {{--<td>--}}

                            {{--<a href="{{ route('creditOrder.detail',$data->id) }}" class="btn btn-info" style="margin-right: 3px;">查看详情</a>--}}
                            {{--@if($data->delivered_status == 0)--}}
                                {{--<a href="{{ route('creditOrder.ship',$data->id) }}" class="btn btn-info" style="margin-right: 3px;">订单发货</a>--}}
                            {{--@endif--}}
                            {{--@if($data->delivered_status == 1)--}}
                                {{--<a href="{{ route('creditOrder.express_kd',$data->id) }}" class="btn btn-info" style="margin-right: 3px;">物流详情</a>--}}
                            {{--@endif--}}
                        {{--</td>--}}
                    {{--</tr>--}}
                {{--@endforeach--}}
                {{--</tbody>--}}
            {{--</table>--}}
            {{--{{ $datas->links() }}--}}
        {{--</div>--}}
    {{--</div>--}}

@endsection
