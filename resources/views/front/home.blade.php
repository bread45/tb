@extends('front.layout.app')
@section('title', 'Home')
@section('content')

    <!-- Slider -->
    <section class="slider-section py-0">

        <div id="carousel" class="carousel slide sliders-img" data-ride="carousel">
            <div class="overlay"></div>
            <!-- Indicators -->
            <ol class="carousel-indicators">
                <li data-target="#carousel" data-slide-to="0" class="active"></li>
                <li data-target="#carousel" data-slide-to="1"></li>
                <li data-target="#carousel" data-slide-to="2"></li>
                <li data-target="#carousel" data-slide-to="3"></li>
            </ol> <!-- End of Indicators -->

            <!-- Carousel Content -->
            <div class="carousel-inner" role="listbox">
                <div class="carousel-item active" style="background-image: url('{{ asset('../front/images/new-template/banner.jpg') }}');">

                </div> <!-- End of Carousel Item -->

                <div class="carousel-item"
                    style="background-image: url('{{ asset('../front/images/new-template/banner-2.jpg') }}');">

                </div> <!-- End of Carousel Item -->

                <div class="carousel-item"
                    style="background-image: url('{{ asset('../front/images/new-template/banner-3.jpg') }}');">

                </div> <!-- End of Carousel Item -->
                <div class="carousel-item"
                    style="background-image: url('{{ asset('../front/images/new-template/banner-5.jpg') }}');">

                </div> <!-- End of Carousel Item -->
            </div> <!-- End of Carousel Content -->

            <!-- Previous & Next -->
            <a href="#carousel" class="carousel-control-prev" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only"></span>
            </a>
            <a href="#carousel" class="carousel-control-next" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only"></span>
            </a>
        </div>

        <div class="banner">
            <div class="banner-content">
                <div class="container">
                    <!-- <h3>Find local services you need to meet your performance goals</h3> -->
                    <h3>Find Performance Services For Your Training</h3>
                    <!-- <p>personal trainers provide expert guidance and support designed for your goals, on your schedule, <span class="d-xl-block"></span>in the comfort and privacy of your home or building gym.</p> -->
                  <form class="searchform" id="searchform" method="get" action="{{route('exploreservices')}}" autocomplete="off">
                       @csrf
                       <input type="hidden" name="curt_location" id='curt_location' class="form-control location-box pl-input" value="" placeholder="">
                       <input type="hidden" name="search_value" id="search_value" value="12">
                       <input type="hidden" name="virtual_in_both" id="virtual_in_both" value="Both">
                    <div class="banner-search">
                        <div class="input-group search-group" >
                            <input type="text" name="keyword"  class="form-control search-box pl-input" required >
                            <img src="{{ asset('../front/images/new-template/search-icon.png') }}" class="search-icons">
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
                    <div class="home_button_section">
                    <button type="button" class="btn" id="help_button" data-toggle="modal" data-target="#helpModal" style="color: #fff;">Help me find an expert</button>
                    <button type="button" class="btn" id="update_location" style="display: none">Locate Me</button>
                    </div>

                        </form>
                </div>
            </div>
        </div>

    </section> 
{{-- End of Slider --}}

{{--  Browse by category  --}}
     <section class="browse-catergory">
        <div class="container position-relative">
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="main-title-sec">Browse By Category</h3>
                </div>
                <div class="swiper category-slider">
                    <div class="swiper-wrapper">
                    @foreach($services as $service)
                        <li class="swiper-slide col-lg-2">
                            <div class="categories-wrapper text-center mb-20" data-aos="fade-up" data-aos-duration="1200" data-aos-delay="100" data-aos-once="true" style="background: {{ $service->home_color != ''? $service->home_color : '#fef1e8' }}">
                            @if(isset($_COOKIE['locations']) && $_COOKIE['locations'] != '')
                                        <a href="{{ url('/explore?location='.$_COOKIE['locations'].'&services='.$service->id) }}">
                                        @else
                                        <a href="{{ url('/explore?services='.$service->id) }}">
                                        @endif
                                    <div class="categiories-thumb"><img src="@if($service->home_icon != ''){{ asset('../front/images/new-template/'.$service->home_icon) }}@endif" alt="{{ $service->name }}"></div>
                                    <div class="categories-content">
                                        <h4>{{ $service->name }}</h4>
                                    </div>
                            </div>
                            </a>
                        </li>
                    @endforeach
                        
                    </div>
                </div>
            </div>
            <div class="category-next swiper-button-next"></div>
            <div class="category-prev swiper-button-prev"></div>
        </div>
    </section>
{{-- End of Browse by category  --}}

