{{-- \resources\views\permissions\index.blade.php --}}
@extends('base.base')

@section('title', '| Permissions')

@section('base')
    <style>
        .form-select{
            width: 40%;
            height: 40px;
        }
        *{box-sizing:border-box;}
        #nav{height:50px;}
        #nav a{display:block;height:50px;line-height:50px;padding:0 30px;font-size:18px;text-align:center;font-family: 'Microsoft YaHei';float:left;background- color:#e1e1e1;cursor:pointer;}
        #nav a.on{border-bottom:2px solid #e60012;}
        #contentBox{width:100%;}
        #contentBox .box{text-align:center;line-height:100px;font-weight:bold;display:none;}
        #contentBox .box.active{display:block;}

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
                        <li class="breadcrumb-item"><a href="{{url('/system/system')}}">系统参数设置</a></li>
                        <li class="breadcrumb-item active" aria-current="page">参数列表</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">参数列表</h4>
                            <p class="card-description">
                            </p>
                            <section>
                                   <nav id="nav">
                                    @foreach($datas as $key => $value)
                                        @if($key=='c2c')
                                                 <a class="on">{{$key}}</a>
                                        @else
                                                 <a>{{$key}}</a>
                                        @endif
                                        {{--     <a>{{$key}}</a>--}}
                                        {{--     <a>tab4</a>--}}
                                    @endforeach
                                       </nav>
                                   <div id="contentBox">
                                    @foreach($datas as $key => $value)
                                        @if($key == 'c2c')
                                                 <div class="box active">
                                                @else
                                                    <div class="box">
                                                        @endif
                                                        {{ Form::open(array('url' => route('system_update'))) }}
                                                        {{ csrf_field() }}
                                                        <table class="table table-bordered table-striped">
                                                            @foreach($value as $k=>$data)
                                                                <thead>
                                                                <th width="40%">{{ $data->title }}: </th>
                                                                <th>
                                                                    @if(($data->config_value == 'open' || $data->config_value == 'close') && $data->show_type == 'radio')
                                                                        <input type="radio" value="open" @if($data->config_value == 'open') checked="checked" @endif name="{{ $data->config_key }}">&nbsp;启用&emsp;&emsp;
                                                                        <input type="radio" value="close" @if($data->config_value == 'close') checked="checked" @endif name="{{ $data->config_key }}">&nbsp;禁用
                                                                    @elseif(($data->config_value == '1' || $data->config_value == '0') && $data->show_type == 'radio')
                                                                        <input type="radio" value="open" @if($data->config_value == '1') checked="checked" @endif name="{{ $data->config_key }}">&nbsp;是&emsp;&emsp;
                                                                        <input type="radio" value="close" @if($data->config_value == '0') checked="checked" @endif name="{{ $data->config_key }}">&nbsp;否
                                                                    @elseif($data->show_type == 'checkbox')
                                                                        <input type="checkbox" value="interim" @if(in_array('interim',$data->config_value)) checked="checked" @endif name="{{ $data->config_key }}[]">&nbsp;普通&emsp;&emsp;
                                                                        <input type="checkbox" value="common" @if(in_array('common',$data->config_value)) checked="checked" @endif name="{{ $data->config_key }}[]">&nbsp;合格&emsp;&emsp;
                                                                        <input type="checkbox" value="vip" @if(in_array('vip',$data->config_value)) checked="checked" @endif name="{{ $data->config_key }}[]">&nbsp;VIP&emsp;&emsp;
                                                                        <input type="checkbox" value="svip" @if(in_array('svip',$data->config_value)) checked="checked" @endif name="{{ $data->config_key }}[]">&nbsp;SVIP
                                                                    @elseif($data->show_type == 'time')
                                                                        <select name="{{ $data->config_key }}" class="form-select">
                                                                            @for($i=1;$i<24;$i++)
                                                                                <option class="form-option" value="{{ $i }}:00" @if($data->config_value == $i) selected="selected" @endif>{{ $i }}:00</option>
                                                                            @endfor
                                                                        </select>
                                                                    @else
                                                                        <input class="form-control" type="text" name="{{ $data->config_key }}" value="{{ $data->config_value }}" onkeyup="value=value.replace(/[^\d{1,}\.\d{1,}|\d{1,}]/g,'')">
                                                                    @endif
                                                                </th>
                                                                </thead>
                                                            @endforeach
                                                        </table>
                                                        {{ Form::submit('保存', array('class' => 'btn btn-primary')) }}

                                                        {{ Form::close() }}
                                                        {{--@endif--}}
                                                    </div>
                                                    @endforeach

                                                       </div>
                                </div>
                            </section>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="{{ url('js/jquery-3.2.1.min.js') }}"></script>
    <script>
        $(function(){
            $("#nav a").off("click").on("click",function(){
                var index = $(this).index();
                $(this).addClass("on").siblings().removeClass("on");
                $("#contentBox .box").eq(index).addClass("active").siblings().removeClass("active");
            });
        });
    </script>

@endsection
