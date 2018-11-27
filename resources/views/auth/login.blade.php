<style>
    body {
        background-image: url("/image/login_bg.jpg") !important;
    }
</style>
@extends('layouts.auth')

@section('base')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">

                <div class="card-body">
                    <div class="logo">
                        <div class="card-logo">INBank后台管理系统</div>
                    </div>

                    <form method="POST" action="{{ route('login') }}" aria-label="{{ __('global.login') }}" style="margin-left: 10%">
                        @csrf

                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="email" class="col-sm-4 col-form-label text-md-right">{{ __('global.account') }}</label>
                                <input id="account" type="text" class="form-control{{ $errors->has('account') ? ' is-invalid' : '' }}" name="account" value="{{ old('account') }}" required autofocus>

                                @if ($errors->has('account'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('account') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">


                            <div class="col-md-6">
                                <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('global.password') }}</label>
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('global.remember_me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary-login">
                                    {{ __('global.login') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
