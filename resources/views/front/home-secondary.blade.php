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
                    <!-- <h3>Find local services you need to meet your performance goals</h3> -->
                    <h1>Find Performance Services For Your Training</h1>
                    <!-- <p>personal trainers provide expert guidance and support designed for your goals, on your schedule, <span class="d-xl-block"></span>in the comfort and privacy of your home or building gym.</p> -->
                    <form class="searchform" id="searchform" method="post" action="{{route('exploreservices')}}" autocomplete="off">
                       @csrf
                       <input type="hidden" name="curt_location" id='curt_location' class="form-control location-box pl-input" value="" placeholder="">
                       <input type="hidden" name="search_value" id="search_value" value="12">
                       <input type="hidden" name="virtual_in_both" id="virtual_in_both" value="Both">
                    <div class="banner-search">
                        <div class="input-group search-group" >
                            <input type="text" name="keyword"  class="form-control search-box pl-input" required >
                            <p class="myinput-placeholder keyword-placeholder"><b>Find your</b> coach, physical therapist...</p>
                        </div>
                        <div class="input-group location-group" style="display:none">
                            <input type="text" name="location" id='location' class="form-control location-box pl-input" value="" placeholder="">
                            <!-- <p class="myinput-placeholder location-placeholder">Near <small>(Optional)</small></p> -->
                            <div id="locationlist"></div>  
                        </div>
                        <button class="btn btn-danger btn-inperson" type="submit" id="button_both">Both</button>
                        <button class="btn btn-danger btn-inperson" id="button_inperson">In Person</button>
                        <button class="btn btn-danger btn-virtual" id="button_virtual">Virtual</button>
                        <button class="btn btn-danger" id="home_submit" style="display: none"><img src="{{ asset('images/search.svg') }}" style="width: 30px;height: auto" class="lazyload" alt="search" /></button>
                        <!-- <button type="button" class="btn btn-danger" id="update_location" style="background: #00ab91;border-color: #00ab91;" ><img src="{{ asset('images/precision.png') }}" style="width: auto;height: auto" class="lazyload" alt="search" /></button> -->
                    </div>
                    <button type="button" class="btn " id="update_location" style="display: none">Locate Me</button>
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
                <?php 
                        if(Auth::guard('front_auth')->user()){
                        $user_role = Auth::guard('front_auth')->user()->user_role;
                        $customer_id = Auth::guard('front_auth')->user()->id;
                        $customer_name = Auth::guard('front_auth')->user()->business_name;
                        if($user_role === "customer"){
                        $customer_id = 'A'.$customer_id;                             
                        }else{
                        $customer_id = 'P'.$customer_id;  
                        }                            
                        }else{
                           $customer_id = null; 
                        }
                        // dd($customer_id);
                        $train2 = Session::get('currentlyAtLogedIn');
                        ?>  
                        @if($customer_id !== null)                                              
                        <script>
                        const fonty = () => {
                            window.dataLayer = window.dataLayer || [];
                            window.dataLayer.push({
                            'event' : 'login',
                            'loginMethod' : 'email',
                            'userId' : <?php echo '"'.$customer_id.'"';?>, 
                            'userName' : <?php echo '"'.$customer_name.'"';?> 
                            });
                        }
                        fonty();
                        </script>
                        <?php 
                        session()->put('currentlyAtLogedIn', "man");
                        ?>      
                        @endif
            </div>
            <div class="row">
                <!--<div class="col-lg-2 mb-lg-0 mb-4 mobile_view_ads">
                    <div class="avd_img avd_div_left">
                         <img src="{{ asset('images/banner.jpg') }}" alt=""> 
                    </div>
                </div>-->
                <div class="col-lg-12 col-xs-12 mb-lg-0 mb-4">
                    <div class="section-title text-center d-none d-lg-block">
                        <h5 class="text-danger">High Performance Running</h5>
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
                <!--<div class="col-lg-2 mb-lg-0 mb-4 mobile_view_ads">
                    <div class="avd_img avd_div_right">
                         <img src="{{ asset('images/banner.jpg') }}" alt=""> 
                    </div>
                </div>-->
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
                        <h5 class="text-danger mb-3">FIND THE SPORTS PROVIDERS THAT WILL ELEVATE YOUR TRAINING</h5>
                        <h2 class="mb-4">TRAIN SMARTER<?php //echo $aboutus_page->short_description ?></h2>
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
                <h5 class="text-danger">Supporting Athletes</h5>
                <!--<h2>THE FUTURE OF TRAINING IS HERE</h2>
                <p>Gone are the days of training alone. Of subpar performances. Of not leaving it all out there. Training Block recognizes that every sport is a team sport: every athlete has a team behind them that pushes them to greatness. Whether it be a trusted coach, physical therapist, nutritionist, or other sports provider, find the provider you need for your unique goals on Training Block.</p>-->
                <h2>Our Mission Is Simple : To Elevate The Running Community</h2>
                <p>Training Block was created with a mission to support and empower runners, in order to elevate our sport. We do so by giving runners access to a network of local sport performance providers, who provide runners with the care they need from coaching, physical therapy, massage, strength training, and more. We also give providers an easy way to connect with each other and share articles, videos, and other resources that benefit runners and providers alike. For every service booked through Training Block, we donate 10% of our revenues to Training Block’s Elite Athlete Fund, which sponsors elite runners who do not have professional contracts and need financial support for racing at their highest level.</p>
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

