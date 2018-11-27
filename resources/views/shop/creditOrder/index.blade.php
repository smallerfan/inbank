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
                                <select class="form-control" name="status" style="margin-bottom: 10px;margin-right: 10px;">
                                    <option value="" @if(!isset($value['status'])) selected @endif>---请选择发货状态---</option>
                                    <option value="0" @if(isset($value['status']) && $value['status'] == '0') selected @endif>待发货</option>
                                    <option value="1" @if(isset($value['status']) && $value['status'] == '1') selected @endif>已收货</option>

                                </select>
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

                                            <a href="{{ route('creditOrder.detail',$data->id) }}" class="btn btn-inverse-info btn-sm" style="margin-right: 3px;"><i class="mdi mdi-format-list-bulleted menu-icon"></i>查看详情</a>
                                            <br>
                                            @if($data->delivered_status == 0)
                                                <a href="{{ route('creditOrder.ship',$data->id) }}" class="btn btn-inverse-danger btn-sm" style="margin-right: 3px;"><i class="mdi mdi-check-circle-outline"></i>订单发货</a>
                                                <br>
                                            @endif
                                            @if($data->delivered_status == 1)
                                                <a href="{{ route('creditOrder.express_kd',$data->id) }}" class="btn btn-inverse-success btn-sm" style="margin-right: 3px;"><i class="mdi mdi-dropbox"></i>物流详情</a>
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
@endsection
