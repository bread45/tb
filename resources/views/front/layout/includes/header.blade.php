<header class="navbar navbar-expand-lg navbar-dark home-header">
        <a class="navbar-brand" href="{{ url('/')}}">
            <img src="{{ asset('images/logo.png') }}" alt="Training Block">
        </a>

        <div class="col d-none d-xl-block position-static">
            <nav class="site-main-menu location_menu">
                 <ul class="navbar-nav ml-auto">

                <li class="has-children position-static nav-item {{  request()->is('explore') ? 'active' : '' }} {{ request()->is('provider/*') ? 'active' : '' }}">
                    
                    <!-- <form action="{{ url('/explore') }}" method="post">
                     @csrf
                     <input type="hidden" name="location" class="location-box" value="" /> 
                    <button type="submit" class="has-children" >
                    <span class="menu-text">Explore </span>
                    </button>
                    </form> -->
                    <a href="#"><span class="menu-text">Explore</span></a>
                    <span class="menu-toggle"><i class="fas fa-angle-down"></i></span>
                            <ul class="mega-menu">
                                <li>
                                <a href="{{ url('/explore?virtual_in_both=Virtual Only') }}" class="virtualMenu">Virtual</a>
                                </li>
                            @php
                                $menuLocationList = DB::table('explore_menu_items')->get();
                            @endphp
                            @foreach($menuLocationList as $menuLocation) 
                                @if($menuLocation->city != '' || $menuLocation->state != '') 
                                @php
                                    $cityState =$menuLocation->city.', '. $menuLocation->state;
                                    @endphp
                                        <li class="nav-item">
                                        <a href="{{ url('/explore?location='.$cityState) }}">{{ $cityState }}</a>
                                        </li>
                                @endif
                            @endforeach
                            </ul>
                </li>
                <li class="nav-item {{ (request()->routeIs('search-event') || request()->routeIs('event-calendar') || request()->routeIs('events.details') || request()->routeIs('events.register') ) ? 'active' : '' }} {{ (request()->routeIs('event-calendar')) ? 'active' : '' }}">
                    <a class="nav-link" href="{{route('event-calendar')}}">Events</a>
                </li>
                <li class="nav-item {{ (request()->segment(1) == 'resource-library') ? 'active' : '' }} {{ (request()->routeIs('resource-details*')) ? 'active' : '' }}">
                    <a class="nav-link" href="{{route('resource-library')}}">Resource Library</a>
                </li>
                <!-- Notification icon -->
                @if(Auth::guard('front_auth')->check() && Auth::guard('front_auth')->user()->user_role == 'customer')
                
                <li id="bell" class="nav-item {{ (request()->segment(1) == 'contactus') ? 'active' : '' }}">
                <a href="{{route('customer.newprofile',Auth::guard('front_auth')->user()->first_name. '-'.Auth::guard('front_auth')->user()->last_name. '-'.Auth::guard('front_auth')->user()->id)}}#notification" id="bell_icon">
                     <nav>
                        @php
                        $requestlist = \App\Friend::with('user', 'friend')->where('friend_id', Auth::guard('front_auth')->user()->id)->where('accept', 0)->get();

                        @endphp
                        <i class="fas fa-bell bell-icn"><span class="badge badge-notify" style="font-size:11px;top: -9px;left: 9px;background: #cf5260;position: absolute;border-radius: 50%;"><?php if($requestlist->count() !=0){ echo $requestlist->count();}?></span></i>
                        <!--@php
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
                    </div>-->
                    </nav>
                    </a>  
                </li>
                 
                @endif

                 @if(Auth::guard('front_auth')->check())

                <li class="has-children">
                    <a class="nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background:none">
                    @if(!empty(Auth::guard('front_auth')->user()->business_name))
                     @if(!empty(Auth::guard("front_auth")->user()->photo))
                    <img style="width: 37px; height: 37px; border-radius: 50%; object-fit: cover; vertical-align: text-top; top: -6px !important; position: relative;" src="{{asset('front/profile/'.Auth::guard('front_auth')->user()->photo)}}" alt="{{!empty(Auth::guard('front_auth')->user()->business_name) ? Auth::guard('front_auth')->user()->business_name :  Auth::guard('front_auth')->user()->first_name}} {{Auth::guard('front_auth')->user()->last_name}}" class="mr-2">
    
                    @else
                    <img style="width: 37px; height: 37px; border-radius: 50%; object-fit: cover; vertical-align: text-top; top: -6px !important; position: relative;" src="{{asset('front/images/details_default.png')}}" alt="{{!empty(Auth::guard('front_auth')->user()->business_name) ? Auth::guard('front_auth')->user()->business_name :  Auth::guard('front_auth')->user()->first_name}} {{Auth::guard('front_auth')->user()->last_name}}" class="mr-2">

                    @endif
                    {{Auth::guard('front_auth')->user()->business_name}}

                    @else
                    @if(!empty(Auth::guard("front_auth")->user()->photo))
                    <img style="width: 37px; height: 37px; border-radius: 50%; object-fit: cover; vertical-align: text-top; top: -6px !important; position: relative;" src="{{asset('front/profile/'.Auth::guard('front_auth')->user()->photo)}}" alt="{{!empty(Auth::guard('front_auth')->user()->business_name) ? Auth::guard('front_auth')->user()->business_name :  Auth::guard('front_auth')->user()->first_name}} {{Auth::guard('front_auth')->user()->last_name}}" class="mr-2">

                    @else
                    <img style="width: 37px; height: 37px; border-radius: 50%; object-fit: cover; vertical-align: text-top; top: -6px !important; position: relative;" src="{{asset('front/trainer/images/profile-img.jpg')}}" alt="{{!empty(Auth::guard('front_auth')->user()->business_name) ? Auth::guard('front_auth')->user()->business_name :  Auth::guard('front_auth')->user()->first_name}} {{Auth::guard('front_auth')->user()->last_name}}" class="mr-2">

                    @endif
                    {{Auth::guard('front_auth')->user()->first_name}} {{Auth::guard('front_auth')->user()->last_name}}

                    @endif
                    </a>
                        <span class="menu-toggle"><i class="fa fa-angle-down"></i></span>
                        @if(Auth::guard('front_auth')->user()->user_role == 'customer') 
                        <ul class="sub-menu">
                            <li>
                             <a class=" {{ (\Request::route()->getName() == 'customer.profile') ? 'active' : '' }}" href="{{route('customer.newprofile',Auth::guard('front_auth')->user()->first_name. '-'.Auth::guard('front_auth')->user()->last_name. '-'.Auth::guard('front_auth')->user()->id)}}">Profile</a>
                            </li>
                            <li>
                             <a class=" {{ (\Request::route()->getName() == 'customer.order.history') ? 'active' : '' }}" href="{{route('customer.order.history')}}">Order History</a> 
                            </li>
                            <li>
                             <a class=" {{ (\Request::route()->getName() == 'customer.ratings.list') ? 'active' : '' }}" href="{{route('customer.ratings.list')}}">Your Reviews</a>
                            </li>
                            <li>
                            <a href="{{route('front.logout')}}" onclick="event.preventDefault();
                                document.getElementById('customer-logout-form').submit();">Logout</a>
                                <form id="customer-logout-form" action="{{route('front.logout')}}" method="POST" style="display: none;">
                                @csrf                        
                                </form>
                            </li>
                        </ul>
                         @endif
                         @if(Auth::guard('front_auth')->user()->user_role == 'trainer') 
                        <ul class="sub-menu">
                            <li>
                            <a class=" {{ (\Request::route()->getName() == 'front.dashboard') ? 'active' : '' }}"  href="{{route('front.dashboard')}}">Dashboard</a>
                            </li>
                            <li>
                            <a class=" {{ (\Request::route()->getName() == 'front.profile') ? 'active' : '' }}"  href="{{url('provider/'.Auth::guard('front_auth')->user()->spot_description)}}">View Profile</a>
                            </li>
                            <li>
                            <a href="{{route('front.logout')}}" onclick="event.preventDefault();
                                document.getElementById('customer-logout-form').submit();">Logout</a>
                                <form id="customer-logout-form" action="{{route('front.logout')}}" method="POST" style="display: none;">
                                @csrf                        
                                </form>
                            </li>
                        </ul>
                        @endif
                    </li>


            @else
                <li class="has-children">
                        <a href="#"><span class="menu-text">Log in / Sign up</span></a>
                        <span class="menu-toggle"><i class="fa fa-angle-down"></i></span>
                        <ul class="sub-menu">
                            <li><a href="{{ url('login') }}">Login</a>
                            </li>
                            <li><a href="{{ url('register') }}">Sign Up
                                </a></li>
                        </ul>
                    </li>
            @endif

                <li class="business_menu">
                    <a class="nav-link" href="{{ route('businessPage')}}">For Providers</a>
                </li>

            </ul>
            </nav>
        </div>
        <div class="account-link dropdown">
         <div class="header-mobile-menu-toggle d-xl-none ml-sm-2">
                <button class="toggle">
                    <i class="icon-top"></i>
                    <i class="icon-middle"></i>
                    <i class="icon-bottom"></i>
                </button>
            </div>
           
        </div>

    </header>
    <div class="clearfix"></div>