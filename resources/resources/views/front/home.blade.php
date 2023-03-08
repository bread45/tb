@extends('front.layout.app')
@section('title', 'Home')
@section('content')


<section class="banner-section py-0">
        <div class="banner">
            <div class="banner-img">
                <img src="{{ asset('images/banner.jpg') }}" class="lazyload" alt="Home Banner" />
            </div>
            <div class="banner-content">
                <div class="container">
                    <h3>Find local services you need to meet your performance goals</h3>
                    <!-- <p>personal trainers provide expert guidance and support designed for your goals, on your schedule, <span class="d-xl-block"></span>in the comfort and privacy of your home or building gym.</p> -->
                    <form class="searchform" id="searchform" method="post" action="{{route('exploreservices')}}" autocomplete="off">
                       @csrf
                    <div class="banner-search">
                        <div class="input-group search-group">
                            <input type="text" name="keyword"  class="form-control search-box pl-input" required >
                            <p class="myinput-placeholder keyword-placeholder"><b>Find your</b> coach, physical therapist...</p>
                        </div>
                        <div class="input-group location-group">
                            <input type="text" name="location" id='location' class="form-control location-box pl-input" value="{{$location_name}}" placeholder="" required>
                            <!-- <p class="myinput-placeholder location-placeholder">Near <small>(Optional)</small></p> -->
                            <div id="locationlist"></div>  
                        </div>
                        <button class="btn btn-danger" ><img src="{{ asset('images/search-icon-large.png') }}" style="width: auto;height: auto" class="lazyload" alt="search" /></button>
                    </div>
                        </form>
                </div>
            </div>
        </div>
    </section>
  
 <section class="tips-section">
        <div class="container-fluid">
            <div class="section-title text-center d-lg-none">
                <h5 class="text-danger">LOCAL ATHLETES. LOCAL TRAINING</h5>
                <h2>WHY TRAINING BLOCK</h2>
            </div>
            <div class="row">
                <div class="col-lg-2 mb-lg-0 mb-4 mobile_view_ads">
                    <div class="avd_img avd_div_left">
                        <!-- <img src="{{ asset('images/banner.jpg') }}" alt=""> -->
                    </div>
                </div>
                <div class="col-lg-8 col-xs-12 mb-lg-0 mb-4">
                    <div class="section-title text-center d-none d-lg-block">
                        <h5 class="text-danger">LOCAL ATHLETES. LOCAL TRAINING</h5>
                        <h2>WHY TRAINING BLOCK</h2>
                    </div>
                    <div class="row">
                @foreach($tips as $tip)
                <div class="col-lg-4 mb-lg-0 mb-4">
                    <div class="card">
                        <div class="card-image">
                            <img src="{{ asset('images/tips/'.$tip->image) }}" class="lazyload" alt="{{$tip->title}}" />
                        </div>
                        <div class="card-body text-center">
                            <h5 class="text-uppercase">{{$tip->title}}</h5>
                            <p>{{$tip->description}}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
                <div class="col-lg-2 mb-lg-0 mb-4 mobile_view_ads">
                    <div class="avd_img avd_div_right">
                        <!-- <img src="{{ asset('images/banner.jpg') }}" alt=""> -->
                    </div>
                </div>
            </div>
        </div>
    </section>
