{{-- \resources\views\permissions\index.blade.php --}}
@extends('base.base')

@section('title', '| Permissions')

@section('base')
<style>
    td,th{
        font-size: 16px;
        text-align: center;
    }
    p {
        font-size: 16px;
        width: 100px;
        float: left;
        margin:30px 0 20px 20px;
    }

    .btn-primary{
        font-size: 16px;
        width: 60px;
        float: left;
        height: 42px;
        margin:18px 0 20px 20px;
        display: inline;
    }
    table tbody tr td{text-align: center;}
    .statics{
        color: black;
        background-color: #14d1ff;
    }
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
    .head{
        text-align: left;
    }

</style>
    <div class="col-lg-12">
        <h3><i class="fa fa-key"></i>  业绩查询</h3>
        <hr>
        <form action="{{ route('results') }}" method="get">
        <p>UID/手机号：</p>
        <div class="input_control">
            <input type="text" name="value" class="form_input" placeholder="输入搜索值..." @if(isset($value)) value="{{ $value }}" @endif>
        </div>
        <input type="submit" value="搜索" class="btn btn-primary" id="form-submit">
        </form>
        <div class="table-responsive">
            @if($datas == -1)
                <span>此用户不存在！</span>
            @elseif($datas == 0)
                <span>此用户资产账户异常！</span>
            @else
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th class="head" colspan="4">&emsp;&emsp;业绩合计</th>
                </tr>
                <tr>
                    <th class="statics">当前可用DK</th>
                    <th class="statics">当前冻结用DK</th>
                    <th class="statics">当前可用DN</th>
                    <th class="statics">当前冻结DN</th>
                </tr>
                </thead>
                @if(!empty($datas))
                <tbody>
                    <tr>
                        <td>{{ $datas['cur_live_dk'] }}</td>
                        <td>{{ $datas['cur_frozen_dk'] }}</td>
                        <td>{{ $datas['cur_live_dn'] }}</td>
                        <td>{{ $datas['cur_frozen_dn'] }}</td>
                    </tr>
                </tbody>
                @endif
                <thead>
                    <tr>
                        <th class="head" colspan="5">&emsp;&emsp;流水合计</th>
                    </tr>
                    <tr>
                        <th class="statics">转账总额</th>
                        <th class="statics">兑换总额</th>
                        <th class="statics">自然挖矿（DK）</th>
                        <th class="statics">加速挖矿（DK）</th>
                        <th class="statics">矿池拓展（DN）</th>
                    </tr>
                </thead>
                @if(!empty($datas))
                    <tbody>
                        <tr>
                            <td>{{ $datas['transfer_num'] }}</td>
                            <td>{{ $datas['exchange_num'] }}</td>
                            <td>{{ $datas['natural_release_num'] }}</td>
                            <td>{{ $datas['speedup_release_num'] }}</td>
                            <td>{{ $datas['pool_expand_num'] }}</td>
                        </tr>
                    </tbody>
                @endif
                <thead>
                <tr>
                    <th class="head" colspan="2">&emsp;&emsp;团队合计</th>
                </tr>
                <tr>
                    <th class="statics" width="20%">直推人数</th>
                    <th class="statics" width="20%">当前团队总人数</th>
                </tr>
                </thead>
                @if(!empty($datas))
                    <tbody>
                    <tr>
                        <td>{{ $datas['dire_num'] }}</td>
                        <td>{{ $datas['cur_team_num'] }}</td>

                    </tr>
                    </tbody>
                @endif
            </table>
        </div>
    </div>
    @endif

    <script type="text/javascript" src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>
@endsection
