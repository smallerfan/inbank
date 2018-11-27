{{-- \resources\views\permissions\index.blade.php --}}
@extends('base.base')

@section('title', '| Permissions')

@section('base')
<style>
    td,th{
        font-size: 16px;
        text-align: center
    }
    table tbody tr td{text-align: center;}
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
        width: 145px;
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
</style>
    <div class="col-lg-12">
        <h3><i class="fa fa-key"></i>商家列表</h3>
        <hr>
        <form action="{{ route('shopers.index') }}" method="get">
            <p>用户UID/店铺名称：</p>
            <div class="input_control">
                <input type="text" name="value" class="form_input" placeholder="输入搜索值..." @if(isset($value)) value="{{ $value }}" @endif>
            </div>
            <input type="submit" value="搜索" class="btn btn-primary" id="form-submit">
        </form>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th width="5%">ID</th>
                    <th width="15%">店铺名称</th>
                    <th width="10%">所属用户</th>
                    <th width="30%">主营业务</th>
                    <th width="5%">可用状态</th>
                    <th width="10%">自营</th>
                    <th width="10%">审核状态</th>
                    <th width="10%">操作</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($shopers as $shoper)
                    <tr>
                        <td>{{ $shoper->id }}</td>
                        <td>{{ $shoper->name }}</td>
                        <td>{{ $shoper->user->invite_code }}</td>
                        <td style="text-align: left;">{{ $shoper->main_business}}</td>
                        <td>
                            @if($shoper->is_usable === 'disable')
                                <form action="{{ route('shopers.set_enable', $shoper) }}" method="post">
                                    {{ csrf_field() }}
                                    {{ method_field('POST') }}
                                    <button type="submit" class="btn btn-warning">设为正常</button>
                                </form>
                            @else
                                <form action="{{ route('shopers.set_disable', $shoper) }}" method="post">
                                    {{ csrf_field() }}
                                    {{ method_field('POST') }}
                                    <button type="submit" class="btn btn-danger">设为禁用</button>
                                </form>
                            @endif
                        </td>
                        <td>
                            @if($shoper->self_support === 'self')
                                <form action="{{ route('shopers.set_no_self', $shoper) }}" method="post">
                                    {{ csrf_field() }}
                                    {{ method_field('POST') }}
                                    <button type="submit" class="btn btn-danger">取消自营</button>
                                </form>
                            @else
                                <form action="{{ route('shopers.set_self', $shoper) }}" method="post">
                                    {{ csrf_field() }}
                                    {{ method_field('POST') }}
                                    <button type="submit" class="btn btn-warning">设为自营</button>
                                </form>
                            @endif
                        </td>
                        <td>{{ \App\Models\Shoper::STATUS[$shoper->status] }}</td>
                        <td>
                            <a href="{{ route('shopers.edit', $shoper) }}" class="btn btn-info">审核</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $shopers->links() }}
        </div>
    </div>

@endsection
