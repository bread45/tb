@extends('front.trainer.layout.trainer')
@section('title', 'Add Event')
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
                        
                      <h1 class="h3 text-muted font-weight-normal mb-0 mt-lg-0 mt-3 order-lg-0 order-2">Add /Edit Event</h1> 
                        @include('front.trainer.layout.includes.header')
                    </div>

                    @if($providerStatus[0]->is_subscription)
                    @if($trailingProviderOrders < 1)
                    @if($providerOrdersCount < 1)
                    <div class="popup popup-danger text-center" role="alert">
                    Your subscription is cancelled and your page will not be visible to the public until you activate your account again, You can reactivate your subscription in the account information tab.
                    </div>
                    @endif
                    @endif
                    @endif


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

                                <form class="form-row add-event-form" method="POST" action="{{route('trainer.events.store')}}" enctype='multipart/form-data'>
                                        @csrf
                                        <input type="hidden" name="event_id" id="event_id" value="@if(isset($events[0]->id)){{ $events[0]->id }}@else{{old('id')}}@endif">
                            <!-- Event Name -->
                                    <div class="form-group col-lg-4 col-md-6">
                                    <label for="sprice-input">EVENT TITLE</label> <span style="font-size:12px;float:right">(50 character max)</span>
                                        <input type="text" class="form-control" name="title" required id="title-input" placeholder="Enter Event Title" maxlength="50" value="@if(isset($events[0]->title)){{ $events[0]->title }}@else{{old('title')}}@endif">
                                            @if ($errors->has('title'))
                                            <div style="display: block;" id="title-error" class="error invalid-feedback">{{ $errors->first('title') }}</div>
                                            @endif
                                    </div>
                                <!-- Event Category -->
                                <div class="form-group col-lg-4 col-md-6">
                                              <label for="type">EVENT CATEGORY</label>
                                              <select name="category" class="form-control" required id="event_category">
                                                <option value="Running event" @if(isset($events[0]->type)) @if($events[0]->category == "Running event") selected @endif @endif>Running event</option>
                                                <option value="Cycling event" @if(isset($events[0]->type)) @if($events[0]->category == "Cycling event") selected @endif @endif>Cycling event</option>
                                                <option value="Triathlon event" @if(isset($events[0]->type)) @if($events[0]->category == "Triathlon event") selected @endif @endif>Triathlon event</option>
                                                <option value="Other group sport event" @if(isset($events[0]->type)) @if($events[0]->category == "Other group sport event") selected @endif @endif>Other group sport event</option>
                                                <option value="Workshop" @if(isset($events[0]->type)) @if($events[0]->category == "Workshop") selected @endif @endif>Workshop</option>
                                                <option value="Webinar" @if(isset($events[0]->type)) @if($events[0]->category == "Webinar") selected @endif @endif>Webinar</option>
                                                <option value="Training Camp" @if(isset($events[0]->type)) @if($events[0]->category == "Training Camp") selected @endif @endif>Training Camp</option>
                                              </select>
                                            </div>

                                @if(empty($StripeAccountsdata))
                                <!-- Event Type -->
                                <div class="form-group col-lg-4 col-md-6">
                                              <label for="type">EVENT TYPE</label>
                                              <select name="type" class="form-control" required id="event_type">
                                                <option value="Free" @if(isset($events[0]->type)) @if($events[0]->type == "Free") selected @endif @endif>Free</option>
                                                <option value="Paid" @if(isset($events[0]->type)) @if($events[0]->type == "Paid") selected @endif @endif disabled>Paid</option>
                                              </select>
                                            </div>
                                @else
                                <div class="form-group col-lg-4 col-md-6">
                                              <label for="type">EVENT TYPE</label>
                                              <select name="type" class="form-control" required id="event_type">
                                                <option value="Free" @if(isset($events[0]->type)) @if($events[0]->type == "Free") selected @endif @endif>Free</option>
                                                <option value="Paid" @if(isset($events[0]->type)) @if($events[0]->type == "Paid") selected @endif @endif>Paid</option>
                                     </select>
                                </div>
                                @endif
                                        <!-- Event Cost -->
                                <div class="form-group col-lg-4 col-md-6 event_cost_block" style="display:none;">
                                              <label for="cost">EVENT COST</label>
                                              <input type="text" autocomplete="off" id="cost_allowed" pattern="\d*" maxlength="4" name="cost" class="form-control" value="@if(isset($events[0]->cost)){{ $events[0]->cost }}@else{{old('cost')}}@endif" />
                                            </div>

                                 <!-- Event Format -->
                                 <div class="form-group col-lg-4 col-md-6">
                                              <label for="format">EVENT FORMAT</label>
                                              <select name="format" class="form-control" required id="event_format">
                                                <option value="In Person" @if(isset($events[0]->format)) @if($events[0]->format == "In Person") selected @endif @endif>In Person</option>
                                                <option value="Virtual" @if(isset($events[0]->format)) @if($events[0]->format == "Virtual") selected @endif @endif>Virtual</option>
                                              </select>
                                            </div>
                                <!-- Event url -->
                                 <div class="form-group col-lg-4 col-md-6 event_url_block">
                                              <label for="url">EVENT URL</label>
                                              <input type="text" class="form-control" name="url" id="event_url" value="@if(isset($events[0]->url)){{ $events[0]->url }}@else{{old('url')}}@endif"  /> 
                                            </div>

                                <!-- Event Venue -->
                                 <div class="form-group col-lg-4 col-md-6 event_venue_block">
                                              <label for="venue">EVENT VENUE</label>
                                              <input type="text" class="form-control" name="venue" id="autocomplete" value="@if(isset($events[0]->venue)){{ $events[0]->venue }}@else{{old('venue')}}@endif"  /> 

                                            </div>
                                 <!-- Event Start Date -->
                                 <div class="form-group col-lg-4 col-md-6">
                                              <label for="start_date">EVENT START DATE</label>
                                              <input type="text" id="start_date" name="start_date" class="form-control" autocomplete="off" value="@if(isset($events[0]->start_date)){{ $events[0]->start_date }}@else{{old('start_date')}}@endif" required  />
                                            </div>

                                 <!-- Event start Time -->
                                 <div class="form-group col-lg-4 col-md-6">
                                              <label for="start_time">EVENT START TIME</label>
                                              <input type="text" id="start_time" name="start_time" class="form-control" autocomplete="off" value="@if(isset($events[0]->start_time)){{ $events[0]->start_time }}@else{{old('start_time')}}@endif" required />
                                            </div>            

                                <!-- Event End Date/Time -->
                                        <div class="form-group col-lg-4 col-md-6">
                                              <label for="end_time">EVENT END TIME</label>
                                              <input type="text" id="end_time" name="end_time" class="form-control" autocomplete="off" value="@if(isset($events[0]->end_time)){{ $events[0]->end_time }}@else{{old('end_time')}}@endif" required />
                                            </div>

                                <!-- Maximum Members Allowed -->
                                           <div class="form-group col-lg-4 col-md-6" id="max_allowed_block">
                                             <label for="sprice-input">Maximum Attendees (Optional)</label>
                                             <input type="text" pattern="\d*" maxlength="3" class="form-control" name="members_allowed" id="max_allowed"
                                               placeholder="Enter Maximum Members Allowed" value="@if(isset($events[0]->members_allowed)){{ $events[0]->members_allowed }}@else{{old('members_allowed')}}@endif">
                                               @if ($errors->has('members_allowed'))
                                               <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('members_allowed') }}</div>
                                           @endif
                                           </div>      

                                <!-- Promo Code -->     
                                <div class="form-group col-lg-4 col-md-6 accept_promo_block" style="display:none;">
                                  <label>Promo Code</label><br />
                                  <input type="checkbox" value="1" name="accept_promo" id="accept_promo" @if(isset($events[0]->accept_promo)) @if($events[0]->accept_promo == "1") checked @endif @endif> <label for="accept_promo">Allow Promo Code</label>
                                </div>
                                     
                                <div class="form-group col-lg-12">
                                    <label for="massage-input">Description (2000 CHARACTERS OR LESS)</label>
                                    <textarea class="form-control ckeditor" name="description" id="massage-input" maxlength="2000" placeholder="Enter Description Here..." required><?php if(isset($events[0]->description)){echo $events[0]->description;} ?></textarea>
                                    @if ($errors->has('description'))
                                        <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('description') }}</div>
                                    @endif
                                </div>

                                @if((isset($events[0]->event_id) && ($events[0]->is_recurring == '1')) || !(isset($events[0]->is_recurring)))

                               <!-- Event Recurring -->     
                                <div class="form-group col-lg-12 col-md-12 recurring_block">
                                  <label>Recurring Event</label>
                                  <label class="switch">
                                        <input type="checkbox" value="1" name="is_recurring" id="is_recurring" @if(isset($events[0]->is_recurring)) @if($events[0]->is_recurring == "1") checked @endif @endif>
                                        <span class="slider round"></span>
                                      </label>
                                </div>
                                
                                <!-- Recurring Type -->
                                <div class="recurring_type_block" style="width: 100%;display:none;">
                                <div class="form-group col-lg-4 col-md-6">
                                    <select name="repeat_type" class="form-control" id="repeat_type">
                                    
                                       <!--<option  value="Daily" @if(isset($events[0]->recurring_type)) @if($events[0]->recurring_type == "Daily") selected @endif @endif>Daily</option> -->
                                      <option value="Weekly" @if(isset($events[0]->recurring_type)) @if($events[0]->recurring_type == "Weekly") selected @endif @endif>Weekly</option>
                                       <!--<option id="MonthlyRecuring" value="Monthly" @if(isset($events[0]->recurring_type)) @if($events[0]->recurring_type == "Monthly") selected @endif @endif>Monthly</option> -->
                                    </select>
                                  </div>
                                  <div class="recurring_fields_block col-12 " style="padding: 0;">
                                  <div class="col-12 " style="padding: 0;overflow: hidden;">
                                    <!-- Recurring Days -->
                                    <div class="recurring_days form-group col-lg-12 col-md-12">
                                    <label for="recurring_day" style="width: 100%;">Select Which Days</label>
                                    <div class="clearfix"></div>
                                    <label>
                                      <input type="checkbox" name="recurring_day[]" value="monday" @if(isset($events[0]->recurring_day)) @if($events[0]->recurring_day == "Monday") checked @endif @else @endif />
                                      <span>Monday</span>
                                    </label>
                                    <label>
                                      <input type="checkbox" name="recurring_day[]" value="tuesday" @if(isset($events[0]->recurring_day)) @if($events[0]->recurring_day == "Tuesday") checked @endif @endif />
                                      <span>Tuesday</span>
                                    </label>
                                    <label>
                                      <input type="checkbox" name="recurring_day[]" value="wednesday" @if(isset($events[0]->recurring_day)) @if($events[0]->recurring_day == "Wednesday") checked @endif @endif />
                                      <span>Wednesday</span>
                                    </label>
                                    <label>
                                      <input type="checkbox" name="recurring_day[]" value="thursday" @if(isset($events[0]->recurring_day)) @if($events[0]->recurring_day == "Thursday") checked @endif @endif />
                                      <span>Thursday</span>
                                    </label>
                                    <label>
                                      <input type="checkbox" name="recurring_day[]" value="friday" @if(isset($events[0]->recurring_day)) @if($events[0]->recurring_day == "Friday") checked @endif @endif />
                                      <span>Friday</span>
                                    </label>
                                    <label>
                                      <input type="checkbox" name="recurring_day[]" value="saturday" @if(isset($events[0]->recurring_day)) @if($events[0]->recurring_day == "Saturday") checked @endif @endif />
                                      <span>Saturday</span>
                                    </label>
                                    <label>
                                      <input type="checkbox" name="recurring_day[]" value="sunday" @if(isset($events[0]->recurring_day)) @if($events[0]->recurring_day == "Sunday") checked @endif @else  @endif />
                                      <span>Sunday</span>
                                    </label>
                                    </div>
                                    <div class="clearfix"></div>
                                    </div> 
                                    
                                    <!-- Recurring end date -->     
                                    <div class="form-group col-lg-12 col-md-12">
                                      <input type="checkbox" value="1" name="recurring_end" id="recurring_end" @if(isset($events[0]->recurring_end)) @if($events[0]->recurring_end == "1") checked @endif @endif style="display:none;"> <label> Set an end date</label>
                                    </div>
                                    <div class="clearfix"></div>
                                    <!-- Event End Date/Time -->
                                    <div class="form-group col-lg-4 col-md-6 recurring_end_date_block" style="display: none;">
                                                  <label for="recurring_end_date">END DATE</label>
                                                  <input type="text" id="recurring_end_date" name="recurring_end_date" class="form-control" value="@if(isset($events[0]->recurring_end_date)){{ $events[0]->recurring_end_date }}@else{{old('recurring_end_date')}}@endif"  />
                                    </div>

                                    </div>
                                    </div>
                                    @endif
                                        <div class="col-lg-12 d-flex justify-content-center">
                                            <input type="submit" value="Save Details" id="submit_event" class="btn btn-danger btn-lg" />
                                        </div>
                                    </form>
                                 
                                
                            </div>
                        </div>
                    </div>
                    

                    @include('front.trainer.layout.includes.footer')

                </div>
            </div>
        </div>

