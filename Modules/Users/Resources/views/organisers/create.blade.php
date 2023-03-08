@extends('admin.layouts.default')
@section('title', 'Organiser Add')
@section('content')

<link href="{{ asset('/theme/vendors/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />

<!-- begin:: Content Head -->
<div class="kt-subheader  kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">Add New User</h3>
        </div>
        <div class="kt-subheader__toolbar">
            <a href="{{route('users.index')}}" class="btn btn-default btn-bold"><i class="fa fa-arrow-left"></i> Back</a>
        </div>
    </div>
</div>
<!-- end:: Content Head -->

<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__body">
            <form method="POST" enctype="multipart/form-data" action="{{route('users.store')}}" class="kt-form kt-form--label-right" _lpchecked="1">
                @csrf
                <input type="hidden" name="user_role" value="organiser">
                <div class="kt-portlet__body">
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>First Name:</label>
                            <input type="text" class="form-control" placeholder="Enter first name" name="first_name" value="{{ old('first_name') }}"
                                   onkeypress="return lettersOnly(event)">
                            @if ($errors->has('first_name'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('first_name') }}</div>
                            @endif
                        </div>
                        <div class="col-lg-6">
                            <label>Last Name:</label>
                            <input type="text" class="form-control" placeholder="Enter last name" name="last_name" value="{{ old('last_name') }}"
                                   onkeypress="return lettersOnly(event)">
                            @if ($errors->has('last_name'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('last_name') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Email:</label>
                            <input type="email" class="form-control" placeholder="Enter email" name="email" value="{{ old('email') }}" autocomplete="off">
                            @if ($errors->has('email'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('email') }}</div>
                            @endif
                        </div>
                        <div class="col-lg-6">
                            <label>Phone Number:</label>
                            <input type="text" class="form-control" placeholder="Enter phone number" name="phone_number" value="{{ old('phone_number') }}" 
                                   onkeypress="return isNumberKey(event)">
                            @if ($errors->has('phone_number'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('phone_number') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Password:</label>
                            <input type="password" class="form-control" placeholder="Enter Password" name="password" value="" autocomplete="off">
                            @if ($errors->has('password'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('password') }}</div>
                            @endif
                        </div>
                        <div class="col-lg-6">
                            <label>Confirm Password:</label>
                            <input type="password" class="form-control" placeholder="Enter confirm password" name="confirm_password" value="" autocomplete="off">
                            @if ($errors->has('confirm_password'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('confirm_password') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Address 1:</label>
                            <input type="text" class="form-control" placeholder="Enter Address 1" name="address_1" value="" autocomplete="off">
                            @if ($errors->has('address_1'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('address_1') }}</div>
                            @endif
                        </div>
                        <div class="col-lg-6">
                            <label>Address 2:</label>
                            <input type="text" class="form-control" placeholder="Enter Address 2" name="address_2" value="" autocomplete="off">
                            @if ($errors->has('address_2'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('address_2') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>City:</label>
                            <input type="text" class="form-control" placeholder="Enter City" name="city" value="" autocomplete="off">
                            @if ($errors->has('city'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('city') }}</div>
                            @endif
                        </div>
                        <div class="col-lg-6">
                            <label>State:</label>
                            <input type="text" class="form-control" placeholder="Enter State" name="state" value="" autocomplete="off">
                            @if ($errors->has('state'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('state') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Country:</label>
                            <select class="form-control" name="country">
                                <option>Select Country</option>
                                @foreach($Countries as $Countrie)
                                <option value="{{$Countrie->country_name}}">{{$Countrie->country_name}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('coutry'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('city') }}</div>
                            @endif
                        </div>
                        <div class="col-lg-6">
                            <label>Zip Code:</label>
                            <input type="text" class="form-control" placeholder="Enter Zip Code" name="zip_code" value="" autocomplete="off">
                            @if ($errors->has('zip_code'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('zip_code') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>User Role:</label>
                            <select name="user_role" class="form-control user_role">
                                <option value="customer">Customer</option>
                                <option value="trainer">Trainer</option>
                            </select>
                            @if ($errors->has('user_role'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->user_role('zip_code') }}</div>
                            @endif
                        </div>
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
                            @if ($errors->has('status'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('status') }}</div>
                            @endif
                        </div>
                        
                    </div>	 
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label class="">Featured:</label>
                            <div class="kt-radio-inline">
                                <label class="kt-radio kt-radio--solid">
                                    <input type="radio" name="is_feature"  value="1"> Yes
                                    <span></span>
                                </label>
                                <label class="kt-radio kt-radio--solid">
                                    <input type="radio" name="is_feature" checked="" value="0"> No
                                    <span></span>
                                </label>
                            </div>
                            @if ($errors->has('is_feature'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('is_feature') }}</div>
                            @endif
                            </div>
<!--                         <div class="col-lg-6 is_sponsored_div">
                            <label class="">Sponsored:</label>
                            <div class="kt-radio-inline">
                                <label class="kt-radio kt-radio--solid">
                                    <input type="radio" name="is_sponsored" class="is_sponsored"  value="1"> Yes
                                    <span></span>
                                </label>
                                <label class="kt-radio kt-radio--solid">
                                    <input type="radio" name="is_sponsored" class="is_sponsored" checked="" value="0"> No
                                    <span></span>
                                </label>
                            </div>
                            @if ($errors->has('is_sponsored'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('is_sponsored') }}</div>
                            @endif
                            </div>-->
                        <!-- <div class="col-lg-6 is_sponsored_div">
                            <label class="">Select Locations:</label>
                            <select class="form-control locationselect" multiple="" name="providerlocations[]" required="">
                                        
                                        @foreach($locations as $location)
                                        <option value="{{$location->id}}">{{$location->name}}</option>
                                        @endforeach
                                    </select>
                            @if ($errors->has('is_sponsored'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('is_sponsored') }}</div>
                            @endif
                            </div> -->
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
    $('.locationselect').select2({
            placeholder: "Select Locations",
//            maximumSelectionLength: 5,
//            language: { 
//                maximumSelected: function (e) {
//                    var t = "You can only select " + e.maximum + " Locations"; 
//                    return t;
//                }
//            }
        });
$(".reset").click(function () {
    $('.kt-form').find("input[type=text], textarea").val("");
});
if($('.user_role').val() == 'trainer') { 
        $('.is_sponsored_div').show();
    }else{
        $('.is_sponsored_div').hide(); 
    }
$(".user_role").change(function () {
    if($(this).val() == 'trainer') { 
        $('.is_sponsored_div').show();
        $('.locationselect').next(".select2-container").show();
    }else{
        $('.is_sponsored_div').hide(); 
        $('.locationselect').next(".select2-container").hide();
    }
});
</script>
@endsection