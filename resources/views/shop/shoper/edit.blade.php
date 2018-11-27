@extends('base.base')

@section('title', '| 编辑 权限')

@section('base')
    <style>
        p{font-weight: bold;font-size: 18px;}
        img{width: 280px;height: 170px;}
        .remark{display: none;}
    </style>
    <div class='col-lg-8'>
        <span></span>
        <h3><i class='fa fa-key'></i> 查看——{{ $shop->name }}</h3>
        <br>
        <div class="form-group">
            <p>店铺名称:</p>{{ $shop->name }}
        </div>
        <div class="form-group">
            <p>主营业务:</p>{{ $shop->main_business }}
        </div>
        <div class="form-group">
            <p>是否自营:</p>{{ \App\Models\Shoper::SUPPORT[$shop->self_support] }}
        </div>
        <div class="form-group">
            <p>启用状态:</p>{{ \App\Models\Shoper::USE[$shop->is_usable] }}
        </div>
        <div class="form-group">
            <p>审核状态:</p>{{ \App\Models\Shoper::STATUS[$shop->status] }}
        </div>
        @if($shop->is_wait())
        {{ Form::open(array('url' => route('shopers.update'))) }}
        {{ csrf_field() }}
        {{ method_field('POST') }}

            {{ Form::hidden('id', $shop->id) }}
        <div class="form-group">
            {{ Form::label('choice', '审核类型') }}&emsp;
            <input type="radio" name="choice" value="pass_approval" onclick="clickItHide()">通过&emsp;&emsp;
            <input type="radio" name="choice" value="reject_approval" onclick="clickItShow()">驳回&emsp;&emsp;
        </div>
        <div class="form-group remark" id="remark">
            {{ Form::label('reason', '操作凭据') }}

            {{ Form::textarea('reason', '', array('class' => 'form-control')) }}
        </div>
        {{ Form::submit('提交') }}
        {{ Form::close() }}
        @endif
        @if($shop->is_pass() || $shop->is_reject())
        <div class="form-group">
            <p>审核结果:</p>{{ \App\Models\C2cUserAuth::STATUS[$shop->status] }}
        </div>
            @if($shop->is_reject())
            <div class="form-group">
                <p>操作凭据:</p>{{ $shop->approval_reason }}
            </div>
            @endif
        @endif
    </div>
    <script type="text/javascript">
        function clickItShow(){
            $("#remark").show();
        }
        function clickItHide(){
            $("#remark").hide();
        }
    </script>
@endsection
