@extends('base.base')

@section('base')
    <style>
        td,th{
            font-size: 16px;
            text-align: center
        }
        a{
            color: black;
        }
        #dk, #dn {
            color: #222d32;
            list-style: none;
            display: inline-block;
            border: 1px solid #1a2226;
            height: 30px;
            border-radius: 10px;
            line-height: 30px;
            width: 120px;
            text-align: center;
        }
        .active{
            color: #222d32;
            background-color: #9581f4;
        }
        .page-item{
            margin-left: 40px;
        }
    </style>
    <div>
        <h3><i class="fa fa-key"></i>用户：{{ $user->username }} ——财富明细</h3>
        <hr>
        <div>
            <ul>
                <li id="dk" @if($flag == 'dk' || empty($flag)) class="active" @endif onclick="light()"><a href="{{ route('user_riches.dk_logs',$user) }}">DK明细</a></li>
                <li id="dn" @if($flag == 'dn') class="active" @endif><a href="{{ route('user_riches.dn_logs',$user) }}">DN明细</a></li>
            </ul>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th scope="col">UID</th>
                    <th scope="col">操作数量</th>
                    <th scope="col">操作类型</th>
                    <th scope="col">当前DK</th>
                    <th scope="col">当前DN</th>
                    <th scope="col">创建时间</th>
                </tr>
                </thead>
                <tbody>
                @if(!empty($logs))
                @foreach ($logs as $log)
                    <tr>
                        <td>{{ $user->invite_code }}</td>
                        <td>{{ $log->get_num }}</td>
                        <td>
                            @if($log->log_type == 'transfer_extra_reward' || $log->log_type == 'transfer_release' ||
                            $log->log_type == 'exchange_release' || $log->log_type == 'exchange_team_reward' ||
                            $log->log_type == 'transfer_in')
                                {{ isset($log->other_user)?$log->other_user:'' }}&nbsp;{{ \App\Models\UserRich::TYPE[$log->log_type] }}
                            @elseif($log->log_type == 'transfer_out' || $log->log_type == 'sale' || $log->log_type == 'buy')
                                {{ \App\Models\UserRich::TYPE[$log->log_type] }}&nbsp;{{ isset($log->other_user)?$log->other_user:'' }}
                            @else
                                {{ \App\Models\UserRich::TYPE[$log->log_type] }}
                            @endif
                        </td>
                        <td>{{ $log->cur_dk }}</td>
                        <td>{{ $log->cur_dn }}</td>
                        <td>{{ $log->created_at }}</td>
                    </tr>
                @endforeach
                @else
                    <tr>
                        <td colspan="6"><p>暂无记录</p></td>
                    </tr>
                @endif
                </tbody>
            </table>
            <ul class="pagination" role="navigation">

            @if($type == 'dk')
                @for ($i = 0; $i < $page_total; $i++)
                    <li @if($page == $i+1 ) class="page-item active" @else class="page-item" @endif><a class="page-link" href="{{ route('user_riches.dk_logs', $user->id) }}?page={{$i+1}}"> {{ $i+1 }}</a></li>
                @endfor
            @else
                @for ($i = 0; $i < $page_total; $i++)
                    <li @if($page == $i+1 ) class="page-item active" @else class="page-item" @endif><a class="page-link" href="{{ route('user_riches.dn_logs', $user->id) }}?page={{$i+1}}"> {{ $i+1 }}</a></li>
                @endfor
            @endif
            </ul>
        </div>
    </div>
@endsection
