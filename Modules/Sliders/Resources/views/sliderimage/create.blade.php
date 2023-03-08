@extends('admin.layouts.default')
@section('title', 'Slider Image Add')
@section('content')

<link href="{{ asset('/theme/vendors/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />

<!-- begin:: Content Head -->
<div class="kt-subheader  kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">Add New Slider Image</h3>
<!--            <span class="kt-subheader__separator kt-subheader__separator--v"></span>-->

        </div>
        <div class="kt-subheader__toolbar">
            <a href="{{route('sliderimage.index')}}" class="btn btn-default btn-bold"><i class="fa fa-arrow-left"></i> Back</a>
        </div>
    </div>
</div>
<!-- end:: Content Head -->

<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__body">
            <form method="POST" enctype="multipart/form-data" action="{{route('sliderimage.store')}}" class="kt-form kt-form--label-right" _lpchecked="1">
                @csrf
                <div class="kt-portlet__body">
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Image Name:</label>
                            <input type="text" class="form-control" placeholder="Enter Image name" name="name" value="{{ old('name') }}">
                            <span class="form-text text-muted">Please enter your image name</span>
                            @if ($errors->has('name'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('name') }}</div>
                            @endif
                        </div>
                        <div class="col-lg-6">
                            <label>Slider Select:</label>
                            <select class="form-control" name="slider_id" id="">
                                <option value="">Select Slider</option>
                                @if(!$createdata->isEmpty())
                                @foreach($createdata as $key=>$value)
                                <option @if(old('slider_id')==$value->id) selected @endif value="{{$value->id}}">{{$value->name}}</option>
                                @endforeach
                                @endif
                            </select>
                            <span class="form-text text-muted">Please select slider</span>
                            @if ($errors->has('slider_id'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('slider_id') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <!--                        <div class="col-lg-6">
                                                    <label>Short Description:</label>
                                                    <input type="text" class="form-control" placeholder="Enter Slider Short Description" name="shortdescription" value="{{ old('shortdescription') }}">
                                                    <span class="form-text text-muted">Please enter your Short description</span>
                                                    @if ($errors->has('shortdescription'))
                                                    <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('shortdescription') }}</div>
                                                    @endif
                                                </div>-->
                        <div class="col-lg-6">
                            <label>Image Description:</label>
                            <textarea name="description" class="form-control">{{ old('description') }}</textarea>
                            <span class="form-text text-muted">Please enter your image description</span>
                            @if ($errors->has('description'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('description') }}</div>
                            @endif
                        </div>

                        <div class="col-lg-6">
                            <label>Image:</label>
                            <div></div>
                            <div class="custom-file">
                                <input type="file" name="image" class="custom-file-input" id="customFile">
                                <label class="custom-file-label text-left" for="customFile">Choose file</label>
                            </div>
                            <span class="form-text text-muted">Please upload image</span>
                            @if ($errors->has('image'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('image') }}</div>
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