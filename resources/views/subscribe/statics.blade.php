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
    .statics{
        color: black;
        background-color: #14d1ff;
    }
    div .block{
        width: 100%;
        height: 30px;
    }
</style>
    <div class="col-lg-10">
        <h3><i class="fa fa-key"></i>  认购统计</h3>
        <hr>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    {{--当前第几期、当期剩余数量、当期目标数量、认购排名（取前10条，显示姓名、用户UID、认购数量）--}}
                    <th class="statics">当前期数</th>
                    <th class="statics">当期剩余数量</th>
                    <th class="statics">当期目标数量</th>
                </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $data->name }}</td>
                        <td>{{ $data->released_num }}</td>
                        <td>{{ $data->release_num}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="block"></div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th class="statics" colspan="3">认购排名</th>
                </tr>
                <tr>
                    <th scope="col">UID</th>
                    <th scope="col">姓名</th>
                    <th scope="col">认购数量</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($datas as $data)
                    <tr>
                        <td>{{ $data->user->invite_code }}</td>
                        <td>{{ $data->c2c_user_auth->name }}</td>
                        <td>{{ $data->live_num }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection
