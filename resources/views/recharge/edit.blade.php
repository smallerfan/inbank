@extends('base.base')

@section('title', '| 审核 充值信息')

@section('base')
    <style>
        .tr-note , .tr-real-num{
            display: none;
        }
        .head{
            text-align: right;
            width: 20%;
        }
        .th-save{
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
                    C2C
                </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{url('/recharge')}}">充值列表</a></li>
                        <li class="breadcrumb-item active" aria-current="page">充值详情</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">审核{{ $data->c2c_coin->sys_name }}信息——{{ $data->user->invite_code }}</h4>
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th class="head">账户: </th>
                                    <th>
                                        <label>UID: {{ $data->user->invite_code }}</label><br/>
                                        <label>手机号: {{ $data->user->mobile }}</label>
                                    </th>
                                </tr>
                                <tr>
                                    <th class="head">币种: </th>
                                    <th>
                                        {{ $data->c2c_coin->name }}({{ $data->c2c_coin->short_name }})
                                    </th>
                                </tr>
                                <tr>
                                    <th class="head">数量: </th>
                                    <th>
                                        {{ $data->coin_num }}
                                    </th>
                                </tr>
                                <tr>
                                    <th class="head">钱包地址: </th>
                                    <th>
                                        {{ $data->wallet }}
                                    </th>
                                </tr>
                                <tr>
                                    <th class="head">状态: </th>
                                    <th>
                                        {{ \App\Models\RechargeExtract::STATUS[$data->status] }}
                                    </th>
                                </tr>
                                @if($data->status == 'wait_approval')
                                    {{ Form::open(array('url' => route('recharge.update'))) }}
                                    {{ Form::hidden('id', $data->id) }}
                                    <tr>
                                        <th class="head">审核类型: </th>
                                        <th>
                                            <div class="form-check form-check-danger">
                                                <label class="form-check-label">
                                                    <input type="radio" class="form-check-input" name="status" id="ExampleRadio4" value="reject"  onclick="clickItShow()" >
                                                    驳回
                                                    <i class="input-helper"></i></label>
                                            </div>
                                            <br>
                                            <div class="form-check form-check-danger">
                                                <label class="form-check-label">
                                                    <input type="radio" class="form-check-input" name="status" id="ExampleRadio4" value="pass" onclick="clickItHide()">
                                                    通过
                                                    <i class="input-helper"></i></label>
                                            </div>
                                            {{--<input type="radio" name="status" value="pass" >&nbsp;&emsp;--}}
                                            {{--<input type="radio" name="status" value="reject"">&nbsp;--}}
                                        </th>
                                    </tr>
                                    <tr class="tr-note">
                                        <th class="head">操作凭证: </th>
                                        <th>
                                            <textarea class="form-control" name="note" rows="5" ></textarea>
                                        </th>
                                    </tr>
                                    <tr class="tr-real-num">
                                        <th class="head">实际充值数量: </th>
                                        <th>
                                            <input class="form-control" name="real_coin_num" value="{{ $data->coin_num }}"/>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th colspan="2" class="th-save">
                                            {{ Form::submit('保存', array('class' => 'btn btn-primary')) }}

                                            {{ Form::close() }}
                                        </th>
                                    </tr>
                                @elseif($data->status == 'reject_approval')
                                    <tr>
                                        <th class="head">操作凭证: </th>
                                        <th style="color: #b62d32;">
                                            {{ $data->note }}
                                        </th>
                                    </tr>
                                @else
                                    <tr>
                                        <th class="head">实际充值数量: </th>
                                        <th>
                                            {{ $data->real_coin_num }}
                                        </th>
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

    {{--@if(isset($msg))--}}
        {{--<div class="note note-info">--}}
            {{--<div class="alert alert-info" role="alert">{{ $msg }}</div>--}}
        {{--</div>--}}
    {{--@endif--}}

    <script type="text/javascript">
        function clickItShow(){
            $(".tr-note").show();
            $(".tr-real-num").hide();
        }
        function clickItHide(){
            $(".tr-note").hide();
            $(".tr-real-num").show();
        }
    </script>

@endsection