{{-- Top rated Practitioners --}}
@if(count($featuredTrainerlist) > 0)
<section class="top-rated">
        <div class="right-round float-bob-x"></div>
        <div class="left-round"></div>
        <div class="container position-relative">
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="main-title-sec mb-3">Top Rated Sport Practitioners</h3>
                </div>
                <div class="swiper swiper-slide top-rated-slider">
                    <div class="swiper-wrapper">
                    @foreach($featuredTrainerlist as $featuredTrainer)
                        <div class="swiper-slide col-lg-3" data-aos="fade-up" data-aos-delay="0">
                            <a href="{{url('provider/'.$featuredTrainer->spot_description) }}">
                                @if($featuredTrainer->photo != NULL)
                                <figure class="full_size_con"><img class="event_img" src="{{ asset('front/profile/'.$featuredTrainer->photo) }}" alt="{{ $featuredTrainer->name }}">
                                @else
                                 <figure class="full_size_con"><img class="event_img" src="{{ asset('../front/images/new-template/top-rated-default-image.jpg') }}" alt="{{ $featuredTrainer->name }}">
                                @endif
                                    <figcaption>
                                        <h5>{{ $featuredTrainer->name }}</h5>
                                        <h4 class="common_heading text_hightlight">{{ $featuredTrainer->business_name }}</h4>
                                        <span class="location-pin"><img src="{{ asset('../front/images/new-template/location-yellow.svg') }}" width="20"
                                                class="location-yellow">
                                                @if($featuredTrainer->address1_virtual == 1)
                                                Virtual
                                                @else
                                                <?php if($featuredTrainer->city != '' ){echo $featuredTrainer->city; } ?>
                                                <?php if($featuredTrainer->state_code != '' && $featuredTrainer->city != '' ){echo ', '; } if($featuredTrainer->state_code != '' ){echo $featuredTrainer->state_code; } ?>
                                                @endif
                                                <blockquote class="details_1">
                                                    <p> {!! str_limit(strip_tags(htmlspecialchars_decode($featuredTrainer->bio)), $limit = 100, $end = '...') !!}</p>
                                        </blockquote>
                                    </figcaption>
                                </figure>
                            </a>
                        </div>
                    @endforeach
                    </div>
                </div>
            </div>
            <div class="top-rated-slider-next swiper-button-next"></div>
            <div class="top-rated-slider-prev swiper-button-prev"></div>
        </div>
    </section>
    @endif
{{-- End of Top rated Practitioners --}}

