@extends('base.base')

{{--@section('title', 'home')--}}

@section('base')
    <style>
        td,th{
            font-size: 16px;
            text-align: center;
        }
        .statics{
            color: black;
            background-color: #14d1ff;
        }
        .page-title {
            padding: 5px 10px;
            margin-bottom: 1.25em;

            font-size: 16px;
            font-weight: 600;
            color: #FFF;

            background: #3C4049;
            background:-moz-linear-gradient(top, #4A515B 0%, #3C4049 100%); /* FF3.6+ */
            background:-webkit-gradient(linear, left top, left bottom, color-stop(0%,#4A515B), color-stop(100%,#3C4049)); /* Chrome,Safari4+ */
            background:-webkit-linear-gradient(top, #4A515B 0%,#3C4049 100%); /* Chrome10+,Safari5.1+ */
            background:-o-linear-gradient(top, #4A515B 0%,#3C4049 100%); /* Opera11.10+ */
            background:-ms-linear-gradient(top, #4A515B 0%,#3C4049 100%); /* IE10+ */
            background:linear-gradient(top, #4A515B 0%,#3C4049 100%); /* W3C */
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#4A515B', endColorstr='#3C4049');
            -ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr='#4A515B', endColorstr='#3C4049')";

            border-radius: 4px;

            text-shadow: 1px 1px 2px rgba(0,0,0,.5);
        }

        .page-title i {
            margin-right: .4em;
        }
        .table-responsive {
            overflow-y: hidden;
        }
        span.small-num {
            display: block;
            font-style: normal;
            font-size: 12px;
            color: #4A515B;
        }
    </style>
    <div class="row">
        <div class="col-md-10">
            {{--<h3><i class="fa fa-key"></i>  平台报表</h3>--}}
            {{--<hr>--}}
            <div class="table-responsive">
                <h1 class="page-title">
                    <i class="icon-home"></i>
                    数据分析
                </h1>

                <div class="stat-container">

                    <div class="stat-holder">
                        <div class="stat">
                            <span class="big-num">{{$sum['special']['sum']}}</span>
                            授信产品订单总收益
                            <br>
                            <br>
                            <br>
                            <br>
                            <span class="small-num">支出产品成本：{{ $sum['special']['product_cost'] }}</span>
                            <span class="small-num">运营成本：{{$sum['special']['operating_cost']}}</span>
                            <span class="small-num">用户分红：{{$sum['special']['user_get']}}</span>
                            <span class="small-num">平台沉淀：{{$sum['special']['system_get']}}</span>
                        </div> <!-- /stat -->
                    </div> <!-- /stat-holder -->

                    <div class="stat-holder">
                        <div class="stat">
                            <span class="big-num">{{$sum['over']['sum']}}</span>
                            普通产品订单（已完成）收益
                            <br>
                            <br>
                            <br>
                            <br>
                            <span class="small-num">支出产品成本：{{ $sum['over']['product_cost'] }}</span>
                            <span class="small-num">运营成本：{{$sum['over']['operating_cost']}}</span>
                            <span class="small-num">用户分红：{{$sum['over']['user_get']}}</span>
                            <span class="small-num">平台沉淀：{{$sum['over']['system_get']}}</span>
                        </div> <!-- /stat -->
                    </div> <!-- /stat-holder -->

                    <div class="stat-holder">
                        <div class="stat">
                            <span class="big-num">{{$sum['all']['sum']}}</span>
                            平台总营收
                            <br>
                            <br>
                            <br>
                            <br>
                            <span class="small-num">支出产品成本：{{ $sum['all']['product_cost'] }}</span>
                            <span class="small-num">运营成本：{{$sum['all']['operating_cost']}}</span>
                            <span class="small-num">用户分红：{{$sum['all']['user_get']}}</span>
                            <span class="small-num">平台沉淀：{{$sum['all']['system_get']}}</span>
                        </div> <!-- /stat -->
                    </div> <!-- /stat-holder -->

                    <div class="stat-holder">
                        <div class="stat">
                            <span class="big-num">{{$sum['payed']}}</span>
                            未完成订单金额总和
                        </div> <!-- /stat -->
                    </div> <!-- /stat-holder -->

                </div>

            </div>
        </div>
    </div>
@endsection
