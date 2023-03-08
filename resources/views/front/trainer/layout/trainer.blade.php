<!DOCTYPE html>
<html lang="en">
<head>
    @include('front.trainer.layout.includes.head')
</head>
<body>
    <!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NZC4WTH"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
    <div id="wrapper" class="trainerWrapper">
    <div class="row"> 
    <div class="col-md-2">  
        @include('front.trainer.layout.includes.sidebar')
    </div>
    <div class="col-md-10">
        @yield('content')  
    </div>
    </div>
    </div>
    @include('front.trainer.layout.includes.scriptlink')
    @yield('pagescript')
</body>
</html>