<section class="aboutus-section">
        <div class="aboutus-outer">
            <div class="about-left">
                <div class="about-img h-100">
                    <img src="{{asset('images/julie2.jpg')}}" class="lazyload" alt="Home About Banner" />
                </div>
            </div>
            <div class="about-right">
                <div class="about-content">
                    <div class="section-title text-left">
                        <h5 class="text-danger mb-3">FIND THE ATHLETIC PROVIDERS YOU NEED</h5>
                        <h2 class="mb-4">A TEAM EFFORT. JUST FOR YOU<?php //echo $aboutus_page->short_description ?></h2>
                        <p class="h5 font-weight-normal"><?php //echo $aboutus_page->description ?>A successful block of training takes more than your own efforts. Utilize the athletic providers in your local area to maximize your training and obtain your desired results. </p>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-lg-5 mb-4">
                            <img src="{{asset('../front/images/work-icon.png')}}"  alt="Work" class="mb-xl-4 icon lazyload" />
                            <!-- <h5 class="mb-lg-4 mb-2">Lorem Ipsum is simply </h5> -->
                            <p class="mb-0">Search for services tailored to your unique athletic goals</p>
                        </div>
                        <div class="col-md-6 mb-lg-5 mb-4">
                            <img src="{{asset('../front/images/gift-icon.png')}}" alt="Work" class="mb-xl-4 icon lazyload" />
                            <!-- <h5 class="mb-lg-4 mb-2">Lorem Ipsum is simply </h5> -->
                            <p class="mb-0">Filter services by star ratings & reviews, in order to ensure quality service</p>
                        </div>
                        <div class="col-md-6 mb-lg-0 mb-4">
                            <img src="{{asset('../front/images/time-icon.png')}}" alt="Work" class="mb-xl-4 icon lazyload" />
                            <!-- <h5 class="mb-lg-4 mb-2">Lorem Ipsum is simply </h5> -->
                            <p class="mb-0">Book a service easily online, at a time most convenient for you</p>
                        </div>
                        <div class="col-md-6 mb-lg-0 mb-4">
                            <img src="{{asset('../front/images/read-icon.png')}}" alt="Work" class="mb-xl-4 icon lazyload" />
                            <!-- <h5 class="mb-lg-4 mb-2">Lorem Ipsum is simply </h5> -->
                            <p class="mb-0">Recommend the service to others through a one-click process</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<!--<section id="slider" class="adv_section" >
    <div class="container avd_div_top" > 
    </div>
</section>-->
 <section class="featured-business-section">
        <div class="container">
            <div class="section-title text-left">
                <h5 class="text-danger">LOCAL ATHLETE-FOCUSED BUSINESSES</h5>
                <h2 class="mb-0">Featured Providers</h2>
            </div>
            <div class="featured-business-slider">
                @foreach($featuredTrainerlist as $trainer)
                <div class="slide featured_providers">  
                <a href="{{url('provider/'.$trainer->spot_description)}}" class="trainer-link">
                    <div class="card">
                            <div class="card-image">
                                @php
                $TrainerPhoto = \App\TrainerPhoto::where('is_featured',1)->where('trainer_id',$trainer->id)->first();
                @endphp
                @if(!empty($TrainerPhoto))
                <img src="@if(!empty($TrainerPhoto->image)){{asset('front/profile/'.$TrainerPhoto->image)}} @endif" alt="Expert Guidance" class="lazyload" />

                @elseif(!empty($trainer->photo))
                <img src="@if(!empty($trainer->photo)){{asset('front/profile/'.$trainer->photo)}} @endif" alt="Expert Guidance" class="lazyload" />

                @else
                <img src="{{asset('images/services/featured_01.jpg')}}" alt="Workout Tips" class="lazyload" />
                             
                @endif 
                                </div>
                            <div class="card-body">
                                <h4 class="text-uppercase mb-3">
                                    @if(!empty($trainer->business_name))
                                    {{$trainer->business_name}}
                                    @else
                                    {{$trainer->first_name .' '. $trainer->last_name}}
                                    @endif    
                                </h4>
                                <h5 class="location">{{$trainer->address_1 .', '. $trainer->city}}</h5>
                                <p class="trainerBio">{!! \Illuminate\Support\Str::limit(strip_tags(htmlspecialchars_decode($trainer->bio)), 70, $end=' ...') !!}</p>
                            </div>     
                    </div> 
                </a>   
                </div>
                @endforeach
                 
            </div>
            <div class="row">
                <div class="col-12 text-center">
                    <a href="{{route('exploreservices')}}" class="btn btn-outline-danger">View All</a>
                </div>
            </div>
        </div>
    </section>