{{-- Upcoming Events --}}
@if(count($futureEvents) > 0)
    <section class="upcoming-events">
        <div class="container position-relative">
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="main-title-sec mb-5">Upcoming Events</h3>
                </div>

                <div class="swiper swiper-slide upcoming-events-slider">
                    <div class="swiper-wrapper">
                    
                     @foreach($futureEvents as $futureEvent)
                        <div class="swiper-slide col-lg-4">
                            <div class="upcoming-events-wrapper position-relative mb-30" data-aos="fade-up"
                                data-aos-duration="1000" data-aos-delay="100" data-aos-once="true">
                                   @if($futureEvent->photo != NULL)
                                     <div class="upcoming-events-thumb">
                                        <a href="{{ url('event-details/'.base64_encode($futureEvent->id)) }}" class="">
                                            <img src="{{ asset('front/profile/'.$futureEvent->photo) }}" alt="upcoming-events-img">
                                        </a>
                                    </div>
                                    @else 
                                     <div class="upcoming-events-thumb">
                                        <a href="{{ url('event-details/'.base64_encode($futureEvent->id)) }}" class=""><img src="{{ asset('front/images/events_default.png') }}" alt="upcoming-events-img">
                                    </a>
                                    </div>
                                    @endif
                                <div class="date-and-time">
                                    <span><?php echo date('l', strtotime($futureEvent->event_start_datetime)); ?></span>
                                    <h4><?php echo date('M  d', strtotime($futureEvent->event_start_datetime)); ?></h4>
                                    <hr>
                                    <p class="eventTime"><img src="{{ asset('../front/images/new-template/time-1.svg') }}" width="16px"> {{ $futureEvent->start_time }}</p>
                                </div>
                                <div class="upcoming-events-content-wrapper">
                                    <div class="upcoming-events-content">
                                        <a href="{{ url('event-details/'.base64_encode($futureEvent->id)) }}" class="">
                                            <h3>{{ $futureEvent->title }}</h3>
                                            @if($futureEvent->format == 'In Person')
                                            <p><img src="{{ asset('../front/images/new-template/location-red.svg') }}" width="20px" class="location-red">
                                                {{ $futureEvent->venue }}</p>
                                            @else
                                            <p><img src="{{ asset('../front/images/new-template/location-red.svg') }}" width="20px" class="location-red">
                                                Virtual Event</p>
                                            @endif
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                     @endforeach
                        
                    </div>
                </div>
            </div>

            <div class="display-center"> <a href="{{ url('/events') }}" class="btn btn-style-new btn-outline-danger" data-aos="fade-up" data-aos-delay="800"
                    data-aos-duration="1200" data-aos-once="true">Show all</a></div>

            <div class="upcoming-events-slider-next swiper-button-next"></div>
            <div class="upcoming-events-slider-prev swiper-button-prev"></div>

        </div>
    </section>
@endif
{{-- End of Upcoming Events --}}

{{-- Why choose us --}}
    <section class="why-choose-us-sec">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <h3 class="main-title-sec mb-5 text-white">Why Training Block</h3>
                    <div class="why-choose-div">
                        <div class="why-choose-icon">
                            <img src="{{ asset('../front/images/new-template/why-choose-icon1.png') }}">
                        </div>
                        <h4>A Curated Network of Sport Performance Experts</h4>
                        <p>Whether you need physical therapy, sport psychology, coaching, a nutrition plan, or better recovery, you can find it all on the Training Block network. We connect you with only the top experts who work with endurance athletes.</p>
                    </div>
                    <div class="why-choose-div">
                        <div class="why-choose-icon">
                            <img src="{{ asset('../front/images/new-template/why-choose-icon2.png') }}">
                        </div>
                        <h4>Educating Athletes</h4>
                        <p>Our Resource Library gives you direct access to articles and videos from top practitioners on varying topics catered specifically for endurance athletes: from rest & recovery, to strength training, to return from injury, to mental toughness.</p>
                    </div>
                    <div class="why-choose-div">
                        <div class="why-choose-icon">
                            <img src="{{ asset('../front/images/new-template/why-choose-icon3.png') }}">
                        </div>
                        <h4>Connecting Coaches, Practitioners and Athletes Through Events</h4>
                        <p>Our community of practitioners and coaches host regular events like local group runs, webinars, and in-person workshops to benefit your training. Find and register for an event near you on our Events page.</p>
                    </div>
                    <div class="why-choose-div">
                        <div class="why-choose-icon">
                            <img src="{{ asset('../front/images/new-template/why-choose-icon4.png') }}">
                        </div>
                        <h4>A Community for Athletes and their Support Crew</h4>
                        <p>Love who you work with, and want to spread the word? Leave them a review, or add them to your “Favorites” on your Training Block profile.</p>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <img src="{{ asset('../front/images/new-template/why-us-img.png') }}" class="why-choose-img">
                </div>
            </div>
        </div>
    </section>

{{-- End of Why choose us --}}

