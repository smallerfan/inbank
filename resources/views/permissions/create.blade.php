{{-- \resources\views\permissions\create.blade.php --}}
@extends('base.base')

@section('title', '| Create Permission')

@section('base')

    <div class='col-lg-4'>

        <h3><i class='fa fa-key'></i>添加权限</h3>
        <br>

        {{ Form::open(array('url' => 'permissions')) }}

        <div class="form-group">
            {{ Form::label('name', '名称') }}
            {{ Form::text('name', '', array('class' => 'form-control')) }}
        </div><br>
        @if(!$roles->isEmpty())
        <h4>分配此权限给以下角色:</h4>

        @foreach ($roles as $role)
            {{ Form::checkbox('roles[]',  $role->id ) }}
            {{ Form::label($role->name, ucfirst($role->name)) }}<br>

        @endforeach
        @endif
        <br>
        {{ Form::submit('添加', array('class' => 'btn btn-primary')) }}

        {{ Form::close() }}

    </div>

@endsection
