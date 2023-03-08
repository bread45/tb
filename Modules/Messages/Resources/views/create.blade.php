@extends('admin.layouts.default')
@section('title', 'Organiser Add')
@section('content')

<link href="{{ asset('/theme/vendors/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />

<!-- begin:: Content Head -->
<div class="kt-subheader  kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">Send Messages</h3>
        </div>
        <div class="kt-subheader__toolbar">
            <a href="{{route('messages.index')}}" class="btn btn-default btn-bold"><i class="fa fa-arrow-left"></i> Back</a>
        </div>
    </div>
</div>
<!-- end:: Content Head -->

<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__body">
            <form method="POST" enctype="multipart/form-data" action="{{route('messages.store')}}" class="kt-form kt-form--label-right" _lpchecked="1">
                @csrf
                <div class="kt-portlet__body">
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label>User:</label>
                            <select class="form-control" name="to_id">
                                <option value="">Select User</option>
                                @foreach($users as $user)
                                <option value="{{$user->id}}">{{$user->first_name}} {{$user->last_name}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('to_id'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('to_id') }}</div>
                            @endif
                        </div>
                        <div class="col-lg-12">
                            <label>Message:</label>
                            <textarea name="message" class="form-control" placeholder="Enter Message Here">
                            </textarea>
                            @if ($errors->has('message'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('message') }}</div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="kt-portlet__foot">
                    <div class="kt-form__actions">
                        <div class="row">
                            <div class="col-lg-6">
                                <button type="submit" class="btn btn-primary">Send</button>
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

<script>
$(".reset").click(function () {
    $('.kt-form').find("input[type=text], textarea").val("");
});
 
</script>
@endsection