{{-- \resources\views\permissions\index.blade.php --}}
@extends('base.base')

@section('title', '| Create Permission')

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
                轮播图
            </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">轮播图管理</a></li>
                    <li class="breadcrumb-item active" aria-current="page">轮播图列表</li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">轮播图列表</h4>
                        <p class="card-description">
                        <div class="add-button">
                            <a href="{{ route('banners.create') }}" class="btn btn-sm btn-gradient-success btn-icon-text">
                                <i class="mdi mdi-plus btn-icon-prepend"></i>添加轮播图
                            </a>
                        </div>
                        </p>
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th width="2%">ID</th>
                                <th width="15%">标题</th>
                                <th width="5%">类型</th>
                                <th width="5%">图片</th>
                                <th width="15%">链接</th>
                                <th colspan="2" width="10%">状态</th>
                                <th width="5%">排序</th>
                                <th width="10%">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($datas as $data)
                                <tr>
                                    <td>{{ $data->id }}</td>
                                    <td>{{ $data->title }}</td>
                                    <td>{{ \App\Models\Banner::TYPE[$data->type] }}</td>
                                    <td><img src="{{ $data->picture }}" width="160" height="80"></td>
                                    <td style="text-align: left;">{{ $data->href }}</td>
                                    <td>{{ \App\Models\Banner::STATUS[$data->status] }}</td>
                                    <td>
                                        @if($data->status === 'open')
                                            <form action="{{ route('banners.set_close', $data) }}" method="post">
                                                {{ csrf_field() }}
                                                {{ method_field('POST') }}
                                                <button type="submit" class="btn btn-outline-danger btn-sm">禁用</button>
                                            </form>
                                        @else
                                            <form action="{{ route('banners.set_open', $data) }}" method="post">
                                                {{ csrf_field() }}
                                                {{ method_field('POST') }}
                                                <button type="submit" class="btn btn-danger btn-sm">启用</button>
                                            </form>
                                        @endif
                                    </td>
                                    <td>{{ $data->sort }}</td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                            <a href="{{ route('banners.edit', $data)}}" class="btn btn-sm btn-gradient-dark btn-icon-text" style="margin-right: 3px">编辑
                                                <i class="mdi mdi-file-check btn-icon-append"></i></a>
                                            <span class="btn btn-sm btn-gradient-danger btn-icon-text" onclick="del({{ $data->id }})"><i class="mdi mdi-delete btn-icon-prepend"></i>删除</span>
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

<script type="text/javascript">
    function del(id){
        swal({
            title:"删除操作不可逆,是否继续?",
            type:"warning",
            showCancelButton:true,
            confirmButtonColor:"#DD6B55",
            confirmButtonText:"是的",
            cancelButtonText:"不！",
            closeOnConfirm:false,
            closeOnCancel:false,
        }).then((isConfirm)=> {
            if(isConfirm === true){
                $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
                $.ajax({
                    url:"{{ route('banners.delete_banner') }}",
                    type:'post',
                    data:{
                        id:id,
                    },
                    success:function (data) {
                        if(data.code === 200){
                            swal({
                                title:"操作成功",
                                text:"您已成功删除该轮播图！",
                                type:"success"
                            }).then(()=>{
                                window.location="/banners";
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
