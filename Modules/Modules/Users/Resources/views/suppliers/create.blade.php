@extends('admin.layouts.default')
@section('title', 'Suppliers Add')
@section('content')

<link href="{{ asset('/theme/vendors/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />

<!-- begin:: Content Head -->
<div class="kt-subheader  kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">Add New Suppliers</h3>
        </div>
        <div class="kt-subheader__toolbar">
            <a href="{{route('suppliers.index')}}" class="btn btn-default btn-bold"><i class="fa fa-arrow-left"></i> Back</a>
        </div>
    </div>
</div>
<!-- end:: Content Head -->

<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__body">
            <form method="POST" enctype="multipart/form-data" action="{{route('suppliers.store')}}" class="kt-form kt-form--label-right" _lpchecked="1">
                @csrf
                <input type="hidden" name="user_role" value="organiser">
                <div class="kt-portlet__body">
                    <ul class="nav nav-tabs nav-fill" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#general">General</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#spotlight">Spotlight</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="general" role="tabpanel">
                                    <div class="form-group row">
                                            <div class="col-lg-6">
                                                <label>First Name:</label>
                                                <input type="text" class="form-control" placeholder="Enter first name" name="first_name" value="{{ old('first_name') }}"
                                                        onkeypress="return lettersOnly(event)">
                                                <span class="form-text text-muted">Please enter your first name</span>
                                                @if ($errors->has('first_name'))
                                                <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('first_name') }}</div>
                                                @endif
                                            </div>
                                            <div class="col-lg-6">
                                                <label>Last Name:</label>
                                                <input type="text" class="form-control" placeholder="Enter last name" name="last_name" value="{{ old('last_name') }}"
                                                        onkeypress="return lettersOnly(event)">
                                                <span class="form-text text-muted">Please enter your last name</span>
                                                @if ($errors->has('last_name'))
                                                <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('last_name') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-lg-6">
                                                <label>Email:</label>
                                                <input type="email" class="form-control" placeholder="Enter email" name="email" value="{{ old('email') }}" autocomplete="off">
                                                <span class="form-text text-muted">Please enter your email.</span>
                                                @if ($errors->has('email'))
                                                <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('email') }}</div>
                                                @endif
                                            </div>
                                            <div class="col-lg-6">
                                                <label>Phone Number:</label>
                                                <input type="text" class="form-control" placeholder="Enter phone number" name="phone_number" value="{{ old('phone_number') }}"
                                                        onkeypress="return isNumberKey(event)">
                                                <span class="form-text text-muted">Please enter your phone number</span>
                                                @if ($errors->has('phone_number'))
                                                <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('phone_number') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-lg-6">
                                                <label>Password:</label>
                                                <input type="password" class="form-control" placeholder="Enter Password" name="password" value="" autocomplete="off">
                                                <span class="form-text text-muted">Please enter your password.</span>
                                                @if ($errors->has('password'))
                                                <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('password') }}</div>
                                                @endif
                                            </div>
                                            <div class="col-lg-6">
                                                <label>Confirm Password:</label>
                                                <input type="password" class="form-control" placeholder="Enter confirm password" name="confirm_password" value="" autocomplete="off">
                                                <span class="form-text text-muted">Please enter your confirm password.</span>
                                                @if ($errors->has('confirm_password'))
                                                <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('confirm_password') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-lg-6">
                                                <label class="">Status:</label>
                                                <div class="kt-radio-inline">
                                                    <label class="kt-radio kt-radio--solid">
                                                        <input type="radio" name="status" checked="" value="active"> Active
                                                        <span></span>
                                                    </label>
                                                    <label class="kt-radio kt-radio--solid">
                                                        <input type="radio" name="status" value="inactive"> InActive
                                                        <span></span>
                                                    </label>
                                                </div>
                                                <span class="form-text text-muted">Please select status</span>
                                                @if ($errors->has('status'))
                                                <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('status') }}</div>
                                                @endif
                                            </div>
                                        </div>	 
                            </div>
                            <div class="tab-pane" id="spotlight" role="tabpanel">
                                <div class="form-group row">
                                    <div class="col-lg-12">
                                            <label>Spotlight Description:</label>
                                            <textarea name="spot_description" class="form-control ckeditor" id="">{{ old('spot_description') }}</textarea>
                                            <span class="form-text text-muted">Please enter description</span>
                                            @if ($errors->has('spot_description'))
                                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('spot_description') }}</div>
                                            @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                       
                  
                </div>
                <div class="kt-portlet__foot">
                    <div class="kt-form__actions">
                        <div class="row">
                            <div class="col-lg-6">
                                <button type="submit" class="btn btn-primary">Save</button>
                                <button type="reset" class="btn btn-secondary reset">Reset</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="contactus" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content ajax_content"></div>
        </div>
    </div>
</div>
<!-- end:: Content -->
@stop

@section('pagescript')
<!--begin::Page Vendors(used by this page) -->
<script src="{{ asset('/theme/vendors/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
<!--end::Page Vendors -->

<script>
$(".reset").click(function () {
    $('.kt-form').find("input[type=text], textarea").val("");
});
</script>
@endsection