{{-- Recent Resources --}}
<section class="resources-sec">
        <div class="dotted-circle left-round"></div>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="main-title-sec mb-3">Recently Uploaded Resources</h3>
                </div>

                @foreach($latestResources as $latestResource)
                <div class="col-xl-4 col-lg-4" data-aos="fade-right" data-aos-duration="1000" data-aos-delay="200"
                    data-aos-once="true">
                    <div class="resources">
                        <div class="image-box"><a href="{{ url('resource-details/'.base64_encode($latestResource->id)) }}"><img src="{{ asset('/front/images/resource/'.$latestResource->image_name) }}" alt=""></a> </div>
                        <div class="content-box">
                            <span class="date-span">{{ $latestResource->category }}
                            </span>
                            <h3><a href="{{ url('resource-details/'.base64_encode($latestResource->id)) }}">{{ $latestResource->title }}</a> </h3>

                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="display-center"> <a href="{{ url('/resource-library') }}" class="btn btn-style-new btn-outline-danger" data-aos="fade-up" data-aos-delay="800"
                    data-aos-duration="1200" data-aos-once="true">Show all</a></div>
        </div>
    </section>
{{-- End of Recent Resources --}}

<section class="about-us-sec">
        <div class="container position-relative">
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="main-title-sec">About Training Block</h3>
                    <p>Training Block is not just a platform: it is a central hub, a builder of community between athletes and local athlete-focused businesses.   Here at Training Block, we celebrate transparency and collaboration within the athletic community, in furtherance of individual and collective performance goals. Everything we do is in support of our athletes and the local athletic providers that help them thrive.</p>
                    <a href="{{url('/aboutus')}}" class="btn btn-style-new btn-outline-danger">Read More</a>
                </div>

            </div>
        </div>
    </section>

{{-- Testimonial --}}
@if(count($testimonials) > 0)
    <section class="testimonial-sec">
        <div class="container position-relative">
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="main-title-sec mb-3">Testimonials</h3>
                </div>
                <div class="swiper swiper-slide testimonial-slider">
                    <div class="swiper-wrapper">
                    @foreach($testimonials as $testimonial)
                        <div class="swiper-slide col-lg-4" data-aos="fade-up" data-aos-duration="1000"
                            data-aos-delay="200">
                            <a href="{{ url('/testimonial/'.base64_encode($testimonial->id)) }}">
                            <div class="testimonial-grid">
                                <div class="thumbnail">
                                    <img src="{{ $testimonial->user_image != '' ? asset('images/testimonials/'.$testimonial->user_image) : asset('front/images/events_default.png') }}" alt="Testimonial" class="profile-icon">
                                    <span class="qoute-icon"><img src="{{ asset('../front/images/new-template/quotes.svg') }}" width="16"></span>

                                </div>
                                <div class="content">
                                    <h4>{{ $testimonial->title }}</h4>
                                    <p>{{ str_limit($testimonial->description, $limit = 100, $end = '...') }}</p>
                                    <div class="rating-icon">
                                        @php
                                        $count = 1;
                                        $result = "";
                                        $stars = $testimonial->rating
                                        @endphp
                                        <?php for($i = 1; $i <= 5; $i++){
                                            if($stars >= $count){
                                                $result .= "<i class='fa-solid fa-star'></i>";
                                            } else {
                                                $result .= "<i class='fa-regular fa-star'></i>";
                                            }
                                            $count++;
                                        } ?>
                                        @php
                                        echo $result;
                                        @endphp
                                    </div>
                                    <h5 class="title">{{ $testimonial->user_name }}</h5>
                                    <span class="subtitle">{{ $testimonial->position }}</span>
                                </div>
                            </div>
                            </a>
                        </div>
                        @endforeach

                    </div>
                </div>
            </div>
            <div class="testimonial-slider-next swiper-button-next"></div>
            <div class="testimonial-slider-prev swiper-button-prev"></div>

        </div>
    </section>
    @endif
{{-- End of Testimonial --}}

 
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
                                        <h3 class="text-center">Looking for ways to train smarter?</h3>
                                        <p class="newsletter_text">Best training tips, directly from experts in sport, delivered to your inbox.</p>
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

<!-- Help Us Modal -->
<div class="modal fade" id="helpModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title w-100" id="myModalLabel" style="text-transform: capitalize;">We'll Help You Find An Expert! Just Let Us Know What You Need.</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body" style="height: auto;width: 100%;overflow: hidden;">
            <div class="alert alert-success" id="email_success" role="alert" style="display:none"></div>
            <form id="emailUsForm" name="emailUsForm" method="post">
               @csrf
               <div class="row">
                  <div class="col-md-12">
                     <div class="row">
                        <div class="col-md-6">
                           <div class="form-group">
                              <label for="name">First Name<span class="required">*</span> :</label>
                              <input type="text" class="form-control" name="firstName" id="firstName" placeholder="Enter First Name">
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                              <label for="name">Last Name<span class="required">*</span> :</label>
                              <input type="text" class="form-control" name="lastName" id="lastName" placeholder="Enter Last Name">
                           </div>
                        </div>
                     </div>
                     <div class="form-group">
                        <label for="email">Email Address<span class="required">*</span> :</label>
                        <input type="text" class="form-control" name="email" id="email" placeholder="Enter Email Address">
                        @if($errors->has('email'))
                        <div class="error">{{ $errors->first('email') }}</div>
                        @endif
                     </div>
                     <div class="form-group">
                        <label for="phone">Phone Number :</label>
                        <input type="text" class="form-control" name="phone" id="phone" placeholder="(998) 000-0000">
                     </div>
                     <div class="form-group">
                        <label for="subject">Subject:</label>
                        <input type="text" class="form-control" name="subject" id="subject" placeholder="Enter Subject" maxlength="50">
                     </div>
                  </div>
                  <div class="col-md-12">
                     <div class="form-group">
                        <label for="email_body">Message</label>
                        <textarea rows="" class="form-control" name="email_body" id="email_body" cols="10" maxlength="500"></textarea>
                     </div>
                  </div>
                  <div class="col-md-12">
                     <div class="form-group">
                        <div class="g-recaptcha" data-sitekey="{{ env('CAPTCHA_SITE_KEY') }}" data-callback="recaptchaCallback"></div>
                        <input type="hidden" class="hiddenRecaptcha" name="hiddenRecaptcha" id="hiddenRecaptcha">
                     </div>
                  </div>
                  <div class="col-md-12 display-center">
                     <button type="submit" id="emailSubmit" class="btn btn-info btn-lg mb-3">Send</button>
                  </div>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>



@stop
@section('pagescript')
<style>
#help_button {
  text-decoration: underline;
}
label.error, .required {
    color: red;
    font-weight: 400;
}
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
<!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD0sBm7n3sKRiVvtBekP81GCR4r0cjmSDQ"></script> -->

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
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
<script type='text/javascript' src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.5/jquery.inputmask.min.js"></script>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>



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
                // new PNotify({
                //     title: 'Oh No!',
                //     text: 'Something went wrong!',
                //     type: 'error'
                // });
                console.log('Something went wrong!');
            }
        });
    } 
    // $(document).ready(function () {
    //     getadvhor('horizontal','top');
    //     getadvhor('horizontal','bottom');
    //     getadvhor('vertical','left');
    //     getadvhor('vertical','right');
    // });
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
      $(document).on('click', '#locationlist li', function(){  
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
    $('#location').val(cookieValue);
    $('#update_location').on('click', function() {
    // $(window).on('load', function() {
        $.removeCookie("locations");
        cookieValue = $.cookie("locations");
        console.log(cookieValue);
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
                        $.cookie("locations", "");
                        $.cookie("locationsLat", "");
                        $.cookie("locationsLng", "")
                        navigator.geolocation.clearWatch(watchId);
                    }
                    switch (error.code) {
                        case error.PERMISSION_DENIED:
                            console.log("Location not provided");
                            break;

                        case error.POSITION_UNAVAILABLE:
                            console.log("Current location not available");
                            break;

                        case error.TIMEOUT:
                            console.log("Timeout");
                            break;

                        default:
                            console.log("unknown error");
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
                        var locationLat = result.latlng.lat;
                        var locationLng = result.latlng.lng;
                        // console.log(result.latlng);
                        console.log(locationAddress);

                        if (locationAddress != '') {
                            $.cookie("locations", locationAddress);
                            $.cookie("locationsLat", locationLat);
                            $.cookie("locationsLng", locationLng);
                            $('#location').val(locationAddress);
                            $('.location-box').val(locationAddress);
                            window.location.reload(true);
                        } else {
                            $.cookie("locations", "");
                            $.cookie("locationsLat", "");
                            $.cookie("locationsLng", "");
                            $('#location').val('');
                            $('.location-box').val('');
                            window.location.reload(true);
                        }

                    });
                };


            } else {
                $('#searchform #location').removeAttr('value');

                //$.cookie("locations", "empty", { expires: 1 });
                $.cookie("locations", "");
                $.cookie("locationsLat", "");
                $.cookie("locationsLng", "");
                $('.location-placeholder').css('display', 'block');
                $('#location').val('');
                $('.location-box').val('');
                window.location.reload(true);
            }
        } else {
            if (cookieValue == '') {
                $('#searchform #location').removeAttr('value');
                $('.location-box').removeAttr('value');
                $('.location-placeholder').css('display', 'block');
                $('#location').val('');
            } else {
                $('#searchform #location').val(cookieValue);
                $('.location-box').val(cookieValue);

            }
        }
    });
    if (cookieValue == '') {
        $('#searchform #location').removeAttr('value');
        $('.location-box').removeAttr('value');
        $('.location-placeholder').css('display', 'block');
    } else {
        $('#searchform #location').val(cookieValue);
        $('.location-box').val(cookieValue);

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
    
}
},3000);
});


