@extends('admin.layouts.default')
@section('title', 'Testimonial Edit')
@section('content')

<link href="{{ asset('/theme/vendors/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />

<!-- begin:: Content Head -->
<div class="kt-subheader  kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">Edit Testimonial</h3>
<!--            <span class="kt-subheader__separator kt-subheader__separator--v"></span>-->

        </div>
        <div class="kt-subheader__toolbar">
            <a href="{{route('testimonials.index')}}" class="btn btn-default btn-bold"><i class="fa fa-arrow-left"></i> Back</a>
        </div>
    </div>
</div>
<!-- end:: Content Head -->

<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__body">
        <form method="POST" enctype="multipart/form-data" action="{{route('testimonials.store')}}" class="kt-form kt-form--label-right" _lpchecked="1">
                @csrf
                <input type="hidden" class="form-control" name="testimonial_id" value="{{ $editdata->id }}">
                <div class="kt-portlet__body">
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Title :</label>
                            <input type="text" class="form-control" placeholder="Enter name" name="title" value="{{ (isset($editdata) && $editdata->title) ? $editdata->title : old('title') }}">
                            @if ($errors->has('title'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('title') }}</div>
                            @endif
                        </div>
                        <div class="col-lg-6">
                            <label>User Name :</label>
                            <input type="text" class="form-control" placeholder="Enter name" name="user_name" value="{{ (isset($editdata) && $editdata->user_name) ? $editdata->user_name : old('user_name') }}">
                            @if ($errors->has('user_name'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('user_name') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Position :</label>
                            <input type="text" class="form-control" placeholder="Enter name" name="position" value="{{ (isset($editdata) && $editdata->position) ? $editdata->position : old('position') }}">
                            @if ($errors->has('position'))
                            <div style="display: block;" id="position-error" class="error invalid-feedback">{{ $errors->first('position') }}</div>
                            @endif
                        </div>
                    <div class="col-lg-6">
                            <label>User Image :</label>
                            <input type="file" class="form-control" name="user_image" accept=".png,.jpg,.jpeg" />
                            <span>Image must be in (jpeg,png,jpg) format. Maximum image size 2MB.</span>
                            @if ($errors->has('user_image'))
                            <div style="display: block;" id="user-image-error" class="error invalid-feedback">{{ $errors->first('user_image') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Rating :</label>

                            <div class='rating-stars'>
                               <ul id='stars'>
                                @for($i=1; $i<= 5; $i++)
                                @if($i<= $editdata->rating)
                                 <li class='star selected' data-value='{{ $i }}'>
                                   <i class='fa fa-star fa-fw'></i>
                                 </li>
                                 @else 
                                 <li class='star' data-value='{{ $i }}'>
                                   <i class='fa fa-star fa-fw'></i>
                                 </li>
                                 @endif
                                 @endfor
                               </ul>
                             </div>
                            <input type="hidden" name="rating" class="form-control" id="rating" value="{{ $editdata->rating }}" /> 
                            <!-- @if ($errors->has('rating'))
                            <div style="display: block;" id="rating-error" class="error invalid-feedback">{{ $errors->first('rating') }}</div>
                            @endif -->
                        </div>
 
                        <div class="col-lg-6">
                            <label>Description:</label>
                            <textarea name="description" class="form-control">{{ (isset($editdata) && $editdata->description) ? $editdata->description : old('description') }}</textarea>
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
<style>

/* Rating Star Widgets Style */
.rating-stars ul {
  list-style-type:none;
  padding:0;
  
  -moz-user-select:none;
  -webkit-user-select:none;
}
.rating-stars ul > li.star {
  display:inline-block;
  
}

/* Idle State of the stars */
.rating-stars ul > li.star > i.fa {
  font-size:2.5em; /* Change the size of the stars */
  color:#ccc; /* Color on idle state */
}

/* Hover state of the stars */
.rating-stars ul > li.star.hover > i.fa {
  color:#FFCC36;
}

/* Selected state of the stars */
.rating-stars ul > li.star.selected > i.fa {
  color:#FF912C;
}


</style>
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

$(document).ready(function(){
  
  /* 1. Visualizing things on Hover - See next part for action on click */
  $('#stars li').on('mouseover', function(){
    var onStar = parseInt($(this).data('value'), 10); // The star currently mouse on
   
    // Now highlight all the stars that's not after the current hovered star
    $(this).parent().children('li.star').each(function(e){
      if (e < onStar) {
        $(this).addClass('hover');
      }
      else {
        $(this).removeClass('hover');
      }
    });
    
  }).on('mouseout', function(){
    $(this).parent().children('li.star').each(function(e){
      $(this).removeClass('hover');
    });
  });
  
  
  /* 2. Action to perform on click */
  $('#stars li').on('click', function(){
    var onStar = parseInt($(this).data('value'), 10); // The star currently selected
    var stars = $(this).parent().children('li.star');
    
    for (i = 0; i < stars.length; i++) {
      $(stars[i]).removeClass('selected');
    }
    
    for (i = 0; i < onStar; i++) {
      $(stars[i]).addClass('selected');
    }

    var ratingValue = parseInt($('#stars li.selected').last().data('value'), 10);;
    if (ratingValue != '') {
       $('#rating').val(ratingValue);
    }
    
  });
  
  
});

</script>

<script>
                                $(".reset").click(function () {
                                    $('.kt-form').find("input[type=text], textarea").val("");
                                });
</script>
@endsection