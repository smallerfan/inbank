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
        <h3><i class="fa fa-key"></i>  认购列表</h3>
        <hr>
        {{ Form::open(['route' => 'subscribes.index', 'method' => 'get']) }}
        <select name="peroid" class="search-for-help">
            <option value='' @if(empty($peroid)) selected @endif>认购期</option>
            <option value='认购一期' @if(isset($peroid) && $peroid == '认购一期') selected @endif>认购一期</option>
            <option value='认购二期' @if(isset($peroid) && $peroid == '认购二期') selected @endif>认购二期</option>
            <option value='认购三期' @if(isset($peroid) && $peroid == '认购三期') selected @endif>认购三期</option>
        </select>&emsp;
        <select name="key" class="search-for-help">
            <option value='1' @if(isset($key) && $key == '1') selected @endif>手机号</option>
            <option value='0' @if(isset($key) && $key == '0') selected @endif>UID</option>
        </select>&emsp;
        <input type="text" name="value" placeholder="输入搜索值..." @if(isset($key)) value="{{ $value }}" @endif>

        {{ Form::submit('搜索', array('class' => 'btn btn-primary')) }}
        {{ Form::close() }}
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th width="8%">UID</th>
                    <th width="10%">姓名</th>
                    <th width="10%">手机号</th>
                    <th width="8%">等级</th>
                    <th width="5%">期数</th>
                    <th width="8%">单价(DK)</th>
                    <th width="10%">认购数量</th>
                    <th width="10%">创建时间</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($datas as $data)
                    <tr>
                        <td>{{ $data->user->invite_code }}</td>
                        <td>{{ $data->c2c_user_auth->name }}</td>
                        <td>{{ $data->user->mobile }}</td>
                        <td>{{ \App\Models\User::GRADE[$data->user->user_grade] }}</td>
                        <td>{{ $data->c2c_release_plan->name }}</td>
                        <td>{{ $data->exchange_rate }}</td> {{-- price--}}
                        <td>{{ $data->exchange_num }}</td>
                        <td>{{ $data->created_at }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $datas->links() }}
        </div>
    </div>

@endsection
