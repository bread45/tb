  <?php //dd(request()->segment(1)); ?>
  <header class="navbar navbar-expand-lg navbar-dark home-header">
        <a class="navbar-brand" href="{{ url('/')}}">
            <img src="{{ asset('images/logo.png') }}" alt="Training Block">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
               <!-- <li class="nav-item {{ (request()->segment(1) == '') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ url('/')}}">Home</a>
                </li>
                <li class="nav-item {{ (request()->segment(1) == 'aboutus') ? 'active' : '' }}">
                    <a class="nav-link" href="{{route('aboutus')}}">About</a>
                </li>-->
                
                <!-- <li class="nav-item {{ (request()->segment(1) == 'exploreservices') ? 'active' : '' }} {{ (request()->segment(1) == 'exploreservices') ? 'active' : '' }}">
                    <a class="nav-link" href="{{route('exploreservices')}}">Explore</a>
                </li> -->
                <li class="nav-item {{  request()->is('explore') ? 'active' : '' }} {{ request()->is('provider/*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{route('exploreservices')}}">Explore</a>
                </li>
                <li class="nav-item {{ (request()->segment(1) == 'resource-library') ? 'active' : '' }} {{ (request()->routeIs('resource-details*')) ? 'active' : '' }}">
                    <a class="nav-link" href="{{route('resource-library')}}">Resource Library</a>
                </li>
