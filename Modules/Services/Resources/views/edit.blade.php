@extends('admin.layouts.default')
@section('title', 'Services Edit')
@section('content')
<link href="{{ asset('/theme/vendors/general/tagify.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('/theme/vendors/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
<!-- begin:: Content Head -->
<div class="kt-subheader  kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">Edit Service</h3>
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
                <input type="hidden" name="service_id" value="{{$service->id}}" />
                <div class="kt-portlet__body">
                    <div class="form-group row">

                        <div class="col-lg-6">
                            <label>Name:</label>
                            <input type="text" class="form-control" placeholder="Enter name" name="name" value="{{ isset($service) ? $service->name : old('name') }}">
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
                                <option value="{{$cat->id}}" {{((isset($service) && $service->category_id == $cat->id) || $cat->id == old('category_id')) ? 'selected' : ''}}>{{$cat->name}}</option>
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
                                <option value="{{$spot_suppliers->id}}" {{((isset($service) && $service->spot_supplier_id == $spot_suppliers->id) || $spot_suppliers->id == old('spot_supplier_id')) ? 'selected' : ''}}>{{$spot_suppliers->business_name}}</option>
                                @endforeach
                            </select>
                            <span class="form-text text-muted">Please select supplier</span>
                            @if ($errors->has('spot_supplier_id'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('spot_supplier_id') }}</div>
                            @endif
                        </div>
                        <div class="col-lg-6">
                            <label class="">Image:</label>
                            <div class="kt-input-icon">
                                <input name="image" type="file">
                            </div>
                            <span class="form-text text-muted">Please Upload category image</span>
                            @if ($errors->has('image'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('image') }}</div>
                            @endif
                            @if($service->image)
                            <a data-fancybox="gallery" href="{{url('sitebucket/services/'. $service->image)}}"><img style="width:50px;" src="{{url('sitebucket/services/'. $service->image)}}" alt="service image"/></a>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label>Short Description:</label>
                            <div class="kt-input-icon">
                                <textarea name="sort_description" class="form-control ckeditor">{{ isset($service) ? $service->sort_description : old('sort_description') }}</textarea>
                            </div>
                            <span class="form-text text-muted">Please enter your sort description</span>
                        </div>

                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Description:</label>
                            <div class="kt-input-icon">
                                <textarea name="description" class="form-control ckeditor">{{ (isset($service) && $service->description) ? $service->description : old('description') }}</textarea>
                            </div>
                            <span class="form-text text-muted">Please enter your description</span>
                        </div>

                        <div class="col-lg-6">
                            <label>About:</label>
                            <div class="kt-input-icon">
                                <textarea name="about" class="form-control ckeditor">{{ (isset($service) && $service->about) ? $service->about : old('about') }}</textarea>
                            </div>
                            <span class="form-text text-muted">Please enter your about</span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Service Tags:</label>
                            <input type="text" class="form-control" name="tags" value="{{ (isset($edittags)) ? $edittags : old('tags') }}">
                            {{-- <input type="text" class="form-control" name="tags" value="100% Free Service, Quick & Simple, No Commission"> --}}
                            <span class="form-text text-muted">Please enter tag lines</span>
                        </div>
                        <div class="col-lg-3">
                            <label>Is Popular ? :</label>
                            <div class="kt-radio-inline">
                                <label class="kt-radio kt-radio--solid">
                                    <input type="radio" name="is_featured" @if(isset($service) && $service->is_featured=='1') checked="" @endif value="1"> On
                                    <span></span>
                                </label>
                                <label class="kt-radio kt-radio--solid">
                                    <input type="radio" name="is_featured" @if(isset($service) && $service->is_featured=='0') checked="" @endif value="0"> Off
                                    <span></span>
                                </label>
                            </div>
                            <span class="form-text text-muted">Please select Is Popular Active</span>
                        </div>
                        <div class="col-lg-3">
                            <label class="">Status:</label>
                            <div class="kt-radio-inline">
                                <label class="kt-radio kt-radio--solid">
                                    <input type="radio" name="status" @if(isset($service) && $service->status=='active') checked="" @endif value="active"> Active
                                    <span></span>
                                </label>
                                <label class="kt-radio kt-radio--solid">
                                    <input type="radio" name="status" @if(isset($service) && $service->status=='inactive') checked="" @endif value="inactive"> InActive
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
                            @if(count($requeststeps) <= 0) 
                            <span class="form-text text-muted">
                                Dynamic steps question not available on this service.
                            </span>
                            @endif
                            <div class="kt-checkbox-list">
                            @foreach($requeststeps as $key=>$value)
                            <label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
                                <input @if(in_array($value->id,$stepselected)) checked @endif name="dynamic_step[]" value="{{ $value->id }}" type="checkbox">{{ $value->question }}
                                <span></span>
                            </label>
                            @endforeach
                            </div>
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
<script src="{{ asset('/theme/vendors/general/tagify.js') }}" type="text/javascript"></script>
<script src="{{ asset('/theme/vendors/general/tagify.min.js') }}" type="text/javascript"></script>
<!--end::Page Vendors -->
<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
<script>
    $(".reset").click(function() {
        $('.kt-form').find("input[type=text], textarea").val("");
    });
    var input = document.querySelector('input[name=tags]');

    // init Tagify script on the above inputs
    new Tagify(input)
</script>

@endsection