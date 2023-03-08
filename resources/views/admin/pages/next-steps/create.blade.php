@extends('admin.layouts.default')
@section('title', 'Next Steps Add')
@section('content')

<link href="{{ asset('/theme/vendors/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('/theme/vendors/general/tagify.css') }}" rel="stylesheet" type="text/css" />
<!-- begin:: Content Head -->
<div class="kt-subheader  kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">Add New Steps Slide</h3>
        </div>
        <div class="kt-subheader__toolbar">
            <a href="{{route('nextsteps.index')}}" class="btn btn-default btn-bold"><i class="fa fa-arrow-left"></i> Back</a>
        </div>
    </div>
</div>
<!-- end:: Content Head -->

<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <div class="kt-portlet kt-portlet--mobile">
        <form method="POST" enctype="multipart/form-data" action="{{route('nextsteps.store')}}" class="kt-form kt-form--label-right" _lpchecked="1">
            <div class="kt-portlet__body">
                @csrf
                <div class="kt-portlet__body">
                @if($sections)
                <div class="form-group row">
                        <div class="col-lg-12">
                            <label>Section:</label>
                            <!-- <input type="text" class="form-control" placeholder="Please enter your subcription plan" name="subcription_plan" value="{{ old('subcription_plan') }}"> -->
                            <select name="slider_section" id="slider_section" class="form-control">
                                <option value="">Please select slider section</option>
                                @foreach ($sections as $section)
                                <option value="{{ $section->id }}">{{ $section->section_title }}</option>
                                @endforeach
                            </select>
                            <!-- <span class="form-text text-muted">Please enter your subcription plan</span> -->
                            @if ($errors->has('slider_section'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('slider_section') }}</div>
                            @endif
                        </div>
                    </div>
                    @endif
                <!-- <div class="form-group row">
                        <div class="col-lg-12">
                            <label>Next Step Icon :</label>
                            <input type="file" class="form-control" id="steps_icon" name="steps_icon">
                            @if ($errors->has('steps_icon'))
                            <div style="display: block;" id="title-error" class="error invalid-feedback">{{ $errors->first('steps_icon') }}</div>
                            @endif
                        </div>
                    </div> -->

                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label>Next Step Title :</label>
                            <input type="text" class="form-control" placeholder="Please enter next steps title" name="steps_title" value="{{ old('steps_title') }}" maxlength="35">
                            <!-- <span class="form-text text-muted">Please enter your subcription plan</span> -->
                            @if ($errors->has('steps_title'))
                            <div style="display: block;" id="title-error" class="error invalid-feedback">{{ $errors->first('steps_title') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label>Next Step Content :</label>
                            <textarea rows="5" class="form-control" placeholder="Please enter next steps content" name="steps_content" maxlength="150">{{ old('steps_content') }}</textarea> 
                            <!-- <span class="form-text text-muted">Please enter your subcription plan</span> -->
                            @if ($errors->has('steps_content'))
                            <div style="display: block;" id="title-error" class="error invalid-feedback">{{ $errors->first('steps_content') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Button Title :</label>
                            <input type="text" class="form-control" placeholder="Please enter button title" name="steps_button_title" value="{{ old('steps_button_title') }}"  maxlength="18">
                            <!-- <span class="form-text text-muted">Please enter your subcription plan</span> -->
                            @if ($errors->has('steps_button_title'))
                            <div style="display: block;" id="title-error" class="error invalid-feedback">{{ $errors->first('steps_button_title') }}</div>
                            @endif
                        </div>
                        <div class="col-lg-6">
                            <label>Button Hyperlink :</label>
                            <input type="url" class="form-control" placeholder="Please enter button hyperlink" name="steps_button_url" value="{{ old('steps_button_url') }}">
                            <!-- <span class="form-text text-muted">Please enter your subcription plan</span> -->
                            @if ($errors->has('steps_button_url'))
                            <div style="display: block;" id="title-error" class="error invalid-feedback">{{ $errors->first('steps_button_url') }}</div>
                            @endif
                        </div>
                    </div>
                    <hr />
                    <h5>Next Steps Popup Details</h5><br />
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label>Modal Button Title :</label>
                            <input type="text" class="form-control" placeholder="Please enter modal button title" name="steps_modal_button_title" value="{{ old('steps_modal_button_title') }}" maxlength="18">
                            <!-- <span class="form-text text-muted">Please enter your subcription plan</span> -->
                            @if ($errors->has('steps_modal_button_title'))
                            <div style="display: block;" id="title-error" class="error invalid-feedback">{{ $errors->first('steps_modal_button_title') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label>Modal Title :</label>
                            <input type="text" class="form-control" placeholder="Please enter next steps modal title" name="steps_modal_title" value="{{ old('steps_modal_title') }}">
                            <!-- <span class="form-text text-muted">Please enter your subcription plan</span> -->
                            @if ($errors->has('steps_modal_title'))
                            <div style="display: block;" id="title-error" class="error invalid-feedback">{{ $errors->first('steps_modal_title') }}</div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label>Modal Content :</label>
                            <textarea rows="5" class="form-control ckeditor" id="message-input" placeholder="Please enter next steps modal content" name="steps_modal_content">{{ old('steps_modal_title') }}</textarea> 
                            <!-- <span class="form-text text-muted">Please enter your subcription plan</span> -->
                            @if ($errors->has('steps_modal_title'))
                            <div style="display: block;" id="title-error" class="error invalid-feedback">{{ $errors->first('steps_modal_title') }}</div>
                            @endif
                        </div>
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
        </div>
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
<script src="//cdn.ckeditor.com/4.13.0/full/ckeditor.js"></script>
<script>

CKEDITOR.config.height = '300px';   // CSS unit (percent).
    CKEDITOR.config.enterMode = CKEDITOR.ENTER_BR;
    CKEDITOR.config.shiftEnterMode = CKEDITOR.ENTER_BR;
   
    CKEDITOR.config.toolbar = 'Basic';
   
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