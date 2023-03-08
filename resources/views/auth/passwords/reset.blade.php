@extends('admin.layouts.auth')
@section('content')
<style>
    .kt-login.kt-login--v2 .kt-login__wrapper .kt-login__container .kt-form .form-control {
        color: #fff;
    }

</style>
<link href="{{ asset('/theme/css/demo1/pages/login/login-2.css') }}" rel="stylesheet" type="text/css" />
<div class="kt-grid kt-grid--ver kt-grid--root">
    <div class="kt-grid kt-grid--hor kt-grid--root kt-login kt-login--v2 kt-login--signin" id="kt_login">
        <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" style="background-image: url({{ asset('assets/media//bg/login-bg.jpg') }});">
            <div class="kt-grid__item kt-grid__item--fluid kt-login__wrapper">
                <div class="kt-login__container">
                    <div class="kt-login__logo">
                        <a href="javascript:">
                            <img style="width: 150px;" src="{{ asset('images/logo.png') }}">
                        </a>
                    </div>
                    <div class="kt-login__forgot" style="display: block;">
                        <div class="kt-login__head">
                            <h3 class="kt-login__title">{{ __('Reset Password') }}</h3>
                            <div class="kt-login__desc">Enter your new password to reset your password:</div>
                        </div>
                        <form class="kt-form" method="POST" action="{{ route('password.update') }}">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            <div class="input-group">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
                                @error('email')
                                <span style="display: block; text-align: center;" class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="input-group">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                <span style="display: block; text-align: center;" class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="input-group">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                            <div class="kt-login__actions">
                                <button type="submit" id="kt_login_forgot_submit" class="btn btn-pill kt-login__btn-primary">{{ __('Reset Password') }}</button>&nbsp;&nbsp;
                                <a style="padding-top: 10px;" id="kt_login_forgot_cancel" class="btn btn-pill kt-login__btn-secondary" href="{{url('admin/login')}}">Cancel</a>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('pagescript')
<script>


</script>
<!--<script src="{{ asset('/theme/js/demo1/pages/login/login-general.js') }}" type="text/javascript"></script>-->
@endsection




