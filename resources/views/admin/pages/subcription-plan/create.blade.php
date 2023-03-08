@extends('admin.layouts.default')
@section('title', 'Subcription Plan Add')
@section('content')

<link href="{{ asset('/theme/vendors/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('/theme/vendors/general/tagify.css') }}" rel="stylesheet" type="text/css" />
<!-- begin:: Content Head -->
<div class="kt-subheader  kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">Add New Subcription Plan</h3>
        </div>
        <div class="kt-subheader__toolbar">
            <a href="{{route('subcriptionplan.index')}}" class="btn btn-default btn-bold"><i class="fa fa-arrow-left"></i> Back</a>
        </div>
    </div>
</div>
<!-- end:: Content Head -->

<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <div class="kt-portlet kt-portlet--mobile">
        <form method="POST" enctype="multipart/form-data" action="{{route('subcriptionplan.store')}}" class="kt-form kt-form--label-right" _lpchecked="1">
            <div class="kt-portlet__body">
                @csrf
                <div class="kt-portlet__body">

                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label>Subcription Plan:</label>
                            <!-- <input type="text" class="form-control" placeholder="Please enter your subcription plan" name="subcription_plan" value="{{ old('subcription_plan') }}"> -->
                            <select name="subcription_plan" id="subcription_plan" class="form-control">
                                <option value="">Please select your subcription plan</option>
                                <option value="monthly">Monthly</option>
                                <option value="yearly">Yearly</option>
                            </select>
                            <!-- <span class="form-text text-muted">Please enter your subcription plan</span> -->
                            @if ($errors->has('subcription_plan'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('subcription_plan') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label>Price:</label>
                            <input type="text" class="form-control amount" placeholder="Please enter your price" name="price" id="price-input" value="{{ old('price') }}">
                            <!-- <span class="form-text text-muted">Please enter your price</span> -->
                            @if ($errors->has('price'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('price') }}</div>
                            @endif
                        </div>
                        <!-- <div class="col-lg-6">
                            <label>Days:</label>
                            <input type="number" class="form-control" placeholder="Please enter your days" name="days" value="{{ old('days') }}" onkeypress="return isNumberKey(event)">
                            <span class="form-text text-muted">Please enter your days</span>
                            @if ($errors->has('days'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('days') }}</div>
                            @endif
                        </div> -->
                    </div>

                    <!-- <div class="form-group row">
                        <div class="col-lg-12">
                            <label>Description:</label>
                            <textarea class="form-control" name="description" id="description" rows="5">{{ old('description') }}</textarea>
                            <span class="form-text text-muted">Please enter your description</span>
                            @if ($errors->has('description'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('description') }}</div>
                            @endif
                        </div>
                    </div> -->

                    <!-- <div class="col-lg-3">
                        <label class="">Status:</label>
                        <div class="kt-radio-inline">
                            <label class="kt-radio kt-radio--solid">
                                <input type="radio" name="is_active" checked="" value="1"> Active
                                <span></span>
                            </label>
                            <label class="kt-radio kt-radio--solid">
                                <input type="radio" name="is_active" value="0"> InActive
                                <span></span>
                            </label>
                        </div>
                        <span class="form-text text-muted">Please select status</span>
                        @if ($errors->has('is_active'))
                        <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('is_active') }}</div>
                        @endif
                    </div> -->

                </div>
            </div>
            <div class="kt-portlet__foot">
                <div class="kt-form__actions">
                    <div class="row">
                        <div class="col-lg-23">
                            <button type="submit" name="submit" class="btn btn-primary">Save</button>
                            <button type="reset" class="btn btn-secondary reset">Reset</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- end:: Content -->
@stop

@section('pagescript')
<!--begin::Page Vendors(used by this page) -->
<script src="{{ asset('/theme/vendors/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
<script src="{{ asset('/theme/js/select2.js') }}" type="text/javascript"></script>
<!--end::Page Vendors -->
<script src="{{ asset('/theme/vendors/general/tagify.js') }}" type="text/javascript"></script>
<script src="{{ asset('/theme/vendors/general/tagify.min.js') }}" type="text/javascript"></script>
<script>
    $(document).ready(function() {
        $(".reset").click(function() {
            $('.kt-form').find("input[type=text], textarea").val("");
        });
        var input = document.querySelector('input[name=tags]');
        // init Tagify script on the above inputs
        new Tagify(input)
    });

    $('#price-input').keypress(function (e) {    
    
        var charCode = (e.which) ? e.which : event.keyCode    

        
        if (String.fromCharCode(charCode).match(/[^0-9]/g)){

            return false;           
        }             

    }); 

    $("#price-input").keyup(function(){
        var value = $(this).val();
        value = value.replace(/^(0*)/,"");
        $(this).val(value);
        
    });
</script>
@endsection