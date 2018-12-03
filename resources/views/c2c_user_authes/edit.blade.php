@extends('base.base')

@section('title', '| 编辑 权限')

@section('base')
    <style>
        img{width: 280px;height: 170px;}
        p{font-weight: bold;font-size: 14px;}
        .remark{display: none;}
        .form-group {
            display: inline-block;
            margin-left: 25px;
            margin-bottom: 1.5rem;
        }
    </style>
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="page-header">
                <h3 class="page-title">
                    <span class="page-title-icon bg-gradient-primary text-white mr-2">
                        <i class="mdi mdi-wrench"></i>
                    </span>
                    C2C
                </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{url('/c2c/c2c_users')}}">用户列表</a></li>
                        <li class="breadcrumb-item active" aria-current="page">用户详情</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">用户详情——{{ $auth->name }}</h4>
                            <p></p>
                            <br>
                            <div style="width: 45%">
                                <blockquote class="blockquote blockquote-primary">
                                <div class="form-group">
                                <p>姓名:</p>
                                {{ $auth->name }}
                            </div>

                            <div class="form-group">
                                <p>手机号:</p>{{ $auth->mobile }}
                            </div>
                            <div class="form-group">
                                <p>身份证号:</p>{{ $auth->id_number }}
                            </div>
                                </blockquote>
                            </div>
                            <div class="form-inline" style="margin: 50px 0">

                            <div class="form-group col-lg-6" style="margin-left: 10px">
                                <p>身份证正面照:</p><img src="{{ config('accessurl.ACCESS_URL').$auth->id_photo_face }}">
                            </div>
                            <div class="form-group col-lg-6" style="margin-top: 20px;margin-left: 10px">
                                <p>身份证反面照:</p><img src="{{ config('accessurl.ACCESS_URL').$auth->id_photo_back }}">
                            </div>
                            <div class="form-group col-lg-12" style="margin-top: 20px;margin-left: 10px">
                                <p>身份证手持照:</p><img src="{{ config('accessurl.ACCESS_URL').$auth->id_photo_hand }}">
                            </div>
                            </div>

                        @if($auth->is_wait())
                                {{ Form::open(array('url' => route('auth.update'))) }}
                                {{ csrf_field() }}
                                {{ method_field('POST') }}

                                {{ Form::hidden('id', $auth->id) }}
                                <div class="form-group">
                                    {{ Form::label('choice', '审核类型') }}&emsp;
                                    <div class="form-check col-md-2 col-sm-2" style="display: inline-block;">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" name="choice" value="pass_approval">&nbsp;通过
                                            <i class="input-helper"></i>
                                        </label>
                                    </div>
                                    <div class="form-check col-md-2 col-sm-2" style="display: inline-block;">
                                        <label class="form-check-label">
                                            <input type="radio"  class="form-check-input" name="choice" value="reject_approval">&nbsp;驳回&emsp;
                                            <i class="input-helper"></i>
                                        </label>
                                    </div>
                                    {{--<input type="radio" name="choice" value="pass_approval" onclick="clickItHide()">通过&emsp;&emsp;--}}
                                    {{--<input type="radio" name="choice" value="reject_approval" onclick="clickItShow()">驳回&emsp;&emsp;--}}
                                </div>
                                <div class="form-group remark" id="remark">
                                    {{ Form::label('remark', '驳回原因') }}

                                    {{ Form::textarea('remark', '', array('class' => 'form-control','rows' => '3')) }}
                                </div>
                                {{ Form::submit('提交', array('class' => 'btn btn-primary')) }}
                                {{ Form::close() }}
                            @endif
                            @if($auth->is_pass() || $auth->is_reject() || $auth->is_disable())
                                <div class="form-group">
                                    <p>审核结果:</p><label class="badge badge-danger">{{ \App\Models\C2cUserAuth::STATUS[$auth->status] }}</label>
                                </div>
                                @if($auth->is_reject() || $auth->is_disable())
                                    <div class="form-group">
                                        <p>驳回原因:</p>{{ $auth->remark }}
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script type="text/javascript">
        function clickItShow(){
            $("#remark").show();
        }
        function clickItHide(){
            $("#remark").hide();
        }
    </script>
@endsection
