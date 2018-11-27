{{-- \resources\views\permissions\index.blade.php --}}
@extends('base.base')

@section('title', '| Permissions')

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
    div .block{
        margin-top: 20px;
        margin-bottom: 20px;
        width: 100%;
        height: 30px;
    }
</style>
    <div class="col-lg-12">
        <h3><i class="fa fa-key"></i>  资金统计</h3>
        <hr>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th class="statics">平台DK合计</th>
                    <th class="statics">平台DN合计</th>
                    <th class="statics">锁定用户DK合计</th>
                    <th class="statics">锁定用户DN合计</th>
                    <th class="statics">昨日DN释放合计</th>
                    <th class="statics">昨日释放率</th>
                </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $data['sum_dk'] }}</td>
                        <td>{{ $data['sum_dk'] }}</td>
                        <td>{{ $data['lock_sum_dk'] }}</td>
                        <td>{{ $data['lock_sum_dn'] }}</td>
                        <td>{{ $data['yestoday_sum_dn_releases'] }}</td>
                        <td>@if($data['yestoday_dn_releases_rate'] == 0)
                                0
                            @else
                                {{ $data['yestoday_dn_releases_rate'] }}%
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="block"><hr></div>
        <div style="width: 49%;height:400px;float: left;">
            <div class="builder-table">
                <div class="panel panel-default table-responsive" id="sectorchart" style="display: none;">
                    {{ $increase }}
                </div>
                <div id="shanxing" style="width: 90%;height:360px;"></div>
            </div>

        </div>
        <div style="width: 49%;height:400px;float: right;">
            <div class="builder-table">
                <div class="panel panel-default table-responsive" id="sectorchart2" style="display: none;">
                    {{ $release }}
                </div>
                <div id="shanxing2" style="width: 90%;height:360px;"></div>
            </div>
        </div>
        <div style="width: 49%;height:400px;float: left;">
            <div class="builder-table">
                <div class="panel panel-default table-responsive" id="sectorchart3" style="display: none;">
                    {{ $increase }}
                </div>
                <div id="shanxing3" style="width: 90%;height:360px;"></div>
            </div>

        </div>
        <div style="width: 49%;height:400px;float: right;">
            <div class="builder-table">
                <div class="panel panel-default table-responsive" id="sectorchart4" style="display: none;">
                    {{ $increase }}
                </div>
                <div id="shanxing4" style="width: 90%;height:360px;"></div>
            </div>

        </div>
    </div>
<script type="text/javascript" src="{{ asset('js/echarts.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>

<script>
    var sectors=document.getElementById('sectorchart').innerText;
    var sector = jQuery.parseJSON(sectors);
    var myChart = echarts.init(document.getElementById('shanxing'));
    console.log(sector[0]['created_at'].split(' ')[0]);
    var dates = [];
    var obj = [sector[0]['created_at'],sector[1]['created_at'],sector[2]['created_at'],sector[3]['created_at'],
        sector[4]['created_at'],sector[5]['created_at'],sector[6]['created_at']];
    $.each(obj, function() {
        // dates.push(this.substr(0,10));
        dates.push(this.substr(5,6));
    });
    option = {
        title : {
            text: '近7天DN新增趋势',
            subtext: ''
        },
        tooltip : {
            trigger: 'axis'
        },
        legend: {
            data:['DN最高值','DN最低值']
        },
        toolbox: {
            show : true,
            feature : {
                mark : {show: true},
                dataView : {show: true, readOnly: false},
                magicType : {show: true, type: ['line', 'bar']},
                restore : {show: true},
                saveAsImage : {show: true}
            }
        },
        calculable : true,
        xAxis : [
            {
                type : 'category',
                boundaryGap : false,
                data : dates,
            }
        ],
        yAxis : [
            {
                type : 'value',
                axisLabel : {
                    formatter: '{value}'
                }
            }
        ],
        series : [
            {
                name:'新增DN量',
                type:'line',
                data:[
                    sector[0]['num'],sector[1]['num'],sector[2]['num'],sector[3]['num'],sector[4]['num'],
                    sector[5]['num'],sector[6]['num']
                ],
                markPoint : {
                    data : [
                        {type : 'max', name: '最高'},
                        {type : 'min', name: '最低'}
                    ]
                }
            }
        ]
    };

    if (option && typeof option === "object") {
        myChart.setOption(option, true);
    }

