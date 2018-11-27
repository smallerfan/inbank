@extends('base.base')

@section('title', '| 编辑')

@section('base')

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
                        <li class="breadcrumb-item"><a href="{{url('/gem/financing')}}">理财宝计划列表</a></li>
                        <li class="breadcrumb-item active" aria-current="page">修改理财宝</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">编辑理财宝——{{ $data->name }}</h4>
                            <br>
                            {{ Form::open(array('url' => route('financing.edit_plan'))) }}
                            {{ csrf_field() }}
                            {{ Form::hidden('id', $data->id) }}
                            <div class="form-group">
                                {{ Form::label('name', '标题') }}
                                {{ Form::text('name', $data->name, array('class' => 'form-control')) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('lock_period', '投资期限') }}
                                {{ Form::text('lock_period', $data->lock_period, array('class' => 'form-control')) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('plan_rate', '年利率%') }}
                                {{ Form::text('plan_rate', $data->plan_rate, array('class' => 'form-control')) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('plan_label', '收益标签') }}
                                {{ Form::text('plan_label', $data->plan_label, array('class' => 'form-control')) }}
                            </div>
                            <div class="form-group">
                                <label>支持币种</label>
                                <br>
                                @foreach($coin as $c)
                                    <div class="form-check form-check-info">
                                        <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input" name="support_coins[{{$c->short_name}}][]"  @if(strpos($data->support_coins,$c->short_name) !== false) checked @endif >
                                            {{$c->name}}({{$c->short_name}})
                                            <i class="input-helper"></i></label>
                                    </div>
                                    {{--<input type="checkbox" name="support_coins[{{$c->short_name}}][]"/>--}}
                                    <ul>
                                        <li class="coin">
                                            <span>投资最低限额：</span>
                                            <input type="text" onkeyup="clearNoNum(this)" name="min[{{$c->id}}][]" value="@if(isset($c->finance_min_num)){{$c->finance_min_num}}@endif" class="form-control-sm">
                                        </li>
                                        <li class="coin">
                                            <span>投资最高限额：</span>
                                            <input type="text" onkeyup="clearNoNum(this)" name="max[{{$c->id}}][]" value="@if(isset($c->finance_max_num)){{$c->finance_max_num}}@endif" class="form-control-sm">
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
                                        <input type="radio" class="form-check-input" name="is_hot" value=0 id="ExampleRadio4" @if($data->is_hot == 0) checked="checked" @endif>
                                        否&emsp;
                                        <i class="input-helper"></i></label>
                                </div>
                                <div class="form-check form-check-danger">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" value=1 name="is_hot" id="ExampleRadio4" @if($data->is_hot == 1) checked="checked" @endif>
                                        是
                                        <i class="input-helper"></i></label>
                                </div>
                            </div>

                            <div class="form-inline">
                                <label>平台认证</label>&emsp;
                                <div class="form-check form-check-danger">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="platform_auth" value=0 id="ExampleRadio4" @if($data->platform_auth == 0) checked="checked" @endif>
                                        否&emsp;
                                        <i class="input-helper"></i></label>
                                </div>
                                <div class="form-check form-check-danger">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" value=1 name="platform_auth" id="ExampleRadio4"  @if($data->platform_auth == 1) checked="checked" @endif>
                                        是
                                        <i class="input-helper"></i></label>
                                </div>
                            </div>

                            <div class="form-inline">
                                <label>状态</label>&emsp;
                                <div class="form-check form-check-danger">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="status" value=0 id="ExampleRadio4" @if($data->status == 0) checked="checked" @endif>
                                        禁用&emsp;
                                        <i class="input-helper"></i></label>
                                </div>
                                <div class="form-check form-check-danger">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" value=1 name="status" id="ExampleRadio4" @if($data->status == 1) checked="checked" @endif >
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
@endsection