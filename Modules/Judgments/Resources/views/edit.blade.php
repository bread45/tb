@extends('admin.layouts.default')
@section('title', 'Judgment Add')
@section('content')

<link href="{{ asset('/theme/vendors/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('/theme/vendors/general/select2/dist/css/select2.css') }}" rel="stylesheet" type="text/css" />
<!-- begin:: Content Head -->
<style>
    .tagify.form-control {
        height: auto;
    }
</style>
<div class="kt-subheader  kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">Add New Judgment</h3>
        </div>
        <div class="kt-subheader__toolbar">
            <a href="{{route('judgments.index')}}" class="btn btn-default btn-bold"><i class="fa fa-arrow-left"></i> Back</a>
        </div>
    </div>
</div>
<!-- end:: Content Head -->

<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__body">
            <form method="POST" enctype="multipart/form-data" action="{{route('judgments.store')}}" class="kt-form kt-form--label-right" _lpchecked="1">
                @csrf
                <input type="hidden" name="id" value="{{$editdata->id}}"/>
                <div class="kt-portlet__body">
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Judgment Name:</label>
                            <input type="text" class="form-control" placeholder="Enter Judgment name" name="name" value="{{ isset($editdata) ? $editdata->name : old('name') }}">
                            @if ($errors->has('name'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('name') }}</div>
                            @endif
                        </div>
                        <div class="col-lg-6">
                            <label>Name of the judge:</label>
                            <input type="text" class="form-control" placeholder="Name of the judge" name="judge_name" value="{{ isset($editdata) ? $editdata->judge_name : old('judge_name') }}" autocomplete="off">
                            @if ($errors->has('judge_name'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('judge_name') }}</div>
                            @endif
                        </div>

                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label>Description:</label>
                            <div class="kt-input-icon">
                                <textarea name="description" class="form-control ckeditor">{{ isset($editdata) ? $editdata->description : old('description') }}</textarea>
                            </div>
                            @if ($errors->has('description'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('description') }}</div>
                            @endif
                        </div>

                    </div>
                    <div class="form-group row">
                        <div class="col-lg-5">
                            <label>Judgment PDF/DOC</label>
                            <div class="custom-file">
                                <input type="file" name="document" class="custom-file-input" id="customFile">
                                <label class="custom-file-label text-left" for="customFile">Choose file</label>
                            </div>
                            @if ($errors->has('document'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('document') }}</div>
                            @endif
                        </div>
                        <div class="col-lg-1">
                            @if(!empty($editdata->document) && file_exists(public_path().'/sitebucket/judgment/'.$editdata->document))
                            <a href="{{url('public/sitebucket/judgment/'.$editdata->document)}}" target="_blank" style="margin-top: 35px;position: absolute;"><u>View</u></a>
                            <a data-id="{{route('judgments.document', $editdata->id)}}" href="javascript:"  style="margin-top: 50px;position: absolute;" class="status_change"><u>Remove</u></a>
                            @endif
                        </div>
                        <div class="col-lg-5">
                            <label>Image:</label>
                            <div class="custom-file">
                                <input type="file" name="image" class="custom-file-input" id="customFile">
                                <label class="custom-file-label text-left" for="customFile">Choose file</label>
                            </div>
                            @if ($errors->has('image'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('image') }}</div>
                            @endif
                        </div>
                        <div class="col-lg-1" style="position: relative">
                            @if($editdata->image != '')
                            <img width="50" height="50" src='{{url('public/sitebucket/judgment/'.$editdata->image)}}' style="margin-top: 20px;" alt = "editdata image">                         
                            <i data-id="{{route('judgments.image', $editdata->id)}}" class="far fa-times-circle remove-ico status_change" style="cursor: pointer;position: absolute;right: 8px;top: 12px;color: #bb3737;"></i>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Category:</label>
                            <select class="form-control kt-select2" name="category_ids[]" id="kt_select2_11" multiple name="param">
                                @foreach($categories as $cat)
                                <option value="{{$cat->id}}" {{(in_array($cat->id,explode(',',$editdata->category_ids))) ? 'selected' : ''}}>{{$cat->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <label class="">Court type :</label>
                            <select class="form-control" name="court_type">
                                <option value="supreme_court" {{('supreme_court' == $editdata->court_type) ? 'selected' : ''}}>Supreme Court</option>
                                <option value="high_court" {{('high_court' == $editdata->court_type) ? 'selected' : ''}}>High Court Of Delhi</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label class="">Status:</label>
                            <div class="kt-radio-inline">
                                <label class="kt-radio kt-radio--solid">
                                    <input type="radio" name="status" @if(isset($editdata) && $editdata->status=='active') checked="" @endif value="active"> Active
                                           <span></span>
                                </label>
                                <label class="kt-radio kt-radio--solid">
                                    <input type="radio" name="status" @if(isset($editdata) && $editdata->status=='inactive') checked="" @endif value="inactive"> InActive
                                           <span></span>
                                </label>
                            </div>
                            @if ($errors->has('status'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('status') }}</div>
                            @endif
                        </div>
                        <!--                        <div class="col-lg-6">
                                                    <label>Date :</label>
                                                    <input type="text" class="form-control datepicker" name="date" value="{{ isset($editdata) ? $editdata->date : old('date') }}" autocomplete="off">
                                                    @if ($errors->has('date'))
                                                    <div style="display: block;" id="email-error" class="error invalid-feedback ">{{ $errors->first('date') }}</div>
                                                    @endif
                                                </div>-->
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
<!--begin::Page Vendors(used by this page) -->
<script src="{{ asset('/theme/vendors/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
<script src="{{ asset('/theme/vendors/general/select2/dist/js/select2.js') }}" type="text/javascript"></script>
<!--end::Page Vendors -->

<script>
$(".reset").click(function () {
    $('.kt-form').find("input[type=text], textarea").val("");
});
$(function () {
    $("#kt_select2_11").select2({
        tags: true
    });
    $('.datepicker').datepicker({
        format: "dd-mm-yyyy",
        autoclose: true,
        todayHighlight: true
    });
    $(document).on('click', '.status_change', function () {
        var id = $(this).attr('data-id');
        swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            animation: false,
            customClass: 'animated tada',
            confirmButtonText: 'Yes, delete it!'
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: id,
                    type: 'GET',
                    success: function (result) {
                        if (result.status == true) {
                            toastr.success(result.Message);
                            window.location.reload();
                        } else {
                            toastr.error(result.Message);
                        }
                    }
                });
            }
        });
    });

});

</script>
@endsection