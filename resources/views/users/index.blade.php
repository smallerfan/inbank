@extends('base.base')

@section('base')

    <style>
        td,th{
            font-size: 16px;
            text-align: center
        }
        div .btn-group{

            margin-top: 5px;
            margin-bottom: 5px;
        }
        td {
            text-align: center;
        }
    </style>
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="page-header">
                <h3 class="page-title">
                    <span class="page-title-icon bg-gradient-primary text-white mr-2">
                        <i class="mdi mdi-wrench"></i>
                    </span>
                    用户
                </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">用户管理</a></li>
                        <li class="breadcrumb-item active" aria-current="page">用户列表</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">用户列表</h4>
                            {{ Form::open(['route' => 'users.index', 'method' => 'get','class'=>'form-inline']) }}
                            <select name="grade" class="form-control mb-2 mr-sm-2">
                            <option value='' @if(empty($grade)) selected @endif>用户等级</option>
                            <option value='interim' @if(isset($grade) && $grade == 'interim') selected @endif>普通会员</option>
                            <option value='common' @if(isset($grade) && $grade == 'common') selected @endif>合格会员</option>
                            <option value='VIP' @if(isset($grade) && $grade == 'VIP') selected @endif>VIP</option>
                            <option value='SVIP' @if(isset($grade) && $grade == 'SVIP') selected @endif>SVIP</option>
                            </select>
                            <select name="status" class="form-control mb-2 mr-sm-2">
                            <option value='' @if(empty($status)) selected @endif>用户状态</option>
                            <option value='enable' @if(isset($status) && $status == 'enable') selected @endif>正常</option>
                            <option value='lock' @if(isset($status) && $status == 'lock') selected @endif>锁定</option>
                            <option value='trans_lock' @if(isset($status) && $status == 'trans_lock') selected @endif>交易锁定</option>
                            </select>
                            <select name="key" class="form-control mb-2 mr-sm-2">
                            <option value='0' @if(isset($key) && $key == '0') selected @endif>UID</option>
                            <option value='1' @if(isset($key) && $key == '1') selected @endif>手机号</option>
                            </select>
                            <br>
                            <input class="form-control mb-2 mr-sm-2" type="text" name="value" placeholder="输入搜索值..." @if(isset($key)) value="{{ $value }}" @endif>

                            {{ Form::submit('搜索', array('class' => 'btn btn-gradient-primary mb-2')) }}
                            {{ Form::close() }}
                            <br>
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th scope="col" width="1%">ID</th>
                                    <th scope="col" width="5%">UID</th>
                                    <th scope="col" width="15%">手机号</th>
                                    <th scope="col" width="5%">直推</th>
                                    <th scope="col" width="10%">父UID</th>
                                    <th scope="col" width="10%">父账号</th>
                                    <th scope="col" width="5%">状态</th>
                                    <th scope="col" width="5%">等级</th>
                                    <th scope="col" width="15%">注册时间</th>
                                    <th scope="col" width="10%">操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <th scope="row">{{ $user->id }}</th>
                                        {{--<td style="text-align: left">--}}
                                        {{--<label>UID: {{ $user->invite_code }}</label>--}}
                                        {{--<label>手机号: {{ $user->account }}</label>--}}
                                        {{--</td>--}}
                                        <td>{{ $user->invite_code  }}</td>
                                        <td>{{ $user->account }}</td>
                                        <td>{{ $user->direct_user_count() }}</td>
                                        <td>
                                            @if(empty($user->parent_user))
                                                无
                                            @else
                                                {{ $user->parent_user->invite_code }}
                                            @endif
                                        </td>
                                        <td>
                                            @if(empty($user->parent_user))
                                                无
                                            @else
                                                {{ $user->parent_user->account }}
                                            @endif
                                        </td>
                                        <td>{{ \App\Models\User::STATUS[$user->status] }}</td>
                                        <td>{{ $user->m_user->dictionaries->dic_item_name}}</td>
                                        {{--<td style="text-align: left">--}}
                                        {{--活DK: {{ $user->user_rich ? $user->user_rich->live_dk_num : '0' }} <br/>--}}
                                        {{--冻DK: {{ $user->user_rich ? $user->user_rich->frozen_dk_num : '0' }} <br/>--}}
                                        {{--活DN: {{ $user->user_rich ? $user->user_rich->dn_num : '0' }}<br/>--}}
                                        {{--活DN: {{ $user->user_rich ? $user->user_rich->frozen_dn_num : '0' }}--}}
                                        {{--</td>--}}
                                        <td>{{ $user->created_at }}</td>
                                        <td style="text-align: center;">

                                            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                                {{--@if (!$user->is_deleted())--}}
                                                {{--<a class="btn btn-link" href="{{ route('user_riches.new', $user) }}">财富</a>--}}

                                                {{--@endif--}}
                                                {{--@if (!$user->is_deleted())--}}
                                                {{--<a class="btn btn-link" href="{{ route('user_riches.dk_logs', $user) }}">财富明细</a>--}}
                                                <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-gradient-dark btn-icon-text" style="margin-right: 3px">编辑
                                                    <i class="mdi mdi-file-check btn-icon-append"></i></a>
                                                <form action="{{ route('users.destroy', $user) }}" method="post">
                                                    {{ csrf_field() }}
                                                    {{ method_field('DELETE') }}

                                                    {{--<button type="submit" class="btn btn-link">删除</button>--}}
                                                </form>
                                                {{--@endif--}}
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <br>
                            @if(isset($key) || isset($value) || isset($grade) || isset($status))
                                {!! $users->appends(['key'=>$key,'value'=>$value,'grade'=>$grade,'status'=>$status])->links()  !!}
                            @else

                                {{ $users->links() }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    {{--<div>--}}
        {{--<h3><i class="fa fa-user"></i>用户列表</h3>--}}

        {{--<hr>--}}


        {{--<div class="table-responsive">--}}
           {{----}}
        {{--</div>--}}
    {{--</div>--}}
@endsection