$('.close').on('click', function(){
    $.cookie("newsletter", 1);
});

// $('#button_both').on('click', function(){

//     if($('#searchform #location').val() !== ""){
//         $('#virtual_in_both').val('In Person');
//     }else{
//         $('#virtual_in_both').val('Both');        
//     }
    
// });

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
    $('.location-box').val('');
});
// $('#button_inperson').on('click', function(){
//     $('.banner-search .search-group').css('max-width', '498px');
// });

</script>

<!-- Help us to find popup ajax call -->
<script>
    
    function recaptchaCallback() {
  		$('#hiddenRecaptcha').valid();
	};

$(function() {

  // Initialize form validation on the registration form.
  // It has the name attribute "registration"
  $("form[name='emailUsForm']").validate({
    // Specify validation rules
	ignore: ".ignore",
    rules: {
	  firstName : {
		  required: true
	  },
	  lastName : {
		  required: true
	  },
      email: {
        required: true,
        email: true,
		customemail : true
      },
	  phone: {
		  phoneUS: true,
		  minlength:14,
          maxlength:14
	  },
	  confirmPassword: {
		equalTo: "#password",
	  },
	  hiddenRecaptcha: {
                required: function () {
                    if (grecaptcha.getResponse() == '') {
                        return true;
                    } else {
                        return false;
                    }
                }
            }
    },
    // Specify validation error messages
    messages: {
	  firstName : "First Name is required",
	  lastName : "Last Name is required",
      email: {
		  required : "Email is required.",
		  email :"Please enter a valid email address"
	  },
	  phone: {
		minlength : "Please enter valid phone number.",
		maxlength : "Please enter valid phone number."
	  },
	  password : "Password is required.",
	  confirmPassword : "Paswword does not match.",
	  hiddenRecaptcha: "Please solve the captcha.",
    },
    submitHandler: function(form) {

	$('#emailSubmit').attr('disabled','disabled');
	$("#emailSubmit").html('Sending...');
	$.ajax({
		url: "{{ route('athlete.help_email') }}",
		type: "POST",
		data : $("form[name='emailUsForm']").serialize(),
		headers: {
					'X-CSRF-TOKEN': '{{ csrf_token() }}'
		},
		success: function(response){
			$('#email_success').html('');
			$('#emailUsForm')[0].reset();
			grecaptcha.reset();
			$('#emailSubmit').removeAttr('disabled');
			$("#emailSubmit").html('Send');
			$('#helpModal').modal('toggle');
            swal({
                title: "Your request is on its way!",
                text: "Be on the lookout for an email from a Training Block representative in the next 48 hours, with personalized recommendations. You can also always email us directly at support@trainingblockusa.com. Happy Training!",
                icon: "success",
                className: "help-thanks",
                button: "Ok",
                });
		},
		error: function(response){
		},
	});
	
	return false;
}
  });

  $('#phone').inputmask({"mask": "(999) 999-9999"})  
});

