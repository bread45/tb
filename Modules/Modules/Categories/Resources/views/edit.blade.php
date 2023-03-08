@extends('admin.layouts.default')
@section('title', 'Categories Edit')
@section('content')

<link href="{{ asset('/theme/vendors/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
<!-- begin:: Content Head -->
<div class="kt-subheader  kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">Edit Categories</h3>
<!--            <span class="kt-subheader__separator kt-subheader__separator--v"></span>-->

        </div>
        <div class="kt-subheader__toolbar">
            <a href="{{route('categories.index')}}" class="btn btn-default btn-bold"><i class="fa fa-arrow-left"></i> Back</a>
        </div>
    </div>
</div>
<!-- end:: Content Head -->

<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__body">
            <form method="POST" enctype="multipart/form-data" action="{{route('categories.store')}}" class="kt-form kt-form--label-right" _lpchecked="1">
                @csrf
                <input type="hidden" name="category_id" value="{{$categories->id}}"/>
                <div class="kt-portlet__body">
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Name:</label>
                            <input type="text" class="form-control" placeholder="Enter name" name="name" value="{{ isset($categories) ? $categories->name : old('name') }}">
                            <span class="form-text text-muted">Please enter your category name</span>
                            @if ($errors->has('name'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('name') }}</div>
                            @endif
                        </div>
                        <div class="col-lg-6">
                            <label>Image:</label>
                            <div class="custom-file">
                                <input type="file" name="image" class="custom-file-input" id="customFile">
                                <label class="custom-file-label text-left" for="customFile">Choose file</label>
                            </div>
                            <span class="form-text text-muted">Please Upload category image</span>
                            @if ($errors->has('image'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('image') }}</div>
                            @endif
                            @if($categories->image)
                            <a data-fancybox="gallery" href="{{url('sitebucket/categories/'. $categories->image)}}"><img style="width:50px;" src="{{url('sitebucket/categories/'. $categories->image)}}" alt="category image"/></a>
                            @endif
                        </div>

                    </div>	 
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label>Description:</label>
                            <div class="kt-input-icon">
                                <textarea name="description" class="form-control ckeditor">{{ isset($categories) ? $categories->description : old('description') }}</textarea>
                            </div>
                            <span class="form-text text-muted">Please enter your description</span>
                        </div>

                    </div>	 
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label class="">Status:</label>
                            <div class="kt-radio-inline">
                                <label class="kt-radio kt-radio--solid">
                                    <input type="radio" name="status" @if(isset($categories) && $categories->status=='active') checked="" @endif value="active"> Active
                                           <span></span>
                                </label>
                                <label class="kt-radio kt-radio--solid">
                                    <input type="radio" name="status" @if(isset($categories) && $categories->status=='inactive') checked="" @endif value="inactive"> InActive
                                           <span></span>
                                </label>
                            </div>
                            <span class="form-text text-muted">Please select status</span>
                            @if ($errors->has('status'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('status') }}</div>
                            @endif
                        </div>
                        <div class="col-lg-6">
                            <label>Is Popular ? :</label>
                            <div class="kt-radio-inline">
                                <label class="kt-radio kt-radio--solid">
                                    <input type="radio" name="is_popular" @if(isset($categories) && $categories->is_popular=='1') checked="" @endif value="1"> On
                                           <span></span>
                                </label>
                                <label class="kt-radio kt-radio--solid">
                                    <input type="radio" name="is_popular" @if(isset($categories) && $categories->is_popular=='0') checked="" @endif value="0"> Off
                                           <span></span>
                                </label>
                            </div>
                            <span class="form-text text-muted">Please select Is Popular Active</span>
                        </div>
                    </div>	                
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
<!--end::Page Vendors -->
<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
<script>
$(".reset").click(function () {
    $('.kt-form').find("input[type=text], textarea").val("");
});
</script>
@endsection