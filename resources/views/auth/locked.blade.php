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
        <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" style="background-image: url({{ asset('assets/media//bg/bg-1.jpg') }});">
            <div class="kt-grid__item kt-grid__item--fluid kt-login__wrapper">
                <div class="kt-login__container">
                    <div class="kt-login__logo">
                        <a href="javascript:">
                            <img style="width: 150px;" src="{{ asset('sitebucket/other/logo.svg') }}"  alt="logo">
                        </a>
                    </div>

                    <div class="kt-login__signin">
                        <div class="kt-login__head">
                            <h3 class="kt-login__title">{{ __('Locked') }}</h3>
                        </div>
                        <form class="kt-form" method="POST" action="{{ route('login.unlock') }}" aria-label="{{ __('Locked') }}">
                            @csrf
                            <div class="input-group">
                                <input class="form-control @error('password') is-invalid @enderror" type="password" placeholder="Password" name="password">
                                @if ($errors->any())
                                {!! implode('', $errors->all('<div style="display: block; text-align: center;" id="email-error" class="error invalid-feedback">:message</div>')) !!}
                                @endif


                            </div>
                            <div class="row kt-login__extra">

                                <!--                                <div class="col kt-align-right">
                                                                    <a href="{{ route('password.request') }}" id="kt_login_forgot" class="kt-link kt-login__link">Forget Password ?</a>
                                                                </div>-->
                            </div>
                            <div class="kt-login__actions">
                                <button type="submit" class="btn btn-pill kt-login__btn-primary">
                                    {{ __('Unlock') }}
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection


