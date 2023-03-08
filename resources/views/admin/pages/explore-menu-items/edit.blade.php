@extends('admin.layouts.default')
@section('title', 'Next Steps Edit')
@section('content')
<link href="{{ asset('/theme/vendors/general/tagify.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('/theme/vendors/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
<!-- begin:: Content Head -->
<div class="kt-subheader  kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">Edit Next Steps Slide</h3>
        </div>
        <div class="kt-subheader__toolbar">
            <a href="{{route('exploreItems.index')}}" class="btn btn-default btn-bold"><i class="fa fa-arrow-left"></i> Back</a>
        </div>
    </div>
</div>
<!-- end:: Content Head -->

<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__body">
        <form method="POST" enctype="multipart/form-data" action="{{route('exploreItems.store')}}" class="kt-form kt-form--label-right" _lpchecked="1">
            <div class="kt-portlet__body">
                @csrf
                <input type="hidden" name="ExploreMenu_id" value="{{$EditExploreMenu->id}}" />
                <div class="kt-portlet__body">
                <div class="form-group row">
                        <div class="col-lg-6">
                            <label>City:</label>
                            <!-- <input type="text" class="form-control" placeholder="Please enter your subcription plan" name="subcription_plan" value="{{ old('subcription_plan') }}"> -->
                            <select name="city" id="city" class="form-control">
                                <option value="">Please select city</option>
                                @foreach ($menuItemsCity as $menuItem)
                                @if($menuItem->city != '')
                                <option value="{{ $menuItem->city }}" <?php if($menuItem->city == $EditExploreMenu->city) { echo "selected"; }  ?>>{{ $menuItem->city }}</option>
                                @endif
                                @endforeach
                            </select>
                            <!-- <span class="form-text text-muted">Please enter your subcription plan</span> -->
                            @if ($errors->has('city'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('city') }}</div>
                            @endif
                        </div>
                        <div class="col-lg-6">
                            <label>State:</label>
                            <!-- <input type="text" class="form-control" placeholder="Please enter your subcription plan" name="subcription_plan" value="{{ old('subcription_plan') }}"> -->
                            <select name="state" id="state" class="form-control">
                                <option value="">Please select state</option>
                                @foreach ($menuItemsState as $menuItem)
                                @if($menuItem->state_code != '')
                                <option value="{{ $menuItem->state_code }}" <?php if($menuItem->state_code == $EditExploreMenu->state) { echo "selected"; }  ?>>{{ $menuItem->state_code }}</option>
                                @endif
                                @endforeach
                            </select>
                            <!-- <span class="form-text text-muted">Please enter your subcription plan</span> -->
                            @if ($errors->has('state'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('state') }}</div>
                            @endif
                        </div>
                    </div>

                    </div> 
                    
                    
                <div class="kt-portlet__foot">
                <div class="kt-form__actions">
                    <div class="row">
                        <div class="col-lg-23">
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
<script src="{{ asset('/theme/js/select2.js') }}" type="text/javascript"></script>
<script src="{{ asset('/theme/vendors/general/tagify.js') }}" type="text/javascript"></script>
<script src="{{ asset('/theme/vendors/general/tagify.min.js') }}" type="text/javascript"></script>
<!--end::Page Vendors -->
<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
<script src="//cdn.ckeditor.com/4.13.0/full/ckeditor.js"></script>
<script>

CKEDITOR.config.height = '300px';   // CSS unit (percent).
    CKEDITOR.config.enterMode = CKEDITOR.ENTER_BR;
    CKEDITOR.config.shiftEnterMode = CKEDITOR.ENTER_BR;
   
    CKEDITOR.config.toolbar = 'Basic';
    CKEDITOR.config.toolbar_Basic =
[
    ['Bold', 'Italic','Underline', '-', 'Link', 'Unlink', 'Undo', 'Redo', 'Image', 'Table', 'PageBreak', 'SpecialChar', 'NumberedList', 'BulletedList', 'Blockquote', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', 'HorizontalRule']
];

    $(document).ready(function() {

        $(".reset").click(function() {
            $('.kt-form').find("input[type=text], textarea").val("");
        });
        var input = document.querySelector('input[name=tags]');
        // init Tagify script on the above inputs
        new Tagify(input)
    });
</script>

@endsection