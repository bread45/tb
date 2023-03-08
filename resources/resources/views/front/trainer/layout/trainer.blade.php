<!DOCTYPE html>
<html lang="en">
<head>
    @include('front.trainer.layout.includes.head')
</head>
<body>
    <div id="wrapper"> 
        @include('front.trainer.layout.includes.sidebar')
        @yield('content')  
       
    </div>
    
    @include('front.trainer.layout.includes.scriptlink')
    @yield('pagescript')
</body>
</html>