@extends('admin.layouts.default')
@section('title', 'Blog Edit')
@section('content')

<link href="{{ asset('/theme/vendors/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />

<!-- begin:: Content Head -->
<div class="kt-subheader  kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">Edit Blog</h3>
<!--            <span class="kt-subheader__separator kt-subheader__separator--v"></span>-->

        </div>
        <div class="kt-subheader__toolbar">
            <a href="{{route('blogs.index')}}" class="btn btn-default btn-bold"><i class="fa fa-arrow-left"></i> Back</a>
        </div>
    </div>
</div>
<!-- end:: Content Head -->

<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__body">
            <form method="POST" enctype="multipart/form-data" action="{{route('blogs.store')}}" class="kt-form kt-form--label-right" _lpchecked="1">
                @csrf
                <input type="hidden" name="blog_id" value="{{$editdata->id}}"/>
                <div class="kt-portlet__body">
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Title:</label>
                            <input type="text" class="form-control" placeholder="Enter blog name" name="title" value="{{ (isset($editdata) && $editdata->title) ? $editdata->title : old('title') }}">
                            <span class="form-text text-muted">Please enter your blog name</span>
                            @if ($errors->has('title'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('title') }}</div>
                            @endif
                        </div>
                        <div class="col-lg-6">
                            <label>Sub Title:</label>
                            <input type="text" class="form-control" placeholder="Enter blog sub title" name="sub_title" value="{{ (isset($editdata) && $editdata->sub_title) ? $editdata->sub_title : old('sub_title') }}">
                            <span class="form-text text-muted">Please enter blog sub title</span>
                            @if ($errors->has('sub_title'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('sub_title') }}</div>
                            @endif
                        </div>
                    </div>	 
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Select Category :</label>
                            <div></div>
                            <select class="custom-select form-control" name="blog_category_id">
                                <option value="">Please select category</option>
                                @foreach($categories as $cat)
                                <option value="{{$cat->id}}" {{($cat->id == $editdata->blog_category_id) ? 'selected' : ''}}>{{$cat->title}}</option>
                                @endforeach
                            </select>
                            <span class="form-text text-muted">Please select category</span>
                            @if ($errors->has('blog_category_id'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('blog_category_id') }}</div>
                            @endif
                        </div>
                        
                        {{-- <div class="col-lg-6">
                            <label>Sub Tags:</label>
                            <select class="form-control kt-select2" id="kt_select2_3" name="tag_id[]" multiple="multiple">
                                @if(!$tags->isEmpty())
                                @foreach($tags as $tag)
                                <option @if(in_array($tag->id, $blogTags))) selected @endif value="{{$tag->id}}">{{$tag->name}}</option>
                                @endforeach
                                @endif
                            </select>
                            <span class="form-text text-muted">Please enter blog tags</span>
                            @if ($errors->has('tag_id'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('tag_id') }}</div>
                            @endif
                        </div> --}}
                    </div>	 

                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Created By:</label>
                            <input type="text" class="form-control" placeholder="Enter blog created by" name="created_by" value="{{ (isset($editdata) && $editdata->created_by) ? $editdata->created_by : old('created_by') }}">
                            <span class="form-text text-muted">Please enter created by</span>
                            @if ($errors->has('created_by'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('created_by') }}</div>
                            @endif
                        </div>
                        {{-- <div class="col-lg-4">
                            <label>Order By:</label>
                            <input type="text" onkeypress="return isNumberKey(event);" class="form-control" placeholder="Enter blog order" name="order_by" value="{{ (isset($editdata) && $editdata->order_by) ? $editdata->order_by : old('order_by') }}">
                            <span class="form-text text-muted">Please enter your blog order</span>
                            @if ($errors->has('order_by'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('order_by') }}</div>
                            @endif
                        </div> --}}
                        <div class="col-lg-6">
                            <label>Created at:</label>
                            <input type="text" name="created_time" class="form-control" id="kt_datepicker"  placeholder="Select date"  value="{{ (isset($editdata) && $editdata->created_time) ? \Carbon\Carbon::parse($editdata->created_time)->format('m/d/Y') : old('created_time') }}"/>
                            <span class="form-text text-muted">Please enter your blog created date</span>
                            @if ($errors->has('created_time'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('created_time') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
        
                        <div class="col-lg-12">
                            <label>Description:</label>
                            <textarea name="description" class="form-control ckeditor">{{ (isset($editdata) && $editdata->description) ? $editdata->description : old('description') }}</textarea>
                            <span class="form-text text-muted">Please enter your description</span>
                            @if ($errors->has('description'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('description') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                            <div class="col-lg-6">
                                <label>Meta Title :</label>
                                <input type="text" class="form-control" placeholder="Enter Meta title" name="meta_title" value="{{ (isset($editdata) && $editdata->meta_title) ? $editdata->meta_title : old('meta_title') }}">
                            </div>
                            <div class="col-lg-6">
                                    <label>Meta Keywords :</label>
                                    <input type="text" class="form-control" placeholder="Enter Meta keywords" name="meta_keywords" value="{{ (isset($editdata) && $editdata->meta_keywords) ? $editdata->meta_keywords : old('meta_keywords') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-12">
                                    <label>Meta Description :</label>
                                    <textarea name="meta_description" class="form-control">{{ (isset($editdata) && $editdata->meta_description) ? $editdata->meta_description : old('meta_description') }}</textarea>
                            </div>    
                        </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Image:</label>
                            <div></div>
                            <div class="custom-file">
                                <input type="file" name="image" class="custom-file-input" id="customFile">
                                <label class="custom-file-label text-left" for="customFile">Choose file</label>
                            </div>
                            <span class="form-text text-muted">Please Upload image</span>
                            @if ($errors->has('image'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('image') }}</div>
                            @endif
                        </div>
                        {{-- <div class="col-lg-4">
                            <label>Is Popular ? :</label>
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
                            <span class="form-text text-muted">Please select Is Popular Active</span>
                        </div> --}}
                        <div class="col-lg-6">
                            <label class="">Status:</label>
                            <div class="kt-radio-inline">
                                <label class="kt-radio kt-radio--solid">
                                    <input type="radio" name="status"  value="active" {{ (isset($editdata) && $editdata->status == "active") ? "checked" : "" }} > Active
                                    <span></span>
                                </label>
                                <label class="kt-radio kt-radio--solid">
                                    <input type="radio" name="status" value="inactive" {{ (isset($editdata) && $editdata->status == "inactive") ? "checked" : "" }} > InActive
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
$('#kt_select2_3').select2({
    placeholder: "Select a Tags",
});
$('#kt_datepicker').datepicker({
    todayHighlight: true,
    orientation: "bottom left",
});

</script>
@endsection