<!--<section id="slider" class="adv_section"   >
    <div class="container avd_div_bottom" > 
    </div>
</section>-->
<section class="expert-guidance-section">
        <div class="container">
            <div class="section-title text-center">
                <h5 class="text-danger">the future of training</h5>
                <h2>THE FUTURE OF TRAINING IS HERE</h2>
                <p>Gone are the days of training alone. Of subpar performances. Of not leaving it all out there. Training Block recognizes that every sport is a team sport: every athlete has a team behind them that pushes them to greatness. Whether it be a trusted coach, physical therapist, nutritionist, or other sports provider, find the provider you need for your unique goals on Training Block.</p>
            </div>
        </div>
        <div class="expert-guidance-slider">
         @foreach($trainerList as $trainer)
            <div class="slide">
            <a href="{{url('provider/'.$trainer->spot_description)}}">
                <div class="slide-inner">
                    <div class="slide-img">
                            @php
                $TrainerPhoto = \App\TrainerPhoto::where('is_featured',1)->where('trainer_id',$trainer->id)->first();
                @endphp
                @if(!empty($TrainerPhoto))
                <img src="@if(!empty($TrainerPhoto->image)){{asset('front/profile/'.$TrainerPhoto->image)}} @endif" alt="Expert Guidance" class="lazyload" />

                @elseif(!empty($trainer->photo))
                <img src="@if(!empty($trainer->photo)){{asset('front/profile/'.$trainer->photo)}} @endif" alt="Expert Guidance" class="lazyload" />

                @else
                <img src="{{asset('images/Expert_01.jpg')}}" alt="Expert Guidance" class="lazyload" />

                @endif 
                                                    </div>
                    <div class="slide-content">
                        <h4 class="text-uppercase mb-sm-3">
                        @if(!empty($trainer->business_name))
                        {{$trainer->business_name}}
                        @else
                        {{$trainer->first_name}}
                        {{$trainer->last_name}}
                        @endif    
                        </h4>
                        <h5 class="location">
                        {{$trainer->address_1}}
                        {{$trainer->city}}
                        {{$trainer->state}}
                        {{$trainer->country}}
                        </h5>
                        <p>{!! \Illuminate\Support\Str::limit(strip_tags(htmlspecialchars_decode($trainer->bio)), 70, $end=' ...') !!}</p>
                        <div class="rating">
                            <ul class="nav">
                                <?php
                                        $rating = $trainer->ratting;
                                        $number_stars = calculate_stars(5, $rating);
                                        $full = $number_stars[0];
                                        $half = $number_stars[1];
                                        $gray = $number_stars[2];
                                    ?> 
                                    @for($i=0; $i<$full;$i++)
                                    <li> <img  src="{{asset('/front/images/star.png')}}" alt="Rating" /> </li>
                                @endfor
                                    @if($half)
                                    <li><img  src="{{asset('images/star-half.png')}}" alt="Rating" /></li>
                                    @endif
                                    @for($i=0;$i<$gray;$i++)
                                    <li><img  src="{{asset('images/star-blank.png')}}" alt="Rating" /></li>
                                @endfor
                                 
                            </ul>
                        </div>
                    </div>
                </div>
            </a>
            </div>
            @endforeach
        
        </div>
    </section>
@stop
@section('pagescript')
<style>
.trainerBio {
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
}
.trainer-link, .trainer-link:hover, .trainer-link:focus{
    outline: none;
    text-decoration: none;
    color: inherit;
}
.featured_providers .card-body {
    min-height: 270px;
    max-height: 270px;
}
/*.featured_providers .card-image img{
    height: 200px;
    object-fit: cover;
    object-position: center center;  
}*/
.myinput-placeholder.focused{
    display:none;
}
div#locationlist {
        position: absolute;
    top: 69px;
    width: 100%;
}
.list-group-item{
    cursor: pointer;
    color: #000;
}
</style>
@if (session('message'))
<script>
    new PNotify({
                    title: '{{ session('message') }}',
                            text: '',
                            type: 'success'
                });
