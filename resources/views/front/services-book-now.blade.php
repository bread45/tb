@extends('front.layout.app')
@section('title', 'Hours of Availability')
@section('content')
<section class="inner-banner-section bg-primary">
    <div class="container">
      <div class="banner-content">
        <h1>Book Now</h1>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{route('exploreservices')}}">Explore Training Services</a></li>
            <li class="breadcrumb-item active" aria-current="page">Login </li>
          </ol>
        </nav>
      </div>
    </div>
</section>
<section class="detail-img-list">
    <div class="container pl-lg-4 pr-lg-4">
      <div class="row justify-content-center">
      <?php

        /*$bookedtimeslot = DB::table('orders')->where(["service_id" => $serviceid, "user_id" => Auth::guard('front_auth')->user()->id])->get();
        $data_value = array();
        if(isset($bookedtimeslot)){
          foreach($bookedtimeslot as $timeslot){
                $data_value[] = array(
                   "event_id" => $timeslot->event_id,
                   "authelete_id" => $timeslot->user_id,
                   "appointment_date" => $timeslot->appointment_date,
                   "service_time" => $timeslot->service_time,
                   "days" => $timeslot->days
                );
          }
        }
         $values = json_encode($data_value);*/
         $get_provider_name = DB::select("select fu.spot_description,ts.name from trainer_services as ts join front_users as fu on ts.trainer_id=fu.id where ts.id='".$serviceid."'");
         //echo '<pre>';print_r($get_provider_name[0]->spot_description);
      ?>

      <?php 

$providerSchedulingDates = DB::table('provider_service_book')->where(["service_id" => $serviceid])->get();
$serviceDay = array();
$serviceDate = array();
$serviceTime = array();
foreach ($providerSchedulingDates as $value) {
        if($value->date !=''){
          $serviceDay[] = $value->days;
          $serviceDate[] = $value->date;
          $serviceTime[] = $value->time;
        } else {
          $serviceDay[] = $value->days;
          $serviceDate[] = 'Closed';
          $serviceTime[] = $value->time;
        }
        
    }
    $serviceDay = implode('/', $serviceDay);
    $serviceDate = implode('/', $serviceDate);
    $serviceTime = implode('/', $serviceTime);
    //Print_r($serviceDate);exit();
    ?>
    <input type="hidden" name="serviceDay" id="serviceDay" value="{{ $serviceDay }}">
    <input type="hidden" name="serviceDate" id="serviceDate" value="{{ $serviceDate }}">
    <input type="hidden" name="serviceTime" id="serviceTime" value="{{ $serviceTime }}">
      
<div class="col-12 col-calendar">
                                          <div class="card">

                                            <div class="card-body p-lg-5">
                                            <div class="col-lg-12 order-1 order-lg-0"> 
                                                  <a style="float:right;background-color: #00ab91;" class="btn btn-primary btn-sm" href="{{ url('/provider/')}}/{{ $get_provider_name[0]->spot_description }}#nav-services">Back</a>
                                                  </div>
<input type="hidden" name="order_timeslot" id="order_timeslot" value="">
                                              <h3>Appointment Times - <?php echo $get_provider_name[0]->name;?></h3>
                                              <!--<p>Note: The below availability is based on your hours of operation. You can overwrite the hours of
                                                specific dates and this will not impact your regular operation hours.</p>-->

                                               <br>
                                                 <div id='trainerAvailability'></div>
                                            </div>
                                          </div>
                                        </div>
        </div>
        </div>


        <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" >
          <h4><span class="glyphicon glyphicon-lock"></span> Appointment Details</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>  
        </div>
        <div class="modal-body" style="padding:1rem 1.5rem;">
        <div class="alert-danger"></div>
        <div class="alert-success"></div>
          <form role="form" id="bookingForm" method="POST" action="{{ url('customer/book-now-service') }}">
           @csrf
            <input type="hidden" name="week_days" id="week_days">
            <input type="hidden" name="trainer_id" id="trainer_id">
            <input type="hidden" name="service_id" id="service_id">
            <input type="hidden" name="event_id" id="event_id">
            <div class="form-group">
              <label for="service"><span class="glyphicon glyphicon-user"></span> Appointment Date</label>
              <input type="text" name="appointment_date" id="appointment_date" readonly class="form-control">
               
            </div>

            <div class="form-group">
              <label for="service"><span class="glyphicon glyphicon-user"></span> Service Name</label>
              <input type="text" name="service_name" id="service_name" readonly class="form-control">
               
            </div>

            <div class="form-group">
              <label for="service"><span class="glyphicon glyphicon-user"></span> Service Category</label>
              <input type="text" name="service_category" id="service_category" readonly class="form-control">
               
            </div>

            <div class="form-group">
              <label for="service"><span class="glyphicon glyphicon-user"></span> Service Type</label>
              <input type="text" name="service_type" id="service_type" readonly class="form-control">
               
            </div>

            <div class="form-group">
              <label for="service"><span class="glyphicon glyphicon-user"></span> Service Price $</label>
              <input type="text" name="service_price" id="service_price" readonly class="form-control">
               
            </div>

            <div class="form-group">
              <label for="service"><span class="glyphicon glyphicon-user"></span> Time Slot</label>
              <input type="text" name="timeslot" id="timeslot" readonly class="form-control">
               
            </div>
              
          
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-danger btn-default pull-left" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
          <button type="submit" id="bookingServiceSbmtsss" class="btn btn-success btn-default"><span class="glyphicon glyphicon-off"></span> BOOK NOW</a></button>
        </div>
       
      </div>
      </form>
    </div>
  </div> 
        </section>