<!-- Join Newsletter Modal -->
@if(Auth::guard('front_auth')->check())
    <input type="hidden" name="logged_email" id="logged_email" value="{{Auth::guard('front_auth')->user()->email}}">
@else 
<input type="hidden" name="logged_email" id="logged_email" value="">
@endif
<div class="modal fade" id="newsletterModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                      <div class="modal-dialog modal-lg" role="document">
                        
                        <div class="modal-content">
                          
                          <div class="modal-header">
                            <!-- <h4 class="modal-title w-100" id="myModalLabel">Join Newsletter</h4> -->
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body" style="height: auto;width: 100%;overflow: hidden;">
                            <div class="row d-flex align-items-center">
                            <form id="newsletterForm" name="newsletterForm" method="post">
								@csrf
							<div class="row">
                                    <div class="col-md-12 newsletter_section">
                                        <img src="{{ asset('images/logo.png') }}" alt="logo" class="newsletter_logo">
                                        <h3 class="text-center">Sign up for the Training Block newsletter</h3>
                                        <p class="newsletter_text">Stay up to date on the best training-related content, created by sport performance experts.</p>
                                        <div class="input-group mb-2">
                                        <input type="text" class="form-control" name="subscribe_email" id="newsletterEmail" placeholder="Enter Your Email Address">
                                        </div>
                                        <div class="input-group mb-3">
                                        <button type="button" id="newsletterSubmit" class="btn btn-info btn-lg mb-2 float-right">Subscribe</button>
                                        <!-- <button type="button" class="close btn_close" data-dismiss="modal" aria-label="Close">Close</button> -->
                                        </div>
                                    </div>
							</form>
                            </div>
                          </div>
                         
                        </div>
                      </div>
                    </div>
</div>

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
#button_both{
        display: none;
		width: 0 !important;
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
 <!-- Load Leaflet from CDN -->
 <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
    integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
    crossorigin=""/>
  <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
    integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
    crossorigin=""></script>

  <!-- Load Esri Leaflet from CDN -->
  <script src="https://unpkg.com/esri-leaflet@3.0.7/dist/esri-leaflet.js"
    integrity="sha512-ciMHuVIB6ijbjTyEdmy1lfLtBwt0tEHZGhKVXDzW7v7hXOe+Fo3UA1zfydjCLZ0/vLacHkwSARXB5DmtNaoL/g=="
    crossorigin=""></script>

  <!-- Load Esri Leaflet Geocoder from CDN -->
  <link rel="stylesheet" href="https://unpkg.com/esri-leaflet-geocoder@3.1.2/dist/esri-leaflet-geocoder.css"
    integrity="sha512-IM3Hs+feyi40yZhDH6kV8vQMg4Fh20s9OzInIIAc4nx7aMYMfo+IenRUekoYsHZqGkREUgx0VvlEsgm7nCDW9g=="
    crossorigin="">
  <script src="https://unpkg.com/esri-leaflet-geocoder@3.1.2/dist/esri-leaflet-geocoder.js"
    integrity="sha512-8bfbGLq2FUlH5HesCEDH9UiuUCnBq0A84yYv+LkUNPk/C2z81PsX2Q/U2Lg6l/QRuKiT3y2De2fy9ZPLqjMVxQ=="
    crossorigin=""></script>

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
                console.log("something went wrong");
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
           if(($.trim($(this).text()) != 'Explore') && ($.trim($(this).text()) != 'Resource Library')){
            $('#location').val($(this).text()); 
           }
            
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


  // get current Location 
