@extends('admin.layouts.default')
@section('title', 'FAQ Edit')
@section('content')

<link href="{{ asset('/theme/vendors/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />

<!-- begin:: Content Head -->
<div class="kt-subheader  kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">FAQ Blog</h3>
<!--            <span class="kt-subheader__separator kt-subheader__separator--v"></span>-->

        </div>
        <div class="kt-subheader__toolbar">
            <a href="{{route('faq.index')}}" class="btn btn-default btn-bold"><i class="fa fa-arrow-left"></i> Back</a>
        </div>
    </div>
</div>
<!-- end:: Content Head -->

<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__body">
            <form method="POST" enctype="multipart/form-data" action="{{route('faq.store')}}" class="kt-form kt-form--label-right" _lpchecked="1">
                @csrf
                <input type="hidden" name="faq_id" value="{{$editdata->id}}"/>
                <div class="kt-portlet__body">
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Select Category :</label>
                            <div></div>
                            <select class="custom-select form-control" name="categories_id">
                                <option value="">Please select category</option>
                                @foreach($categories as $cat)
                                <option value="{{$cat->id}}" {{($cat->id == $editdata->categories_id) ? 'selected' : ''}}>{{$cat->title}}</option>
                                @endforeach
                            </select>
                            <span class="form-text text-muted">Please select category</span>
                            @if ($errors->has('blog_category_id'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('blog_category_id') }}</div>
                            @endif
                        </div>
                        <div class="col-lg-6">
                            <label>Title:</label>
                            <input type="text" class="form-control" placeholder="Enter blog name" name="title" value="{{ (isset($editdata) && $editdata->title) ? $editdata->title : old('title') }}">
                            <span class="form-text text-muted">Please enter your blog name</span>
                            @if ($errors->has('title'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('title') }}</div>
                            @endif
                        </div>
                    </div>	 
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Order By:</label>
                            <input type="text" onkeypress="return isNumberKey(event);" class="form-control" placeholder="Enter blog order" name="order_by" value="{{ (isset($editdata) && $editdata->order_by) ? $editdata->order_by : old('order_by') }}">
                            <span class="form-text text-muted">Please enter your blog order</span>
                            @if ($errors->has('order_by'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('order_by') }}</div>
                            @endif
                        </div>
                        <div class="col-lg-6">
                            <label>Image:</label>
                            <div></div>
                            <div class="custom-file">
                                <input type="file" name="image" class="custom-file-input" id="customFile">
                                <label class="custom-file-label text-left" for="customFile">Choose file</label>
                            </div>
                            <span class="form-text text-muted">Please Upload image</span>
                            @if(isset($editdata) && $editdata->image)
                            <img src="{{url('sitebucket/faq/'.$editdata->image)}}" style="width: 100px;" alt= "editdata image"/>
                            @endif
                            @if ($errors->has('image'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('image') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                            <div class="col-lg-12">
                                    <label>Description:</label>
                                    <textarea name="description" class="form-control">{{ (isset($editdata) && $editdata->description) ? $editdata->description : old('description') }}</textarea>
                                    <span class="form-text text-muted">Please enter your Slider description</span>
                                    @if ($errors->has('description'))
                                    <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('description') }}</div>
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