@stop
@section('pagescript')
<style>
.fc .fc-axis, .fc button, .fc-day-grid-event .fc-content, .fc-list-item-marker, .fc-list-item-time, .fc-time-grid-event .fc-time, .fc-time-grid-event.fc-short .fc-content {
    white-space: break-spaces !important;
    padding: 9px;
    font-size: 12px;
}

.fc-event {
    position: relative;
    display: block;
    font-size: .85em;
    line-height: 1.3;
    border-radius: 3px;
    border: 1px solid #14b29a;
}
.fc-rigid, .fc-day-grid-container{
   height: auto !important;
}

.fc-dayGrid-view .fc-body .fc-row {
    min-height: 18em !important;
}
.fc-more-popover {
  max-height: 95%;
  overflow-y: auto;
}
.fc-unthemed .fc-content, .fc-unthemed .fc-divider, .fc-unthemed .fc-list-heading td, .fc-unthemed .fc-list-view, .fc-unthemed .fc-popover, .fc-unthemed .fc-row, .fc-unthemed tbody, .fc-unthemed td, .fc-unthemed th, .fc-unthemed thead{
  border-color: #fff;
}

.fc-content, .fc-event, .fc-event-dot {
    background: none !important;
    
    color: #2c3e50 !important;
    border: 1px solid #fff;
}
.fc-event, .fc-event:hover {
    
    border: 1px solid #00ab91;
}
  .btn-success:not(:disabled):not(.disabled).active, .btn-success:not(:disabled):not(.disabled):active, .show > .btn-success.dropdown-toggle {
    color: #fff;
    background-color: #00ab91 !important;
    border-color: #00ab91 !important;
}
.btn-success:not(:disabled):not(.disabled).active:focus, .btn-success:not(:disabled):not(.disabled):active:focus, .show > .btn-success.dropdown-toggle:focus {
    box-shadow: 0 0 0 .2rem rgb(0, 171, 145) !important;
}
.btn-success:hover {
    color: #fff;
    background-color: #00ab91 !important;
    border-color: #00ab91 !important;
}
.btn-success {
    color: #fff;
    background-color: #00ab91 !important;
}
.btn {
    font-size: 14px !important;
    height: 40px !important;
    line-height: 36px !important;
    padding: 0 14px !important;
}
.card{
  overflow: unset !important;
}
.fc-widget-header .fc-title {
    display: none;
    
}
.fc-unthemed .fc-popover .fc-header .fc-close {
    padding: 3px;
    float: right;
}
</style>
<link rel="stylesheet" href="{{ asset('../front/css/calendar.min.css') }}">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="{{ asset('../front/css/fullcalendar.min.css') }}">
<script src='https://unpkg.com/@fullcalendar/core@4.4.0/main.min.js'></script>
<script src='https://unpkg.com/@fullcalendar/interaction@4.4.0/main.min.js'></script>
<script src='https://unpkg.com/@fullcalendar/daygrid@4.4.0/main.min.js'></script>
<script src='https://unpkg.com/@fullcalendar/timegrid@4.4.0/main.min.js'></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
 <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="https://unpkg.com/@fullcalendar/interaction@4.4.0/main.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.js"></script>

<script>
  var calendar;
  var calendarEl;
$(document).ready(function() {

  var date = new Date();
  var d = date.getDate();
  var m = date.getMonth();
  var y = date.getFullYear();
  });

