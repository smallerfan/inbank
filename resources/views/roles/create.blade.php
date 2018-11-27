@extends('base.base')

@section('title', '| Add Role')

@section('base')

    <div class='col-lg-4'>

        <h3><i class='fa fa-key'></i> 添加角色</h3>
        <hr>

        {{ Form::open(array('url' => 'roles')) }}

        <div class="form-group">
            {{ Form::label('name', '名称') }}
            {{ Form::text('name', null, array('class' => 'form-control')) }}
        </div>

        <h5><b>角色权限</b></h5>

        <div class='form-group'>
            @foreach ($permissions as $permission)
                {{ Form::checkbox('permissions[]',  $permission->id ) }}
                {{ Form::label($permission->name, ucfirst($permission->name)) }}<br>
            @endforeach
        </div>

        {{ Form::submit('添加', array('class' => 'btn btn-primary')) }}

        {{ Form::close() }}

    </div>

@endsection
