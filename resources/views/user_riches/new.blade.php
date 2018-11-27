@extends('base.base')

@section('base')
    <style>
        .head{
            text-align: right;
            width: 20%;
        }
    </style>
    <div class='col-lg-8'>
        <h3><i class='fa fa-key'></i> 财富管理</h3>
        <br>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th class="head">账户: </th>
                    <th>
                        <label>UID: {{ $user->invite_code }}</label><br/>
                        <label>手机号: {{ $user->mobile }}</label>
                    </th>
                </tr>
                <tr>
                    <th class="head">DK余额: </th>
                    <th>
                        {{ $user->user_rich->live_dk_num }}
                    </th>
                </tr>
                <tr>
                    <th class="head">DN余额: </th>
                    <th>
                        {{ $user->user_rich->dn_num }}
                    </th>
                </tr>
                <tr>
                    <th class="head">当前等级: </th>
                    <th>
                        {{ \App\Models\User::GRADE[$user->user_grade]  }}
                    </th>
                </tr>
                <form action="{{ route('user_riches.create', $user) }}" method="post">
                    @csrf
                    <tr>
                        <th class="head">操作类型: </th>
                        <th>
                            <div class="col-sm-10">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="operate_type" id="inlineRadio1" value="send_dk">
                                    <label class="form-check-label" for="inlineRadio1">增加DK</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="operate_type" id="inlineRadio2" value="take_back_dk">
                                    <label class="form-check-label" for="inlineRadio2">减少DK</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="operate_type" id="inlineRadio3" value="send_dn" >
                                    <label class="form-check-label" for="inlineRadio3">增加DN</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="operate_type" id="inlineRadio3" value="take_back_dn" >
                                    <label class="form-check-label" for="inlineRadio3">减少DN</label>
                                </div>
                            </div>
                        </th>
                    </tr>
                    <tr>
                        <th class="head">数量: </th>
                        <th>
                            <input type="text" class="form-control" name="operate_num" value="0">
                        </th>
                    </tr>
                    <tr>
                        <th colspan="2" style="text-align: center;">
                            <button type="submit" class="btn btn-primary">提交</button>
                        </th>
                    </tr>

                </form>
                </thead>
            </table>
        </div>
    </div>
@endsection
