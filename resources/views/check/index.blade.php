@extends('base.base')

@section('base')
    <style>
         .table td {
            font-size: 12.5px;
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
                        <li class="breadcrumb-item"><a href="#">挂单审核</a></li>
                        <li class="breadcrumb-item active" aria-current="page">挂单列表</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">挂单列表</h4>
                            <p class="card-description">
                            </p>
                            {{ Form::open(['route' => 'check.index', 'method' => 'get','class'=>'form-inline']) }}
                            <select name="status" class="form-control" id="status">

                                <option value=' ' @if(isset($key) && $key == '0') selected @endif>--请选择订单状态--</option>
                                <option value='cancelled' @if(isset($key) && $key == '0') selected @endif>已取消</option>
                                <option value='wait_pay' @if(isset($key) && $key == '1') selected @endif>待支付</option>
                                <option value='wait_confirm' @if(isset($key) && $key == '1') selected @endif>待确认收款</option>
                                <option value='completed' @if(isset($key) && $key == '1') selected @endif>已完成</option>
                                <option value='appealing' @if(isset($key) && $key == '1') selected @endif>申诉中</option>
                                <option value='appeal_fail' @if(isset($key) && $key == '1') selected @endif>申诉失败</option>
                                <option value='appeal_success' @if(isset($key) && $key == '1') selected @endif>申诉成功</option>
                            </select>&emsp;
                            {{ Form::submit('搜索', array('class' => 'btn btn-gradient-primary sm-2')) }}
                            {{ Form::close() }}
                            <br>
                            <table class="table table-bordered table-striped" style="">
                                <thead>
                                <tr>
                                    <th scope="col" width="200px">订单编号</th>
                                    <th scope="col" width="5%">币种</th>
                                    <th scope="col" width="15%">买入者信息</th>
                                    <th scope="col" width="15%">挂卖者信息</th>
                                    <th scope="col" width="15%">挂卖者收款账户</th>
                                    <th scope="col" width="5%">交易数量</th>
                                    <th scope="col" width="5%">交易金额</th>
                                    <th scope="col" width="15%">转账时间</th>
                                    <th scope="col" width="15%">更新时间</th>
                                    <th scope="col" width="5%">状态</th>
                                    <th scope="col">操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($data as $d)
                                    <tr>
                                        <td>
                                            {{$d->order_sn}}
                                        </td>
                                        <td>
                                            {{$d->trans_coin}}
                                        </td>
                                        <td>
                                            <label>姓名: {{ $d->user->username }}</label>
                                            <label>uid: {{ $d->user->invite_code }}</label>
                                            <label>手机: {{ $d->user->mobile  }}</label>
                                        </td>
                                        <td>
                                            <label>姓名: {{ $d->publish_order->user->username }}</label>
                                            <label>uid: {{ $d->publish_order->user->invite_code}}</label>
                                            <label>手机: {{ $d->publish_order->user->mobile }}</label>
                                        </td>
                                        <td>
                                            (<label>{{\App\Models\InPayment::PAY_TYPE[ $d->publish_order->payment->pay_type ]}}</label>)
                                            <label>{{ $d->publish_order->payment->card_no }}</label>
                                        </td>
                                        <td>
                                            {{$d->trans_num}}
                                        </td>
                                        <td>
                                            {{$d->amount}}
                                        </td>
                                        <td>
                                            {{$d->created_at}}
                                        </td>
                                        <td>
                                            {{$d->updated_at}}
                                        </td>
                                        <td>
                                            {{ \App\Models\C2cTransOrder::STATUS[$d->status]}}
                                        </td>

                                        {{--<td>{{ \App\Models\C2cUser::STATUS[$data->status] }}</td>--}}
                                        <td>
                                            <div class="form-inline" role="group" aria-label="Button group with nested dropdown" >
                                                @if($d->status === 'wait_confirm')
                                                    <a class="btn btn-gradient-success btn-sm" style="margin-right: 3px;margin-bottom: 10px;" onClick="complete({{$d['id']}})">确认收款</a>
                                                @endif
                                                <a class="btn btn-gradient-warning btn-sm" style="margin-right: 3px;" onClick="cancel({{$d['id']}})">关闭订单</a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <br>
                            {{ $data->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        function complete(id){
//            console.info(id);
            swal({
                title:"您确认收到款项了么",
//                text:"确认后",
                type:"warning",
                showCancelButton:true,
                confirmButtonColor:"#DD6B55",
                confirmButtonText:"是的",
                cancelButtonText:"不！",
                closeOnConfirm:false,
                closeOnCancel:false,
            }).then((isConfirm)=> {
                if(isConfirm === true){
                    $.ajax({
                        url:"<?php echo e(route('check.complete')); ?>",
                        type:'POST',
                        data:{
                            id:id,
                        },
                        success:function (data) {
                            if(data.code === 200){
                                swal({
                                    title:"操作成功",
                                    type:"success"
                                }).then(()=>{
                                    window.location="/c2c/check";
                                })
                            }else {
                                swal("出错啦。。。", data.msg, "error");  //后端删除失败
                            }
                        },
                        error: function () {  // ajax请求失败
                            swal("啊哦。。。", "服务器走丢了。。。", "error");
                        }

                    })
                }
            })
        }
        function cancel(id){
            swal({
                title:"您确定取消该订单？",
//                text:"撤回驳回后，用户将恢复为待审核状态！",
                type:"warning",
                showCancelButton:true,
                confirmButtonColor:"#DD6B55",
                confirmButtonText:"是的",
                cancelButtonText:"不！",
                closeOnConfirm:false,
                closeOnCancel:false,
            }).then((isConfirm)=> {
                if(isConfirm === true){
                    $.ajax({
                        url:"<?php echo e(route('check.cancel')); ?>",
                        type:'POST',
                        data:{
                            id:id,
                        },
                        success:function (data) {
//                            var dataObj = $.parseJSON(data);
//                            console.info(data);
                            if(data.code === 200){
                                swal({
                                    title:"操作成功",
                                    text:"您已经撤回了对该用户的驳回",
                                    type:"success"
                                }).then(()=>{
                                    window.location="/c2c/check";
                                })
                            }else {
                                swal("出错啦。。。", data.msg, "error");  //后端删除失败
                            }
                        },
                        error: function () {  // ajax请求失败
                            swal("啊哦。。。", "服务器走丢了。。。", "error");
                        }

                    })
                }
            })
        }
    </script>
@endsection
