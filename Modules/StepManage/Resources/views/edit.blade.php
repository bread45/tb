@extends('admin.layouts.default')
@section('title', 'Step Edit')
@section('content')

<link href="{{ asset('/theme/vendors/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />

<!-- begin:: Content Head -->
<div class="kt-subheader  kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">Edit Step</h3>
<!--            <span class="kt-subheader__separator kt-subheader__separator--v"></span>-->

        </div>
        <div class="kt-subheader__toolbar">
            <a href="{{route('stepmanage.index')}}" class="btn btn-default btn-bold"><i class="fa fa-arrow-left"></i> Back</a>
        </div>
    </div>
</div>
<!-- end:: Content Head -->

<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__body">
            <form method="POST" enctype="multipart/form-data" action="{{route('stepmanage.store')}}" class="kt-form kt-form--label-right" _lpchecked="1">
                @csrf
                <input type="hidden" name="step_id" value="{{$editdata->id}}"/>
                <div class="kt-portlet__body">
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Categories : {{$editdata->type}}</label>
                            <select class="form-control" name="type">
                                <option @if((isset($editdata) && $editdata->type && $editdata->type == 'supplierpage-organisers')) selected @endif value="supplierpage-organisers">Supplier Page (Organisers)</option>
                                <option @if((isset($editdata) && $editdata->type && $editdata->type == 'supplierpage-suppliers')) selected @endif value="supplierpage-suppliers">Supplier Page (Suppliers)</option>
                                <option @if((isset($editdata) && $editdata->type && $editdata->type == 'organiserpage-organisers')) selected @endif value="organiserpage-organisers">Organiser Page (Organisers)</option>
                                <option @if((isset($editdata) && $editdata->type && $editdata->type == 'organiserpage-suppliers')) selected @endif value="organiserpage-suppliers">Organiser Page (Suppliers)</option>
                            </select>
                            <span class="form-text text-muted">Please Select Category</span>
                            @if ($errors->has('type'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('type') }}</div>
                            @endif
                        </div>    
                        <div class="col-lg-6">
                            <label>Number :</label>
                            <input type="text" class="form-control" placeholder="Enter Number" name="number" value="{{ (isset($editdata) && $editdata->number) ? $editdata->number : old('number') }}">
                            <span class="form-text text-muted">Please enter Number</span>
                            @if ($errors->has('number'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('number') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Title :</label>
                            <input type="text" class="form-control" placeholder="Enter title" name="title" value="{{ (isset($editdata) && $editdata->title) ? $editdata->title : old('title') }}">
                            <span class="form-text text-muted">Please enter your title</span>
                            @if ($errors->has('title'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('title') }}</div>
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
                                @if ($errors->has('image'))
                                <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('image') }}</div>
                                @endif
                            </div>
                    </div>	 
                    <div class="form-group row">
                       
                        <div class="col-lg-12">
                            <label>Description:</label>
                            <textarea name="description" class="form-control" id="kt-ckeditor-1">{{ (isset($editdata) && $editdata->description) ? $editdata->description : old('description') }}</textarea>
                            <!-- <div class="kt-input-icon">
                                <textarea name="description" class="form-control ckeditor">{{ (isset($editdata) && $editdata->description) ? $editdata->description : old('description') }}</textarea>
                            </div> -->
                            <span class="form-text text-muted">Please enter your description</span>
                            @if ($errors->has('description'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('description') }}</div>
                            @endif
                        </div>
                       
                    </div>	 
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Order By:</label>
                            <input type="text" onkeypress="return isNumberKey();" class="form-control" placeholder="Enter order" name="order_by" value="{{ (isset($editdata) && $editdata->order_by) ? $editdata->order_by : old('order_by') }}">
                            <span class="form-text text-muted">Please enter your order</span>
                            @if ($errors->has('order_by'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('order_by') }}</div>
                            @endif
                        </div>
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