<!--                <li class="nav-item {{ (request()->segment(1) == 'contactus') ? 'active' : '' }}">
                    <a class="nav-link" href="{{route('contactuspage')}}">Contact Us</a>
                </li>-->

               <!--  <li class="nav-item {{ (request()->segment(1) == 'blogs') ? 'active' : '' }}">
                    <a class="nav-link" href="{{route('blogs')}}">Blog</a>
                </li> -->
                <!-- @if(Auth::guard('front_auth')->check())
                
                <li id="bell" class="nav-item {{ (request()->segment(1) == 'contactus') ? 'active' : '' }}">
                    <nav>
                        <i class="fas fa-bell bell-icn"></i>
                        @php
                        $notificationdata = \App\Notification::with('quotebyfrom')->with('quotebyto')->where('to_user_id', Auth::guard('front_auth')->user()->id)->orderBy('created_at', 'desc')->get();

                        @endphp
                        
                        <div class="notifications" id="box" style="display: none">
                        <h2>Notifications <span>({{$notificationdata->count()}})</span></h2>
                        @foreach($notificationdata as $notification)
                        <div class="notifications-item"> <img src="@if(isset($notification->quotebyfrom->photo) && !empty($notification->quotebyfrom->photo) && file_exists(public_path('front/profile/'.$notification->quotebyfrom->photo))) {{ asset('front/profile/'.$notification->quotebyfrom->photo)}} @else {{ asset('/front/images/details_default.png')}}  @endif" alt="img">
                            <div class="text">
                                <h4>{{$notification->title}}</h4>
                                <p>{{$notification->message}}</p>
                            </div>
                        </div> 
                        @endforeach
                    </div>
                    </nav> 
                </li>
                 
                @endif -->
            </ul>
        </div>
        <div class="account-link dropdown">
            @if(Auth::guard('front_auth')->check())
                <a class="btn btn-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background:none">
                @if(!empty(Auth::guard('front_auth')->user()->business_name))
                 @if(!empty(Auth::guard("front_auth")->user()->photo))
                <img style="width: 37px;height: 37px;border-radius: 50%;object-fit: cover;" src="{{asset('front/profile/'.Auth::guard('front_auth')->user()->photo)}}" alt="{{!empty(Auth::guard('front_auth')->user()->business_name) ? Auth::guard('front_auth')->user()->business_name :  Auth::guard('front_auth')->user()->first_name}} {{Auth::guard('front_auth')->user()->last_name}}" class="mr-2">
   
    @else
    <img style="width: 37px;height: 37px;border-radius: 50%;object-fit: cover;" src="{{asset('front/trainer/images/profile-img.jpg')}}" alt="{{!empty(Auth::guard('front_auth')->user()->business_name) ? Auth::guard('front_auth')->user()->business_name :  Auth::guard('front_auth')->user()->first_name}} {{Auth::guard('front_auth')->user()->last_name}}" class="mr-2">
    
    @endif
                {{Auth::guard('front_auth')->user()->business_name}}
               
                @else
                @if(!empty(Auth::guard("front_auth")->user()->photo))
    <img style="width: 37px;height: 37px;border-radius: 50%;object-fit: cover;" src="{{asset('front/profile/'.Auth::guard('front_auth')->user()->photo)}}" alt="{{!empty(Auth::guard('front_auth')->user()->business_name) ? Auth::guard('front_auth')->user()->business_name :  Auth::guard('front_auth')->user()->first_name}} {{Auth::guard('front_auth')->user()->last_name}}" class="mr-2">
   
    @else
    <img style="width: 37px;height: 37px;border-radius: 50%;object-fit: cover;" src="{{asset('front/trainer/images/profile-img.jpg')}}" alt="{{!empty(Auth::guard('front_auth')->user()->business_name) ? Auth::guard('front_auth')->user()->business_name :  Auth::guard('front_auth')->user()->first_name}} {{Auth::guard('front_auth')->user()->last_name}}" class="mr-2">
    
    @endif
                {{Auth::guard('front_auth')->user()->first_name}} {{Auth::guard('front_auth')->user()->last_name}}
                
                @endif
                </a>
                <div class="dropdown-menu dropdown-menu-right mt-3" aria-labelledby="navbarDropdown">
                    @if(Auth::guard('front_auth')->user()->user_role == 'customer') 
                        <a class="dropdown-item {{ (\Request::route()->getName() == 'customer.profile') ? 'active' : '' }}" href="{{route('customer.newprofile',Auth::guard('front_auth')->user()->first_name. '-'.Auth::guard('front_auth')->user()->last_name. '-'.Auth::guard('front_auth')->user()->id)}}">Profile</a>
                         <a class="dropdown-item {{ (\Request::route()->getName() == 'customer.order.history') ? 'active' : '' }}" href="{{route('customer.order.history')}}">Order History</a> 
                        <a class="dropdown-item {{ (\Request::route()->getName() == 'customer.ratings.list') ? 'active' : '' }}" href="{{route('customer.ratings.list')}}">Your Reviews</a>
                        <!--<a class="dropdown-item {{ (\Request::route()->getName() == 'customer.private_messaging') ? 'active' : '' }}" href="{{route('customer.private_messaging')}}">Private Messaging</a>-->
                    @endif
                    @if(Auth::guard('front_auth')->user()->user_role == 'trainer') 
                    <a class="dropdown-item {{ (\Request::route()->getName() == 'front.dashboard') ? 'active' : '' }}"  href="{{route('front.dashboard')}}">Dashboard</a>
                    
                    <a class="dropdown-item {{ (\Request::route()->getName() == 'front.profile') ? 'active' : '' }}"  href="{{url('provider/'.Auth::guard('front_auth')->user()->spot_description)}}">View Profile</a>
                    {{-- <a class="dropdown-item {{ (\Request::route()->getName() == 'trainer.order.history') ? 'active' : '' }}" href="{{route('trainer.order.history')}}">Order History</a> --}}
                    <!--<a class="dropdown-item {{ (\Request::route()->getName() == 'trainer.ratings.list') ? 'active' : '' }}" href="{{route('trainer.ratings.list')}}">Your Reviews</a>-->
                    <!--<a class="dropdown-item {{ (\Request::route()->getName() == 'trainer.private_messaging') ? 'active' : '' }}"  href="{{route('trainer.private_messaging')}}">Private Messaging</a>-->
                    @endif
                    
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{route('front.logout')}}" onclick="event.preventDefault();
                                document.getElementById('customer-logout-form').submit();">Logout</a>
                    <form id="customer-logout-form" action="{{route('front.logout')}}" method="POST" style="display: none;">
                    @csrf                        
                    </form>
                </div>
            @else
                <a class="btn btn-link {{ (request()->segment(1) == 'login' || request()->segment(1) == 'register') ? 'active' : '' }}" href="{{ route('front.login')}}">LOGIN</a>
            @endif
           
        </div>
        @if(!(Auth::guard('front_auth')->check()))
        <div class="account-link dropdown">
         <a class="btn btn-link {{ (request()->routeIs('trainer.register')) ? 'active' : '' }}" href="{{ route('trainer.register')}}" style="background-image: none;">Join Our Provider Network</a>
        </div>
        @endif
    </header>
    <div class="clearfix"></div>