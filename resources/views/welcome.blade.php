@extends('admin.layouts.default')
@section('title', 'Event Me Now')
@section('content')
<style>
    .fc-content, .fc-event, .fc-event-dot {
    background: #44859b;
    color: #fff;
    border-color: #44859b;
}
.fc-unthemed .fc-event .fc-time, .fc-unthemed .fc-event-dot .fc-time{
    color: #fff;
}
.fc-unthemed .fc-event .fc-title, .fc-unthemed .fc-event-dot .fc-title{
    color: #fff;
}
.card {
    border-radius: 5px;
    box-shadow: 0px 19px 29px 0px rgba(44, 110, 168, 0.05);
    border: none;
}
.bg-danger {
    background-color: #cf5260 !important;
}
</style>
<!-- begin:: Content Head -->
<div class="kt-subheader  kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">Dashboard</h3>

        </div>

    </div>
</div>

<!-- end:: Content Head -->

<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

    <!--Begin::Dashboard 1-->

    <!--Begin::Row-->
    <div class="row mb-4 pb-2">
         @foreach($services as $trainerService)
                        <div class="col-xl-4 col-lg-4 mb-xl-0 mb-4">
                            <div class="card bg-danger h-100 justify-content-center">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col text-white pl-3">
                                            <h4 class="mb-0 font-weight-normal">{{$trainerService->service->name}}</h4>
                                            <h3 class="h1 mb-0 font-weight-normal">{{$trainerService->count}}</h3>
                                        </div>
                                        <div class="col-auto pr-2">
                                            <img style="max-width:45px;" src="{{asset('front/images/'.$trainerService->service->white_icon)}}" alt="Yoga" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
    </div>
   
<!--    <div class="row mb-4">
        <div class="col-12">
            <div class="block-title">
                <h2 class="font-weight-normal">Availability</h2>
            </div>
            <div class="card">
                <div class="card-body p-lg-3 p-2">
                    <div id='dashboard_calendar'></div>
                </div>
            </div>
        </div>
    </div>-->
    <!-- end:: Content -->
    <div class="modal fade" id="contactus" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content ajax_content"></div>
        </div>
    </div>
    @stop
    @section('pagescript')
    <script>
        $(function () {
            $(document).on('click', '.view_contactus', function () {
                var id = $(this).attr('data-id');
                $.ajax({
                    url: id,
                    type: 'GET',
                    success: function (result) {
                        if (result.status == true) {
                            $('.ajax_content').html(result.data);
                            $('#contactus').modal('show');
                            table.draw(true);
                        } else {
                            toastr.error(result.Message);
                        }
                    }, error: function () {
                        toastr.error("Permission Denied!");
                    }
                });
            });
        });
//document.addEventListener('DOMContentLoaded', function() {
//    var calendarEl = document.getElementById('dashboard_calendar');
//
//    var calendar = new FullCalendar.Calendar(calendarEl, {
//        plugins: [ 'interaction', 'dayGrid', 'timeGrid', 'list' ],
//        timeZone: 'UTC',
//        defaultView: 'dayGridMonth',
//        header: {
//        left: 'prev,next today',
//        center: 'title',
//        right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
//        },
//        editable: true,
//        eventLimit: true,
//        
//        /* EVENT FORMAT */
//        events: <?php echo $eventData; ?>
//    });
//
//    calendar.render();
//});
    </script>
    @endsection