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
                        <li class="breadcrumb-item"><a href="#">实名认证</a></li>
                        <li class="breadcrumb-item active" aria-current="page">用户列表</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">用户列表</h4>
                            <p class="card-description">
                            </p>
                            {{ Form::open(['route' => 'auth.index', 'method' => 'get','class'=>"form-inline"]) }}
                            <select name="status" class="form-control mb-2 mr-sm-2">
                                <option value='' @if(empty($status)) selected @endif>请选择审核状态</option>
                                <option value='wait_approval' @if(isset($status) && $status == 'wait_approval') selected @endif>待审核</option>
                                <option value='reject_approval' @if(isset($status) && $status == 'reject_approval') selected @endif>已驳回</option>
                                <option value='pass_approval' @if(isset($status) && $status == 'pass_approval') selected @endif>已通过</option>
                            </select>&emsp;
                            <select name="key" class="form-control mb-2 mr-sm-2">
                                <option value='0' @if(isset($key) && $key == '0') selected @endif>UID</option>
                                <option value='1' @if(isset($key) && $key == '1') selected @endif>手机号</option>
                            </select>&emsp;
                            <input type="text" class="form-control mb-2 mr-sm-2" name="value" placeholder="输入搜索值..." @if(isset($key)) value="{{ $value }}" @endif>

                            {{ Form::submit('搜索', array('class' => 'btn btn-gradient-primary mb-2')) }}
                            {{ Form::close() }}
                            <table class="table table-bordered table-striped" style="font-size: 16px;">
                                <thead>
                                <tr>
                                    <th scope="col">UID</th>
                                    <th scope="col">姓名</th>
                                    <th scope="col">手机号</th>
                                    <th scope="col">身份证号</th>
                                    <th scope="col">状态</th>
                                    <th scope="col">创建时间</th>
                                    <th scope="col">操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($datas as $data)
                                    <tr>
                                        <td>{{ $data->user->invite_code }}</td>
                                        <td>{{ $data->name }}</td>
                                        <td>{{ $data->mobile }}</td>
                                        <td>{{ $data->id_number }}</td>
                                        <td>{{ \App\Models\C2cUserAuth::STATUS[$data->status] }}</td>
                                        <td>{{ $data->created_at }}</td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                                <a href="{{ route('auth.edit', $data) }}" class="btn btn-gradient-info btn-sm" style="margin-right: 3px;">
                                                    <i class="mdi mdi-format-list-bulleted"></i>
                                                    @if($data->status !== 'wait_approval')详情@elseif($data->status === 'wait_approval')审核@endif
                                                </a>
                                                @if($data->status === 'reject_approval')
                                                    <a class="btn btn-sm btn-gradient-danger btn-icon-text" style="margin-right: 3px;" onClick="check({{$data->id}})">                                                    <i class="mdi mdi-replay"></i>
                                                        撤销驳回</a>
                                                @endif
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
    <script>
        function check(id){
//            console.info(id);
            swal({
                title:"您是否撤回对此用户的驳回",
                text:"撤回驳回后，用户将恢复为待审核状态！",
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
                        url:"{{ route('auth.recall') }}",
                        type:'POST',
                        data:{
                            id:id,
                        },
                        success:function (data) {
                            if(data.code === 200){
                                swal({
                                    title:"操作成功",
                                    text:"您已经撤回了对该用户的驳回",
                                    type:"success"
                                }).then(()=>{
                                    window.location="/c2c/auth";
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
