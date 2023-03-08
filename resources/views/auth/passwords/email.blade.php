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
                            @if (session('status'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('status') }}.
                            </div>
                            @endif
                            <h3 class="kt-login__title">Forgotten Password ?</h3>
                            <div class="kt-login__desc">Enter your email to reset your password:</div>
                        </div>

                        <form class="kt-form" method="POST" action="{{ route('password.email') }}">
                            @csrf
                            <div class="input-group">
                                <input class="form-control" type="text" placeholder="Email" name="email" value="{{ old('email') }}" required id="kt_email" autocomplete="off">
                                @if ($errors->has('email'))
                                <div style="display: block; text-align: center;" id="email-error" class="error invalid-feedback">{{ $errors->first('email') }}</div>
                                @endif
                            </div>
                            <div class="kt-login__actions">
                                <button type="submit" id="kt_login_forgot_submit" class="btn btn-pill kt-login__btn-primary">Request</button>&nbsp;&nbsp;
                                <a style="padding-top: 10px;" id="kt_login_forgot_cancel" class="btn btn-pill kt-login__btn-secondary" href="{{url('admin/login')}}">Cancel</a>
                            </div>
                        </form>
                    </div>
                    <!--                    <div class="kt-login__account">
                                            <span class="kt-login__account-msg">
                                                Don't have an account yet ?
                                            </span>&nbsp;&nbsp;
                                            <a href="javascript:;" id="kt_login_signup" class="kt-link kt-link--light kt-login__account-link">Sign Up</a>
                                        </div>-->
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

