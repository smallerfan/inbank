@extends('base.base')

@section('title', '| 编辑 权限')

@section('base')
    <style>
        label.badge.badge-gradient-danger {
            font-size: 20px;
            padding: 9px;
            letter-spacing: 2px;
            margin-left: 18px;
        }
        p{font-weight: bold;font-size: 14px;}
        .head{
            width: 30%;
            text-align: right;
        }
        span.user-name {
            margin-left: 10px;
            color: #ffff;
            background-color: #efb73a;
            padding: 8px;
            border-radius: 12px;
            letter-spacing: 4px;
        }
    </style>
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="page-header">
                <h3 class="page-title">
                    <span class="page-title-icon bg-gradient-primary text-white mr-2">
                        <i class="mdi mdi-wrench"></i>
                    </span>
                    C2C
                </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{url('/c2c/c2c_users')}}">C2C用户列表</a></li>
                        <li class="breadcrumb-item active" aria-current="page">用户财富详情</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title"><i class='fa fa-key'></i> 查看用户 :<label class="badge badge-gradient-danger"> {{ $data->user->username }}</label></h4>
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th class="head">账户: </th>
                                    <th style="text-align: left;">
                                        <label>UID: {{ $data->user->invite_code }}</label><br/>
                                        <label>昵称: {{ $data->user->username }}</label><br/>
                                        <label>手机号: {{ $data->user->mobile }}</label>
                                    </th>
                                </tr>
                                @foreach($data->c2c_user_riches as $datum)
                                    <tr>
                                        @if($datum->c2c_coin->sys_name == 'DATC')
                                            <th class="head">DATC财富: </th>
                                            <th>
                                                <label>可用: {{ $datum->live_num }}</label><br/>
                                                <label>冻结: {{ $datum->frozen_num }}</label>
                                            </th>
                                        @endif
                                        @if($datum->c2c_coin->sys_name == 'ETH')
                                            <th class="head">ETH财富: </th>
                                            <th>
                                                <label>可用: {{ $datum->live_num }}</label><br/>
                                                <label>冻结: {{ $datum->frozen_num }}</label>
                                            </th>
                                        @endif
                                        @if($datum->c2c_coin->sys_name == 'BTC')
                                            <th class="head">BTC财富: </th>
                                            <th>
                                                <label>可用: {{ $datum->live_num }}</label><br/>
                                                <label>冻结: {{ $datum->frozen_num }}</label>
                                            </th>
                                        @endif
                                        @if($datum->c2c_coin->sys_name == 'EOS')
                                            <th class="head">EOS财富: </th>
                                            <th>
                                                <label>可用: {{ $datum->live_num }}</label><br/>
                                                <label>冻结: {{ $datum->frozen_num }}</label>
                                            </th>
                                        @endif
                                        @if($datum->c2c_coin->sys_name == 'USDT')
                                            <th class="head">USDT财富: </th>
                                            <th>
                                                <label>可用: {{ $datum->live_num }}</label><br/>
                                                <label>冻结: {{ $datum->frozen_num }}</label>
                                            </th>
                                        @endif
                                        @if($datum->c2c_coin->sys_name == 'IN')
                                            <th class="head">IN财富: </th>
                                            <th>
                                                <label>可用: {{ $datum->live_num }}</label><br/>
                                                <label>冻结: {{ $datum->frozen_num }}</label>
                                            </th>
                                        @endif
                                        @if($datum->c2c_coin->sys_name == 'DCOIN')
                                            <th class="head">DCOIN财富: </th>
                                            <th>
                                                <label>可用: {{ $datum->live_num }}</label><br/>
                                                <label>冻结: {{ $datum->frozen_num }}</label>
                                            </th>
                                        @endif
                                        @if($datum->c2c_coin->sys_name == 'FUP')
                                            <th class="head">FUP财富: </th>
                                            <th>
                                                <label>可用: {{ $datum->live_num }}</label><br/>
                                                <label>冻结: {{ $datum->frozen_num }}</label>
                                            </th>
                                        @endif
                                    </tr>
                                @endforeach
                                {{ Form::open(array('url' => route('c2c_users.update'))) }}
                                {{ method_field('POST') }}
                                {{ Form::hidden('id', $data->id) }}
                                <tr>
                                    <th class="head">操作</th>
                                    <th>
                                        <div class="form-check form-check-danger">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input" name="operate" id="ExampleRadio4" value="add">
                                                增加
                                                <i class="input-helper"></i></label>
                                        </div>
                                        <div class="form-check form-check-danger">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input" name="operate" id="ExampleRadio4" value="reduce" >
                                                减少
                                                <i class="input-helper"></i></label>
                                        </div>
                                        {{--<input type="radio" value="add" name="operate">&nbsp;--}}
                                        {{--<input type="radio" value="reduce" name="operate">&nbsp;减少--}}
                                    </th>
                                </tr>
                                <tr>
                                    <th class="head">财富类型: </th>
                                    <th>
                                        @foreach($coin as $c)
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input type="radio" class="form-check-input" name="rich" id="optionsRadios1" value="{{$c->id}}">
                                                    &nbsp;{{$c->name}}({{$c->short_name}})
                                                    <i class="input-helper"></i></label>
                                            </div>
                                        @endforeach
                                    </th>
                                </tr>
                                <tr>
                                    <th class="head">操作数量: </th>
                                    <th>
                                        <input type="number" name="num" class="form-control">
                                    </th>
                                </tr>
                                <tr>
                                    <th colspan="2" style="text-align: center;">
                                        {{ Form::submit('提交',array('class'=>'btn btn-gradient-primary mb-2')) }}
                                        {{ Form::close() }}
                                    </th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection
