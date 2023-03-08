@extends('front.trainer.layout.trainer')
@section('title', 'Add Service')
@section('content')
<div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <div class="container-fluid">
                    <div class="page-title d-flex align-items-center justify-content-between mb-lg-4 mb-3 pb-lg-3 flex-wrap">
                        <a href="javascript:void(0);" class="menu-trigger d-lg-none d-flex order-0">
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>
                        </a>

                           
                        
                        <h1 class="h3 text-muted font-weight-normal mb-0 mt-lg-0 mt-3 order-lg-0 order-2">Add /Edit Service</h1>
                        
                        @include('front.trainer.layout.includes.header')
                    </div>

                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body p-lg-5">
                                @if (session('message'))
                                    <div class="alert alert-success">
                                        {{ session('message') }}
                                    </div>
                                @endif
                                @if (session('error'))
                                    <div class="alert alert-danger">
                                        {{ session('error') }}
                                    </div>
                                @endif
                                
                                <form class="form-row add-service-form" method="POST" action="{{route('service.store')}}" enctype='multipart/form-data'>
                                        @csrf
                                        <input type="hidden" name="serviceId" value="@if(isset($trainerService->id)) {{ $trainerService->id }} @endif" />
                                        <input type="hidden" name="serviceId" value="@if(isset($trainerService->id)) {{ $trainerService->id }} @endif" />
                                        <input type="hidden" name="eventdata" value="{{ $eventData }}">
                                        <input type="hidden" name="promo_code" value="0">

                            <!-- Service Name -->
                                    <div class="form-group col-lg-4 col-md-6">
                                    <label for="sprice-input">SERVICE NAME</label> <span style="font-size:12px;float:right">(30 character max)</span>
                                        <input type="text" class="form-control" name="name" required id="name-input" maxlength="30" placeholder="enter service name" value="@if(isset($trainerService->name)){{ $trainerService->name }}@else{{old('name')}}@endif">
                                            @if ($errors->has('name'))
                                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('name') }}</div>
                                            @endif
                                    </div>
                            <!-- Service Category -->
                                        <div class="form-group col-lg-4 col-md-6">
                                         <label for="service-input">SERVICE CATEGORY</label>
                                         <select name="service" class="form-control" required id="service-input">
                                             <option value="">select service category</option>
                                             @foreach($services as $service)
                                              <option @if(isset($trainerService->service_id)) @if($trainerService->service_id == $service->id) selected @endif @endif value="{{ $service->id }}" >{{ $service->name }}</option> 
                                             @endforeach
                                         </select>
                                         @if ($errors->has('service'))
                                             <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('service') }}</div>
                                         @endif
                                     </div>
                                <!-- Service Type -->
                                <div class="form-group col-lg-4 col-md-6">
                                              <label for="format-input">Service Type </label>
                                              <select name="service_type" class="form-control" required id="service_type">
                                                <option value="In person - Single Appointment" @if(isset($trainerService->format)) @if($trainerService->format == "In person - Single Appointment") selected @endif @endif>In person - Single Appointment</option>
                                                <option value="In person - Group Appointment" @if(isset($trainerService->format)) @if($trainerService->format == "In person - Group Appointment") selected @endif @endif>In person - Group Appointment</option>
                                                <option value="Virtual - Single Appointment" @if(isset($trainerService->format)) @if($trainerService->format == "Virtual - Single Appointment") selected @endif @endif>Virtual - Single Appointment</option>
                                                <option value="Virtual - Group Appointment" @if(isset($trainerService->format)) @if($trainerService->format == "Virtual - Group Appointment") selected @endif @endif>Virtual - Group Appointment</option>
                                                <option value="Monthly Membership" @if(isset($trainerService->format)) @if($trainerService->format == "Monthly Membership") selected @endif @endif>Monthly Membership</option>
                                                <option value="Yearly Membership" @if(isset($trainerService->format)) @if($trainerService->format == "Yearly Membership") selected @endif @endif>Yearly Membership</option>
                                                <option value="Package Deal" @if(isset($trainerService->format)) @if($trainerService->format == "Package Deal") selected @endif @endif>Package Deal</option>


                                              </select>
                                            </div>
                    <!-- Duration -->
                   <!-- <div class="form-group col-lg-4 col-md-6">
                                  <label for="service-input">Duration </label> <br />


                                  <div class="input-group bootstrap-timepicker timepicker">
                                    <input id="timepicker1" name="duration" type="text" class="form-control input-small" value="@if(isset($trainerService->duration)){{ $trainerService->duration }}@else{{old('duration')}}@endif">

                                  </div>
                                  <a href="#" class="buffer_time"><i class="fa fa-clock-o"></i> Buffer
                                    Time</a>

                                </div>

                                <div class="form-group col-lg-4 col-md-6 display_none_maxi">
                                  <label for="appt">Buffer Time</label>
                                  <div class="input-group bootstrap-timepicker timepicker">
                                    <input type="text" id="buffer_time" name="buffer_time" class="form-control input-small" value="@if(isset($trainerService->buffer_time)){{ $trainerService->buffer_time }}@else{{old('buffer_time')}}@endif">

                                  </div>
                                  </div>-->
                                  <div class="form-group col-lg-4 col-md-6 durationBlock" style="display:none">
                      <label for="service-input">Duration </label> <br />
                      <div class="row">
                        <div class="col-md-6">

                          <select name="duration" class="form-control" required id="hours" >
                            <option value="">Hours</option>
                            <option value="1"  @if(isset($trainerService->duration)) @if($trainerService->duration == "1") selected @endif @endif>1 hour</option>
                            <option value="2" @if(isset($trainerService->duration)) @if($trainerService->duration == "2") selected @endif @endif>2 hour</option>
                            <option value="3" @if(isset($trainerService->duration)) @if($trainerService->duration == "3") selected @endif @endif>3 hour</option>
                            <option value="4" @if(isset($trainerService->duration)) @if($trainerService->duration == "4") selected @endif @endif>4 hour</option>
                            <option value="5" @if(isset($trainerService->duration)) @if($trainerService->duration == "5") selected @endif @endif>5 hour</option>
                            <option value="6" @if(isset($trainerService->duration)) @if($trainerService->duration == "6") selected @endif @endif>6 hour</option>
                            <option value="7" @if(isset($trainerService->duration)) @if($trainerService->duration == "7") selected @endif @endif>7 hour</option>
                            <option value="8" @if(isset($trainerService->duration)) @if($trainerService->duration == "8") selected @endif @endif>8 hour</option>
                            <option value="9" @if(isset($trainerService->duration)) @if($trainerService->duration == "9") selected @endif @endif>9 hour</option>
                            <option value="10" @if(isset($trainerService->duration)) @if($trainerService->duration == "10") selected @endif @endif>10 hour</option>
                            <option value="11" @if(isset($trainerService->duration)) @if($trainerService->duration == "11") selected @endif @endif>11 hour</option>
                            <option value="12" @if(isset($trainerService->duration)) @if($trainerService->duration == "12") selected @endif @endif>12 hour</option>
                            <option value="13" @if(isset($trainerService->duration)) @if($trainerService->duration == "13") selected @endif @endif>13 hour</option>
                            <option value="14" @if(isset($trainerService->duration)) @if($trainerService->duration == "14") selected @endif @endif>14 hour</option>
                            <option value="15" @if(isset($trainerService->duration)) @if($trainerService->duration == "15") selected @endif @endif>15 hour</option>
                            <option value="16" @if(isset($trainerService->duration)) @if($trainerService->duration == "16") selected @endif @endif>16 hour</option>
                            <option value="17" @if(isset($trainerService->duration)) @if($trainerService->duration == "17") selected @endif @endif>17 hour</option>
                            <option value="18" @if(isset($trainerService->duration)) @if($trainerService->duration == "18") selected @endif @endif>18 hour</option>
                            <option value="19" @if(isset($trainerService->duration)) @if($trainerService->duration == "19") selected @endif @endif>19 hour</option>
                            <option value="20" @if(isset($trainerService->duration)) @if($trainerService->duration == "20") selected @endif @endif>20 hour</option>
                            <option value="21" @if(isset($trainerService->duration)) @if($trainerService->duration == "21") selected @endif @endif>21 hour</option>
                            <option value="22" @if(isset($trainerService->duration)) @if($trainerService->duration == "22") selected @endif @endif>22 hour</option>
                            <option value="23" @if(isset($trainerService->duration)) @if($trainerService->duration == "23") selected @endif @endif>23 hour</option>
                            <option value="24" @if(isset($trainerService->duration)) @if($trainerService->duration == "24") selected @endif @endif>24 hour</option>

                          </select>
                        </div>
                        <div class="col-md-6">
                          <select name="duration_mins" class="form-control" id="duration_mins">
                            <option value="">Minutes</option>
                            <option value="5" @if(isset($trainerService->duration_mins)) @if($trainerService->duration_mins == "5") selected @endif @endif>5 min</option>
                            <option value="10" @if(isset($trainerService->duration_mins)) @if($trainerService->duration_mins == "10") selected @endif @endif>10 min</option>
                            <option value="15" @if(isset($trainerService->duration_mins)) @if($trainerService->duration_mins == "15") selected @endif @endif>15 min</option>
                            <option value="20" @if(isset($trainerService->duration_mins)) @if($trainerService->duration_mins == "20") selected @endif @endif>20 min</option>
                            <option value="25" @if(isset($trainerService->duration_mins)) @if($trainerService->duration_mins == "25") selected @endif @endif>25 min</option>
                            <option value="30" @if(isset($trainerService->duration_mins)) @if($trainerService->duration_mins == "30") selected @endif @endif>30 min</option>
                            <option value="35" @if(isset($trainerService->duration_mins)) @if($trainerService->duration_mins == "35") selected @endif @endif>35 min</option>
                            <option value="40" @if(isset($trainerService->duration_mins)) @if($trainerService->duration_mins == "40") selected @endif @endif>40 min</option>
                            <option value="45" @if(isset($trainerService->duration_mins)) @if($trainerService->duration_mins == "45") selected @endif @endif>45 min</option>
                            <option value="50" @if(isset($trainerService->duration_mins)) @if($trainerService->duration_mins == "50") selected @endif @endif>50 min</option>
                            <option value="55" @if(isset($trainerService->duration_mins)) @if($trainerService->duration_mins == "55") selected @endif @endif>55 min</option>
                          </select>
                        </div>
                      </div>


                      <a href="#" class="buffer_time"><i class="fa fa-clock-o"></i> Buffer
                        Time</a>


                    </div>
                    <div class="form-group col-lg-4 col-md-6 display_none_maxi">
                      <label for="appt">Buffer Time</label>
                      <select name="buffer_time" class="form-control" id="buffer_times">
                        <option value="">Minutes</option>
                        <option value="5" @if(isset($trainerService->buffer_time)) @if($trainerService->buffer_time == "5") selected @endif @endif>5 Min</option>
                        <option value="10" @if(isset($trainerService->buffer_time)) @if($trainerService->buffer_time == "10") selected @endif @endif>10 Min</option>
                        <option value="15" @if(isset($trainerService->buffer_time)) @if($trainerService->buffer_time == "15") selected @endif @endif>15 Min</option>
                        <option value="20" @if(isset($trainerService->buffer_time)) @if($trainerService->buffer_time == "20") selected @endif @endif>20 Min</option>
                        <option value="25" @if(isset($trainerService->buffer_time)) @if($trainerService->buffer_time == "25") selected @endif @endif>25 Min</option>
                        <option value="30" @if(isset($trainerService->buffer_time)) @if($trainerService->buffer_time == "30") selected @endif @endif>30 Min</option>
                        <option value="35" @if(isset($trainerService->buffer_time)) @if($trainerService->buffer_time == "35") selected @endif @endif>35 Min</option>
                        <option value="40" @if(isset($trainerService->buffer_time)) @if($trainerService->buffer_time == "40") selected @endif @endif>40 Min</option>
                        <option value="45" @if(isset($trainerService->buffer_time)) @if($trainerService->buffer_time == "45") selected @endif @endif>45 Min</option>
                        <option value="50" @if(isset($trainerService->buffer_time)) @if($trainerService->buffer_time == "50") selected @endif @endif>50 Min</option>
                        <option value="55" @if(isset($trainerService->buffer_time)) @if($trainerService->buffer_time == "55") selected @endif @endif>55 Min</option>
                      </select>

                      <!-- <input class="form-control" type="time" id="buffer_time" name="appt"> -->
                    </div>

                         <!-- Service Price -->
                                <div class="form-group col-lg-4 col-md-6">
                                  <label for="service-input">Service Price</label>
                                  <div class="clearfix"></div>
                                  <!--<div class="float-left" style="width: 44%;">
                                    <div class="list_style_form"><label class="radio-inline font_size_sml"><input type="radio"
                                          class="radio-inline font_size_sml" name="service_price_type" required value="monthly_price"
                                          @if(isset($trainerService->price_monthly)) @if($trainerService->price_monthly == "1") checked @endif @endif />
                                        Weekly Price </label></div>
                                    <div class="list_style_form"><label class="radio-inline font_size_sml"><input type="radio"
                                          class="radio-inline font_size_sml" name="service_price_type" required value="annual_price" @if(isset($trainerService->price_monthly)) @if($trainerService->price_weekly == "1") checked @endif @endif/>
                                        Monthly Price
                                      </label></div>
                                  </div>-->

                                  <div class="input-group service_price_input d-flex">
                                    <div class="input-group-prepend">
                                      <span class="input-group-text" id="basic-addon1">$</span>
                                    </div>
                                    <input type="text" class="form-control" name="price" required id="price-input" maxlength="30" placeholder="Service Price" value="@if(isset($trainerService->price)){{ $trainerService->price }}@else{{old('buffer_time')}}@endif">
                                  </div>


                                </div>
                <!-- Promo Code -->     
                    <div class="form-group col-lg-4 col-md-6">
                      <label for="sprice-input">Promo Code</label><br />
                      
                        <input type="checkbox" value="1" name="promo_code" @if(isset($trainerService->promo_code)) @if($trainerService->promo_code == "1") checked @endif @endif> <label>Allow Promo Code</label>
                    </div>
                 <!-- Booking Method -->
                    <div class="form-group col-lg-8 col-md-8">

                      <div class="clearfix"></div>

                      <li class="booking_method"><label class="radio-inline font_size_lg"><input type="radio"
                            class="radio-inline font_size_sml" name="book_type" id="book_type" required value="1"
                            @if(isset($trainerService->book_type)) @if($trainerService->book_type == "1") checked @endif @else checked @endif />
                          Instant Booking</label></li>
                      <li class="booking_method"><label class="radio-inline font_size_lg"><input type="radio"
                            class="radio-inline font_size_sml" name="book_type" id="book_type" required value="2" @if(isset($trainerService->book_type)) @if($trainerService->book_type == "2") checked @endif @endif/>
                          Request to Book
                        </label></li>


                      <div class="input-group">

                        <input type="text" class="form-control" name="auto_book_desc" id="auto_book" maxlength="30" placeholder="Athletes can book this service instantly"
                           value="@if(isset($trainerService->book_type)) @if($trainerService->book_type == '1') @if(isset($trainerService->desc)){{ $trainerService->desc }}@else{{old('auto_book_desc')}}@endif @endif @endif">
                          <input type="text" class="form-control" name="request_book_desc" id="request_book" maxlength="30" placeholder="All athletes must send you a request to book this service" value="@if(isset($trainerService->book_type))@if($trainerService->book_type == '2') @if(isset($trainerService->desc)){{ $trainerService->desc }}@else{{old('request_book_desc')}}@endif @endif @endif">
                      </div>


                    </div>


             <!-- Maximum Members Allowed -->
                    <div class="form-group col-lg-4 col-md-6" id="max_allowed_block" style="display: none;">
                      <label for="sprice-input">Maximum Members Allowed</label>
                      <input type="number" class="form-control" name="max_booking" id="max_allowed" maxlength="3"
                        placeholder="enter maximum members allowed" value="@if(isset($trainerService->max_bookings)){{ $trainerService->max_bookings }}@else{{old('max_booking')}}@endif">
                        @if ($errors->has('max_booking'))
                        <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('max_booking') }}</div>
                    @endif
                    </div>      

                                     
                                        <div class="form-group col-lg-12">
                                            <label for="massage-input">Description (700 CHARACTERS OR LESS)</label>
                                            <textarea class="form-control" name="message" id="massage-input" maxlength="700" placeholder="enter message here..." required>@if(isset($trainerService->message)){{strip_tags(htmlspecialchars_decode($trainerService->message))}}@else{{old('message')}}@endif</textarea>
                                            @if ($errors->has('message'))
                                                <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('message') }}</div>
                                            @endif
                                        </div>

                                        <div class="row mb-4">
                                        <div class="col-12 col-calendar">
                                          <div class="card">
                                            <div class="card-body p-lg-5">

                                              <h3>Set Hours of Availability</h3>
                                              <p>Note: The below availability is based on your hours of operation. you can overwrite the hours of
                                                specific dates and this will not impact your regular operation hours.</p>
                                                 <div id='trainerAvailability'></div>
                                            </div>
                                          </div>
                                        </div>
                                    </div>
                                       
                                        <div class="col-lg-12 d-flex justify-content-center">
                                            <input type="submit" value="Save Details" class="btn btn-danger btn-lg" />
                                        </div>
                                    </form>
                                 
                                </div>
                            </div>
                        </div>
                    </div>
                    

                    @include('front.trainer.layout.includes.footer')

                </div>
            </div>
        </div>

        <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" >
          <h4><span class="glyphicon glyphicon-lock"></span> Add Time Slot</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>  
        </div>
        <div class="modal-body">
        <div class="alert-danger"></div>
        <div class="alert-success"></div>
          <form role="form" id="bookingForm" method="POST">
           @csrf
            
            <div class="">
            
             <div class="">
               
          <div class="multi-field-wrapper">
               

                 <div class="multi-fields" id="timeslot" style="display:none">
                    <div class="multi-field form-group">
                    <input type="hidden" name="days" id="days">
                    <input type="hidden" name="serviceIDs" id="serviceIDs" value="@if(isset($trainerService->message)){{$trainerService->id}}@endif">
                     <input type="time" name="addtimeslot" id="addtimeslot" class="form-control w-25 p-3" value=""  style="float: left;margin-right:5px;" >
                
                     <input type="time" name="addtimeslot" id="addtimeslot" class="form-control w-25 p-3" value="" style="float: left;">
                 
                     <button type="button" class="btn btn-danger remove-field">☓</button>
                     <button type="button" class="add-field btn btn-info">Add Field</button>
                     <div class="clearfix"></div>
                     
                     
                   </div> 
                 </div>
               
             </div>
            </div>
              
          </form>
        </div>
        <div class="modal-footer">
          <button type="submit" id="bookingCancel" class="btn btn-danger btn-default pull-left" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
          <button type="submit" id="bookingSbmt" class="btn btn-success btn-default"><span class="glyphicon glyphicon-off"></span> Save</button>
        </div>
       
      </div>
      
    </div>
  </div> 
@endsection

@section('pagescript')

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css">
<script src="{{ asset('/front/js/bootstrap-timepicker.min.js') }}"></script>
<script src='https://unpkg.com/@fullcalendar/core@4.4.0/main.min.js'></script>
<script src='https://unpkg.com/@fullcalendar/interaction@4.4.0/main.min.js'></script>
<script src='https://unpkg.com/@fullcalendar/daygrid@4.4.0/main.min.js'></script>
<script src='https://unpkg.com/@fullcalendar/timegrid@4.4.0/main.min.js'></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

<style>
label.radio-inline{
  margin: 0 10px 0 0;
}
.checkRecurring{
 margin-right: 8px;  
 float: right;
 margin: 2px 10px;
}

.remove-field{
    margin:0 8px;
}

</style>
<script>
function readURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    
    reader.onload = function(e) {
      $('#show_image').attr('src', e.target.result);
    }
    
    reader.readAsDataURL(input.files[0]); // convert to base64 string
  }
}



$("#service_image").change(function() {
  readURL(this);
});
function setMultiFieldEvent(){
$('.multi-field-wrapper').each(function() {

    var $wrapper = $('.multi-fields', this);
console.info($wrapper);
    $(".add-field", $(this)).off('click').on('click', function(e) {
   
        $('.multi-field:nth(0)', $wrapper).clone(true).appendTo($wrapper).find('input').val('').focus();
    });
    $('.multi-field .remove-field', $wrapper).click(function() {
console.info('Clicked');        
        if ($('.multi-field', $wrapper).length > 1)
            $(this).parent('.multi-field').remove();
    });
});

}
$( document ).ready(function() {
setMultiFieldEvent();

var radioValue = $("input[name='book_type']:checked").val();

if (radioValue == "1") {
        $('#auto_book').removeAttr('disabled');
        $('#request_book').attr('disabled', 'disabled');
    } 

if (radioValue == "2") {
        $('#request_book').removeAttr('disabled');
        $('#auto_book').attr('disabled', 'disabled');
    } 

var buffer_times = $('#buffer_times').val();

if(buffer_times != ''){
    $('.display_none_maxi').addClass('openBtn');
} 

$('#price-input').keypress(function (e) {    
    
    var charCode = (e.which) ? e.which : event.keyCode    

    if (String.fromCharCode(charCode).match(/[^0-9]/g))    

        return false;                        

}); 

$("form input:radio").change(function () {

    if ($(this).val() == "1") {
        $('#auto_book').removeAttr('disabled');
        $('#request_book').attr('disabled', 'disabled');
    } else {
        
        $('#request_book').removeAttr('disabled');
        $('#auto_book').attr('disabled', 'disabled');
    }
});

var option = $('#service_type').val();

      if (option == 'In person - Group Appointment' || option == 'Virtual - Group Appointment') {
        //$('#max_allowed').val("");
        $('#max_allowed_block').css('display', 'block');
      }
      else {
        $('#max_allowed_block').css('display', 'none');
      }

      if (option == 'In person - Single Appointment' || option == 'In person - Group Appointment' || option == 'Virtual - Single Appointment' || option == 'Virtual - Group Appointment') {
        $('.col-calendar').css('display', 'block');
        $('.durationBlock').css('display', 'block');
       
      }
      else {
        $('.col-calendar').css('display', 'none');
        $('.durationBlock').css('display', 'none');
        
      }

      if (option == 'In person - Single Appointment' || option == 'In person - Group Appointment' || option == 'Virtual - Single Appointment' || option == 'Virtual - Group Appointment') {
        $('.col-calendar').css('display', 'block');
        $('.durationBlock').css('display', 'block');
       
      }
      else {
        $('.col-calendar').css('display', 'none');
        $('.durationBlock').css('display', 'none');
        
      }

if ($('input.checkRecurring').is(':checked')) { //console.log("checked");
    $('.recurring').show();
    $('#weekly-input').prop('required', true);
    //$('#monthly-input').prop('required', true);
    $('.singlePrice').hide();
    //$('#sprice-input').prop('required', false);
}else{
    $('.recurring').hide();
    $('#weekly-input').prop('required', false);
    //$('#monthly-input').prop('required', false);
    $('.singlePrice').show();
    //$('#sprice-input').prop('required', true);
}


$("input[name='isRecuring']").change(function() {
        if(this.checked) {
            $('.singlePrice').hide();
            //$('#sprice-input').prop('required', false);
            $('.recurring').show();
            $('#weekly-input').prop('required', true);
            //$('#monthly-input').prop('required', true);
        }else{
            $('.singlePrice').show();
            //$('#sprice-input').prop('required', true);
            $('.recurring').hide();
            $('#weekly-input').prop('required', false);
            //$('#monthly-input').prop('required', false);
        }
     });
});

</script>

<script type="text/javascript">
    $('#timepicker1').timepicker({
      showInputs: false,
      format: 'HH:mm'
    });
    $('#buffer_time').timepicker({
      showInputs: false
    });
  </script>
<script>
    $('.buffer_time').click(function () {
      if ($('.display_none_maxi').hasClass('openBtn')) {
        $('.display_none_maxi').removeClass('openBtn');
        $('#buffer_time').val("");
      } else {
        $('.display_none_maxi').addClass('openBtn');
      }
    });


    $('#service_type').on('change', function () {
      var option = $(this).val();

      if (option == 'In person - Group Appointment' || option == 'Virtual - Group Appointment') {
        $('#max_allowed').val("");
        $('#max_allowed_block').css('display', 'block');
      }
      else {
        $('#max_allowed_block').css('display', 'none');
      }

      if (option == 'In person - Single Appointment' || option == 'In person - Group Appointment' || option == 'Virtual - Single Appointment' || option == 'Virtual - Group Appointment') {
        $('.col-calendar').css('display', 'block');
        $('.durationBlock').css('display', 'block');

       
      }
      else {
        $('.col-calendar').css('display', 'none');
        $('.durationBlock').css('display', 'none');
        // $('#hours').attr('value', '1');
        $('#hours').removeAttr('required');
        
      }
    });

