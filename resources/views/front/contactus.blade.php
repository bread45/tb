@extends('front.layout.app')
@section('title', 'Contact')
@section('content')
<section class="inner-banner-section bg-primary">
        <div class="container">
            <div class="banner-content">
                <h1>Contact</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Contact</li>
                    </ol>
                </nav>
            </div>
        </div>
    </section>
<section class="contact-map-section py-0">
    <!--<div id="map"></div>-->
<!--<div class="mapouter"><div class="gmap_canvas"><iframe width="600" height="765" id="gmap_canvas" src="https://maps.google.com/maps?q=jacksonville%2C%20florida&t=&z=13&ie=UTF8&iwloc=&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe><a href="https://embed-google-map.net">maps.goog;e.com</a></div><style>.mapouter{position:relative;text-align:right;height:765px;width:600px;}.gmap_canvas {overflow:hidden;background:none!important;height:765px;width:600px;}</style></div>-->
        <iframe src="https://maps.google.com/maps?q=jacksonville%2C%20florida&t=&z=13&ie=UTF8&iwloc=&output=embed" height="765" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
    </section>
<section class="contact-information-section">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-lg-5">
                    <div class="section-title mb-3">
                        <h5 class="text-danger">HOW TO REACH US</h5>
                        <h2 class="mb-0">SAY HELLO</h2>
                    </div>
                    <div class="contact-detail">
                        <p class="mb-4">We would love to hear from you. Reach out to us any time at the phone number and email address listed below, or send us a brief message directly through our site. Follow along on our instagram page to stay in the know on what is happening in the Training Block community!</p>
                        <div class="contact-call pt-lg-3">
                            <a class="call-link" href="callto:{{getSetting('phone-number')}}">{{getSetting('phone-number')}}</a>
                            <a class="email-link" href = "mailto: {{getSetting('email')}}">{{getSetting('email')}}</a>
                        </div>
                        <div class="contact-social pt-2">
                            <ul class="nav social-nav mb-3">
                                <li class="nav-item">
                                    <a href="{{getSetting('facebook-link')}}" class="nav-link facebook" title="Facebook">facebook</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{getSetting('linkdin-link')}}" class="nav-link linkedin" title="Linkedin">linked in</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{getSetting('twitter-link')}}" class="nav-link twitter" title="Twitter">twitter</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{getSetting('insta-link')}}" class="nav-link insta" title="Instagram">insta</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <form class="" method="post"> 
                        @csrf
                        <div class="contact-form">
                            <div class="section-title mb-3">
                                @include('admin.theme.includes.message')
                                <h5 class="text-danger">get in touch</h5>
                                <h2 class="mb-0">Contact us</h2>
                            </div>
                            <div class="form-group">
                                <label for="name-input">Name</label>
                                <input type="text" class="form-control" name="name" id="name-input" placeholder="Enter Your name" value="{{ old('name') }}" required="" />
                            </div>
                            <div class="form-group">
                                <label for="mail-input">Email</label>
                                <input type="email" class="form-control" name="email" id="mail-input" placeholder="Enter Your Email" value="{{ old('email') }}" required="" />
                            </div>
                            <div class="form-group">
                                <label for="number-input">Phone Number</label>
                                <input type="tel" class="form-control" name="phone_number" id="number-input" placeholder="Enter Your Phone Number" value="{{ old('phone_number') }}" required="" pattern="^(\+\d{1,2}[\s.-]?)?\(?\d{3}\)?[\s.-]?\d{3}[\s.-]?\d{4}$" />
                            </div>
                            <div class="form-group">
                                <label for="name-input">Message </label>
                                <textarea class="form-control" name="message" id="message" placeholder="Enter Your Message Here..." required="">{{ old('message') }}</textarea>
                            </div>
                            <script src='https://www.google.com/recaptcha/api.js'></script>
                            <div class="g-recaptcha" style="margin-bottom: 20px;"
        data-sitekey="6LdnSykaAAAAALJrvGhc1sJODDfc4eHRFEJgm-Es">
    </div>
                            @if ($errors->has('g-recaptcha-response'))
                                    <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('g-recaptcha-response') }}</div>
                                    @endif 
                            <div class="text-center">
                                <input type="submit" name="submit" class="btn btn-danger btn-lg" value="Submit"> 
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@stop
@section('pagescript')
<script>
    $(function(){
        $("input[name=phone_number]")[0].oninvalid = function () {
            this.setCustomValidity("Please enter a valid phone number");
        };
        $("input[name=phone_number]")[0].oninput= function () {
            this.setCustomValidity("");
        };
    });
</script>
@endsection
<!--   <script>
// Initialize and add the map
function initMap() {
  // The location of Uluru
  var uluru = {lat: 30.286200, lng: -81.487100};
  // The map, centered at Uluru
  var map = new google.maps.Map(
      document.getElementById('map'), {zoom: 4, center: uluru});
  // The marker, positioned at Uluru
  var marker = new google.maps.Marker({position: uluru, map: map});
}
    </script>
    Load the API from the specified URL
    * The async attribute allows the browser to render the page while the API loads
    * The key parameter will contain your own API key (which is not needed for this tutorial)
    * The callback parameter executes the initMap() function
    
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCwTQvBSMhoj6K_YipJMI-PKXMN0xRBLBc&callback=initMap">
    </script>-->
