@extends('admin.layouts.default')
@section('title', 'Advertisement Edit')
@section('content')

<style>
    .datepicker.datepicker-dropdown.dropdown-menu.datepicker-orient-left.datepicker-orient-top {
    z-index: 999 !important;
}
</style>
<!-- begin:: Content Head -->
<div class="kt-subheader  kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">Edit Advertisement</h3>
        </div>
        <div class="kt-subheader__toolbar">
            <a href="{{route('advertisement.index')}}" class="btn btn-default btn-bold"><i class="fa fa-arrow-left"></i> Back</a>
        </div>
    </div>
</div>
<!-- end:: Content Head -->

<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__body">
            <form method="POST" enctype="multipart/form-data" action="{{route('advertisement.store')}}" class="kt-form kt-form--label-right" _lpchecked="1">
                @csrf 
                <input type="hidden" name="id" value="{{$advertisement->id}}" >
                <div class="kt-portlet__body">
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Name:</label>
                            <input type="text" class="form-control" placeholder="Enter name" name="name" value="{{ $advertisement->name }}"
                                    >
                            @if ($errors->has('name'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('name') }}</div>
                            @endif
                        </div>
                        <div class="col-lg-6">
                            <label>Amount:</label>
                            <input type="text" class="form-control" placeholder="Ented amount" name="amount" value="{{ $advertisement->amount }}"
                                   >
                            @if ($errors->has('amount'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('amount') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Start Date:</label>
                            <input type="text" class="form-control startenddate" placeholder="Select date" name="start_date" value="{{ date('d-m-Y',strtotime($advertisement->start_date)) }}"
                                   >
                            @if ($errors->has('start_date'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('start_date') }}</div>
                            @endif
                        </div>
                        <div class="col-lg-6">
                            <label>End Date:</label>
                            <input type="text" class="form-control startenddate" placeholder="Select Date" name="end_date" value="{{ date('d-m-Y',strtotime($advertisement->end_date)) }}"
                                   >
                            @if ($errors->has('end_date'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('end_date') }}</div>
                            @endif
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
                            @if ($errors->has('image'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('image') }}</div>
                            @endif
                            @if($advertisement->image)
                            <a data-fancybox="gallery" href="{{url('public/sitebucket/advertisement/'. $advertisement->image)}}"><img style="width:50px;" src="{{url('public/sitebucket/advertisement/'. $advertisement->image)}}" alt="advertisement image"/></a>
                            @endif
                        </div>
                        <div class="col-lg-6">
                            <label>Select Location:</label>
                            <select class="form-control locationselect" multiple="" name="locations[]">
                                @foreach($Locations as $Location)
                                    <option value="{{$Location->id}}" @if(in_array($Location->id,explode(',',$advertisement->locations))) selected="" @endif >{{$Location->name}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('locations'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('locations') }}</div>
                            @endif
                        </div> 
                    </div> 
                     <div class="form-group row">
                         <div class="col-lg-6">
                            <label>URL:</label>
                            <input type="url" class="form-control" placeholder="Enter URL" name="url" value="{{ $advertisement->url }}"
                                    >
                            @if ($errors->has('url'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('url') }}</div>
                            @endif
                        </div>
                         <div class="col-lg-6"> 
                            <label class="">Status:</label> 
                            <div class="kt-radio-inline"> 
                                <label class="kt-radio kt-radio--solid"> 
                                    <input type="radio" name="status" {{ (isset($advertisement) && $advertisement->status == "active") ? "checked" : "" }}   value="active"> Active
                                    <span></span>
                                </label>
                                <label class="kt-radio kt-radio--solid">
                                    <input type="radio" name="status" {{ (isset($advertisement) && $advertisement->status == "inactive") ? "checked" : "" }} value="inactive"> InActive
                                    <span></span>
                                </label>
                            </div>
                            @if ($errors->has('status'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('status') }}</div>
                            @endif
                        </div> 
                         
                    </div>
                     <div class="form-group row">
                         <div class="col-lg-6"> 
                            <label class="">Method:</label> 
                            <div class="kt-radio-inline"> 
                                <label class="kt-radio kt-radio--solid"> 
                                    <input type="radio" name="method" checked="" {{ (isset($advertisement) && $advertisement->method == "fixcost") ? "checked" : "" }} value="fixcost"> Fixed Cost
                                    <span></span>
                                </label>
                                <label class="kt-radio kt-radio--solid">
                                    <input type="radio" name="method" value="payperclick" {{ (isset($advertisement) && $advertisement->method == "payperclick") ? "checked" : "" }}> Pay-Per click
                                    <span></span>
                                </label>
                            </div>
                            @if ($errors->has('status'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('status') }}</div>
                            @endif
                        </div>
                         <div class="col-lg-6"> 
                            <label class="">Where to display this ad:</label> 
                            <div class="kt-radio-inline">  
                                <label class="kt-radio kt-radio--solid">
                                    <input type="radio" name="pageview" class="displayradio" {{ (isset($advertisement) && $advertisement->pageview == "explore") ? "checked" : "" }}  value="explore"> Explore Page 
                                    <span></span>
                                </label>
                                <label class="kt-radio kt-radio--solid"> 
                                    <input type="radio" name="pageview" class="displayradio" {{ (isset($advertisement) && $advertisement->pageview == "home") ? "checked" : "" }} value="home"> Home page
                                    <span></span>
                                </label>
                            </div> 
                        </div>
                         <div class="col-lg-6 displayradiovediv" style="display: none;margin-top: 20px;"> 
                            <label class="">Type:</label> 
                            <div class="kt-radio-inline"> 
                                <label class="kt-radio kt-radio--solid"> 
                                    <input type="radio" name="view"   class="displayradiove" {{ (isset($advertisement) && $advertisement->view == "horizontal") ? "checked" : "" }} value="horizontal"> Horizontal
                                    <span></span>
                                </label>
                                <label class="kt-radio kt-radio--solid">
                                    <input type="radio" name="view" value="vertical" class="displayradiove" {{ (isset($advertisement) && $advertisement->view == "vertical") ? "checked" : "" }}> Vertical
                                    <span></span>
                                </label>
                            </div>
                            @if ($errors->has('status'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('status') }}</div>
                            @endif
                        </div>
                         <div class="col-lg-6 topbottomviewdiv" style="display: none;margin-top: 20px;"> 
                                <label class="">Where to display this ad:</label> 
                                <div class="kt-radio-inline"> 
                                    <label class="kt-radio kt-radio--solid"> 
                                        <input type="radio" name="typeview" {{ (isset($advertisement) && $advertisement->typeview == "top") ? "checked" : "" }} class="sideradio"  value="top">top  
                                        <span></span>
                                    </label>
                                    <label class="kt-radio kt-radio--solid">
                                        <input type="radio" name="typeview" {{ (isset($advertisement) && $advertisement->typeview == "bottom") ? "checked" : "" }} class="sideradio" value="bottom"> bottom
                                        <span></span>
                                    </label>
                                </div> 
                            </div>
                            <div class="col-lg-6 leftrightviewdiv" style="display: none;margin-top: 20px;"> 
                                <label class="">Where to display this ad:</label> 
                                <div class="kt-radio-inline"> 
                                    <label class="kt-radio kt-radio--solid"> 
                                        <input type="radio" name="typeview" class="sideradio"  {{ (isset($advertisement) && $advertisement->typeview == "left") ? "checked" : "" }} value="left"> left 
                                        <span></span>
                                    </label>
                                    <label class="kt-radio kt-radio--solid">
                                        <input type="radio" name="typeview" class="sideradio" {{ (isset($advertisement) && $advertisement->typeview == "right") ? "checked" : "" }} value="right"> Right
                                        <span></span>
                                    </label>
                                </div> 
                            </div> 
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
<script>
    @if($advertisement->view == "horizontal")
        $('.topbottomviewdiv').show(); 
            $('.leftrightviewdiv').hide(); 
            @else
              $('.topbottomviewdiv').hide();
            $('.leftrightviewdiv').show();  
    @endif
    @if($advertisement->pageview == "home") 
            $('.displayradiovediv').show();
            @else 
            $('.displayradiovediv').hide();
               $('.topbottomviewdiv').hide();
            $('.leftrightviewdiv').hide();  
    @endif
      $(document).on("click", ".displayradio", function () {
       if($(this).val() == 'home'){
           $('input[value=top]').prop("checked", true);
            $('.topbottomviewdiv').show();
            $('.displayradiovediv').show();
        }else{
            $('.topbottomviewdiv').hide();
            $('.displayradiovediv').hide();
        }
    });
    $(document).on("click", ".displayradiove", function () {
       if($(this).val() == 'horizontal'){
           $('input[value=top]').prop("checked", true);
            $('.topbottomviewdiv').show(); 
            $('.leftrightviewdiv').hide(); 
        }else{
            $('input[value=left]').prop("checked", true);
            $('.topbottomviewdiv').hide();
            $('.leftrightviewdiv').show();
        }
    });
    $('.startenddate').datepicker({
        format: 'dd-mm-yyyy'
    });
    $(".reset").click(function () {
        $('.kt-form').find("input[type=text], textarea").val("");
    });
    $('.locationselect').select2({
            placeholder: "Select Locations", 
        });
</script>
@endsection