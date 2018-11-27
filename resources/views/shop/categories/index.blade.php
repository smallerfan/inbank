@extends('base.base')

@section('title', '| 商品分类')

@section('base')
    <style>
        td,th{
            font-size: 16px;
            text-align: center
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
                        <li class="breadcrumb-item"><a href="#">商城管理</a></li>
                        <li class="breadcrumb-item active" aria-current="page">商品分类</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">商品分类列表</h4>
                            <p class="card-description">
                            <div class="add-button">
                                <a href="{{ route('categories.create') }}" class="btn btn-sm btn-gradient-success btn-icon-text">
                                    <i class="mdi mdi-plus btn-icon-prepend"></i>添加商品分类
                                </a>
                            </div>
                            </p>
                            <form action="{{ route('goods.index') }}" method="get" class="form-inline">
                                <p>商品名称：</p>
                                <div class="input_control">
                                    <input type="text" name="value" class="form-control mb-2 mr-sm-2" placeholder="输入搜索值..." @if(isset($value)) value="{{ $value }}" @endif>
                                </div>
                                <input type="submit" value="搜索" class="btn btn-gradient-primary mb-2" id="form-submit">
                            </form>
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>分类名称[cn]</th>
                                    <th>分类名称[hk]</th>
                                    <th>分类名称[en]</th>
                                    <th>排序</th>
                                    <th>操作</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach ($categories as $category)
                                    <tr>
                                        <td>{{ $category->id }}</td>
                                        <td>{{ $category->name_cn }}</td>
                                        <td>{{ $category->name_hk }}</td>
                                        <td>{{ $category->name_en }}</td>
                                        <td>{{ $category->sort }}</td>
                                        <td>
                                            <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-gradient-dark btn-icon-text" style=" margin-bottom: 10px;">编辑
                                                <i class="mdi mdi-file-check btn-icon-append"></i></a>
                                            {{--<a class="" style="margin-right: 3px;" onClick="check({{$data->id}})">删除</a>--}}

                                            {!! Form::open(['method' => 'DELETE', 'route' => ['categories.destroy', $category->id] ]) !!}
                                            <button type="submit" class="btn btn-sm btn-gradient-danger btn-icon-text">                                            <i class="mdi mdi-delete btn-icon-prepend"></i>
                                                删除</button>
                                            {{--{!! Form::submit('删除', ['class' => 'btn btn-sm btn-gradient-danger btn-icon-text']) !!}--}}
                                            {!! Form::close() !!}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>

                            </table>
                            <br>
                            {{ $categories->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
