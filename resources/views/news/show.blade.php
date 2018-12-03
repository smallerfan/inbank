@extends('layouts.app')

@section('title', '| 查看 公告')

@section('content')
    <div class='col-lg-10'>
        <h3><i class='fa fa-key'></i> 查看公告——{{$new->title_cn}}</h3>
        <br>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th width="33%">标题</th>
                        {{--<th width="33%">标题[hk]</th>--}}
                        {{--<th width="33%">标题[en]</th>--}}
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $new->title_cn }}</td>
{{--                        <td>{{ $new->title_hk }}</td>--}}
{{--                        <td>{{ $new->title_en }}</td>--}}
                    </tr>
                </tbody>
                <thead>
                    <tr>
                        <th width="33%">内容</th>
                        {{--<th width="33%">内容[hk]</th>--}}
                        {{--<th width="33%">内容[en]</th>--}}
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="height: 260px;vertical-align:text-top;">{!! html_entity_decode($new->content_cn) !!}</td>
                        {{--<td style="height: 260px;vertical-align:text-top;">{!! html_entity_decode($new->content_hk) !!}</td>--}}
                        {{--<td style="height: 260px;vertical-align:text-top;">{!! html_entity_decode($new->content_en) !!}</td>--}}
                    </tr>
                </tbody>
                <thead>
                    <tr>
                        @if(empty($new->status))
                        <th>状态</th>
                        @endif
                        <th>类型</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        @if(empty($new->status))
                        <td>{{ \App\Models\News::STATUS[$new->status] }}</td>
                        @endif
                        <td>{{ \App\Models\News::TYPE[$new->news_type] }}</td>
                    </tr>
                </tbody>
                @if($new->news_type == 'urgent')
                <thead>
                    <tr>
                        <th>开始时间</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $new->start_time }}</td>
                    </tr>
                </tbody>
                    <thead>
                    <tr>
                        <th>结束时间</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>{{ $new->end_time }}</td>
                    </tr>
                    </tbody>
                @endif

            </table>

        </div>
    </div>
@endsection
