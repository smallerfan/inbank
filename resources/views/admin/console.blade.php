@extends('base.base')
@section('base')
    <style>
        a{
            color: black;
        }
        #common, #special {
            color: #222d32;
            list-style: none;
            display: inline-block;
            border: 1px solid #1a2226;
            height: 30px;
            border-radius: 10px;
            line-height: 30px;
            width: 120px;
            text-align: center;
        }
        .active{
            color: #222d32;
            background-color: #808080;
        }
    </style>
    <!-- 内容区域 -->
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="page-header">
                <h3 class="page-title">
              <span class="page-title-icon bg-gradient-primary text-white mr-2">
                <i class="mdi mdi-home"></i>
              </span>
                    数据分析
                </h3>
                <nav aria-label="breadcrumb">
                <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                <span></span>Overview
                <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
                </li>
                </ul>
                </nav>
            </div>
            {{--数据统计--}}
            <div class="row">
                <div class="col-md-4 stretch-card grid-margin">
                    <div class="card bg-gradient-danger card-img-holder text-white">
                        <div class="card-body">
                            <img src="/assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image"/>
                            <h4 class="font-weight-normal mb-3">Weekly Sales
                                <i class="mdi mdi-chart-line mdi-24px float-right"></i>
                            </h4>
                            <h2 class="mb-5">$ 15,0000</h2>
                            <h6 class="card-text">Increased by 60%</h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 stretch-card grid-margin">
                    <div class="card bg-gradient-info card-img-holder text-white">
                        <div class="card-body">
                            <img src="/assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image"/>
                            <h4 class="font-weight-normal mb-3">Weekly Orders
                                <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i>
                            </h4>
                            <h2 class="mb-5">45,6334</h2>
                            <h6 class="card-text">Decreased by 10%</h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 stretch-card grid-margin">
                    <div class="card bg-gradient-success card-img-holder text-white">
                        <div class="card-body">
                            <img src="/assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image"/>
                            <h4 class="font-weight-normal mb-3">Visitors Online
                                <i class="mdi mdi-diamond mdi-24px float-right"></i>
                            </h4>
                            <h2 class="mb-5">95,5741</h2>
                            <h6 class="card-text">Increased by 5%</h6>
                        </div>
                    </div>
                </div>
            </div>
            {{--订单统计--}}
            <div class="row">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">商品销售统计</h4>
                            <div class="d-flex">
                                <div class="d-flex align-items-center text-muted font-weight-light">
                                    <i class="mdi mdi-clock icon-sm mr-2"></i>
                                    <span>{{date('Y-m-d H:i:s')}}</span>
                                </div>
                            </div>
                          <br>
                            <div class="row mt-3">
                                <div style="    text-align: center;padding: 74px;"><label class="badge badge-gradient-warning" style="font-size: 18px;padding: 16px;border-radius: 34px;    letter-spacing: 7px;"><i class="mdi mdi-heart-outline"></i>&nbsp&nbsp普通商品</label></div>
                                <hr>
                            @foreach($datas as $k=>$data)
                                           @if($k<2)
                                                <div class="col-2 pr-1">
                                                    <div class="head{{ $k }}">
                                                        <label class="badge badge-gradient-danger">{{ $data['tip'] }}成交</label>
                                                        {{--<label class="label_sale">人均消费:&emsp;¥{{ sprintf("%.2f",$data['average']) }}</label>--}}
                                                    </div>
                                                    <div class="form-inline" >
                                                        <div class="form-control" style="width: 200px;">
                                                            <label class="label_trade">成交量/交易量</label><br/>
                                                            <label class="label_value">{{ $data['num'] }}/{{ $data['count'] }}</label>
                                                        </div>
                                                        <div class="form-control" style="width: 200px;">
                                                            <label class="label_trade">成交额/交易额</label><br/>
                                                            <label class="label_value">{{ sprintf("%.2f",$data['sum']) }}/{{ sprintf("%.2f",$data['amount']) }}</label>
                                                        </div>
                                                    </div>
                                                </div>
                                    @endif
                                @if($k>=2)
                                        <div class="col-2 pr-1">
                                            <div class="head{{ $k }}">
                                                <label class="badge badge-gradient-danger">{{ $data['tip'] }}成交</label>
                                                {{--<label class="label_sale">人均消费:&emsp;¥{{ sprintf("%.2f",$data['average']) }}</label>--}}
                                            </div>
                                            <div class="form-inline">
                                                <div class="form-control" style=" width: 200px;">
                                                    <label class="label_trade">成交量/交易量</label><br/>
                                                    <label class="label_value">{{ $data['num'] }}/{{ $data['count'] }}</label>
                                                </div>
                                                <div class="form-control" style="width: 200px;">
                                                    <label class="label_trade">成交额/交易额</label><br/>
                                                    <label class="label_value">{{ sprintf("%.2f",$data['sum']) }}/{{ sprintf("%.2f",$data['amount']) }}</label>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            <div class="row mt-3">

                                <hr>
                                @foreach($data2 as $k=>$data)
                                    @if($k<2)
                                        <div class="col-2 pr-1">
                                            <div class="head{{ $k }}">
                                                <label class="badge badge-gradient-danger">{{ $data['tip'] }}成交</label>
                                                {{--<label class="label_sale">人均消费:&emsp;¥{{ sprintf("%.2f",$data['average']) }}</label>--}}
                                            </div>
                                            <div class="form-inline" >
                                                <div class="form-control" style="width: 200px;">
                                                    <label class="label_trade">成交量/交易量</label><br/>
                                                    <label class="label_value">{{ $data['num'] }}/{{ $data['count'] }}</label>
                                                </div>
                                                <div class="form-control" style="width: 200px;">
                                                    <label class="label_trade">成交额/交易额</label><br/>
                                                    <label class="label_value">{{ sprintf("%.2f",$data['sum']) }}/{{ sprintf("%.2f",$data['amount']) }}</label>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if($k>=2)
                                        <div class="col-2 pr-1">
                                            <div class="head{{ $k }}">
                                                <label class="badge badge-gradient-danger">{{ $data['tip'] }}成交</label>
                                                {{--<label class="label_sale">人均消费:&emsp;¥{{ sprintf("%.2f",$data['average']) }}</label>--}}
                                            </div>
                                            <div class="form-inline">
                                                <div class="form-control" style=" width: 200px;">
                                                    <label class="label_trade">成交量/交易量</label><br/>
                                                    <label class="label_value">{{ $data['num'] }}/{{ $data['count'] }}</label>
                                                </div>
                                                <div class="form-control" style="width: 200px;">
                                                    <label class="label_trade">成交额/交易额</label><br/>
                                                    <label class="label_value">{{ sprintf("%.2f",$data['sum']) }}/{{ sprintf("%.2f",$data['amount']) }}</label>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                                <hr>
                                <div style="    text-align: center;padding: 74px;"><label class="badge badge-gradient-info" style="font-size: 18px;padding: 16px;border-radius: 34px;    letter-spacing: 7px;"><i class="mdi mdi-heart-outline"></i>&nbsp&nbsp授信商品</label></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-7 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="panel panel-default table-responsive" id="chart1" style="display: none;">
                                {{ $line }}
                            </div>
                            <div class="panel panel-default table-responsive" id="chart2" style="display: none;">
                                {{ $types }}
                            </div>
                            <div id="zhexian" style="width: 100%;height:400px;"></div>
                            <div class="btn-group" role="group" aria-label="Basic example" style="float: right;">
                                <button type="button" class="btn btn-outline-secondary" onclick="chart('{{$line}}','{{ $types }}')">普通商品</button>
                                <button type="button" class="btn btn-outline-secondary" onclick="chart('{{$line1}}','{{$type1}}')">授信商品</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-5 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            {{--<h4 class="card-title">Traffic Sources</h4>--}}
                            <div class="panel panel-default table-responsive" id="chart3" style="display: none;">
                                {{ $data3 }}
                            </div>
                            <div class="panel panel-default table-responsive" id="chart4" style="display: none;">
                                {{ $levels }}
                            </div>
                            <div id="shanxing" style="width: 100%;height:400px;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 grid-margin">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Recent Tickets</h4>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>
                                            Assignee
                                        </th>
                                        <th>
                                            Subject
                                        </th>
                                        <th>
                                            Status
                                        </th>
                                        <th>
                                            Last Update
                                        </th>
                                        <th>
                                            Tracking ID
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>
                                            <img src="/assets/images/faces/face1.jpg" class="mr-2" alt="image">
                                            David Grey
                                        </td>
                                        <td>
                                            Fund is not recieved
                                        </td>
                                        <td>
                                            <label class="badge badge-gradient-success">DONE</label>
                                        </td>
                                        <td>
                                            Dec 5, 2017
                                        </td>
                                        <td>
                                            WD-12345
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <img src="/assets/images/faces/face2.jpg" class="mr-2" alt="image">
                                            Stella Johnson
                                        </td>
                                        <td>
                                            High loading time
                                        </td>
                                        <td>
                                            <label class="badge badge-gradient-warning">PROGRESS</label>
                                        </td>
                                        <td>
                                            Dec 12, 2017
                                        </td>
                                        <td>
                                            WD-12346
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <img src="/assets/images/faces/face3.jpg" class="mr-2" alt="image">
                                            Marina Michel
                                        </td>
                                        <td>
                                            Website down for one week
                                        </td>
                                        <td>
                                            <label class="badge badge-gradient-info">ON HOLD</label>
                                        </td>
                                        <td>
                                            Dec 16, 2017
                                        </td>
                                        <td>
                                            WD-12347
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <img src="/assets/images/faces/face4.jpg" class="mr-2" alt="image">
                                            John Doe
                                        </td>
                                        <td>
                                            Loosing control on server
                                        </td>
                                        <td>
                                            <label class="badge badge-gradient-danger">REJECTED</label>
                                        </td>
                                        <td>
                                            Dec 3, 2017
                                        </td>
                                        <td>
                                            WD-12348
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Project Status</h4>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>
                                            #
                                        </th>
                                        <th>
                                            Name
                                        </th>
                                        <th>
                                            Due Date
                                        </th>
                                        <th>
                                            Progress
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>
                                            1
                                        </td>
                                        <td>
                                            Herman Beck
                                        </td>
                                        <td>
                                            May 15, 2015
                                        </td>
                                        <td>
                                            <div class="progress">
                                                <div class="progress-bar bg-gradient-success" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            2
                                        </td>
                                        <td>
                                            Messsy Adam
                                        </td>
                                        <td>
                                            Jul 01, 2015
                                        </td>
                                        <td>
                                            <div class="progress">
                                                <div class="progress-bar bg-gradient-danger" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            3
                                        </td>
                                        <td>
                                            John Richards
                                        </td>
                                        <td>
                                            Apr 12, 2015
                                        </td>
                                        <td>
                                            <div class="progress">
                                                <div class="progress-bar bg-gradient-warning" role="progressbar" style="width: 90%" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            4
                                        </td>
                                        <td>
                                            Peter Meggik
                                        </td>
                                        <td>
                                            May 15, 2015
                                        </td>
                                        <td>
                                            <div class="progress">
                                                <div class="progress-bar bg-gradient-primary" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            5
                                        </td>
                                        <td>
                                            Edward
                                        </td>
                                        <td>
                                            May 03, 2015
                                        </td>
                                        <td>
                                            <div class="progress">
                                                <div class="progress-bar bg-gradient-danger" role="progressbar" style="width: 35%" aria-valuenow="35" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            5
                                        </td>
                                        <td>
                                            Ronald
                                        </td>
                                        <td>
                                            Jun 05, 2015
                                        </td>
                                        <td>
                                            <div class="progress">
                                                <div class="progress-bar bg-gradient-info" role="progressbar" style="width: 65%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- partial:partials/_footer.html -->
        <footer class="footer">
            <div class="d-sm-flex justify-content-center justify-content-sm-between">
                <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2017-2018 <a href="http://www.yamecent.com/" target="_blank">西安趣链有限公司</a>. All rights reserved. </span>
            </div>
        </footer>

        <!-- partial -->
    </div>
    <script type="text/javascript" src="{{ asset('js/echarts.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>
    <script>
        {{--折线图--}}
        var datas=document.getElementById('chart1').innerText;
        var data = jQuery.parseJSON(datas);
        var types=document.getElementById('chart2').innerText;
        var type = jQuery.parseJSON(types);
        var myChart = echarts.init(document.getElementById('zhexian'));
        option = {
            title : {
                text: '近七日交易走势图',
                subtext: ''
            },
            tooltip : {
                trigger: 'axis'
            },
            legend: {
                data:['交易量','成交量','交易额','成交额']
            },
            toolbox: {
                show : true,
                feature : {
                    dataView : {show: true, readOnly: false},
                    magicType : {show: true, type: ['bar', 'line']},
                    restore : {show: true},
                    saveAsImage : {show: true}
                }
            },
            calculable : true,
            xAxis : [
                {
                    type : 'category',
                    data : type
                }
            ],
            yAxis : [
                {
                    type : 'value'
                }
            ],
            series : [
                {
                    name:'交易量',
                    type:'line',
                    data:data['num'],
                    markPoint : {
                        data : [
                            {type : 'max', name: '最大值'},
                            {type : 'min', name: '最小值'}
                        ]
                    },
                    markLine : {
                        data : [
                            // {type : 'average', name: '平均值'}
                        ]
                    }
                },
                {
                    name:'成交量',
                    type:'line',
                    data:data['count'],
                    markPoint : {
                        data : [
                            {type : 'max', name: '最大值'},
                            {type : 'min', name: '最小值'}
                        ]
                    },
                    markLine : {
                        data : [
                            // {type : 'average', name : '平均值'}
                        ]
                    }
                },
                {
                    name:'交易额',
                    type:'line',
                    data:data['sum'],
                    markPoint : {
                        data : [
                            {type : 'max', name: '最大值'},
                            {type : 'min', name: '最小值'}
                        ]
                    },
                    markLine : {
                        data : [
                            // {type : 'average', name : '平均值'}
                        ]
                    }
                },
                {
                    name:'成交额',
                    type:'line',
                    data:data['amount'],
                    markPoint : {
                        data : [
                            {type : 'max', name: '最大值'},
                            {type : 'min', name: '最小值'}
                        ]
                    },
                    markLine : {
                        data : [
                            {type : 'average', name : '平均值'}
                        ]
                    }
                }
            ]
        };
        // myChart.setOption(option);
        if (option && typeof option === "object") {
            myChart.setOption(option, true);
        }


       {{--扇形图--}}
        var datas1=document.getElementById('chart3').innerText;
        var data1 = jQuery.parseJSON(datas1);
        var levels1=document.getElementById('chart4').innerText;
        var level1 = jQuery.parseJSON(levels1);
        var myChart = echarts.init(document.getElementById('shanxing'));
        option1 = {
            title : {
                text: '平台所有客户信息统计',
                subtext: '',
                x:'center'
            },
            tooltip : {
                trigger: 'item',
                formatter: "{a} <br/>{b} : {c} ({d}%)"
            },
            legend: {
                orient : 'vertical',
                x : 'left',
                data:level1
            },
            toolbox: {
                show : true,
                feature : {
                    mark : {show: true},
                    dataView : {show: true, readOnly: false},
                    magicType : {
                        show: true,
                        type: ['pie', 'funnel'],
                        option: {
                            funnel: {
                                x: '25%',
                                width: '50%',
                                funnelAlign: 'left',
                                max: 1548
                            }
                        }
                    },
                    restore : {show: true},
                    saveAsImage : {show: true}
                }
            },
            calculable : true,
            series : [
                {
                    name:'来源InBank',
                    type:'pie',
                    radius : '55%',
                    center: ['50%', '60%'],
                    data:data1
                }
            ]
        };
        // myChart.setOption(option);
        if (option1 && typeof option1 === "object") {
            myChart.setOption(option1, true);
        }







        function chart(datas,types) {
            var data = jQuery.parseJSON(datas);
            var type = jQuery.parseJSON(types);
            option = {
                title : {
                    text: '近七日交易走势图',
                    subtext: ''
                },
                tooltip : {
                    trigger: 'axis'
                },
                legend: {
                    data:['交易量','成交量','交易额','成交额']
                },
                toolbox: {
                    show : true,
                    feature : {
                        dataView : {show: true, readOnly: false},
                        magicType : {show: true, type: ['bar', 'line']},
                        restore : {show: true},
                        saveAsImage : {show: true}
                    }
                },
                calculable : true,
                xAxis : [
                    {
                        type : 'category',
                        data : type
                    }
                ],
                yAxis : [
                    {
                        type : 'value'
                    }
                ],
                series : [
                    {
                        name:'交易量',
                        type:'line',
                        data:data['num'],
                        markPoint : {
                            data : [
                                {type : 'max', name: '最大值'},
                                {type : 'min', name: '最小值'}
                            ]
                        },
                        markLine : {
                            data : [
                                // {type : 'average', name: '平均值'}
                            ]
                        }
                    },
                    {
                        name:'成交量',
                        type:'line',
                        data:data['count'],
                        markPoint : {
                            data : [
                                {type : 'max', name: '最大值'},
                                {type : 'min', name: '最小值'}
                            ]
                        },
                        markLine : {
                            data : [
                                // {type : 'average', name : '平均值'}
                            ]
                        }
                    },
                    {
                        name:'交易额',
                        type:'line',
                        data:data['sum'],
                        markPoint : {
                            data : [
                                {type : 'max', name: '最大值'},
                                {type : 'min', name: '最小值'}
                            ]
                        },
                        markLine : {
                            data : [
                                // {type : 'average', name : '平均值'}
                            ]
                        }
                    },
                    {
                        name:'成交额',
                        type:'line',
                        data:data['amount'],
                        markPoint : {
                            data : [
                                {type : 'max', name: '最大值'},
                                {type : 'min', name: '最小值'}
                            ]
                        },
                        markLine : {
                            data : [
                                {type : 'average', name : '平均值'}
                            ]
                        }
                    }
                ]
            };
            // myChart.setOption(option);
            if (option && typeof option === "object") {
                myChart.setOption(option, true);
            }
        }


    </script>
@endsection