</div>
@endsection

@section('pagescript')
<style>
#DailyRecuring, #MonthlyRecuring{
  display: none;
}
</style>
<script src="https://code.jquery.com/jquery-3.4.1.js"></script>
<link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="{{ asset('/front/css/bootstrap-datetimepicker.min.css') }}">
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.21.0/moment.min.js" type="text/javascript"></script>
<script src="{{ asset('/front/js/bootstrap-datetimepicker.min.js') }}"></script>
<link rel="stylesheet" type="text/css" href="{{ asset('../front/css/croppie.css') }}"> 
<script src="{{ asset('../front/js/croppie.js') }}"></script> 
<script src="https://www.trainingblockusa.com//vendor/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.7/jquery.validate.min.js"></script>

<script type="text/javascript">
    $(function () {
        var strDate = $('#start_date').val();
        if(strDate.length <= 0 ){
          var date = new Date();
          strDate = date.getFullYear() + "/" + (date.getMonth()+1) + "/" + date.getDate();
        }
        else {
          var oldDate = new Date(strDate);
          strDate = oldDate.getFullYear() + "/" + (oldDate.getMonth()+1) + "/" + oldDate.getDate();
        }
        $('#start_date').datetimepicker({
          format: 'MM/DD/YYYY',
          minDate: strDate
        });

        $("#recurring_end_date").datetimepicker({
          format: 'MM/DD/YYYY'
        });
        
        $("#start_date").on("dp.change", function (e) {
           $('#recurring_end_date').data("DateTimePicker").minDate(e.date);
       });

        $('#start_time').datetimepicker({
          format: 'hh:mm A'
        });
        $('#end_time').datetimepicker({
          format: 'hh:mm A'
        });

        if($('#event_type').val() == 'Paid'){ 
        $('.event_cost_block').css('display', 'block');
        $('.accept_promo_block').css('display', 'block');
        $('.event_cost_block input').attr('required', '');
        
      }
      else 
      {
        $('.event_cost_block').css('display', 'none');
        $('.accept_promo_block').css('display', 'none');
        $('.event_cost_block input').removeAttr('required');
      }

      if($('#repeat_type').val() == 'Weekly'){ 
        $('.recurring_days').css('display', 'block');
      }
      else 
      {
        $('.recurring_days').css('display', 'none');
      }

      if($('#event_format').val() == 'Virtual'){ 
        $('.event_venue_block').css('display', 'none');
        $('.event_venue_block input').removeAttr('required');
        $('.event_url_block').css('display', 'block');
        // $('.event_url_block input').attr('required', '');
      }
      else 
      {
        $('.event_venue_block').css('display', 'block');
        $('.event_venue_block input').attr('required', '');
        $('.event_url_block').css('display', 'none');
        // $('.event_url_block input').removeAttr('required');
      }

      if($('#is_recurring').is(':checked')){
        $('.recurring_end_date_block').css('display', 'block');
        $('.recurring_type_block').css('display', 'block');
      }
      else {
        // $('.recurring_days').css('display', 'none');
      }





        // Event Venue show and hide condition
        $('#event_format').on('change', function() {
          if( $(this).val() == 'Virtual'){
              $('.event_venue_block').css('display', 'none');
              $('.event_venue_block input').removeAttr('required');
              $('.event_url_block').css('display', 'block');
              // $('.event_url_block input').attr('required', '');
          }
          else {
            $('.event_venue_block').css('display', 'block');
            $('.event_venue_block input').attr('required', '');
            $('.event_url_block').css('display', 'none');
              // $('.event_url_block input').removeAttr('required');
          }
      });

       // Event Recurring block show and hide condition
       $('#is_recurring').change(function() {
          if( $(this).is(':checked')){
              $('.recurring_type_block').css('display', 'block');
              $('#recurring_end').attr('checked', '');
              $('.recurring_end_date_block').css('display', 'block');
              $('#recurring_end_date').attr('required', '');
          }
          else {
            $('.recurring_type_block').css('display', 'none');
          }
      });

      // Event Recurring end date show and hide condition
      $('#recurring_end').change(function() {
          if( $(this).is(':checked')){
              $('.recurring_end_date_block').css('display', 'block');
          }
          else {
            $('.recurring_end_date_block').css('display', 'none');
          }
      });

      // Recurring Days show and hide condition
      $('#repeat_type').on('change', function() {
          if( $(this).val() == 'Weekly'){
              $('.recurring_days').css('display', 'block');
          }
          else {
            $('.recurring_days').css('display', 'none');
          }
      });


      // Event Cost condition
      $('#event_type').on('change', function() {
          if( $(this).val() == 'Paid'){
            $('.event_cost_block').css('display', 'block');
            $('.accept_promo_block').css('display', 'block');
            $('.event_cost_block input').attr('required', '');
          }
          else {
            $('.event_cost_block').css('display', 'none');
            $('.accept_promo_block').css('display', 'none');
            $('.event_cost_block input').removeAttr('required');
          }
      });

        // Recurring End Date Condition
        $('#recurring_end').change(function() {
          if( $(this).is(':checked')){
              $('#recurring_end_date').attr('required', '');
          }
          else {
            $('#recurring_end_date').removeAttr('required');
          }
      });

      });
