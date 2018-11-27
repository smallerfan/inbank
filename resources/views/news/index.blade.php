{{-- \resources\views\permissions\index.blade.php --}}
@extends('base.base')

@section('title', '| Permissions')

@section('base')
<style>
    td,th{
        font-size: 16px;
        text-align: center;
    }
    table tbody tr td{text-align: center;}
</style>
    <div class="col-lg-12">
        <h3><i class="fa fa-key"></i>公告列表</h3>
        <hr>
        <a href="{{ route('news.create') }}" class="btn btn-success">添加公告</a>
        <hr>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th width="3%">ID</th>
                    <th width="16%">标题【cn】</th>
                    <th width="5%">类型</th>
                    <th colspan="2" width="10%">状态</th>
                    <th width="13%">创建时间</th>
                    <th width="20%">操作</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($datas as $data)
                    <tr>
                        <td>{{ $data->id }}</td>
                        <td>{{ $data->title_cn }}</td>
                        <td>{{ \App\Models\News::TYPE[$data->news_type] }}</td>

                        <td>
                            @if($data->news_type != 'site')
                                {{ \App\Models\News::STATUS[$data->status] }}
                            @endif
                        </td>
                        <td>
                            @if($data->news_type != 'site')
                                @if($data->status === 'open')
                                    <form action="{{ route('news.set_close', $data) }}" method="post">
                                        {{ csrf_field() }}
                                        {{ method_field('POST') }}
                                        <button type="submit" class="btn btn-danger">禁用</button>
                                    </form>
                                @else
                                    <form action="{{ route('news.set_open', $data) }}" method="post">
                                        {{ csrf_field() }}
                                        {{ method_field('POST') }}
                                        <button type="submit" class="btn btn-warning">启用</button>
                                    </form>
                                @endif
                            @endif
                        </td>
                        <td>{{ $data->created_at }}</td>
                        <td>
                            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                <a href="{{ route('news.show', $data) }}" class="btn btn-info" style="margin-right: 3px;">详情</a>
                                <a href="{{ route('news.edit', $data) }}" class="btn btn-info" style="margin-right: 3px;">编辑</a>
                                @if($data->news_type != 'site')
                                    {!! Form::open(['method' => 'DELETE', 'route' => ['news.destroy', $data->id] ]) !!}
                                    {!! Form::submit('删除', ['class' => 'btn btn-danger']) !!}
                                    {!! Form::close() !!}
                                @endif
                            </div>
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
</script>

@endsection
