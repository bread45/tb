@extends('admin.layouts.default')
@section('title', 'Subscription Plan Edit')
@section('content')
<link href="{{ asset('/theme/vendors/general/tagify.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('/theme/vendors/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
<!-- begin:: Content Head -->
<div class="kt-subheader  kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">Subscription Plan</h3>
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
        <div class="kt-portlet__body">
            <form method="POST" enctype="multipart/form-data" action="{{route('nextsections.store')}}" class="kt-form kt-form--label-right" _lpchecked="1">
                @csrf

                <input type="hidden" name="next_section_id" value="{{$NextStepsSections->id}}" />

                <div class="kt-portlet__body">

                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label>Section Title :</label>
                            <input type="text" class="form-control amount" placeholder="Please enter section title" name="section_title" id="section_title" value="{{ isset($NextStepsSections) ? $NextStepsSections->section_title : old('section_title') }}">
                            <!-- <span class="form-text text-muted">Please enter your price</span> -->
                            @if ($errors->has('section_title'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('section_title') }}</div>
                            @endif
                        </div>
                        <div class="col-lg-6">
                            <label>Slider Title:</label>
                            <input type="text" class="form-control" placeholder="Please enter slider title" name="slider_title" id="slider_title" value="{{ isset($NextStepsSections) ? $NextStepsSections->slider_title : old('slider_title') }}" >
                            <!-- <span class="form-text text-muted">Month</span> -->
                            @if ($errors->has('slider_title'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('slider_title') }}</div>
                            @endif
                        </div>
                    </div>

                    <!-- <div class="form-group row">
                        <div class="col-lg-12">
                            <label>Description:</label>
                            <textarea class="form-control" name="description" id="description" rows="5">{{ isset($subscriptionPlan) ? $subscriptionPlan->description : old('description') }}</textarea>
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
                                <input type="radio" name="is_active" @if(isset($subscriptionPlan) && $subscriptionPlan->is_active=='1') checked="" @endif value="1"> Active
                                <span></span>
                            </label>
                            <label class="kt-radio kt-radio--solid">
                                <input type="radio" name="is_active" @if(isset($subscriptionPlan) && $subscriptionPlan->is_active=='0') checked="" @endif value="0"> InActive
                                <span></span>
                            </label>
                        </div>
                        <span class="form-text text-muted">Please select status</span>
                        @if ($errors->has('is_active'))
                        <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('is_active') }}</div>
                        @endif
                    </div> -->

                </div>
                <div class="kt-portlet__foot">
                    <div class="kt-form__actions">
                        <div class="row">
                            <div class="col-lg-6">
                                <button type="submit" name="submit" class="btn btn-primary">Save</button>
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
<script src="{{ asset('/theme/js/select2.js') }}" type="text/javascript"></script>
<script src="{{ asset('/theme/vendors/general/tagify.js') }}" type="text/javascript"></script>
<script src="{{ asset('/theme/vendors/general/tagify.min.js') }}" type="text/javascript"></script>
<!--end::Page Vendors -->
<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
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
        var charCode = (e.which) ? e.which : event.keyCode;  
        if (String.fromCharCode(charCode).match(/[^\d{0,5}(\.\d{1,2})?$]/g)){
            return false;           
        }             

    }); 


    $('#price-input').on('keypress',function (event) {
    if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
        event.preventDefault();
    }
    var input = $(this).val();
    if ((input.indexOf('.') != -1) && (input.substring(input.indexOf('.')).length > 2)) {
        event.preventDefault();
    }
});

    $('#free_trial_months').keyup(function () {    
    
        var month = parseInt($('#free_trial_months').val());       
        if (month > 12 || month < 1) {
          alert('Please enter correct month');
          $('#free_trial_months').val('');
          return false;
        }    

    }); 

    // $("#price-input").keyup(function(){
    //     var value = $(this).val();
    //     value = value.replace(/^(0*)/,"");
    //     $(this).val(value);
        
    // });
</script>

@endsection