@extends('base.base')
@section('base')
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
                        <li class="breadcrumb-item"><a href="#">商城管理</a></li>
                        <li class="breadcrumb-item active" aria-current="page">商品管理</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">商品列表</h4>
                            <p class="card-description">
                            <div class="add-button">
                                <a href="{{ route('goods.create') }}" class="btn btn-sm btn-gradient-success btn-icon-text">
                                    <i class="mdi mdi-plus btn-icon-prepend"></i>添加商品
                                </a>
                            </div>
                            </p>
                            <form action="{{ route('goods.index') }}" method="get" class="form-inline">
                                <p>商品名称：</p>
                                <div class="input_control">
                                    <input type="text" name="value" class="form-control mb-2 mr-sm-2" placeholder="输入搜索值..." @if(isset($value)) value="{{ $value }}" @endif>
                                </div>
                                <input type="submit" value="搜索" class="btn btn-gradient-primary mb-2" id="form-submit">
                            </form>
                            <table class="table table-bordered table-striped" style="text-align: center">
                                <thead>
                                <tr>
                                    <th width="3%">ID</th>
                                    <th width="20%">商品名称</th>
                                    <th width="5%">分类</th>
                                    <th width="5%">价格</th>
                                    <th width="8%">启用禁用</th>
                                    <th width="8%">是否热销</th>
                                    <th width="8%">是否为授信产品</th>
                                    <th width="3%">综合排序</th>
                                    <th width="8%">状态</th>
                                    <th width="10%">操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($datas as $data)
                                    <tr>
                                        <td>{{ $data->id }}</td>
                                        <td>{{ $data->name }}</td>
                                        <td>
                                            @if($data->category_id == 0)
                                                ——
                                            @else
                                                {{ $data->category->name_cn }}
                                            @endif
                                        </td>
                                        <td>{{ $data->price }}</td>

                                        <td>
                                            @if($data->is_usable === 'disable')
                                                <form action="{{ route('goods.set_enable', $data) }}" method="post">
                                                    {{ csrf_field() }}
                                                    {{ method_field('POST') }}
                                                    <button type="submit" class="btn btn-secondary btn-sm">设为正常</button>
                                                </form>
                                            @else
                                                <form action="{{ route('goods.set_disable', $data) }}" method="post">
                                                    {{ csrf_field() }}
                                                    {{ method_field('POST') }}
                                                    <button type="submit" class="btn btn-outline-secondary btn-sm">设为禁用</button>
                                                </form>
                                            @endif
                                        </td>
                                        @if($data->has_hot_sale->count() == 0)
                                            <td>
                                                <form action="{{ route('goods.set_hot_sale', $data) }}" method="post">
                                                    {{ csrf_field() }}
                                                    {{ method_field('POST') }}
                                                    <button type="submit" class="btn btn-outline-danger btn-sm">设为热销</button>
                                                </form>
                                            </td>

                                        @elseif($data->has_hot_sale->count() > 0)
                                            @if($data->has_hot_sale[0]->channel_type === 'hot_sale')
                                                <td>
                                                    <form action="{{ route('goods.unset_hot_sale', $data) }}" method="post">
                                                        {{ csrf_field() }}
                                                        {{ method_field('POST') }}
                                                        <button type="submit" class="btn btn-danger btn-sm">取消热销</button>
                                                    </form>
                                                </td>
                                            @else
                                                <td>
                                                    <form action="{{ route('goods.set_hot_sale', $data) }}" method="post">
                                                        {{ csrf_field() }}
                                                        {{ method_field('POST') }}
                                                        <button type="submit" class="btn btn-warning">设为热销</button>
                                                    </form>
                                                </td>
                                            @endif
                                        @endif
                                        {{--设为授信产品--}}
                                        @if($data->is_special == 1)
                                            <td>
                                                <form action="{{ route('goods.update_special', $data) }}" method="post">
                                                    {{ csrf_field() }}
                                                    {{ method_field('POST') }}
                                                    <button type="submit" class="btn btn-success btn-sm">设为普通商品</button>
                                                </form>
                                            </td>
                                        @else
                                            <td>
                                                <form action="{{ route('goods.update_special', $data) }}" method="post">
                                                    {{ csrf_field() }}
                                                    {{ method_field('POST') }}
                                                    <button type="submit" class="btn btn-outline-success btn-sm">设为授信商品</button>
                                                </form>
                                            </td>
                                        @endif
                                        {{--@endif--}}

                                        <td>
                                            <input value="{{ $data->sort }}" id="sort{{ $data->id }}" readonly class="sort-value" style="width: 50px"
                                                   ondblclick="this.readOnly=false"
                                                   onchange="this.readOnly=true"
                                                   onblur="lostFocus({{ $data->id }})"
                                                   onkeydown="if(event.keyCode==13) lostFocus({{ $data->id }})"
                                            >
                                        </td>
                                        <td>{{ \App\Models\Goods::STATUS[$data->status] }}</td>
                                        <td>
                                            <a href="{{ route('goods.edit', $data) }}" class="btn btn-sm btn-gradient-dark btn-icon-text" style="margin-right: 3px;    margin-bottom: 10px;">编辑
                                                <i class="mdi mdi-file-check btn-icon-append"></i></a>
                                            <a class="btn btn-sm btn-gradient-danger btn-icon-text" style="margin-right: 3px;" onClick="check({{$data->id}})"><i class="mdi mdi-delete btn-icon-prepend"></i>删除</a>

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
        //input失去焦点时候
        function lostFocus(id) {
            var val = document.getElementById('sort'+id).value;
            $.ajax({
                type: 'POST',
                url: "{{ route('goods.set_sort')}}",
                dataType: 'json',
                data: {'id': id, 'sort': val,'_token':'{{csrf_token()}}'},
                success: function (data) {
                    if(data == 0){
                        alert('设置失败');
                    }
                }
            });
        }
        function check(id){
            swal({
                title:"您确定要删除这个商品吗？",
//                text:"删除后将无法恢复，请谨慎操作！",
                type:"warning",
                showCancelButton:true,
                confirmButtonColor:"#DD6B55",
                confirmButtonText:"是的，我要删除！",
                cancelButtonText:"让我再考虑一下…",
                closeOnConfirm:false,
                closeOnCancel:false,
            }).then((isConfirm)=> {
                if(isConfirm){
                    swal({title:"删除成功！",
                        text:"您已经永久删除了这条信息。",
                        type:"success"
                    }).then(()=>{
                        window.location="/shop/goods/destroy/" + id;
                    })
                }
                else{
                    swal({title:"已取消",
                        text:"您取消了删除操作！",
                        type:"error"})
                }
            })


        }
        function add(){
            var page = layer.open({
                type: 2,
                title: '添加角色',
                shadeClose: true,
                shade: 0.8,
                area: ['70%', '90%'],
                content: '{{ route('goods.create') }}'
            });
        }
        function update(id){
            var page = layer.open({
                type: 2,
                title: '修改角色',
                shadeClose: true,
                shade: 0.8,
                area: ['70%', '90%'],
                content: '/admin/role/update/'+id
            });
        }
        function del(id){
            myConfirm("删除操作不可逆,是否继续?",function(){
                myRequest("/admin/role/del/"+id,"post",{},function(res){
                    layer.msg(res.msg)
                    setTimeout(function(){
                        window.location.reload();
                    },1500)
                },function(){
                    layer.msg(res.msg, function(){});
                });
            });
        }

        $('.menu-switch').click(function(){
            id = $(this).attr('id');
            state = $(this).attr('state');
            console.log(id)
            console.log(state)
            if(state == "on"){
                $('.pid-'+id).hide();
                $(this).attr("state","off")
                $(this).removeClass('mdi-menu-down').addClass('mdi-menu-right');
            }else{
                $('.pid-'+id).show();
                $(this).attr("state","on")
                $(this).removeClass('mdi-menu-right').addClass('mdi-menu-down');
            }
        })
    </script>
@endsection