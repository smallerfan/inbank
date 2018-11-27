@extends('base.base')
@section('base')
    <style>
        p{font-weight: bold;font-size: 14px;}
        .head{
            width: 20%;
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
                        <li class="breadcrumb-item"><a href="#">奖金提现</a></li>
                        <li class="breadcrumb-item active" aria-current="page">C2C奖金提现列表</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">奖金提现审核 -- {{ $data->user->username }} </h4>
                            <p></p>
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th class="head">账户: &nbsp;</th>
                                    <th style="text-align: left;">
                                        <label>&emsp;UID: {{ $data->user->invite_code }}</label><br/>
                                        <label>&emsp;昵称: {{ $data->user->username }}</label><br/>
                                        <label>&emsp;手机号: {{ $data->user->mobile }}</label>
                                    </th>
                                </tr>
                                <tr>
                                    <th class="head">收款账户: &nbsp;</th>
                                    <th style="text-align: left;line-height: 30px;">
                                        @if($data->payment)
                                            @if($data->payment->pay_type == 'bank_card')
                                                <label>&emsp;姓名: {{ $data->payment->real_name }}</label><br/>
                                                <label>&emsp;卡号: {{ $data->payment->card_no }}</label><br/>
                                                <label>&emsp;开户行: {{ $data->payment->open_bank }}</label>
                                            @else
                                                <label>&emsp;姓名: {{ $data->payment->real_name }}</label><br/>
                                                <label>&emsp;账户号: {{ $data->payment->card_no }}</label><br/>
                                                <label>&emsp;二维码:
                                                    <img src="{{ config('filesystems.disks.qiniu.domain') }}{{ $data->payment->code_img }}" width="80px" height="80px">
                                                </label>
                                            @endif
                                        @else
                                            <p style="color: #9f191f;">收款账户被删除</p>
                                        @endif
                                    </th>
                                </tr>
                                <tr>
                                    <th class="head">提现金额: &nbsp;</th>
                                    <th style="text-align: left;">
                                        {{ number_format(abs($data->award),6) }}&nbsp;(CNY)
                                    </th>
                                </tr>
                                <tr>
                                    <th class="head">手续费: &nbsp;</th>
                                    <th style="text-align: left;">
                                        {{ number_format(abs($data->fee),6) }}&nbsp;(CNY)
                                    </th>
                                </tr>
                                @if($data->status == 0)
                                    {{ Form::open(array('url' => route('withdraw.update'))) }}
                                    {{ Form::hidden('id', $data->id) }}
                                    <tr>
                                        <th class="head">操作&nbsp;</th>
                                        <th>
                                            <div class="form-check form-check-danger">
                                                <label class="form-check-label">
                                                    <input type="radio" class="form-check-input" name="status" id="ExampleRadio4" value="2"  onclick="clickItShow()" >
                                                    驳回
                                                    <i class="input-helper"></i></label>
                                            </div>
                                            <br>
                                            <div class="form-check form-check-danger">
                                                <label class="form-check-label">
                                                    <input type="radio" class="form-check-input" name="status" id="ExampleRadio4" value="1" onclick="clickItHide()">
                                                    通过
                                                    <i class="input-helper"></i></label>
                                            </div>
                                            {{--<input type="radio" value="1" name="status">&nbsp;通过&emsp;--}}
                                            {{--<input type="radio" value="2" name="status">&nbsp;驳回--}}
                                        </th>
                                    </tr>
                                    <tr>
                                        <th colspan="2" style="text-align: center;">
                                            {{ Form::submit('提交',['class'=>'btn btn-gradient-primary mb-2']) }}
                                            {{ Form::close() }}
                                        </th>
                                    </tr>
                                @elseif($data->status == 1)
                                    <tr>
                                        <th class="head">驳回原因：&nbsp;</th>
                                        <th>&emsp;已打款</th>
                                    </tr>
                                @else
                                    <tr>
                                        <th class="head">驳回原因：&nbsp;</th>
                                        <th style="color: #9f191f"> &emsp;打款驳回</th>
                                    </tr>
                                @endif
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
