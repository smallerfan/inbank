{{-- \resources\views\permissions\index.blade.php --}}
@extends('base.base')

@section('title', '| order')

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
        width:200px;
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
        margin:45px 0 20px 20px;
    }
    .add-button {
        float: right;
        margin: 8.5px;
        display: inline-block;
    }
    a.add-button {
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
    select.form-control {
        box-sizing: border-box;
        text-align: left;
        font-size: 1em;
        height: 2.7em;
        border-radius: 4px;
        border: 1px solid #c8cccf;
        color: #6a6f77;
        -web-kit-appearance: none;
        -moz-appearance: none;
        display: block;
        outline: 0;
        padding: 0 1em;
        text-decoration: none;
        width: 100%;
    }
    span.good_name {
        font-size: 12px;
    }
    span.user-info {
        display: flow-root;
    }
</style>

<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                    <span class="page-title-icon bg-gradient-primary text-white mr-2">
                        <i class="mdi mdi-wrench"></i>
                    </span>
                系统
            </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/shop/node')}}">点位列表</a></li>
                    <li class="breadcrumb-item active" aria-current="page">点位下级列表</li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">点位下级列表</h4>
                        <p class="card-description">
                        </p>
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th width="8%">UID</th>
                                <th width="20%">用户名</th>
                                <th width="20%">用户级别</th>
                                <th width="20%">用户手机号</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($data as $a)
                                <tr>
                                    <td>{{ $a->invite_code}}</td>
                                    <td>{{ $a->username}}</td>
                                    <td>{{ $a->m_user->level}}</td>
                                    <td>{{ $a->mobile}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

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
