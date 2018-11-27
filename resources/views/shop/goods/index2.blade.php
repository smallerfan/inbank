{{-- \resources\views\permissions\index.blade.php --}}
@extends('base.base')

@section('base')
<style>
    td,th{
        font-size: 16px;
        text-align: center
    }
    table tbody tr td{text-align: center;}
    .weight{width:55px;}
    .sort-value{width: 45px;}
    .input_control{
        width:360px;
        margin:20px 0 20px 20px;
        float: left;
    }
    input[type="text"]{
        box-sizing: border-box;
        text-align:left;
        font-size:1em;
        height:2.7em;
        border-radius:4px;
        border:1px solid #c8cccf;
        color:#6a6f77;
        -web-kit-appearance:none;
        -moz-appearance: none;
        display:block;
        outline:0;
        padding:0 1em;
        text-decoration:none;
        width:100%;
    }
    input[type="text"]:focus{
        border:1px solid #ff7496;
    }
    p {
        font-size: 16px;
        width: 80px;
        float: left;
        margin:30px 0 20px 20px;
    }
    .data-div span{
        width: 100%;
        height: 240px;
        font-size: 17px;
        color: #14d1ff;
        float: left;
        text-align: left;
        margin-top: 60px;
        margin-left: 80px;
    }
    #form-submit{
        margin:22px 0 20px 20px;
    }
    .add-button {
        float: right;
        margin: 8.5px;
        display: inline-block;
    }
    a.add-button {
        margin-top: 15px;
        text-decoration: none;
        background: #222d32;
        color: #f2f2f2;
        padding: 10px 30px 10px 30px;
        font-size: 16px;
        font-family: 微软雅黑,宋体,Arial,Helvetica,Verdana,sans-serif;
        font-weight: bold;
        border-radius: 3px;
        -webkit-transition: all linear 0.30s;
    }
</style>
    <div class="col-lg-12">
        <h3><i class="fa fa-key"></i>商品列表</h3>
        <hr>
        <form action="{{ route('goods.index') }}" method="get">
            <p>商品名称：</p>
            <div class="input_control">
                <input type="text" name="value" class="form_input" placeholder="输入搜索值..." @if(isset($value)) value="{{ $value }}" @endif>
            </div>
            <input type="submit" value="搜索" class="btn btn-primary" id="form-submit">
            <div class="add-button">
                <a href="{{ route('goods.create') }}" class="add-button">添加商品</a>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th width="2%">ID</th>
                    <th width="35%">商品名称</th>
                    <th width="10%">分类</th>
                    <th width="5%">价格</th>
                    <th width="8%">启用禁用</th>
                    <th width="8%">是否热销</th>
                    <th width="8%">是否为授信产品</th>
                    <th class="weight" width="8%">综合排序</th>
                    <th width="8%">状态</th>
                    <th width="8%">操作</th>
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
                                   <button type="submit" class="btn btn-warning">设为正常</button>
                               </form>
                           @else
                               <form action="{{ route('goods.set_disable', $data) }}" method="post">
                                   {{ csrf_field() }}
                                   {{ method_field('POST') }}
                                   <button type="submit" class="btn btn-danger">设为禁用</button>
                               </form>
                           @endif
                       </td>
                       @if($data->has_hot_sale->count() == 0)
                           <td>
                               <form action="{{ route('goods.set_hot_sale', $data) }}" method="post">
                                   {{ csrf_field() }}
                                   {{ method_field('POST') }}
                                   <button type="submit" class="btn btn-warning">设为热销</button>
                               </form>
                           </td>

                       @elseif($data->has_hot_sale->count() > 0)
                           @if($data->has_hot_sale[0]->channel_type === 'hot_sale')
                               <td>
                                   <form action="{{ route('goods.unset_hot_sale', $data) }}" method="post">
                                       {{ csrf_field() }}
                                       {{ method_field('POST') }}
                                       <button type="submit" class="btn btn-danger">取消热销</button>
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
                                        <button type="submit" class="btn btn-danger">设为普通商品</button>
                                    </form>
                                </td>
                            @else
                                <td>
                                    <form action="{{ route('goods.update_special', $data) }}" method="post">
                                        {{ csrf_field() }}
                                        {{ method_field('POST') }}
                                        <button type="submit" class="btn btn-warning">设为授信商品</button>
                                    </form>
                                </td>
                            @endif
                        {{--@endif--}}

                       <td>
                           <input value="{{ $data->sort }}" id="sort{{ $data->id }}" readonly class="sort-value"
                                  ondblclick="this.readOnly=false"
                                  onchange="this.readOnly=true"
                                  onblur="lostFocus({{ $data->id }})"
                                  onkeydown="if(event.keyCode==13) lostFocus({{ $data->id }})"
                           >
                       </td>
                       <td>{{ \App\Models\Goods::STATUS[$data->status] }}</td>
                       <td>
                           <a href="{{ route('goods.edit', $data) }}" class="btn btn-info" style="margin-right: 3px;">编辑</a>
                           <a class="btn btn-info" style="margin-right: 3px;" onClick="check({{$data->id}})">删除</a>

                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $datas->links() }}
        </div>
    </div>
<script type="text/javascript">

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
        console.info(id);
        swal({
                title:"您确定要删除这条信息吗",
                text:"删除后将无法恢复，请谨慎操作！",
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

</script>

@endsection
