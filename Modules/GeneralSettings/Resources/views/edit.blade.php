@extends('admin.layouts.default')
@section('title', 'Setting Edit')
@section('content')

<link href="{{ asset('/theme/vendors/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />

<!-- begin:: Content Head -->
<div class="kt-subheader  kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">Setting Page</h3>
<!--            <span class="kt-subheader__separator kt-subheader__separator--v"></span>-->

        </div>
        <div class="kt-subheader__toolbar">
            <a href="{{route('general_setting.index')}}" class="btn btn-default btn-bold"><i class="fa fa-arrow-left"></i> Back</a>
        </div>
    </div>
</div>
<!-- end:: Content Head -->

<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__body">
            <form method="POST" enctype="multipart/form-data" action="{{route('general_setting.store')}}" class="kt-form kt-form--label-right" _lpchecked="1">
                @csrf
                <input type="hidden" name="general_setting_id" value="{{$editdata->id}}"/>
                <div class="kt-portlet__body">
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Key :</label>
                            <input type="text" class="form-control" placeholder="Enter key" name="attr_key" readonly="" value="{{ (isset($editdata) && $editdata->attr_key) ? $editdata->attr_key : old('attr_key') }}">
                            <span class="form-text text-muted">Please enter key</span>
                            @if ($errors->has('attr_key'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('attr_key') }}</div>
                            @endif
                        </div>
                        <div class="col-lg-6">
                            <label>Value:</label>
                            <textarea name="attr_value" class="form-control">{{ (isset($editdata) && $editdata->attr_value) ? $editdata->attr_value: old('attr_value') }}</textarea>
                            <span class="form-text text-muted">Please enter Value</span>
                            @if ($errors->has('attr_value'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('attr_value') }}</div>
                            @endif
                        </div>
                    </div>	 
                    <div class="form-group row">

                        <div class="col-lg-6">
                            <label class="">Status:</label>
                            <div class="kt-radio-inline">
                                <label class="kt-radio kt-radio--solid">
                                    <input type="radio" name="status" @if((isset($editdata) && $editdata->status=='active')) checked="" @endif value="active"> Active
                                           <span></span>
                                </label>
                                <label class="kt-radio kt-radio--solid">
                                    <input type="radio" name="status" @if((isset($editdata) && $editdata->status=='inactive')) checked="" @endif value="inactive"> InActive
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
                <div class="kt-portlet__foot">
                    <div class="kt-form__actions">
                        <div class="row">
                            <div class="col-lg-6">
                                <button type="submit" name="submit" class="btn btn-primary">Save</button>
                                <button type="reset" class="btn btn-secondary reset">Cancel</button>
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