@extends('base.base')
@section('base')
    <style>
        td,th{
            font-size: 16px;
            text-align: center
        }
    </style>
    <div class="col-lg-12">
        <h3><i class="fa fa-key"></i>投诉列表</h3>
        <hr>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                <tr>
                    <th style="width: 10%">用户</th>
                    <th style="width: 30%;">投诉内容</th>
                    <th style="width:5%">状态</th>
                    <th style="width:10%">创建时间</th>
                    <th style="width:5%">操作</th>
                </tr>
                {{--<tr>--}}
                    {{--<th style="width: 15%">用户</th>--}}
                    {{--<th>投诉类型</th>--}}
                    {{--<th style="width: 30%;">投诉内容</th>--}}
                    {{--<th>状态</th>--}}
                    {{--<th>创建时间</th>--}}
                    {{--<th>操作</th>--}}
                {{--</tr>--}}
                </thead>

                <tbody>
                @foreach ($datas as $data)
                    <tr>
                        <td style="text-align: left;">
                            <label>UID: {{ $data->user->invite_code }}</label><br/>
                            <label>账户: {{ $data->user->mobile }}</label>
                        </td>
{{--                        <td>{{ \App\Models\Complaint::TYPE[$data->advice_type] }}</td>--}}
                        <td  style="width: 30%;text-align: left;">{{ $data->content }}</td>
                        <td>{{ \App\Models\Complaint::STATUS[$data->is_reply] }}</td>
                        <td>{{ $data->created_at }}</td>
                        <td>
                            <a href="{{ route('complaints.edit', $data) }}" class="btn btn-info" style="margin-right: 3px;">查看</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>

            </table>
            {{ $datas->links() }}

        </div>
    </div>
@endsection
