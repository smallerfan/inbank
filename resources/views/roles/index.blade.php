{{-- \resources\views\roles\index.blade.php --}}
@extends('base.base')

@section('title', '| Roles')

@section('base')
    <style>
        td,th{
            font-size: 16px;
            text-align: center
        }
    </style>
    <div class="col-lg-10">
        <h3><i class="fa fa-key"></i>角色列表</h3>

        <hr>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>角色</th>
                    <th>权限</th>
                    <th>操作</th>
                </tr>
                </thead>

                <tbody>
                @foreach ($roles as $role)
                    <tr>
                        <td>{{ $role->name }}</td>
                        <td>{{ str_replace(array('[',']','"'),'', $role->permissions()->pluck('name')) }}</td>
                        <td>
                            <a href="{{ URL::to('roles/'.$role->id.'/edit') }}" class="btn btn-info pull-left" style="margin-right: 3px;">编辑</a>

                            {!! Form::open(['method' => 'DELETE', 'route' => ['roles.destroy', $role->id] ]) !!}
                            {!! Form::submit('删除', ['class' => 'btn btn-danger']) !!}
                            {!! Form::close() !!}
                        </td>
                    </tr>
                @endforeach
                </tbody>

            </table>
        </div>

        <a href="{{ URL::to('roles/create') }}" class="btn btn-success">添加角色</a>

    </div>

@endsection
