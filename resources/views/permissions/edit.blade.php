@extends('base.base')

@section('title', '| 编辑 权限')

@section('base')

    <div class='col-lg-4'>

        <h3><i class='fa fa-key'></i> 编辑 {{$permission->name}}</h3>
        <br>
        {{ Form::model($permission, array('route' => array('permissions.update', $permission->id), 'method' => 'PUT')) }}{{-- Form model binding to automatically populate our fields with permission data --}}

        <div class="form-group">
            {{ Form::label('name', '名称') }}
            {{ Form::text('name', null, array('class' => 'form-control')) }}
        </div>
        <br>
        {{ Form::submit('保存', array('class' => 'btn btn-primary')) }}

        {{ Form::close() }}

    </div>

@endsection
