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
        <h3><i class="fa fa-key"></i>  DK下拨统计</h3>
        <hr>
        {{ Form::open(['route' => 'dk-stir.index', 'method' => 'get']) }}
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
                    <th scope="col">UID</th>
                    <th scope="col">昵称</th>
                    <th scope="col">手机号</th>
                    <th scope="col">下拨数量</th>
                    <th scope="col">当前DK量</th>
                    <th scope="col">当前DN量</th>
                    <th scope="col">创建时间</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($datas as $data)
                    <tr>
                        <td>{{ $data->user->invite_code }}</td>
                        <td>{{ $data->user->username }}</td>
                        <td>{{ $data->user->mobile }}</td>
                        <td>{{ $data->get_num }}</td>
                        <td>{{ $data->cur_live_dk }}</td>
                        <td>{{ $data->cur_dn }}</td>
                        <td>{{ $data->created_at }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $datas->links() }}
        </div>
    </div>

@endsection
