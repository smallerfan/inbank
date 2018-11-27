{{-- \resources\views\permissions\index.blade.php --}}
@extends('base.base')

@section('title', '| Permissions')

@section('base')
    <style>
        .form-select{
            width: 40%;
            height: 40px;
        }
    </style>
    <div class="col-lg-6">
        <h3><i class="fa fa-key"></i>InBank模块设置</h3>
        <hr>
        {{ Form::open(array('url' => route('datc_update'))) }}
        {{ csrf_field() }}
        <table class="table table-bordered table-striped">
            @foreach($datas as $k=>$data)
                <thead>
                    <th width="40%">{{ $data->title }}: </th>
                    <th>
                        @if(($data->config_value == 'open' || $data->config_value == 'close') && $data->show_type == 'radio')
                            <input type="radio" value="open" @if($data->config_value == 'open') checked="checked" @endif name="{{ $data->config_key }}">&nbsp;启用&emsp;&emsp;
                            <input type="radio" value="close" @if($data->config_value == 'close') checked="checked" @endif name="{{ $data->config_key }}">&nbsp;禁用
                        @elseif(($data->config_value == '1' || $data->config_value == '0') && $data->show_type == 'radio')
                            <input type="radio" value="open" @if($data->config_value == '1') checked="checked" @endif name="{{ $data->config_key }}">&nbsp;是&emsp;&emsp;
                            <input type="radio" value="close" @if($data->config_value == '0') checked="checked" @endif name="{{ $data->config_key }}">&nbsp;否
                        @elseif($data->show_type == 'time')
                            <select name="{{ $data->config_key }}" class="form-select">
                                @for($i=1;$i<24;$i++)
                                    <option class="form-option" value="{{ $i }}:00" @if($data->config_value == $i) selected="selected" @endif>{{ $i }}:00</option>
                                @endfor
                            </select>
                        @else
                            <input class="form-control" type="text" name="{{ $data->config_key }}" value="{{ $data->config_value }}" onkeyup="value=value.replace(/[^\d{1,}\.\d{1,}|\d{1,}]/g,'')">
                        @endif
                    </th>
                </thead>
            @endforeach
        </table>
        {{ Form::submit('保存', array('class' => 'btn btn-primary')) }}
        {{ Form::close() }}
    </div>

@endsection
