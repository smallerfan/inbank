{{-- \resources\views\permissions\create.blade.php --}}
@extends('base.base')

@section('title', '| Create Goods')

@section('base')
    {{--<link href="{{url('css/bootstrap-fileupload.min.css')}}" rel="stylesheet">--}}
    {{--<script src="{{url('js/bootstrap-fileupload.min.js')}}"></script>--}}

    <style>
        span.gui_ge {
            margin-left: 10px;
            color: #6e6c6c;
            font-size: 12px;
        }
    </style>

    <div class="main-panel">
        <div class="content-wrapper">
            <div class="page-header">
                <h3 class="page-title">
                    <span class="page-title-icon bg-gradient-primary text-white mr-2">
                        <i class="mdi mdi-wrench"></i>
                    </span>
                    商城
                </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{url('/shop/goods')}}">商品列表</a></li>
                        <li class="breadcrumb-item active" aria-current="page">添加商品</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">添加商品</h4>
                            </p>
                            {{ Form::open(array('url' => route('goods.add_goods'),'enctype'=>"multipart/form-data" ,'files' => true)) }}
                            {{ csrf_field() }}
                            {{ method_field('POST') }}
                            <div class="form-group">
                                <input name="token" type="hidden" value="{{$up_token}}">
                            </div>
                            <div class="form-group">
                                {{ Form::label('goods_type', '*商品类型') }}
                                {{ Form::select('goods_type', ['real_goods' => '实体商品', 'virtual_goods' => '虚拟商品','service_goods' => '服务商品'],null,array('class' => 'form-control'))}}
                            </div>
                            <div class="form-group">
                                {{ Form::label('category_id', '*商品分类') }}
                                {{ Form::select('category_id', $cate,null,array('class' => 'form-control'))}}
                            </div>
                            <div class="form-group">
                                {{ Form::label('name', '*商品名称') }}
                                {{ Form::text('name', null , array('class' => 'form-control')) }}
                            </div>
                            <div class="form-group">
                                {{ Form::label('price', '*商品售价') }}
                                {{ Form::text('price', null , array('class' => 'form-control')) }}
                            </div>
                            <div class="form-group">
                                {{ Form::label('is_stock', '是否启用库存') }}
                                {{ Form::checkbox('is_stock', '1',array('class' => 'form-control')) }}
                            </div>
                            <div class="form-group">
                                {{ Form::label('stock', '商品库存') }}
                                {{ Form::number('stock', null , array('class' => 'form-control')) }}
                            </div>
                            <div class="form-group">
                                <label>商品主图:</label><span class="gui_ge">( 规格：300*300 )</span>
                                <br>
                                <label for="file" id="label11">
                                    <img src="{{ asset('images/default.png') }}" width="80px" height="80px"  id="img2">
                                </label>
                                <input type="file" name="main_img[]" style="display: none;" id="file" onchange="show(this.files)" multiple="multiple">

                            </div>

                            <div class="form-group">
                                {{ Form::label('intro', '商品介绍') }}
                                {{ Form::textarea('intro', null , array('class' => 'form-control')) }}
                            </div>
                            <div class="form-group">
                                <label>商品详情图:</label><span class="gui_ge">( 规格：300*300 )</span>
                                <br>
                                <label for="file1" id="label22">
                                    <img src="{{ asset('images/default.png') }}" width="80px" height="80px"  id="img3">
                                </label>
                                <input type="file" name="info_img[]" style="display: none;" id="file1" onchange="show1(this.files)" multiple="multiple">

                            </div>
                            <div class="form-group">
                                {{ Form::label('sort', '排序(数值越大越靠前)') }}
                                {{ Form::number('sort', '0' , array('class' => 'form-control')) }}
                            </div>

                            {{ Form::submit('提交', array('class' => 'btn btn-primary')) }}

                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


    <script type="text/javascript">
        function show(f) {
            var str = "";
            var img = "<img src=\"{{ asset('images/default.png') }}\" width=\"80px\" height=\"80px\"  id=\"img2\">";
            for (var i = 0; i < f.length; i++) {

                var tmpFile = f[i];

                var reader = new FileReader();
                reader.readAsDataURL(tmpFile);
                reader.onload = function (e) {
                    str += "<img  height='80px' width='80px' id='img2' src='" + e.target.result + "'/>";
                    $("#label11").html(str+img);
                }
            }
//            $(".imgq").remove()
        }
        function show1(f) {
            var str = "";
            var img = "<img src=\"{{ asset('images/default.png') }}\" width=\"80px\" height=\"80px\"  id=\"img3\">";

            for (var i = 0; i < f.length; i++) {

                var tmpFile = f[i];

                var reader = new FileReader();
                reader.readAsDataURL(tmpFile);
                reader.onload = function (e) {
                    str += "<img  height='80px' width='80px' id='img3' src='" + e.target.result + "'/>";
                    $("#label22").html(str);
                }
            }
//            $(".imgw").remove()
        }

    </script>
@endsection
