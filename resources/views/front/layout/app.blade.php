<!DOCTYPE html>
<html lang="en">
    <head>@include('front.layout.includes.head')</head>
    <body>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NZC4WTH"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->	


        <!--<div id="loader-wrapper">
            <div id="loader">
                <img src="{{ asset('images/loader.png') }}" alt="Loader">
            </div>
            <div class="loader-section section-left"></div>
            <div class="loader-section section-right"></div>
        </div>-->
        @include('front.layout.includes.header')
        @yield('content')
        @include('front.layout.includes.footer')
        @include('front.layout.includes.script')
        @yield('pagescript')
    </body>
</html>