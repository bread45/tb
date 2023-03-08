@extends('admin.layouts.default')
@section('title', 'Location Add')
@section('content')


<!-- begin:: Content Head -->
<div class="kt-subheader  kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">Add Location</h3>
        </div>
        <div class="kt-subheader__toolbar">
            <a href="{{route('locations.index')}}" class="btn btn-default btn-bold"><i class="fa fa-arrow-left"></i> Back</a>
        </div>
    </div>
</div>
<!-- end:: Content Head -->

<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__body">
            <form method="POST" enctype="multipart/form-data" action="{{route('locations.store')}}" class="kt-form kt-form--label-right" _lpchecked="1">
                @csrf 
                <div class="kt-portlet__body">
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Location Name:</label>
                            <input type="text" class="form-control" placeholder="Enter Location name" name="name" value="{{ old('name') }}"
                                    >
                            @if ($errors->has('name'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('name') }}</div>
                            @endif
                        </div>
                        <div class="col-lg-6">
                            <label>Latitude:</label>
                            <input type="text" class="form-control" placeholder="Enter latitude" name="latitude" value="{{ old('latitude') }}"
                                   >
                            @if ($errors->has('latitude'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('latitude') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Longitude:</label>
                            <input type="text" class="form-control" placeholder="Enter Longitude" name="longitude" value="{{ old('longitude') }}"
                                   >
                            @if ($errors->has('longitude'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('longitude') }}</div>
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

<script>
    $(".reset").click(function () {
        $('.kt-form').find("input[type=text], textarea").val("");
    });
</script>
@endsection