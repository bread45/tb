@extends('admin.layouts.default')
@section('title', 'Service Add')
@section('content')

<link href="{{ asset('/theme/vendors/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('/theme/vendors/general/tagify.css') }}" rel="stylesheet" type="text/css" />
<!-- begin:: Content Head -->
<div class="kt-subheader  kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">Add New Service</h3>
        </div>
        <div class="kt-subheader__toolbar">
            <a href="{{route('services.index')}}" class="btn btn-default btn-bold"><i class="fa fa-arrow-left"></i> Back</a>
        </div>
    </div>
</div>
<!-- end:: Content Head -->

<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__body">
            <form method="POST" enctype="multipart/form-data" action="{{route('services.store')}}" class="kt-form kt-form--label-right" _lpchecked="1">
                @csrf
                <div class="kt-portlet__body">
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Name:</label>
                            <input type="text" class="form-control" placeholder="Enter name" name="name" value="{{ old('name') }}">
                            <span class="form-text text-muted">Please enter your service name</span>
                            @if ($errors->has('name'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('name') }}</div>
                            @endif
                        </div>
                        <div class="col-lg-6">
                            <label>Select Category:</label>
                            <div></div>
                            <select class="custom-select form-control" name="category_id">
                                <option value="">Please select category</option>
                                @foreach($categories as $cat)
                                <option value="{{$cat->id}}" {{($cat->id == old('category_id')) ? 'selected' : ''}}>{{$cat->name}}</option>
                                @endforeach
                            </select>
                            <span class="form-text text-muted">Please select category</span>
                            @if ($errors->has('category_id'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('category_id') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Select Spot Supplier:</label>
                            <div></div>
                            <select class="custom-select form-control" name="spot_supplier_id">
                                <option value="">Please select category</option>
                                @foreach($spot_supplier as $spot_suppliers)
                                <option value="{{$spot_suppliers->id}}" {{($spot_suppliers->id == old('spot_supplier_id')) ? 'selected' : ''}}>{{$spot_suppliers->business_name}}</option>
                                @endforeach
                            </select>
                            <span class="form-text text-muted">Please select supplier</span>
                            @if ($errors->has('spot_supplier_id'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('spot_supplier_id') }}</div>
                            @endif
                        </div>
                        <div class="col-lg-6">
                            <label>Image:</label>
                            <div></div>
                            <div class="custom-file">
                                <input type="file" name="image" class="custom-file-input" id="customFile">
                                <label class="custom-file-label text-left" for="customFile">Choose file</label>
                            </div>
                            <span class="form-text text-muted">Please Upload service image</span>
                            @if ($errors->has('image'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('image') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label>Short Description:</label>
                            <div class="kt-input-icon">
                                <textarea name="sort_description" class="form-control ckeditor">{{ old('sort_description') }}</textarea>
                            </div>
                            <span class="form-text text-muted">Please enter your sort description</span>
                        </div>

                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Description:</label>
                            <div class="kt-input-icon">
                                <textarea name="description" class="form-control ckeditor">{{ old('description') }}</textarea>
                            </div>
                            <span class="form-text text-muted">Please enter your description</span>
                        </div>
                        <div class="col-lg-6">
                            <label>About:</label>
                            <div class="kt-input-icon">
                                <textarea name="about" class="form-control ckeditor">{{ old('about') }}</textarea>
                            </div>
                            <span class="form-text text-muted">Please enter your about</span>
                        </div>
                    </div>
                    <div class="form-group row">

                        <div class="col-lg-6">
                            <label>Service Tags:</label>
                            <input type="text" class="form-control" name="tags" value="100% Free Service, Quick & Simple, No Commission">
                            <span class="form-text text-muted">Please enter tag lines</span>
                        </div>
                        <div class="col-lg-3">
                            <label>Is Featured ? :</label>
                            <div class="kt-radio-inline">
                                <label class="kt-radio kt-radio--solid">
                                    <input type="radio" name="is_popular" value="1"> On
                                    <span></span>
                                </label>
                                <label class="kt-radio kt-radio--solid">
                                    <input type="radio" name="is_popular" checked="" value="0"> Off
                                    <span></span>
                                </label>
                            </div>
                            <span class="form-text text-muted">Please select Is Featured Active</span>
                        </div>
                        <div class="col-lg-3">
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
                    <!-- <div class="form-group row">
                        <div class="col-lg-12">
                            <label>Select Dynamic Steps:</label>
                            <div></div>
                            @foreach($requeststeps as $key=>$value)
                            <label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
                                <input name="dynamic_step[]" value="{{ $value->id }}" type="checkbox">{{ $value->title }}
                                <span></span>
                            </label>
                            &nbsp; &nbsp;
                            @endforeach
                        </div>
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
</div>
<!-- end:: Content -->
@stop

@section('pagescript')
<!--begin::Page Vendors(used by this page) -->
<script src="{{ asset('/theme/vendors/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
<!--end::Page Vendors -->
<script src="{{ asset('/theme/vendors/general/tagify.js') }}" type="text/javascript"></script>
<script src="{{ asset('/theme/vendors/general/tagify.min.js') }}" type="text/javascript"></script>
<script>
    $(".reset").click(function() {
        $('.kt-form').find("input[type=text], textarea").val("");
    });
    var input = document.querySelector('input[name=tags]');

    // init Tagify script on the above inputs
    new Tagify(input)
</script>
@endsection