/* Calendar Customization */
document.addEventListener('DOMContentLoaded', function() {
    calendarEl = document.getElementById('trainerAvailability');
    var aryTitle= $('#serviceTime').val().split('/');
    var aryDate= $('#serviceDate').val().split('/');
    calendar = new FullCalendar.Calendar(calendarEl, {
        plugins: [ 'interaction', 'dayGrid', 'timeGrid', 'list' ],
        selectable: true,
       // timeZone: 'UTC',
        defaultView: 'dayGridWeek',
        validRange: {start: new Date()},
        header: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,dayGridWeek,listWeek',
        },
        dateClick: function(info) {
        //alert('clicked ' + info.dateStr);
        },
        select: function(info) {
            if(info.start.isBefore(moment())) {
                $('#trainerAvailability').fullCalendar('unselect');
                return false;
            }
            alert('clicked ' + info.dateStr);

        },
        editable: true,
        eventLimit: true,
        dayPopoverFormat: true,
        
        /* EVENT FORMAT */
        events: <?php echo $eventData; ?>,
        //eventColor: '#378006',
        
         eventRender: function (info) {
          var idxDate= aryDate.indexOf(info.event.start.toLocaleDateString('en-ca'));
            if(idxDate>-1){
                if(aryTitle[idxDate].split(',').indexOf(info.event.title)==-1){
                    return false;
                }else{
                    console.info(info.event.start.toLocaleDateString('en-ca'),' # ', aryTitle[idxDate],' # ', info.event.title);
                    aryTitle[idxDate]=aryTitle[idxDate].replace(info.event.title,'');

                }
            }
            info.el = $(info.el).attr('fc-date', info.event.start.toLocaleDateString('en-ca'))[0].outerHTML;
        },
        eventAfterAllRender: function(view){alert(view);},
        eventDrop: function(info) {
          info.revert();
        },
          eventClick: function(info) {
          
          var start = info.event.start;
          //var appointment_date = (new Date(start)).toISOString().slice(0, 10);
          //var check = (new Date(start)).toISOString().slice(0, 10);
          var today = (new Date()).toISOString().slice(0, 10);
          var currtime = moment(new Date()).format('HH:mm');
          var oTempDate = new Date(start),
          sMonth = '' + (oTempDate.getUTCMonth() + 1),
          sDay = '' + oTempDate.getUTCDate(),
          iYear = oTempDate.getUTCFullYear();

          if (sMonth.length < 2) { sMonth = '0' + sMonth; }
          if (sDay.length < 2) { sDay = '0' + sDay; }
          var appointment_date = sMonth + "-" + sDay + "-" + iYear;
          var check = iYear + "-" + sMonth + "-" + sDay;
      
          //alert(iYear + "-" + sMonth + "-" + sDay);
          if(check == today){
            if(info.event.title < currtime){
              alert('Sorry, you cannot select a time in the past.');
              return false;
            } 
          }
          if(info.event.title == 'Closed'){
            alert('Sorry, you cannot select a time in the closed.');
            return false;
          }
          if(check < today)
          {
            $('#trainerAvailability').fullCalendar('unselect');
                return false;
          }
          else
          {

              $.ajax({
              url: "{{url("servicebookdetails")}}/"+info.event.id,
              contentType: "application/json",
              dataType: "json",
              type: "GET",
              async: false,
              success: function(data) {
                //var data = $.parseJSON(result);
                //alert(data.service_id);

                  $.ajax({
                  //url: "{{url("confirmservicebookdetails")}}/"+data.service_id+"/"+info.event.title+"/"+appointment_date,
                  url: "{{url("confirmservicebookdetails")}}",                  
                  data: {"_token":"{{csrf_token()}}", "service_id" : data.service_id, "event_time" : info.event.title, "event_date" : appointment_date },
                  dataType: "json",                  
                  type: "POST",
                  async: false,
                  success: function(result_data) {
                      
                      if(data.service_type == 'In person - Single Appointment' || data.service_type == 'Virtual - Single Appointment'){

                          if(result_data.event_count == 1){
                              alert('Already Booked');
                                return false;
                            } else {
                              $('#timeslot').val(info.event.title);
                              $('#trainer_id').val(info.event.id);
                              $('#service_id').val(info.event.daysOfWeek);
                              $('#week_days').val(data.days);
                              $('#service_name').val(data.service_name);
                              $('#service_category').val(data.service_category);
                              $('#service_type').val(data.service_type);
                              $('#service_price').val(data.service_price);
                              $('#appointment_date').val(appointment_date);
                              $('#event_id').val(data.event_id);
                              $("#myModal").modal('show');
                            }

                      } else {
                          if(result_data.event_count < result_data.maximum_count){
                            $('#timeslot').val(info.event.title);
                            $('#trainer_id').val(info.event.id);
                            $('#service_id').val(info.event.daysOfWeek);
                            $('#week_days').val(data.days);
                            $('#service_name').val(data.service_name);
                            $('#service_category').val(data.service_category);
                            $('#service_type').val(data.service_type);
                            $('#service_price').val(data.service_price);
                            $('#appointment_date').val(appointment_date);
                            $('#event_id').val(data.event_id);
                            $("#myModal").modal('show');
                          } else {
                            alert('Already Booked');
                            return false;
                          }
                      }
                      

                        
                  }
                });
                
                
              }
            })
          }
          
            
            

        },
        eventPositioned: function(info){
          aryTitle= $('#serviceTime').val().split('/'); 
          aryDate= $('#serviceDate').val().split('/');
        },
        dayPopoverFormat: function(info){aryTitle= $('#serviceTime').val().split('/'); aryDate= $('#serviceDate').val().split('/');},
    });

    calendar.render();

    
});
</script>