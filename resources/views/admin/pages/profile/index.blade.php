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
                    <div class="kt-portlet">
                        <div class="kt-portlet__head">
                            <div class="kt-portlet__head-label">
                                <h3 class="kt-portlet__head-title">Personal Information <small>update your personal informaiton</small></h3>
                            </div>
                        </div>
                        {{ Form::model(Auth::user(), [
                            'route' => ['profile.update', Auth::user()->id], 
                                    'method' => 'PATCH', 
                                    'files' => true,
                                    'class' => 'kt-form kt-form--label-right'
                        ]) }}
                        <div class="kt-portlet__body">
                            @include('admin.theme.includes.message')
                            <div class="kt-section kt-section--first">
                                <div class="kt-section__body">
                                    <div class="form-group row">
                                        <label class="col-xl-3 col-lg-3 col-form-label">Avatar</label>
                                        <div class="col-lg-9 col-xl-6">
                                            <div class="kt-avatar kt-avatar--outline kt-avatar--circle" id="kt_apps_user_add_avatar">
                                                <div class="kt-avatar__holder imagePreviewLoadUrl" style="background-image: url('{{isFileExist('sitebucket/userProfile/', Auth::user()->image)}}');"></div>
                                                <label class="kt-avatar__upload" data-toggle="kt-tooltip" title="" data-original-title="Change avatar">
                                                    <i class="fa fa-pen"></i>
                                                    <input type="file" class="imageInputUrl" name="image" accept=".png, .jpg, .jpeg">
                                                </label>
                                                <span class="kt-avatar__cancel" data-toggle="kt-tooltip" title="" data-original-title="Cancel avatar">
                                                    <i class="fa fa-times"></i>
                                                </span>
                                            </div>
                                            @if ($errors->has('image'))
                                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('image') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-xl-3 col-lg-3 col-form-label">First Name</label>
                                        <div class="col-lg-9 col-xl-6">
                                            <input class="form-control" type="text" id="name" name="name" value="{{isset(Auth::user()->name) ? Auth::user()->name : ''}}">
                                            @if ($errors->has('name'))
                                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('name') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label class="col-xl-3"></label>
                                        <div class="col-lg-9 col-xl-6">
                                            <h3 class="kt-section__title kt-section__title-sm">Contact Info:</h3>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-xl-3 col-lg-3 col-form-label">Contact Phone</label>
                                        <div class="col-lg-9 col-xl-6">
                                            <div class="input-group">
                                                <div class="input-group-prepend"><span class="input-group-text"><i class="la la-phone"></i></span></div>
                                                <input type="text" class="form-control" id="phone_number" name="phone_number" value="{{isset(Auth::user()->phone_number) ? Auth::user()->phone_number : ''}}" placeholder="Phone" aria-describedby="basic-addon1">
                                                @if ($errors->has('phone_number'))
                                                <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('phone_number') }}</div>
                                                @endif
                                            </div>
                                            <span class="form-text text-muted">We'll never share your phone number with anyone else.</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-xl-3 col-lg-3 col-form-label">Email Address</label>
                                        <div class="col-lg-9 col-xl-6">
                                            <div class="input-group">
                                                <div class="input-group-prepend"><span class="input-group-text"><i class="la la-at"></i></span></div>
                                                <input type="email" class="form-control" id="email" name="email" value="{{isset(Auth::user()->email) ? Auth::user()->email : ''}}" placeholder="Email" aria-describedby="basic-addon1">
                                                @if ($errors->has('email'))
                                                <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('email') }}</div>
                                                @endif
                                            </div>
                                            <span class="form-text text-muted">We'll never share your email with anyone else.</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-xl-3 col-lg-3 col-form-label">Address</label>
                                        <div class="col-lg-9 col-xl-6">
                                            <div class="input-group">
                                                <div class="input-group-prepend"><span class="input-group-text"><i class="la la-location-arrow"></i></span></div>
                                                <textarea name="address" class="form-control" id="address">{{isset(Auth::user()->address) ? Auth::user()->address : ''}}</textarea>
                                                @if ($errors->has('address'))
                                                <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('address') }}</div>
                                                @endif
                                            </div>
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
                                        <button type="submit" class="btn btn-success">Submit</button>&nbsp;
                                        <button type="reset" class="btn btn-secondary">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{ Form::close() }}
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

<script>
$(".imageInputUrl").change(function () {
    if (this.files && this.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            console.log(e.target.result);
            $('.imagePreviewLoadUrl').css('background-image', "url(" + e.target.result + ")");
        }
        reader.readAsDataURL(this.files[0]);
    }
});
</script>
@endsection