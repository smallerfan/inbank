@extends('base.base')

@section('title', '| 添加')

@section('base')
    <style>
        li.coin {
            float: left;
            margin-right: 30px;
        }
        ul {
            margin-bottom: 50px;
        }
    </style>
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="page-header">
                <h3 class="page-title">
                    <span class="page-title-icon bg-gradient-primary text-white mr-2">
                        <i class="mdi mdi-wrench"></i>
                    </span>
                    理财
                </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{url('/gem/coin')}}">币增宝计划列表</a></li>
                        <li class="breadcrumb-item active" aria-current="page">添加币增宝</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">添加币增宝</h4>
                            <br>
                            {{ Form::open(array('url' => route('coin.add'))) }}
                            {{ csrf_field() }}
                            <div class="form-group">
                                {{ Form::label('name', '标题') }}
                                {{ Form::text('name', '', array('class' => 'form-control')) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('lock_period', '投资期限（天）') }}
                                {{ Form::text('lock_period', '', array('class' => 'form-control')) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('plan_rate', '年利率(例：0.01 = 1%)') }}
                                {{ Form::text('plan_rate', '0.01', array('class' => 'form-control')) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('plan_label', '收益标签') }}
                                {{ Form::text('plan_label', '', array('class' => 'form-control')) }}
                            </div>

                            <div class="form-group">
                                <label>支持币种</label>
                                <br>
                                @foreach($coin as $c)
                                    <div class="form-check form-check-info">
                                        <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input" name="support_coins[{{$c->short_name}}][]" >
                                            {{$c->name}}({{$c->short_name}})
                                            <i class="input-helper"></i></label>
                                    </div>
                                    {{--<input type="checkbox" />--}}
                                    <ul>
                                        <li class="coin">
                                            <span>投资最低限额：</span>
                                            <input type="text" onkeyup="clearNoNum(this)" name="min[{{$c->id}}][]" value="@if(isset($c->finance_min_num)){{$c->finance_min_num}}@endif" class="form-control">
                                        </li>
                                        <li class="coin">
                                            <span>投资最高限额：</span>
                                            <input type="text" onkeyup="clearNoNum(this)" name="max[{{$c->id}}][]" value="@if(isset($c->finance_max_num)){{$c->finance_max_num}}@endif" class="form-control">
                                        </li>
                                    </ul>
                                    <br>
                                    <br>
                                @endforeach
                            </div>

                            <div class="form-inline">
                                <label>是否热门</label>&emsp;
                                <div class="form-check form-check-danger">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="is_hot" value=0 id="ExampleRadio4" checked="">
                                        否&emsp;
                                        <i class="input-helper"></i></label>
                                </div>
                                <div class="form-check form-check-danger">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" value=1 name="is_hot" id="ExampleRadio4" >
                                        是
                                        <i class="input-helper"></i></label>
                                </div>
                            </div>

                            <div class="form-inline">
                                <label>平台认证</label>&emsp;
                                <div class="form-check form-check-danger">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="platform_auth" value=0 id="ExampleRadio4" checked="">
                                        否&emsp;
                                        <i class="input-helper"></i></label>
                                </div>
                                <div class="form-check form-check-danger">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" value=1 name="platform_auth" id="ExampleRadio4" >
                                        是
                                        <i class="input-helper"></i></label>
                                </div>
                            </div>

                            <div class="form-inline">
                                <label>状态</label>&emsp;
                                <div class="form-check form-check-danger">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="status" value=0 id="ExampleRadio4" checked="">
                                        禁用&emsp;
                                        <i class="input-helper"></i></label>
                                </div>
                                <div class="form-check form-check-danger">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" value=1 name="status" id="ExampleRadio4" >
                                        正常
                                        <i class="input-helper"></i></label>
                                </div>
                            </div>
                            <br>
                            {{ Form::submit('保存', array('class' => 'btn btn-primary')) }}
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <script>
        function clearNoNum(obj){
            obj.value = obj.value.replace(/[^\d.]/g,"");  //清除“数字”和“.”以外的字符
            obj.value = obj.value.replace(/\.{2,}/g,"."); //只保留第一个. 清除多余的
            obj.value = obj.value.replace(".","$#$").replace(/\./g,"").replace("$#$",".");
            obj.value = obj.value.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3');//只能输入两个小数
            if(obj.value.indexOf(".")< 0 && obj.value !=""){//以上已经过滤，此处控制的是如果没有小数点，首位不能为类似于 01、02的金额
                obj.value= parseFloat(obj.value);
            }
        }
    </script>
@endsection