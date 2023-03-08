<!DOCTYPE html>
<html lang="en">
@section('title', 'Business')
<head>@include('front.layout.includes.head')</head>
    
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NZSZPSN"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>

<body class="loaded">
<header class="navbar navbar-expand-lg navbar-dark home-header business-header">
        <a class="navbar-brand" href="{{ url('/') }}">
            <img src="{{ asset('images/logo.png') }}" alt="Training Block">
        </a>

        <!-- <div class="dropdown">
            <a class="btn btn-link " href="{{ url('/') }}"
                style="background-image: none;">Home</a>
        </div> -->
    </header>
    <div class="clearfix"></div>

 <!-- Slider -->
 <section class="account-page-area section-gap-equal">
        <div class="container position-relative">
            <div class="row g-5 justify-content-end">
                <div class="col-lg-5">
                    <div class="login-form-box">
                        <h3 class="title">LOGIN</h3>
                        @if ($errors->any())
                        <div class="alert alert-danger">
                           {{$errors->first()}}
                        </div>
                        @endif
                        <form  class="form" method="POST" action="{{ route('front.provider.loggedIn') }}">
                            @csrf
                             <div class="form-group">
                                 <label for="mail-input">Email</label>
                                 <input type="email" class="form-control" name="email" id="mail-input-customer" placeholder="Enter your email" pattern="[^@\s]+@[^@\s]+\.[^@\s]+" required="" />
                             </div>
                             <div class="form-group">
                                 <label for="password-input">Password</label>
                                 <input type="password" class="form-control" name="password" id="password-input" placeholder="Enter your password" required="" />
                             </div>
                        
                            <div class="form-group">
                                <button type="submit" class="edu-btn btn-medium">Login</button>
                            </div>
                            <div class="form-group chekbox-area mt-2">
                            <div class="remember-password custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="remember" id="customCheck1" value="true">
                                <label class="custom-control-label" for="customCheck1">Remember Me</label>
                            </div>
                                <a href="{{route('front.password.reset')}}" class="lostposs password-reset">Lost Your Password?</a>
                            </div>
                            <p class="text-white text-center">Or login with</p>
                            <div class="row">
                                <div class="col-md-6">
                                    <a href="{{ env('FRONT_URL') }}/auth/google" class="mobile-res-btn"> <button type="button"
                                            class="google-btn btn-medium"><img src="{{ asset('../front/images/new-template/google.svg') }}"
                                                alt="GOOGLE">Google
                                        </button></a>
                                </div>
                                <div class="col-md-6">
                                    <a href="{{ env('FRONT_URL') }}auth/fbredirect/facebook" class="mobile-res-btn"> <button type="button"
                                            class="facebook-btn btn-medium"><img src="{{ asset('../front/images/new-template/facebook.svg') }}"
                                                alt="Facebook">
                                            Facebook
                                        </button></a>
                                </div>
                            </div>
                            <div class="form-group mt-3 text-center">
                                <p style="color: #fff;">New to training block? <a href="{{ url('provider-register') }}">Join our provider network</a></p>
                            </div>

                        </form>
                    </div>
                </div>

            </div>

        </div>
    </section>

    <!-- Why choose us section -->

    <section class="why-choose-us-sec-provider">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <h3 class="main-title-sec mb-5">Why Training Block</h3>
                    <div class="why-choose-div-provider">
                        <div class="why-choose-icon">
                            <img src="{{ asset('../front/images/new-template/why-choose-business-icon1.png') }}">
                        </div>
                        <h4>Reach new clients</h4>
                        <p>Training Block gets you in front of thousands of potential athlete clients who are looking for training-related services.</p>
                    </div>
                    <div class="why-choose-div-provider">
                        <div class="why-choose-icon">
                            <img src="{{ asset('../front/images/new-template/why-choose-business-icon2.png') }}">
                        </div>
                        <h4>Centralize your content and events</h4>
                        <p>Upload your blogs and videos to our Resource Library, to get your content in front of more people. Grow event participation by adding your workshops, webinars, and/or sporting events to our Events page.</p>
                    </div>
                    <div class="why-choose-div-provider">
                        <div class="why-choose-icon">
                            <img src="{{ asset('../front/images/new-template/why-choose-business-icon3.png') }}">
                        </div>
                        <h4>Grow your referral network</h4>
                        <p>Whether you need to make a referral out of the area, or connect with local sport performance experts, quality networking starts here.</p>
                    </div>
                    <div class="why-choose-div-provider">
                        <div class="why-choose-icon">
                            <img src="{{ asset('../front/images/new-template/why-choose-business-icon4.png') }}">
                        </div>
                        <h4>Join the mission</h4>
                        <p>We believe athletes need better access to quality sport performance coaches and practitioners. Help us support athletes by joining our network of experts.</p>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <img src="{{ asset('../front/images/new-template/why-us-business-img-1.png') }}" class="why-choose-img">
                </div>
            </div>
        </div>
    </section>


    <section>
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <img src="{{ asset('../front/images/new-template/provider-1.jpg') }}" class="border-radius">
                </div>
                <div class="col-md-8">
                    <div class="content-center-bloc">
                        <div>
                            <h4>The Industry Leader For Sport Performance</h4>
                        </div>
                        <div>
                            <p>Training Block is the leading full-service platform for endurance athletes to find quality, sport-related services. We ensure each practitioner and coach on our platform has the right experience, expertise, and education to positively impact the athletes they work with.</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
    <section class="light-bg-1">
        <div class="container">
            <div class="row">
                <div class="col-md-8 order-md-1 order-sm-2">
                    <div class="content-center-bloc">
                        <div>
                            <h4>Centralizing The Performance Network</h4>
                        </div>
                        <div>
                            <p>We understand athletes have unique needs when it comes to their training. This is why we work with all experts in sport performance, including coaches, strength and conditioning experts, physical therapists, chiropractors, acupuncturists, dietitians, massage therapists, sport psychologists, and more.</p>
                        </div>
                    </div>

                </div>
                <div class="col-md-4 order-sm-1">
                    <img src="{{ asset('../front/images/new-template/provider-2.jpg') }}" class="border-radius">
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <img src="{{ asset('../front/images/new-template/provider-3.jpg') }}" class="border-radius">
                </div>
                <div class="col-md-8">
                    <div class="content-center-bloc">
                        <div>
                            <h4>Educating Athletes By Highlighting Experts</h4>
                        </div>
                        <div>
                            <p>At Training Block, we believe athletes not only need access to quality performance services, but also want to learn more about different aspects of their training. This is why we offer a Resource Library for providers to upload helpful articles and videos, and an Events page for providers to host workshops, clinics, and other educational events.</p>
                        </div>
                    </div>

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
                            <a href="{{ env('FRONT_URL') }}testimonial/{{ base64_encode($testimonial->id)}}">
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
                                                $result .= " <i class='fa-regular fa-star'></i>";
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

@section('pagescript')
<style>
#help_button {
  text-decoration: underline;
}
label.error, .required {
    color: red;
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

@endsection
@include('front.layout.includes.footer')
@include('front.layout.includes.script')
@yield('pagescript')
<script type="text/javascript">
jQuery(document).ready(function($) {
        if (window.history && window.history.pushState) {
        window.history.pushState('explore'+window.location.search, null);
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
        var swiper = new Swiper(".testimonial-slider", {
            slidesPerView: "auto",
            spaceBetween: 0,
            loop: false,
            allowTouchMove: false,
            a11y: false,
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
    @if (session('message'))
<script>
    new PNotify({
                    title: '{{ session('message') }}',
                            text: '',
                            type: 'success'
                });
</script>
@endif
    </body>
</html>