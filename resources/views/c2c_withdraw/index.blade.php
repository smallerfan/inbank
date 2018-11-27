@extends('base.base')
@section('base')
    <style>
        .input_control{
            width:200px;
            margin:20px 0 20px 20px;
            float: left;
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
                        <li class="breadcrumb-item"><a href="#">奖金提现</a></li>
                        <li class="breadcrumb-item active" aria-current="page">C2C奖金提现列表</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">奖金提现列表</h4>
                            <p></p>
                            <form action="{{ route('withdraw.index') }}" method="get" class="form-inline">
                                    {{--<label class="form-inline" >按UID/手机号查找：</label>--}}
                                    <input type="text" name="value" class="form-control" placeholder="输入UID/手机号..." @if(isset($value)) value="{{ $value }}" @endif>
                                {{--<br>--}}

                                    {{--<label class="form-inline">按打款状态查找：</label>--}}
                                    <select class="form-control" name="status"style="margin-left: 10px">
                                        <option value="" @if(!isset($status))selected @endif>---请选择打款状态---</option>
                                        <option value="0" @if($status == '0')selected @endif>待打款</option>
                                        <option value="1" @if($status == '1')selected @endif>已打款</option>
                                        <option value="2" @if($status == '2')selected @endif>打款驳回</option>

                                    </select>
                                <input type="submit" value="搜索" class="btn btn-gradient-primary mb-2" id="form-submit" style="    margin-top: 8px;margin-left: 24px;">
                            </form>
                            <br>
                            <table class="table table-bordered table-striped" style="font-size: 16px; text-align: center">
                                <thead>
                                <tr>
                                    <th scope="col" width="10%">UID</th>
                                    <th scope="col" width="15%">账户</th>
                                    <th scope="col" width="15%">提现金额</th>
                                    <th scope="col" width="15%">当前资产和</th>
                                    <th scope="col" width="10%">状态</th>
                                    <th scope="col" width="20%">创建时间</th>
                                    <th scope="col">操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($datas as $data)
                                    <tr>
                                        <td>{{ $data->user->invite_code }}</td>
                                        <td>{{ $data->user->mobile }}</td>
                                        <td>{{ number_format(abs($data->award),6) }}&nbsp;(CNY)</td>
                                        <td>{{ $data->current_award }}&nbsp;(CNY)</td>
                                        <td>{{ \App\Models\AssetsLog::STATUS[$data->status] }}</td>
                                        <td>{{ $data->created_at }}</td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                                <a href="{{ route('withdraw.edit', $data) }}" class="btn btn-gradient-info btn-sm" style="margin-right: 3px;"><i class="mdi mdi-format-list-bulleted menu-icon"></i>审核</a>
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