</script>
@endif
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.js"></script>
<script>
     $(document).on('click', 'body', function(){  
           $('#locationlist').fadeOut();  
      }); 
    function getadvhor(type,side) {
        $.ajax({
            url: '{{ url("advertisement/get/") }}',
            type: 'get',
            data: {'type':type,'side':side},
            success: function (data) {
                $('.avd_div_'+side).html(data);
            },
            error: function () {
                new PNotify({
                    title: 'Oh No!',
                    text: 'Something went wrong!',
                    type: 'error'
                });
            }
        });
    } 
    $(document).ready(function () {
        getadvhor('horizontal','top');
        getadvhor('horizontal','bottom');
        getadvhor('vertical','left');
        getadvhor('vertical','right');
    });
    $(function () {
        
        
    var $inputs = $('input[name=keyword],input[name=location]');
    $inputs.on('input', function () {
        // Set the required property of the other input to false if this input is not empty.
        $inputs.not(this).prop('required', !$(this).val().length);
    });

    $('input[name="keyword"]').on('input', function() {
        if($(this).val() != "") {
          $('.keyword-placeholder').addClass('focused');
        }else{
           $('.keyword-placeholder').removeClass('focused');
        }
    });

    $('input[name=location]').on('input', function() {
        if($(this).val() != "") {
          $('.location-placeholder').addClass('focused');
        }else{
           $('.location-placeholder').removeClass('focused');
        }
    });
 $('#location').keyup(function(){  
           var query = $(this).val();  
           if(query != '')  
           {  
                $.ajax({  
                     url:"{{route('get.locationdata')}}",  
                     method:"POST",  
                     data:{search:query,"_token": "{{ csrf_token() }}"},  
                     success:function(data)  
                     {  
                          $('#locationlist').fadeIn();  
                          $('#locationlist').html(data);  
                     }  
                });  
           }  
      });  
      $(document).on('click', 'li', function(){  
           //$('#location').val($(this).text());  
           $('#locationlist').fadeOut();  
      });
    // $('input[name="keyword"]').on('focus', function() {
    //     $('.keyword-placeholder').addClass('focused');
    // });
    // $('input[name="keyword"]').keyup(function() {
    //     $('.keyword-placeholder').removeClass('focused');
    // });
    
   
    
    function hasValue(elem) {
        return $(elem).filter(function() { return $(this).val(); }).length > 0;
    } 
    
    if(hasValue('input[name="location"]')){
         $('.location-placeholder').addClass('focused');
     }else{
         $('.location-placeholder').removeClass('focused');
    }
   
     if(hasValue('input[name="keyword"]')){
         $('.keyword-placeholder').addClass('focused');
     }else{
         $('.keyword-placeholder').removeClass('focused');
     }
});


    $( document ).ready(function() {

   
      
    var location = $('#searchform #location').val();
    var cookieValue = $.cookie("locations");
  
    if(cookieValue == undefined){

       if (confirm('Use the current location')) {
              
              $('#searchform #location').val('Jacksonville, Fl');
              
              $.cookie("locations", "Jacksonville, Fl", { expires: 1 });
            } else {
              $('#searchform #location').removeAttr('value');
              
              $.cookie("locations", "empty", { expires: 1 });
              $('.location-placeholder').css('display', 'block');
            }
    } else {
        if(cookieValue == 'empty'){
            $('#searchform #location').removeAttr('value');
            $('.location-placeholder').css('display', 'block');
        } else {
            $('#searchform #location').val('Jacksonville, Fl');
            
        }
    }
   
});


</script>

@endsection


