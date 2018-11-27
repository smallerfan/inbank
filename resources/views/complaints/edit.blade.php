@extends('base.base')

@section('title', '| 编辑 权限')

@section('base')
    <style>
        img{width: 160px;height: 280px;}
        p{font-weight: bold;font-size: 14px;}
        .head{
            width: 6%;
            text-align: right;
        }
    </style>
    <div class='col-lg-8'>

        <h3><i class='fa fa-key'></i> 查看申诉</h3>
        <br>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="head">账户: </th>
                        <th style="text-align: left;">
                            <label>UID: {{ $data->user->invite_code }}</label><br/>
                            <label>账户: {{ $data->user->mobile }}</label>
                        </th>
                    </tr>
                    <tr>
                        <th class="head">投诉类型: </th>
                        <th>{{ \App\Models\Complaint::TYPE[$data->advice_type] }}</th>
                    </tr>
                    <tr>
                        <th class="head">投诉内容: </th>
                        <th  style="width: 30%;text-align: left;">{{ $data->content }}</th>
                    </tr>
                    @if(!empty($data->imgs[0]))

                        <tr>
                            <th class="head">投诉凭证: </th>
                            <th>
                                @if(is_array($data->imgs))
                                    @foreach($data->imgs as $img)
                                        <img src="{{ config('accessurl.ACCESS_URL').$img }}">
                                    @endforeach
                                @else
                                    <img src="{{ config('accessurl.ACCESS_URL').$data->imgs }}">
                                @endif
                            </th>
                        </tr>

                    @endif
                    <tr>
                        <th class="head">状态: </th>
                        <th>{{ \App\Models\Complaint::STATUS[$data->is_reply] }}</th>
                    </tr>
                    @if($data->is_reply == 'reply' && !empty($data->reply_content))

                        <tr>
                            <th class="head">回复内容: </th>
                            <th>{{ $data->reply_content }}</th>
                        </tr>
                        <tr>
                            <th class="head">回复时间: </th>
                            <th>{{ $data->reply_time }}</th>
                        </tr>
                    @elseif($data->is_reply == 'no_reply')
                        {{ Form::open(array('url' => route('complaints.update'))) }}
                        {{ method_field('POST') }}
                        {{ Form::hidden('id', $data->id) }}
                        <tr>
                            <th class="head">
                                {{ Form::label('reply_content', '回复内容: ') }}
                            </th>
                            <th>
                                {{ Form::textarea('reply_content', '', array('class' => 'form-control','rows' => '5')) }}
                            </th>
                        </tr>
                        <tr>
                            <th colspan="2" style="text-align: center;">
                                {{ Form::submit('提交') }}
                                {{ Form::close() }}
                            </th>
                        </tr>
                    @endif
                </thead>
            </table>
        </div>
    </div>

@endsection
