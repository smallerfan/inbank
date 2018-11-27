@extends('base.base')

@section('title', '| 编辑 权限')

@section('base')
    <style>
        img{width: 300px;}
        p{font-weight: bold;font-size: 18px;}
        .remark{display: none;}
    </style>
    <div class='col-lg-8'>
        <span></span>
        <h3><i class='fa fa-key'></i> 查看——{{ $goods->name }}</h3>
        <br>
        <div class="form-group col-lg-6">
            <p>商品名称:</p>{{ $goods->name }}
        </div>
        @if(!empty($goods->category))
        <div class="form-group col-lg-6">
            <p>分类:</p>{{ $goods->category->name_cn }}
        </div>
        @endif
        <div class="form-group col-lg-6">
            <p>类型:</p>{{ \App\Models\Goods::TYPE[$goods->goods_type] }}
        </div>
        <div class="form-group col-lg-6">
            <p>价格:</p>¥{{ $goods->price }}
        </div>
        <div class="form-group">
            <p>商品主图:</p>
            @if(!empty($goods->imgs))
                @foreach ($goods->imgs as $img)
                    <img src="{{ config('accessurl.ACCESS_URL').$img }}">
                @endforeach
            @endif
        </div>
        <div class="form-group">
            <p>商品详情:</p>
            @if(!empty($goods->detail_imgs))
                @foreach ($goods->detail_imgs as $detail_img)
                    @if(!empty($detail_img))
                        <img src="{{ config('accessurl.ACCESS_URL').$detail_img }}">
                    @endif
                @endforeach
            @else
                &emsp;无
            @endif
        </div>
        <div class="form-group">
            <p>店铺名称:</p>{{ $goods->shoper->name }}
        </div>
        @if($goods->is_wait())
        {{ Form::open(array('url' => route('goods.update'))) }}
        {{ csrf_field() }}
        {{ method_field('POST') }}

            {{ Form::hidden('id', $goods->id) }}
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
        @if($goods->is_pass() || $goods->is_reject())
            <div class="form-group">
                <p>审核结果:</p>{{ \App\Models\C2cUserAuth::STATUS[$goods->status] }}
            </div>
            @if($goods->is_reject())
                <div class="form-group">
                    <p>操作凭据:</p>{{ $goods->approval_reason }}
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