$(document).ready(function() {
    var location = $('#curt_location').val();
    var cookieValue = $.cookie("locations");
    $('#update_location').on('click', function() {
        $.removeCookie("locations");
        cookieValue = $.cookie("locations");
        // console.log(cookieValue);
        if (cookieValue == undefined) {

            if (confirm('Use the current location')) {


                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(zoomToLocation, locationError);
                } else {
                    alert("Browser doesn't support Geolocation. Visit http://caniuse.com to see browser support for the Geolocation API.");
                }

                function locationError(error) {
                    //error occurred so stop watchPosition
                    if (navigator.geolocation) {
                        navigator.geolocation.clearWatch(watchId);
                    }
                    switch (error.code) {
                        case error.PERMISSION_DENIED:
                            alert("Location not provided");
                            break;

                        case error.POSITION_UNAVAILABLE:
                            alert("Current location not available");
                            break;

                        case error.TIMEOUT:
                            alert("Timeout");
                            break;

                        default:
                            alert("unknown error");
                            break;
                    }
                }


                var geocodeService = L.esri.Geocoding.geocodeService({
                    apikey: "AAPK1468e59aa8d84c5192603f17da8fd233I2bNwgowIbomirjinrZpX_WkqkHFW7RroqgqRM-FDCoOu07ZxZAFSN3sN0SdzFsb"
                });

                function zoomToLocation(location) {
                    var latlng = {
                        lat: location.coords.latitude,
                        lng: location.coords.longitude
                    };
                    geocodeService.reverse().latlng(latlng).run(function(error, result) {
                        if (error) {
                            return;
                        }
                        var locationAddress = result.address.City + ', ' + result.address.RegionAbbr;
                        // console.log(result.latlng);
                        // console.log(result);

                        if (locationAddress != '') {
                            $.cookie("locations", locationAddress);
                            $('#location').val(locationAddress);
                        } else {
                            $.cookie("locations", "");
                            $('#location').val('');
                        }

                    });
                };


            } else {
                $('#searchform #location').removeAttr('value');

                //$.cookie("locations", "empty", { expires: 1 });
                $.cookie("locations", "");
                $('.location-placeholder').css('display', 'block');
                $('#location').val('');
            }
        } else {
            if (cookieValue == '') {
                $('#searchform #location').removeAttr('value');
                $('.location-placeholder').css('display', 'block');
                $('#location').val('');
            } else {
                $('#searchform #location').val(cookieValue);

            }
        }
    });
    if (cookieValue == '') {
        $('#searchform #location').removeAttr('value');
        $('.location-placeholder').css('display', 'block');
    } else {
        $('#searchform #location').val(cookieValue);

    }

    
    
});

$(document).ready(function(){
setTimeout(function(){
var logged_in_email = $('#logged_email').val();
	let data = {
		email : logged_in_email
	}
if(logged_in_email != '' && $.cookie("newsletter") != 1){
    $.ajax({
			url: "{{ url('email_verify') }}",
			type: "POST",
			data : data,
			headers: {
				'X-CSRF-TOKEN': '{{ csrf_token() }}'
			},
		success: function(response){
			if(response.status == true){
                $.cookie("newsletter", "");
                $('#newsletterModal').modal({
                backdrop: 'static',
                keyboard: false
                });
            }
            else {
                $.cookie("newsletter", "1", { expires: 1 });
            }
        }
	});
}
else {
    if(!$.cookie("newsletter")){
        $.cookie("newsletter", "");
    }
    
    if($.cookie("newsletter") == ""){
    $('#newsletterModal').modal({
        backdrop: 'static',
        keyboard: false
    });
}
else {
    // $('#newsletterModal').modal();
}
    

    // var newsletterValue = $.cookie("newsletter");
    // if(newsletterValue == 0){
    //     $('#newsletterModal').modal({
    //     backdrop: 'static',
    //     keyboard: false
    // });
    // }
    // else {
    //     $.cookie("newsletter", "1");
    // }
}
},2000);
});


$('.close').on('click', function(){
    $.cookie("newsletter", 1);
});

$('#button_inperson').on('click', function(){
    $('#searchform #update_location').css('display', 'inline-block');
    $('#searchform #home_submit').css('display', 'block');
    $('#searchform .location-group').css('display', 'block');
    $('#virtual_in_both').val('In Person');
    // $('#searchform #location').attr('required', '');
    $('#searchform #button_virtual').remove();
    $(this).remove();
});

$('#button_virtual').on('click', function(){
    $('#virtual_in_both').val('Virtual Only');
    $('#searchform #location').val('');
});
// $('#button_inperson').on('click', function(){
//     $('.banner-search .search-group').css('max-width', '498px');
// });

</script>

@endsection


