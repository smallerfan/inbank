@extends('base.base')

@section('title', '| 编辑 权限')


@section('base')
    <style>
        img{width: 300px;}
        p{font-weight: bold;font-size: 18px;}
        .remark{display: none;}
        span.order_status {
            color: red;
            margin: 46px;
            font-size: 20px;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .form-group.col-lg-12 {
            line-height: 33px;
            padding: 30px;
            border: 3px solid #222d3203;
            border-radius: 25px;
            background: #222d3236;
        }
        .form-control {
            width: 50%;
            border-radius: 11px;
        }
    </style>

    <script src="http://www.jq22.com/jquery/1.11.1/jquery.min.js"></script>
    <script src="http://www.jq22.com/jquery/bootstrap-3.3.4.js"></script>
    <script src="{{ url('js/distpicker.data.js') }}"></script>
    <script src="{{ url('js/distpicker.js') }}"></script>
    <script src="{{ url('js/main.js') }}"></script>
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
                        <li class="breadcrumb-item"><a href="{{url('/user/user_level/index')}}">字典列表</a></li>
                        <li class="breadcrumb-item active" aria-current="page">新增字典</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">新增字典</h4>
                            <p class="card-description">
                            </p>
                                {{ Form::open(array('url' => route('user_level.create'))) }}
                                {{ csrf_field() }}
                                {{ method_field('POST') }}

                                <div class="form-group">
                                    {{ Form::label('module', '商品类型') }}
                                    {{ Form::select('module', ['in' => 'in', 'c2c' => 'c2c','market'=>'market'],null,array('class' => 'form-control'))}}
                                </div>
                                <div class="form-group">
                                    {{ Form::label('dic_type', '字典类型') }}
                                    {{ Form::text('dic_type', null ,array('class' => 'form-control'))}}
                                </div>
                                <div class="form-group">
                                    {{ Form::label('dic_type_name', '字典类型名称') }}
                                    {{ Form::text('dic_type_name', null ,array('class' => 'form-control'))}}
                                </div>
                                <div class="form-group">
                                    {{ Form::label('dic_item', '字典项') }}
                                    {{ Form::text('dic_item', null ,array('class' => 'form-control'))}}
                                </div>
                                <div class="form-group">
                                    {{ Form::label('dic_item_name', '字典项名称') }}
                                    {{ Form::text('dic_item_name', null ,array('class' => 'form-control'))}}
                                </div>
                                <div class="form-group">
                                    {{ Form::label('dic_value', '字典值') }}
                                    {{ Form::text('dic_value', null ,array('class' => 'form-control'))}}
                                </div>

                                {{ Form::submit('提交',array('class'=>'btn btn-gradient-primary mb-2')) }}
                                {{ Form::close() }}
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
//        function showEdit() {
//            $('#edit-express').show()
//        }
    </script>
@endsection

