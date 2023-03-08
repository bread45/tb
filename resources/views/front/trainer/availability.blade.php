@extends('front.trainer.layout.trainer')
@section('title', 'Scheduling Management')
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
                        <h1 class="h3 text-muted font-weight-normal mb-0 mt-lg-0 mt-3 order-lg-0 order-2">Calendar</h1>
                        
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
                    <div class="alert alert-warning alert-dismissible fade show">
                    Review your upcoming appointments with athletes
                    </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                            <div class="col-12">
                                      <div class="">
                                        <div class="p-lg-5">

                                          <form class="form-row add-service-form" method="POST" action="{{ route('trainer.search.scheduling')}}">
                                          @csrf
                                            <input type="hidden" name="search" value="1">
                                            <div class="form-group col-lg-4 col-md-6">
                                              <label for="sprice-input">SERVICE NAME</label>
                                              <input type="text" class="form-control" name="service_name" id="name-input" maxlength="30" placeholder="enter service name" value="{{ $service_name }}">
                                            </div>
                                            <div class="form-group col-lg-4 col-md-6">
                                              <label for="sprice-input">SERVICE Category</label>
                                              <select name="service_category" class="form-control" id="service-input">
                                             <option value="">select service category</option>
                                             @foreach($services as $service)
                                              <option @if($service_category == $service->id) selected @endif value="{{ $service->id }}" >{{ $service->name }}</option> 
                                             @endforeach
                                         </select>
                                            </div>
                                            <div class="form-group col-lg-4 col-md-6">
                                              <label for="sprice-input">SERVICE Type</label>
                                              <select name="package_type" class="form-control" id="service-input">
                                              <option value="">select Service Type</option>
                                              
                                              <option value="In person - Single Appointment" @if($package_type == 'In person - Single Appointment') selected @endif>In person - Single Appointment</option>
                                              <option value="In person - Group Appointment" @if($package_type == 'In person - Group Appointment') selected @endif>In person - Group Appointment</option>
                                              <option value="Virtual - Single Appointment" @if($package_type == 'Virtual - Single Appointment') selected @endif>Virtual - Single Appointment</option>
                                              <option value="Virtual - Group Appointment" @if($package_type == 'Virtual - Group Appointment') selected @endif>Virtual - Group Appointment</option>
                                              </select>
                                            </div>
                                            <div class="form-group col-lg-4 col-md-6">
                                              <label for="sprice-input">Status</label>
                                              <select name="status" class="form-control" id="service-input">
                                              <option value="">select status</option>
                                              
                                              <option value="1" @if($status == 1) selected @endif>Active</option>
                                              <option value="2" @if($status == 2) selected @endif>Cancelled</option>
                                              </select>
                                            </div>
                                            <div class="col-lg-12 d-flex justify-content-center mb-1">
                                              <input type="submit" name="submit" value="Search" class="btn btn-success btn-lg" style="background-color: #07ad94;" />
                                            </div>
                                              <div class="col-lg-12 d-flex justify-content-center mb-1">
                                              <a href="{{ route('trainer.scheduling') }}"><input type="button" value="Reset" class="btn bg-transparent" style="color: #44859b;text-decoration: underline;"/></a>
                                            </div>
                                          </form>
                                          

                                        </div>
                                      </div>
                                    </div>
                                <div class="card-body p-lg-3 pb-lg-5 p-2 pb-3">
                                    <div id='trainerAvailability'></div>
                                    <div class="text-center mt-5">
                                        <!-- <a href="#" class="btn btn-danger btn-lg">Save availibility/Slot</a> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @include('front.trainer.layout.includes.footer')

                </div>
            </div>
        </div>

        <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" >
          <h4><span class="glyphicon glyphicon-lock"></span> Add Time Slot</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>  
        </div>
        <div class="modal-body" style="padding:1rem 1.5rem;">
        <div class="alert-danger"></div>
        <div class="alert-success"></div>
          <form role="form" id="bookingForm" method="POST">
           @csrf
            <div class="form-group">
              <label for="service"><span class="glyphicon glyphicon-user"></span> Service</label>
              <select  name="service" id="trainerService" class="form-control" required>
                    <option value="">Select service</option>
                    @foreach($trainerServices as $trainerService)
                    <option value="{{$trainerService->id}}">{{$trainerService->name}}</option>
                    @endforeach
              </select>
              @if ($errors->has('service'))
                <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('service') }}</div>
              @endif 
            </div>
            <div class="form-group row">
              <div class="col-md-6">
                <label for="service"><span class="glyphicon glyphicon-user"></span> Start Date</label>
                <input required type="text" class="form-control" name="start_date" id="start_date" value="" readonly />
                @if ($errors->has('start_date'))
                <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('start_date') }}</div>
                @endif 
              </div>
              <div class="col-md-6">
                <label for="service"><span class="glyphicon glyphicon-user"></span> End Date</label>
                <input type="text" class="form-control" name="end_date" id="end_date" value=""  />
                @if ($errors->has('end_date'))
                <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('end_date') }}</div>
                @endif
              </div>
            </div>
            <div class="form-group">
              <label for="service"><span class="glyphicon glyphicon-user"></span> Time Slot</label>
              <select required name="timeslot" id="timeslot" class="form-control">
                    <option value="">Select Time</option>
                    @foreach($timeSlots as $timeSlot)
                    <option value="{{$timeSlot}}">{{$timeSlot}}</option>
                    @endforeach
              </select>
                @if ($errors->has('timeslot'))
                <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('timeslot') }}</div>
                @endif
            </div>
              
          </form>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-danger btn-default pull-left" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
          <button type="submit" id="bookingSbmt" class="btn btn-success btn-default"><span class="glyphicon glyphicon-off"></span> Save</button>
        </div>
       
      </div>
      
    </div>
  </div> 
