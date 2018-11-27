{{-- \resources\views\permissions\index.blade.php --}}
@extends('base.base')

@section('title', '| Permissions')

@section('base')
<style>
    a:hover {
        color: #efb73a;
        text-decoration: none;
    }
    td,th{
        font-size: 16px;
        text-align: center;
    }
    a {
        color: #efb73a;
        text-decoration: none;
        background-color: transparent;
        -webkit-text-decoration-skip: objects;
    }
    table tbody tr td{text-align: center;}
    @foreach($coin as $c)
    {{'#'. strtolower($c->sys_name)}}
    {
    /*color: #222d32;*/
        list-style: none;
        display: inline-block;
    /*border: 1px solid #21252924;*/
        box-shadow: 0 2px 5px 0 rgba(0,0,0,.26);
        height: 30px;
        border-radius: 10px;
        line-height: 30px;
        width: 120px;
        text-align: center;
    }
    @endforeach
    .active{
        color: #222d32 !important;
        background-color: #21252924;
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
                    <li class="breadcrumb-item"><a href="#">提取管理</a></li>
                    <li class="breadcrumb-item active" aria-current="page">提取列表</li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">提取列表</h4>
                        <div>
                            <ul>
                                @foreach($coin as $c)
                                    @if($c->sys_name == 'ETH')
                                        <li id="eth" @if($msg == 'ETH' || empty($msg)) class="active" @endif><a href="{{ route('extract.index') }}" >ETH提取</a></li>
                                    @else
                                        <li id="{{strtolower($c->sys_name)}}" @if($msg == $c->sys_name || empty($msg)) class="active" @endif><a href="{{ route('extract.lists',$flag = $c->sys_name) }}" >{{$c->sys_name}}提取</a></li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                        {{ Form::open(['route' => 'extract.index', 'method' => 'get','class'=>'form-inline']) }}
                        <input type="hidden" name="coin" value="{{ $msg }}" id="coin" class="form-control">
                        <select name="status" class="form-control" >
                            <option value='' @if(empty($status)) selected @endif>提取状态</option>
                            <option value='wait_approval' @if(isset($status) && $status == 'wait_approval') selected @endif>待审核</option>
                            <option value='reject_approval' @if(isset($status) && $status == 'reject_approval') selected @endif>已驳回</option>
                            <option value='pass_approval' @if(isset($status) && $status == 'pass_approval') selected @endif>已通过</option>
                        </select>&emsp;
                        <select name="key" class="form-control">
                            <option value='0' @if(isset($key) && $key == '0') selected @endif>UID</option>
                            <option value='1' @if(isset($key) && $key == '1') selected @endif>手机号</option>
                        </select>&emsp;
                        <div class="input-group">
                            <input type="text" class="form-control" aria-label="Recipient's username" aria-describedby="basic-addon2"  name="value" placeholder="输入搜索值..."  @if(isset($key)) value="{{ $value }}" @endif>
                            <div class="input-group-append">
                                <button class="btn btn-sm btn-gradient-primary" type="submit">搜索</button>
                            </div>
                        </div>
                        {{ Form::close() }}
                        <br>
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th width="12%">账户</th>
                                <th width="8%">币种</th>
                                <th width="5%">数量</th>
                                <th width="13%">钱包地址</th>
                                <th width="5%">状态</th>
                                <th width="12%">创建时间</th>
                                <th width="5%">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(!empty($datas))
                                @foreach ($datas as $data)
                                    <tr>
                                        <td style="text-align: left;">
                                            <label>UID: {{ $data->user->invite_code }}</label>
                                            <label>手机号: {{ $data->user->mobile }}</label>
                                        </td>
                                        <td>
                                            {{ $data->c2c_coin->name }}
                                            ({{ $data->c2c_coin->short_name }})
                                        </td>
                                        <td>{{ $data->coin_num }}</td>
                                        <td style="text-align: left;">{{ $data->wallet }}</td>
                                        <td>{{ \App\Models\RechargeExtract::STATUS[$data->status] }}</td>
                                        <td>{{ $data->created_at }}</td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                                <a href="{{ route('extract.edit', $data) }}" class="btn btn-gradient-info btn-sm" style="margin-right: 3px;">
                                                    <i class="mdi mdi-format-list-bulleted"></i>
                                                    审核
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7"><p>暂无记录</p></td>
                                </tr>
                            @endif
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
