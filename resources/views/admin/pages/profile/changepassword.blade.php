@extends('admin.layouts.default')
@section('title', 'Event Me Now')
@section('content')
<!-- begin:: Content Head -->
<div class="kt-subheader  kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <button class="kt-subheader__mobile-toggle kt-subheader__mobile-toggle--left" id="kt_subheader_mobile_toggle"><span></span></button>
            <h3 class="kt-subheader__title">Profile</h3>
        </div>
    </div>
</div>
<!-- end:: Content Head -->

<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <div class="kt-grid kt-grid--desktop kt-grid--ver kt-grid--ver-desktop kt-app">
        <button class="kt-app__aside-close" id="kt_user_profile_aside_close"><i class="la la-close"></i></button>
        
        @include('admin.pages.profile.sidebar')

        <!--Begin:: App Content-->
        <div class="kt-grid__item kt-grid__item--fluid kt-app__content">
            <div class="row">
                <div class="col-xl-12">
                    <div class="kt-portlet kt-portlet--height-fluid">
                        <div class="kt-portlet__head">
                            <div class="kt-portlet__head-label">
                                <h3 class="kt-portlet__head-title">Change Password<small>change your account password</small></h3>
                            </div>
                        </div>
                        <form id="form-change-password" class="kt-form kt-form--label-right" role="form" method="POST" action="{{ route('profile.changepassword.save') }}" novalidate class="form-horizontal">
                            <div class="kt-portlet__body">
                                @include('admin.theme.includes.message')
                                <div class="kt-section kt-section--first">
                                    <div class="kt-section__body">
                                        @csrf
                                        <div class="form-group row">
                                            <label class="col-xl-3 col-lg-3 col-form-label">Current Password</label>
                                            <div class="col-lg-9 col-xl-6">
                                                <input type="password" class="form-control" id="password" name="password" value="" placeholder="Current password">
                                                @if ($errors->has('password'))
                                                <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('password') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-xl-3 col-lg-3 col-form-label">New Password</label>
                                            <div class="col-lg-9 col-xl-6">
                                                <input type="password" class="form-control" id="new_password" name="new_password" value="" placeholder="New password">
                                                @if ($errors->has('new_password'))
                                                <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('new_password') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group form-group-last row">
                                            <label class="col-xl-3 col-lg-3 col-form-label">Verify Password</label>
                                            <div class="col-lg-9 col-xl-6">
                                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" value="" placeholder="Verify password">
                                                @if ($errors->has('confirm_password'))
                                                <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('confirm_password') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="kt-portlet__foot">
                                <div class="kt-form__actions">
                                    <div class="row">
                                        <div class="col-lg-3 col-xl-3">
                                        </div>
                                        <div class="col-lg-9 col-xl-9">
                                            <button type="submit" class="btn btn-brand btn-bold">Change Password</button>&nbsp;
                                            <!--<button type="reset" class="btn btn-secondary">Cancel</button>-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--End:: App Content-->
    </div>

    <!--End::App-->
</div>
<!-- end:: Content -->
@stop
@section('pagescript')
<!--<script src="{{ asset('/theme/js/demo1/pages/dashboard.js') }}" type="text/javascript"></script>-->
<script src="{{ asset('/theme/js/demo1/pages/custom/user/profile.js') }}" type="text/javascript"></script>
@endsection