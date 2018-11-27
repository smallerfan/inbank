@extends('base.base')

@section('title', '| 编辑 权限')

@section('base')
    <style>
        .uploadImg{
            display: none !important;
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
                        <li class="breadcrumb-item"><a href="{{url('shop/categories')}}">商品分类列表</a></li>
                        <li class="breadcrumb-item active" aria-current="page">编辑商品分类</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <p>
                            <h4 class="card-title">编辑商品分类</h4>
                            </p>
                            {{ Form::model($category,  ['route' => ['categories.update', $category], 'method' => 'POST','enctype'=>"multipart/form-data"]) }}
                            {{ csrf_field() }}
                            {{ Form::hidden('id', $category->id) }}
                            <div class="form-group">
                                {{ Form::label('name_cn', '名称[cn]') }}
                                {{ Form::text('name_cn', $category->name_cn, array('class' => 'form-control')) }}
                            </div>
                            <div class="form-group">
                                {{ Form::label('name_hk', '名称[hk]') }}
                                {{ Form::text('name_hk', $category->name_hk, array('class' => 'form-control')) }}
                            </div>
                            <div class="form-group">
                                {{ Form::label('name_en', '名称[en]') }}
                                {{ Form::text('name_en', $category->name_en, array('class' => 'form-control')) }}
                            </div>
                            <div class="form-group">
                                <label>图标</label>
                                <label for="file">
                                    <img src="{{ $category->img }}" width="80px" height="80px"  id="img"></label>
                                <input type="file" name="img" class="uploadImg" id="file" onchange="show(this.files)">

                            </div>
                            <div class="form-group">
                                {{ Form::label('pid', '上级分类') }}
                                {{ Form::select('pid', $categories,$category->pid,array('class' => 'form-control')) }}
                            </div>
                            <div class="form-group">
                                {{ Form::label('sort', '排序(数值越大越靠前)') }}
                                {{ Form::text('sort', $category->sort, array('class' => 'form-control')) }}
                            </div>

                            {{ Form::submit('保存', array('class' => 'btn btn-primary')) }}

                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class='col-lg-8'>

        <h3><i class='fa fa-key'></i> 编辑商品分类——{{$category->name_cn}}</h3>
        <br>


    </div>
    <script type="text/javascript">
        function show(f) {
            var str = "";
            for (var i = 0; i < f.length; i++) {
                var reader = new FileReader();
                reader.readAsDataURL(f[i]);
                reader.onload = function (e) {
                    str += "<img  height='80px' width='80px' id='img' src='" + e.target.result + "'/>";
                    $("#img")[0].outerHTML = str;
                }
            }
        }

    </script>
@endsection
