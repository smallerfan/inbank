@extends('base.base')

@section('title', '| order')

@section('base')
<style>
    a.btn.btn-inverse-success.btn-sm {
        margin-top: 5px;
        margin-bottom: 5px;
    }
    span.good_name {
        font-size: 12px;
    }
    a.btn.btn-inverse-danger.btn-sm {
        margin-top: 5px;
        margin-bottom: 5px;
    }
    .table {
        text-align: center;
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
                        <h4 class="card-title">普通商品订单</h4>
                        <form action="{{ route('order.index') }}" method="get" class="form-inline">
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
                                    <option value=" ">---请选择状态---</option>
                                    <option value="close">已关闭</option>
                                    <option value="wait_deliver">待发货</option>
                                    <option value="wait_collect">待收货</option>
                                    <option value="complete">已完成</option>
                                    <option value="appeal">申诉</option>

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

                                            <a href="{{ route('order.detail',$data->id) }}" class="btn btn-inverse-info btn-sm" style="margin-right: 3px;"><i class="mdi mdi-format-list-bulleted menu-icon"></i> 查看详情</a>
                                            <br>
                                            @if($data->status == 'wait_deliver')
                                                <a href="{{ route('order.ship',$data->id) }}" class="btn btn-inverse-danger btn-sm" style="margin-right: 3px;"><i class="mdi mdi-check-circle-outline"></i> 订单发货</a>
                                                <br>
                                            @endif
                                            @if($data->status == 'wait_collect')
                                                <a href="{{ route('order.express_kd',$data->id) }}" class="btn btn-inverse-success btn-sm" style="margin-right: 3px;"><i class="mdi mdi-dropbox"></i> 物流详情</a>
                                                <br>
                                            @endif
                                            @if($data->status == 'wait_deliver' || $data->status == 'wait_collect')
                                                <form action="{{ route('order.cancel',$data->id) }}" method="post">
                                                    {{ csrf_field() }}
                                                    {{ method_field('POST') }}
                                                    <button type="submit" class="btn btn-inverse-warning btn-sm"><i class="mdi mdi-close-circle-outline"></i> 取消订单</button>
                                                </form>
                                            @endif
                                        </td>

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

@endsection