$.validator.addMethod("customemail", 
    function(value, element) {
        return /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/.test(value);
    }, 
    "Please enter a valid email address"
);
jQuery.validator.addMethod("phoneUS", function(phone_number, element) {
    phone_number = phone_number.replace(/\s+/g, "");
    return this.optional(element) || phone_number.length > 9 && 
    phone_number.match(/^(\+?1-?)?(\([2-9]\d{2}\)|[2-9]\d{2})-?[2-9]\d{2}-?\d{4}$/);
}, "Please enter valid phone number");

// remove alert box while close modal
$('#helpModal .close').on('click', function(){
	$('#email_success').html('');
	$('#email_success').css('display', 'none');
	$('#email_danger').html('');
	$('#email_danger').css('display', 'none');
	$('.error').each(function(i, obj) {
    $(this).html('');
	$('label.error').css('display', 'none');
	});
	$('#helpModal input').each(function(i, obj) {
    $(this).val('');
	});
	grecaptcha.reset()
});

// Reset Email body values while close modal
$('#helpModal .close').on('click', function(){
	$('#helpModal #email_body').val('');
});



</script>

 <script type="text/javascript">
        jQuery(document).ready(function ($) {
            if (window.history && window.history.pushState) {
                window.history.pushState('explore' + window.location.search, null);
            }
        });
    </script>
    <script>
        AOS.init({
            duration: 800,
        });
    </script>
    <script src="{{ asset('../front/swiper/swiper-bundle.min.js') }}"></script>
    <script>
        var swiper = new Swiper(".category-slider", {
            slidesPerView: "auto",
            spaceBetween: 0,
            loop: false,
            allowTouchMove: false,
            a11y: false,
            navigation: {
                nextEl: ".category-next",
                prevEl: ".category-prev",
            },

            breakpoints: {
                "@0.00": {
                    slidesPerView: 2,

                },
                "@0.65": {
                    slidesPerView: 2,

                },
                "@1.00": {
                    slidesPerView: 3,

                },
                "@1.20": {
                    slidesPerView: 3,

                },
                "@1.50": {
                    slidesPerView: "auto",

                },
            },

        });
    </script>

    <script>
        var swiper = new Swiper(".top-rated-slider", {
            slidesPerView: "auto",
            spaceBetween: 0,
            loop: false,
            allowTouchMove: false,
            a11y: false,
            navigation: {
                nextEl: ".top-rated-slider-next",
                prevEl: ".top-rated-slider-prev",
            },
            breakpoints: {
                "@0.00": {
                    slidesPerView: 1,

                },
                "@0.65": {
                    slidesPerView: "auto",

                },
                "@1.00": {
                    slidesPerView: "auto",

                },
                "@1.50": {
                    slidesPerView: "auto",

                },
            },
        });
    </script>
    <script>
        var swiper = new Swiper(".testimonial-slider", {
            slidesPerView: "auto",
            spaceBetween: 0,
            loop: false,
            a11y: false,
            allowTouchMove: false,
            navigation: {
                nextEl: ".testimonial-slider-next",
                prevEl: ".testimonial-slider-prev",
            },
            breakpoints: {
                "@0.00": {
                    slidesPerView: 1,

                },
                "@0.75": {
                    slidesPerView: "auto",

                },
                "@1.00": {
                    slidesPerView: "auto",

                },
                "@1.50": {
                    slidesPerView: "auto",

                },
            },
        });
    </script>
    <script>
        var swiper = new Swiper(".upcoming-events-slider", {
            slidesPerView: "auto",
            spaceBetween: 0,
            loop: false,
            allowTouchMove: false,
            a11y: false,
            navigation: {
                nextEl: ".upcoming-events-slider-next",
                prevEl: ".upcoming-events-slider-prev",
            },
            breakpoints: {
                "@0.00": {
                    slidesPerView: 1,

                },
                "@0.75": {
                    slidesPerView: "auto",

                },
                "@1.00": {
                    slidesPerView: "auto",

                },
                "@1.50": {
                    slidesPerView: "auto",

                },
            },
        });
    </script>

@endsection


