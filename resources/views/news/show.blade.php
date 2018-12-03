@extends('base.base')
@section('base')
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
                        <li class="breadcrumb-item"><a href="{{url('/news')}}">公告列表</a></li>
                        <li class="breadcrumb-item active" aria-current="page">公告详情</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">查看公告——{{$new->title_cn}}</h4>
                            <br>
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th width="33%">标题</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>{{ $new->title_cn }}</td>
                                </tr>
                                </tbody>
                                <thead>
                                <tr>
                                    <th width="33%">内容</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td style="height: 260px;vertical-align:text-top;">{!! html_entity_decode($new->content_cn) !!}</td>
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
                </div>
            </div>
        </div>

    </div>
@endsection