/* Calendar Customization */
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('trainerAvailability');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        plugins: [ 'interaction', 'dayGrid', 'timeGrid', 'list' ],
        selectable: true,
        timeZone: 'UTC',
        defaultView: 'dayGridWeek',
        header: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,dayGridWeek,listWeek',
        },
        dateClick: function(info) {
            
        },
        select: function(info) {
        
        var days = new Date(info.startStr).getDay();
        $('#days').val(days);
        $('#timeslot').show();
            $('addtimeslot').val('');
            setMultiFieldEvent();
            $("#myModal").modal();
        
        },
        editable: true,
        eventLimit: true,
        
        /* EVENT FORMAT */
        events: <?php echo $eventData; ?>,
        
         eventRender: function (info) {
           /*var title = info.event.title;
           var desc = info.event._def.extendedProps.description;
      
            $(info.el).popover({
              html: true, 
              title: title,
              placement:'top',
              trigger : 'hover',
              content: desc,
              container:'body',
            }).popover('show');*/
        },
          eventClick: function(info) {

          $('#addtimeslot').hide();
        $('#timeslot').show();
        $('#timeslot').html(info.event._def.extendedProps.description);
        setMultiFieldEvent();
        $("#myModal").modal();

  },
    });

    calendar.render();

    
});
$('#bookingCancel').on('click', function(e) {
 location.reload();
})
$('#bookingSbmt').on('click', function(e) {
    e.preventDefault();
    var timeSlot = $( "#timeslot" ).val();
    $.ajax({
                url: '{{ URL::route('trainer.service.book') }}',
                type:"POST",
                data:{
                  "_token": "{{ csrf_token() }}",
                  days : $('#days').val(),
                  serviceIDs : $('#serviceIDs').val(),
                  timeSlot: $('#bookingForm').serializeArray(),
                },
                success:function(response){
                  jQuery('.alert-danger').html('');
                  jQuery('.alert-success').html('');
                  if(response.success){
                   // jQuery('.alert-success').show();
                    //jQuery('.alert-success').append('<p>'+response.success+'</p>');
                    $('#myModal').modal('hide');
                     Swal.fire(
                     'Added!',
                     'Time slot added.',
                     'success'
                     );
                     setTimeout(function(){// wait for 5 secs(2)
                        location.reload(); // then reload the page.(3)
                    }, 2000); 
                  }
                  if(response.errors){
                      $.each(response.errors, function(key, value){
                            jQuery('.alert-danger').show();
                            jQuery('.alert-danger').append('<p>'+value+'</p>');
                        });
                  }
                  if(response.fail){
                    jQuery('.alert-danger').show();
                    jQuery('.alert-danger').append('<p>'+response.fail+'</p>');
                  }
                },
              });
    return false;
});
  </script>
  

@endsection