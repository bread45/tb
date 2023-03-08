@extends('admin.layouts.default')
@section('title', 'Page Add')
@section('content')

<link href="{{ asset('/theme/vendors/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />

<!-- begin:: Content Head -->
<div class="kt-subheader  kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">Add New Pages</h3>
<!--            <span class="kt-subheader__separator kt-subheader__separator--v"></span>-->

        </div>
        <div class="kt-subheader__toolbar">
            <a href="{{route('cms_pages.index')}}" class="btn btn-default btn-bold"><i class="fa fa-arrow-left"></i> Back</a>
        </div>
    </div>
</div>
<!-- end:: Content Head -->

<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__body">
            <form method="POST" enctype="multipart/form-data" action="{{route('cms_pages.store')}}" class="kt-form kt-form--label-right" _lpchecked="1">
                @csrf
                <div class="kt-portlet__body">
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Title :</label>
                            <input type="text" class="form-control" placeholder="Enter title" name="title" value="{{ old('title') }}">
                            <span class="form-text text-muted">Please enter your title</span>
                            @if ($errors->has('title'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('title') }}</div>
                            @endif
                        </div>
                        <div class="col-lg-6">
                            <label>Sub Title :</label>
                            <input type="text" class="form-control" placeholder="Enter sub title" name="sub_title_text" value="{{ old('sub_title_text') }}">
                            <span class="form-text text-muted">Please enter blog sub title</span>
                            @if ($errors->has('sub_title_text'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('sub_title_text') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Short Description:</label>
                            <textarea name="short_description" class="form-control ckeditor">{{ old('short_description') }}</textarea>
                            <span class="form-text text-muted">Please enter your short description</span>
                            @if ($errors->has('short_description'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('short_description') }}</div>
                            @endif
                        </div>
                        <div class="col-lg-6">
                            <label>Description:</label>
                            <textarea name="description" class="form-control ckeditor" id="">{{ old('description') }}</textarea>
                            <span class="form-text text-muted">Please enter your description</span>
                            @if ($errors->has('description'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('description') }}</div>
                            @endif
                        </div>

                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Meta Title :</label>
                            <input type="text" class="form-control" placeholder="Enter Meta Title" name="meta_title" value="{{ old('meta_title') }}">
                            <span class="form-text text-muted">Please enter meta title</span>
                            @if ($errors->has('meta_title'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('meta_title') }}</div>
                            @endif
                        </div>
                        <div class="col-lg-6">
                            <label>Meta Keywords :</label>
                            <input type="text" class="form-control" placeholder="Enter Meta keywords" name="meta_keywords" value="{{ old('meta_keywords') }}">
                            <span class="form-text text-muted">Please enter meta keywords</span>
                            @if ($errors->has('meta_keywords'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('meta_keywords') }}</div>
                            @endif
                        </div>

                    </div>

                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Meta Description:</label>
                            <textarea name="meta_description" class="form-control ckeditor" id="">{{ old('meta_description') }}</textarea>
                            <span class="form-text text-muted">Please enter your meta description</span>
                            @if ($errors->has('meta_description'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('meta_description') }}</div>
                            @endif
                        </div>
                        <div class="col-lg-6">
                            <label>Image:</label>
                            <div></div>
                            <div class="custom-file">
                                <input type="file" name="banner_image" class="custom-file-input" id="customFile">
                                <label class="custom-file-label text-left" for="customFile">Choose file</label>
                            </div>
                            <span class="form-text text-muted">Please Upload image</span>
                            @if ($errors->has('banner_image'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('banner_image') }}</div>
                            @endif
                        </div>

                    </div>	 

                    <div class="form-group row">
<!--                        <div class="col-lg-6">
                            <label>Order By:</label>
                            <input type="text" onkeypress="return isNumberKey();" class="form-control" placeholder="Enter order" name="order_by" value="{{ old('order_by') }}">
                            <span class="form-text text-muted">Please enter your order</span>
                            @if ($errors->has('order_by'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('order_by') }}</div>
                            @endif
                        </div>-->
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
                                var KTCkeditor={
    init:function()
    {
        ClassicEditor.create(document.querySelector("#kt-ckeditor-1")).then(e=>{console.log(e)}).catch(e=>{console.error(e)}),
        ClassicEditor.create(document.querySelector("#kt-ckeditor-2")).then(e=>{console.log(e)}).catch(e=>{console.error(e)}),
        ClassicEditor.create(document.querySelector("#kt-ckeditor-3")).then(e=>{console.log(e)}).catch(e=>{console.error(e)}),
        ClassicEditor.create(document.querySelector("#kt-ckeditor-4")).then(e=>{console.log(e)}).catch(e=>{console.error(e)}),
        ClassicEditor.create(document.querySelector("#kt-ckeditor-5")).then(e=>{console.log(e)}).catch(e=>{console.error(e)})
        }
    };
jQuery(document).ready(function(){KTCkeditor.init()});

</script>
@endsection