</script>
<style>
/* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}

.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
.recurring_block label {
  line-height : 2.5;
}

.recurring_days label {
	 display: flex;
	 cursor: pointer;
	 font-weight: 500;
	 position: relative;
	 overflow: hidden;
	 margin-bottom: 1.375em;
   float: left;
   margin-right : 10px;
}
 .recurring_days label input {
	 position: absolute;
	 left: -9999px;
}
 .recurring_days label input:checked + span {
	 background-color: #d6d6e5;
}
 .recurring_days label input:checked + span:before {
	 box-shadow: inset 0 0 0 0.4375em #00005c;
}
 .recurring_days label span {
	 display: flex;
	 align-items: center;
	 padding: 0.375em 0.75em 0.375em 0.375em;
	 border-radius: 99em;
	 transition: 0.25s ease;
}
 .recurring_days label span:hover {
	 background-color: #d6d6e5;
}
 .recurring_days label span:before {
	 display: flex;
	 flex-shrink: 0;
	 content: "";
	 background-color: #fff;
	 width: 1.5em;
	 height: 1.5em;
	 border-radius: 50%;
	 margin-right: 0.375em;
	 transition: 0.25s ease;
	 box-shadow: inset 0 0 0 0.125em #00005c;
}
.recurring_days {
  margin : 10px 0;
}

</style>

    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script> -->
    <!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script> -->
  



    <script type="text/javascript"
        src="https://maps.google.com/maps/api/js?key={{ env('GOOGLE_MAP_KEY') }}&libraries=places" ></script>
    <script>
        $(document).ready(function () {
            $("#latitudeArea").addClass("d-none");
            $("#longtitudeArea").addClass("d-none");
        });
    </script>
    <script>
        google.maps.event.addDomListener(window, 'load', initialize);
  
        function initialize() {
            var input = document.getElementById('autocomplete');
            var autocomplete = new google.maps.places.Autocomplete(input);
  
            autocomplete.addListener('place_changed', function () {
                var place = autocomplete.getPlace();
                $('#latitude').val(place.geometry['location'].lat());
                $('#longitude').val(place.geometry['location'].lng());
  
                $("#latitudeArea").removeClass("d-none");
                $("#longtitudeArea").removeClass("d-none");
            });
        }
    </script> 
    <script>
  
var editor = CKEDITOR.replace( 'massage-input', {
    language: 'en',
});


editor.on( 'required', function( evt ) {
    editor.showNotification( 'This field is required.', 'warning' );   
    evt.cancel();
    
} );

$( ".btn-danger" ).click(function() {
  var editorData= CKEDITOR.instances['massage-input'].getData();
  if(editorData !=''){
    return true;
  } else {
    setTimeout(function(){ $('#cke_notifications_area_massage-input').hide(); }, 500);    
  }
  
});


    $(document).ready(function () {
        $('form.add-event-form').on('submit', function(e){
          var desc = $('#massage-input').val();
          var event_id = $('#event_id').val();
          if(desc != ''){
            if(event_id == ''){
              e.preventDefault();
            }
            $('#submit_event').attr('disabled', 'true');
          }
        });
    });

    $('#max_allowed').keypress(function (e) {    
    var charCode = (e.which) ? e.which : event.keyCode    
    if (String.fromCharCode(charCode).match(/[^0-9]/g)){
        return false;           
      }
    }); 

    $("#cost_allowed").keyup(function(){
        var value = $(this).val();
        value = value.replace(/^(0*)/,"");
        $(this).val(value);
    });

    $('#cost_allowed').keypress(function (e) {    
    var charCode = (e.which) ? e.which : event.keyCode    
    if (String.fromCharCode(charCode).match(/[^0-9]/g)){
        return false;           
      }
    }); 

    $("#max_allowed").keyup(function(){
        var value = $(this).val();
        value = value.replace(/^(0*)/,"");
        $(this).val(value);
    });

    </script>
    <script>
      $('#cost_allowed, #max_allowed').bind('copy paste cut',function(e) {
  e.preventDefault();
      });
    </script>

@endsection