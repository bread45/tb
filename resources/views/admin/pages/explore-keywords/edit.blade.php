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
            <h3 class="kt-subheader__title">Edit Keywords</h3>
        </div>
        <div class="kt-subheader__toolbar">
            <a href="{{route('exploreKeywords.index')}}" class="btn btn-default btn-bold"><i class="fa fa-arrow-left"></i> Back</a>
        </div>
    </div>
</div>
<!-- end:: Content Head -->

<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__body">
        <form method="POST" enctype="multipart/form-data" action="{{route('exploreKeywords.store')}}" class="kt-form kt-form--label-right" _lpchecked="1">
            <div class="kt-portlet__body">
                @csrf
                <input type="hidden" name="ExploreKeywords_id" value="{{$EditExploreKeywords->id}}" />
                <div class="kt-portlet__body">
                <div class="form-group row">
                        <div class="col-lg-12">
                            <label>Keywords:</label>
                            <textarea name="keywords" id="keywords" cols="30" rows="5" class="form-control" onchange="removeSpaces(event)" >{{ $EditExploreKeywords->keywords }}</textarea>
                            @if ($errors->has('keywords'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('keywords') }}</div>
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
<script src="{{ asset('/theme/vendors/general/tagify.js') }}" type="text/javascript"></script>
<script src="{{ asset('/theme/vendors/general/tagify.min.js') }}" type="text/javascript"></script>
<!--end::Page Vendors -->
<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
<script>

    $(document).ready(function() {

        $(".reset").click(function() {
            $('.kt-form').find("input[type=text], textarea").val("");
        });
        var input = document.querySelector('input[name=tags]');
        // init Tagify script on the above inputs
        new Tagify(input)
    });
    function removeSpaces(e){
        var keywordsStr = document.getElementById("keywords").value;
        var keywordsStr = keywordsStr.split(', ').join(',');
        document.getElementById("keywords").value = keywordsStr;
    }
</script>

@endsection