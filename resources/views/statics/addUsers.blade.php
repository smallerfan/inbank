{{-- \resources\views\permissions\index.blade.php --}}
@extends('base.base')

@section('title', '| Permissions')

@section('base')
    <style>
        td,th{
            font-size: 16px;
            text-align: center
        }
    </style>
    <div class="col-lg-10">
        {{--<h3><i class="fa fa-key"></i>  新增用户统计（15天）</h3>--}}
        {{--<hr>--}}
        <div class="builder-table">
            <div class="panel panel-default table-responsive" id="sectorchart" style="display: none;">
            {{ $datas }}
            </div>
            <div id="shanxing" style="width: 100%;height:400px;"></div>
        </div>
        <script type="text/javascript" src="{{ asset('js/echarts.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>

        <script>
            var sectors=document.getElementById('sectorchart').innerText;
            var sector = jQuery.parseJSON(sectors);
            var myChart = echarts.init(document.getElementById('shanxing'));
            option = {
                title : {
                    text: '新增用户统计（15天）',
                    subtext: ''
                },
                tooltip : {
                    trigger: 'axis'
                },
                legend: {
                    data:['人数最多','人数最少']
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
                        data : [
                            sector[0]['date'],sector[1]['date'],sector[2]['date'],sector[3]['date'],sector[4]['date'],
                            sector[5]['date'],sector[6]['date'],sector[7]['date'],sector[8]['date'],sector[9]['date'],
                            sector[10]['date'],sector[11]['date'],sector[12]['date'],sector[13]['date'],
                            sector[14]['date'],
                            // sector[15]['date'],sector[16]['date'],sector[17]['date'],
                            // sector[18]['date'],sector[19]['date'],sector[20]['date'],sector[21]['date'],
                            // sector[22]['date'],sector[23]['date'],sector[24]['date'],sector[25]['date'],
                            // sector[26]['date'],sector[27]['date'],sector[28]['date'],sector[29]['date']
                        ]
                    }
                ],
                yAxis : [
                    {
                        type : 'value',
                        axisLabel : {
                            formatter: '{value} 人'
                        }
                    }
                ],
                series : [
                    {
                        name:'新增人数',
                        type:'line',
                        data:[
                            sector[0]['count'],sector[1]['count'],sector[2]['count'],sector[3]['count'],sector[4]['count'],
                            sector[5]['count'],sector[6]['count'],sector[7]['count'],sector[8]['count'],sector[9]['count'],
                            sector[10]['count'],sector[11]['count'],sector[12]['count'],sector[13]['count'],
                            sector[14]['count'],
                            // sector[15]['count'],sector[16]['count'],sector[17]['count'],
                            // sector[18]['count'],sector[19]['count'],sector[20]['count'],sector[21]['count'],
                            // sector[22]['count'],sector[23]['count'],sector[24]['count'],sector[25]['count'],
                            // sector[26]['count'],sector[27]['count'],sector[28]['count'],sector[29]['count']
                        ],
                        markPoint : {
                            data : [
                                {type : 'max', name: '月增最高'},
                                {type : 'min', name: '月增最低'}
                            ]
                        }
                    }
                ]
            };

            // myChart.setOption(option);
            if (option && typeof option === "object") {
                myChart.setOption(option, true);
            }

        </script>


    </div>

@endsection
