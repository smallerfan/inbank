@extends('base.base')

@section('base')
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
                        <li class="breadcrumb-item"><a href="#">用户管理</a></li>
                        <li class="breadcrumb-item active" aria-current="page">用户列表</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">用户列表</h4>
                            {{ Form::open(['route' => 'c2c_users.index', 'method' => 'get','class'=>'form-inline']) }}
                            <select name="key" class="form-control">
                                <option value='0' @if(isset($key) && $key == '0') selected @endif>UID</option>
                                <option value='1' @if(isset($key) && $key == '1') selected @endif>手机号</option>
                            </select>&emsp;
                            <div class="input-group">
                                <input type="text" class="form-control" aria-label="Recipient's username" aria-describedby="basic-addon2"  name="value" placeholder="输入搜索值..."  @if(isset($key)) value="{{ $value }}" @endif>
                                <div class="input-group-append">
                                    <button class="btn btn-sm btn-gradient-primary" type="submit">搜索</button>
                                </div>
                            </div>
                            {{ Form::close() }}
                            <br>
                            <table class="table table-bordered table-striped" style="font-size: 16px;">
                                <thead>
                                <tr>
                                    <th scope="col" width="17%">账户</th>
                                    @foreach($coin as $c)
                                        <th scope="col">{{$c->name}}({{$c->short_name}})</th>
                                    @endforeach
                                    <th scope="col" width="5%">状态</th>
                                    <th scope="col">操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($datas as $data)
                                    <tr>
                                        <td>
                                            <label>UID: {{ $data->user->invite_code }}</label>
                                            <label>手机号: {{ $data->user->mobile }}</label>
                                        </td>
                                        @foreach($data->c2c_user_riches as $datum)
                                            <td>
                                                @if($datum->c2c_coin->sys_name == 'IN')
                                                    <label>可用: {{ $datum->live_num }}</label>
                                                    <label>冻结: {{ $datum->frozen_num }}</label>
                                                @endif
                                                @if($datum->c2c_coin->sys_name == 'FUP')
                                                    <label>可用: {{ $datum->live_num }}</label>
                                                    <label>冻结: {{ $datum->frozen_num }}</label>
                                                @endif
                                                @if($datum->c2c_coin->sys_name == 'ETH')
                                                    <label>可用: {{ $datum->live_num }}</label>
                                                    <label>冻结: {{ $datum->frozen_num }}</label>
                                                @endif
                                                @if($datum->c2c_coin->sys_name == 'BTC')
                                                    <label>可用: {{ $datum->live_num }}</label>
                                                    <label>冻结: {{ $datum->frozen_num }}</label>
                                                @endif
                                                @if($datum->c2c_coin->sys_name == 'EOS')
                                                    <label>可用: {{ $datum->live_num }}</label>
                                                    <label>冻结: {{ $datum->frozen_num }}</label>
                                                @endif
                                                @if($datum->c2c_coin->sys_name == 'USDT')
                                                    <label>可用: {{ $datum->live_num }}</label>
                                                    <label>冻结: {{ $datum->frozen_num }}</label>
                                                @endif
                                                @if($datum->c2c_coin->sys_name == 'DCOIN')
                                                    <label>可用: {{ $datum->live_num }}</label>
                                                    <label>冻结: {{ $datum->frozen_num }}</label>
                                                @endif
                                            </td>
                                        @endforeach
                                        <td>{{ \App\Models\C2cUser::STATUS[$data->status] }}</td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                                <a href="{{ route('c2c_users.edit', $data) }}" class="btn btn-gradient-info btn-sm" style="margin-right: 3px;"><i class="mdi mdi-format-list-bulleted menu-icon"></i>财富</a>
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
