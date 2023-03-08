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
<div class="col-12 col-calendar">
                                          <div class="card">
                                            <div class="card-body p-lg-5">

                                              <h3>Hours of Availability</h3>
                                              <p>Note: The below availability is based on your hours of operation. You can overwrite the hours of
                                                specific dates and this will not impact your regular operation hours.</p>
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
</style>
<link rel="stylesheet" href="{{ asset('../front/css/calendar.min.css') }}">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css">
<script src='https://unpkg.com/@fullcalendar/core@4.4.0/main.min.js'></script>
<script src='https://unpkg.com/@fullcalendar/interaction@4.4.0/main.min.js'></script>
<script src='https://unpkg.com/@fullcalendar/daygrid@4.4.0/main.min.js'></script>
<script src='https://unpkg.com/@fullcalendar/timegrid@4.4.0/main.min.js'></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
 <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>

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

    calendar = new FullCalendar.Calendar(calendarEl, {
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
        
        /* EVENT FORMAT */
        events: <?php echo $eventData; ?>,
        
         eventRender: function (info) {
          /* var title = info.event.title;
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
          
          var start = info.event.start;
          var appointment_date = (new Date(start)).toISOString().slice(0, 10);
          var check = (new Date(start)).toISOString().slice(0, 10);
          var today = (new Date()).toISOString().slice(0, 10);
          
          if(check < today)
          {
            $('#trainerAvailability').fullCalendar('unselect');
                return false;
          }
          else
          {
              $.ajax({
              url: "{{url("servicebookdetails")}}/"+info.event.id,
              type: "GET",
              success: function(result) {
                
                var data = $.parseJSON(result);
                
                //alert(data.service_type);
                $('#timeslot').val(info.event.title);
                $('#trainer_id').val(info.event.id);
                $('#service_id').val(info.event.daysOfWeek);
                $('#week_days').val(data.days);
                $('#service_name').val(data.service_name);
                $('#service_category').val(data.service_category);
                $('#service_type').val(data.service_type);
                $('#service_price').val(data.service_price);
                $('#appointment_date').val(appointment_date);
                $("#myModal").modal();
                
              }
            })
          }
          
            
            

        },
    });

    calendar.render();

    
});
</script>