@endsection

@section('pagescript')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src='https://unpkg.com/@fullcalendar/core@4.4.0/main.min.js'></script>
<script src='https://unpkg.com/@fullcalendar/interaction@4.4.0/main.min.js'></script>
<script src='https://unpkg.com/@fullcalendar/daygrid@4.4.0/main.min.js'></script>
<script src='https://unpkg.com/@fullcalendar/timegrid@4.4.0/main.min.js'></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<style>
.modal .form-control{
  height: 40px;
}
.modal .form-group {
  margin-bottom: 25px;
}
.fc-content-skeleton,.fc-widget-content {
    cursor: pointer;
}
</style>
<script>
$(function() {

$('#bookingSbmt').on('click', function(e) {
    e.preventDefault();
    var service = $( "#trainerService" ).val();
    var startDate = $( "#start_date" ).val();
    var endDate = $( "#end_date" ).val();
    var timeSlot = $( "#timeslot" ).val();
    $.ajax({
                url: '{{ URL::route('trainer.book') }}',
                type:"POST",
                data:{
                  "_token": "{{ csrf_token() }}",
                  service:service,
                  start_date:startDate,
                  end_date:endDate,
                  timeSlot:timeSlot,
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
        right: 'dayGridWeek,dayGridMonth,timeGridDay,listWeek',
        },
        dateClick: function(info) {
        //alert('clicked ' + info.dateStr);
        },
        select: function(info) {
        /*$('#start_date').val(info.startStr);
          $('#end_date').datepicker({
            dateFormat: 'yy-mm-dd',
            minDate: new Date(info.startStr),
          });
        $("#myModal").modal();*/
        
        },
        editable: true,
        eventLimit: true,
        
        /* EVENT FORMAT */
        events: <?php echo $eventData; ?>,
         eventRender: function (info) {
           var title = info.event.title;
           var desc = info.event._def.extendedProps.description;
      
            $(info.el).popover({
              html: true, 
              title: 'Appointment Details',
              placement:'top',
              trigger : 'hover',
              content: desc,
              container:'body',
            }).popover('show');
        }
    });

    calendar.render();

    
});


</script>


@endsection

