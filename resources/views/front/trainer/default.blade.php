@extends('front.trainer.layout.trainer')
@section('title', 'Dashboard')
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
                        <h1 class="h3 text-muted font-weight-normal mb-0 mt-lg-0 mt-3 order-lg-0 order-2">Dashboard</h1>
                        
                        <?php 
                        if(Auth::guard('front_auth')->user()){
                        $user_role = Auth::guard('front_auth')->user()->user_role;                        
                        $trainer_id = Auth::guard('front_auth')->user()->id;
                        if($user_role !== "customer"){                        
                        $trainer_id = 'P'.$trainer_id;  
                        }
                        }else{
                           $trainer_id = null; 
                        }
                        $train2 = Session::get('currentlyLogedIn');
                        ?>
                        @if($train2 == "name")                                              
                        <script>
                        const fonty = () => {
                            window.dataLayer = window.dataLayer || [];
                            window.dataLayer.push({
                            'event' : 'login',
                            'loginMethod' : 'email',
                            'userId' : <?php echo '"'.$trainer_id.'"';?>
                            });
                        }
                        fonty();
                        </script>
                        <?php 
                        session()->put('currentlyLogedIn', "man");
                        ?>      
                        @endif
                        
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



                    <div class="row mb-4 pb-2">
                        @foreach($services as $trainerService)
                        <div class="col-xl-3 col-lg-6  mb-4">
                            <div class="card bg-danger h-100 justify-content-center">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col text-white pl-3">
                                            <h4 class="mb-0 font-weight-normal">{{$trainerService->service->name}}</h4>
                                            <h3 class="h1 mb-0 font-weight-normal">{{$trainerService->count}}</h3>
                                        </div>
                                        <!--<div class="col-auto pr-2">
                                            <img style="max-width:45px;" src="{{asset('front/images/'.$trainerService->service->white_icon)}}" alt="{{$trainerService->service->name}}" />
                                        </div>-->
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        <!-- <div class="col-xl-4 col-lg-6 mb-xl-0 mb-4">
                            <div class="card bg-warning h-100 justify-content-center">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col text-white pl-3">
                                            <h4 class="mb-0 font-weight-normal">Dumbbells</h4>
                                            <h3 class="h1 mb-0 font-weight-normal">30</h3>
                                        </div>
                                        <div class="col-auto pr-2">
                                            <img src="{{asset('front/trainer/images/dumbbells-icon.png')}}" alt="Dumbbells" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 mb-xl-0 mb-4">
                            <div class="card bg-info h-100 justify-content-center">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col text-white pl-3">
                                            <h4 class="mb-0 font-weight-normal">Fitness</h4>
                                            <h3 class="h1 mb-0 font-weight-normal">20</h3>
                                        </div>
                                        <div class="col-auto pr-2">
                                            <img src="{{asset('front/trainer/images/fitness-icon.png')}}" alt="Fitness" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                    </div>

                    <div class="row mb-4 pb-2">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body p-0">
                                    <div class="row no-gutters">
                                        <div class="col-lg-6 border-right border-bottom">
                                            <div class="p-4">
                                                <div class="row no-gutters align-items-center">
                                                    <div class="col">
                                                        <h4 class="mb-0 font-weight-normal">Athletes Connected</h4>        
                                                    </div>
                                                    <div class="col-auto">
                                                        <span class="badge badge-info h4 mb-0">{{$totalAthletsConnected}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 border-bottom">
                                            <div class="p-4">
                                                <div class="row no-gutters align-items-center">
                                                    <div class="col">
                                                        <h4 class="mb-0 font-weight-normal">Total Number of Services Booked</h4>        
                                                    </div>
                                                    <div class="col-auto">
                                                        <span class="badge badge-info h4 mb-0">{{$ordersCount}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 border-right border-bottom">
                                            <div class="p-4">
                                                <div class="row no-gutters align-items-center">
                                                    <div class="col">
                                                        <h4 class="mb-0 font-weight-normal">Overall Rating</h4>        
                                                    </div>
                                                    <div class="col-auto">
                                                        <span class="badge badge-info h4 mb-0">{{$ratting}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 border-bottom">
                                            <div class="p-4">
                                                <div class="row no-gutters align-items-center">
                                                    <div class="col">
                                                        <h4 class="mb-0 font-weight-normal">Services Booked This Month</h4>        
                                                    </div>
                                                    <div class="col-auto">
                                                        <span class="badge badge-info h4 mb-0">{{$currentMonthBookings}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--<div class="col-lg-6 border-right">
                                            <div class="p-4">
                                                <div class="row no-gutters align-items-center">
                                                    <div class="col">
                                                        <h4 class="mb-0 font-weight-normal">No. Of Reviews</h4>        
                                                    </div>
                                                    <div class="col-auto">
                                                        <span class="badge badge-info h4 mb-0">{{$reviewCount}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>-->
                                        <div class="col-lg-6">
                                            <div class="p-4">
                                                <div class="row no-gutters align-items-center">
                                                    <div class="col">
                                                        <h4 class="mb-0 font-weight-normal">Total Earnings</h4>        
                                                    </div>
                                                    <div class="col-auto">
                                                        <span class="badge badge-info h4 mb-0">{{$earnings}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="p-4">
                                                <div class="row no-gutters align-items-center">
                                                    <div class="col">
                                                        <h4 class="mb-0 font-weight-normal">Resources Uploaded</h4>        
                                                    </div>
                                                    <div class="col-auto">
                                                        <span class="badge badge-info h4 mb-0">{{$resources}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- <div class="row mb-4" style="display: none"> -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <!-- <div class="block-title">
                                <h2 class="font-weight-normal">Availability</h2>
                            </div> -->
                            <div class="card">
                                <div class="card-body p-lg-3 p-2">
                                    <div id='dashboard_calendar'></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @include('front.trainer.layout.includes.footer')

                </div>
            </div>
        </div>

        <!-- Book Modal -->

    <!-- Next Steps Modal -->
    @foreach($nextSteps as $nextStep)
            @if(!empty($nextStep->button_2))
                    <div class="modal fade" id="nextModal{{$nextStep->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                      <div class="modal-dialog modal-lg" role="document">
                        
                        <div class="modal-content">
                          
                          <div class="modal-header">
                            <h4 class="modal-title w-100" id="myModalLabel">{{$nextStep->modal_title}}</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body" style="height: auto;width: 100%;overflow: hidden;">
                          {!! $nextStep->modal_content !!}
                          </div>
                         
                        </div>
                      </div>
                    </div>
                    @endif
        @endforeach
       


    <!-- End of Modal -->


@endsection

@section('pagescript')

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src='https://unpkg.com/@fullcalendar/core@4.4.0/main.min.js'></script>
<script src='https://unpkg.com/@fullcalendar/interaction@4.4.0/main.min.js'></script>
<script src='https://unpkg.com/@fullcalendar/daygrid@4.4.0/main.min.js'></script>
<script src='https://unpkg.com/@fullcalendar/timegrid@4.4.0/main.min.js'></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script>


/* Calendar Customization */
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('dashboard_calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        plugins: [ 'interaction', 'dayGrid', 'timeGrid', 'list' ],
        selectable: true,
        timeZone: 'UTC',
        defaultView: 'dayGridWeek',
        header: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,dayGridWeek',
        },
        dateClick: function(info) {
        //alert('clicked ' + info.dateStr);
        },
        select: function(info) {
        
        
        },
        editable: true,
        eventLimit: true,
        
        /* EVENT FORMAT */
        events: <?php echo $eventDatacal; ?>,
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script>
        $(function () {
            // Owl Carousel
            var owl = $(".owl-carousel");
            owl.owlCarousel({
                items: 3,
                margin: 10,
                loop: false,
                dots: false,
                nav: true,
                mouseDrag  : false,
                touchDrag  : true,
                responsive: {
                    0: {
                        items: 1
                    },

                    600: {
                        items: 2
                    },

                    1024: {
                        items: 3
                    },

                    1366: {
                        items: 3
                    }
                }
            });

            $('.process_slide .owl-item .close_btn').on('click', function(){
            let slideCount = $('.process_slide .owl-item').length;
            if(slideCount <= 1){
                $('.next_slider').remove();
            }
            else {
                $(".owl-carousel").trigger('refresh.owl.carousel')
                var currentIndex = $(this).attr("data-id");
                var owlCarousel = owl.data('owl.carousel');
                $(".owl-carousel").trigger('remove.owl.carousel', [currentIndex]).trigger('refresh.owl.carousel');
                // Refresh Index
                $('.owl-item .close_btn').each(function(index){
                    $(this).attr('data-id', index);
                });
                console.log(currentIndex);
               
            } 
            
        });
        
        });

    </script>

@endsection