</script>
<script>
    var sectors=document.getElementById('sectorchart2').innerText;
    var sector = jQuery.parseJSON(sectors);
    var myChart = echarts.init(document.getElementById('shanxing2'));
    console.log(sector[0]['created_at'].split(' ')[0]);
    var dates = [];
    var obj = [sector[0]['created_at'],sector[1]['created_at'],sector[2]['created_at'],sector[3]['created_at'],
        sector[4]['created_at'],sector[5]['created_at'],sector[6]['created_at']];
    $.each(obj, function() {
        // dates.push(this.substr(0,10));
        dates.push(this.substr(5,6));
    });
    option = {
        title : {
            text: '近7天DN释放趋势',
            subtext: ''
        },
        tooltip : {
            trigger: 'axis'
        },
        legend: {
            data:['DN释放最高值','DN释放最低值']
        },
        toolbox: {
            show : true,
            feature : {
                mark : {show: true},
                dataView : {show: true, readOnly: false},
                magicType : {show: true, type: ['line', 'bar']},
                restore : {show: true},
                saveAsImage : {show: true}
            }
        },
        calculable : true,
        xAxis : [
            {
                type : 'category',
                boundaryGap : false,
                data : dates,
            }
        ],
        yAxis : [
            {
                type : 'value',
                axisLabel : {
                    formatter: '{value}'
                }
            }
        ],
        series : [
            {
                name:'DN释放量',
                type:'line',
                data:[
                    sector[0]['num'],sector[1]['num'],sector[2]['num'],sector[3]['num'],sector[4]['num'],
                    sector[5]['num'],sector[6]['num']
                ],
                markPoint : {
                    data : [
                        {type : 'max', name: '最高'},
                        {type : 'min', name: '最低'}
                    ]
                }
            }
        ]
    };

    if (option && typeof option === "object") {
        myChart.setOption(option, true);
    }

</script>
<script>
    var sectors=document.getElementById('sectorchart3').innerText;
    var sector = jQuery.parseJSON(sectors);
    var myChart = echarts.init(document.getElementById('shanxing3'));
    console.log(sector[0]['created_at'].split(' ')[0]);
    var dates = [];
    var obj = [sector[0]['created_at'],sector[1]['created_at'],sector[2]['created_at'],sector[3]['created_at'],
        sector[4]['created_at'],sector[5]['created_at'],sector[6]['created_at']];
    $.each(obj, function() {
        // dates.push(this.substr(0,10));
        dates.push(this.substr(5,6));
    });
    option = {
        title : {
            text: '近7天DK兑换趋势',
            subtext: ''
        },
        tooltip : {
            trigger: 'axis'
        },
        legend: {
            data:['DK兑换最高值','DK兑换最低值']
        },
        toolbox: {
            show : true,
            feature : {
                mark : {show: true},
                dataView : {show: true, readOnly: false},
                magicType : {show: true, type: ['line', 'bar']},
                restore : {show: true},
                saveAsImage : {show: true}
            }
        },
        calculable : true,
        xAxis : [
            {
                type : 'category',
                boundaryGap : false,
                data : dates,
            }
        ],
        yAxis : [
            {
                type : 'value',
                axisLabel : {
                    formatter: '{value}'
                }
            }
        ],
        series : [
            {
                name:'DK兑换量',
                type:'line',
                data:[
                    sector[0]['num'],sector[1]['num'],sector[2]['num'],sector[3]['num'],sector[4]['num'],
                    sector[5]['num'],sector[6]['num']
                ],
                markPoint : {
                    data : [
                        {type : 'max', name: '最高'},
                        {type : 'min', name: '最低'}
                    ]
                }
            }
        ]
    };

    if (option && typeof option === "object") {
        myChart.setOption(option, true);
    }

</script>
<script>
    var sectors=document.getElementById('sectorchart4').innerText;
    var sector = jQuery.parseJSON(sectors);
    var myChart = echarts.init(document.getElementById('shanxing4'));
    console.log(sector[0]['created_at'].split(' ')[0]);
    var dates = [];
    var obj = [sector[0]['created_at'],sector[1]['created_at'],sector[2]['created_at'],sector[3]['created_at'],
        sector[4]['created_at'],sector[5]['created_at'],sector[6]['created_at']];
    $.each(obj, function() {
        // dates.push(this.substr(0,10));
        dates.push(this.substr(5,6));
    });
    option = {
        title : {
            text: '近7天DK转账趋势',
            subtext: ''
        },
        tooltip : {
            trigger: 'axis'
        },
        legend: {
            data:['DK转账最高值','DK转账最低值']
        },
        toolbox: {
            show : true,
            feature : {
                mark : {show: true},
                dataView : {show: true, readOnly: false},
                magicType : {show: true, type: ['line', 'bar']},
                restore : {show: true},
                saveAsImage : {show: true}
            }
        },
        calculable : true,
        xAxis : [
            {
                type : 'category',
                boundaryGap : false,
                data : dates,
            }
        ],
        yAxis : [
            {
                type : 'value',
                axisLabel : {
                    formatter: '{value}'
                }
            }
        ],
        series : [
            {
                name:'DK转账量',
                type:'line',
                data:[
                    sector[0]['num'],sector[1]['num'],sector[2]['num'],sector[3]['num'],sector[4]['num'],
                    sector[5]['num'],sector[6]['num']
                ],
                markPoint : {
                    data : [
                        {type : 'max', name: '最高'},
                        {type : 'min', name: '最低'}
                    ]
                }
            }
        ]
    };

    if (option && typeof option === "object") {
        myChart.setOption(option, true);
    }

</script>
@endsection
