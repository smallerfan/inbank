{{-- \resources\views\permissions\index.blade.php --}}
@extends('base.base')

@section('title', '| coin')

@section('base')
    <style>
        td,th{
            font-size: 16px;
            text-align: center;
        }
        table tbody tr td{text-align: center;}
    </style>
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="page-header">
                <h3 class="page-title">
                    <span class="page-title-icon bg-gradient-primary text-white mr-2">
                        <i class="mdi mdi-wrench"></i>
                    </span>
                    理财
                </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">理财管理</a></li>
                        <li class="breadcrumb-item active" aria-current="page">理财宝列表</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">理财宝列表</h4>
                            <br>
                            <a href="{{ route('financing.create') }}" class="btn btn-success">添加</a>
                            <br>
                            <br>
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th width="2%">ID</th>
                                    <th width="8%">标题</th>
                                    <th width="10%">投资期限</th>
                                    <th width="10%">年利率</th>
                                    <th width="30%">支持币种</th>
                                    <th width="15%">收益标签</th>
                                    <th width="10%">热门</th>
                                    <th width="10%">平台认证</th>
                                    <th width="10%">状态</th>
                                    <th width="10%">操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($datas as $data)
                                    <tr>
                                        <td>{{ $data->id }}</td>
                                        <td>{{ $data->name }}</td>
                                        <td>{{ $data->lock_period }}</td>
                                        <td>{{ $data->plan_rate }}</td>
                                        <td>
                                            @foreach($data->coin_item as $k => $c)
                                                <div class="coin_list">
                                                    <label class="">{{$data->support_coins[$k]}}最小值：</label>
                                                    <span>{{$c->cast_min_num}}</span>
                                                </div>
                                                <div class="coin_list">
                                                    <label class="">{{$data->support_coins[$k]}}最大值：</label>
                                                    <span>{{$c->cast_max_num}}</span>
                                                </div>
                                            @endforeach
                                        </td>
                                        <td>{{ $data->plan_label }}</td>
                                        <td>{{ \App\Models\Coin::IS_HOT[$data->is_hot] }}</td>
                                        <td>{{ \App\Models\Coin::PLATFORM_AUTH[$data->platform_auth] }}</td>
                                        <td>{{ \App\Models\Coin::STATUS[$data->status] }}</td>

                                        <td>
                                            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                                <a href="{{ route('financing.edit', $data) }}" class="btn btn-sm btn-gradient-dark btn-icon-text" style="margin-right: 3px;    margin-bottom: 10px;">编辑
                                                    <i class="mdi mdi-file-check btn-icon-append"></i></a>


                                                {!! Form::open(['method' => 'DELETE', 'route' => ['coin.destroy', $data->id] ]) !!}
                                                <button type="submit" class="btn btn-sm btn-gradient-danger btn-icon-text"><i class="mdi mdi-delete btn-icon-prepend"></i>删除</button>

                                                {!! Form::close() !!}
                                            </div>
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
