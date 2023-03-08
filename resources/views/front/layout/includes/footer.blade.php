<footer class="footer-sec">
        <div class="footer-dotted "><img src="{{ asset('../front/images/new-template/footer-dooted.png') }}"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-md-0 mb-4">

                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-md-0 mb-4 text-md-left text-center">
                    <a href="{{ url('/')}}" class="mb-3 d-inline-block"><img src="{{ asset('images/logo.png') }}"
                            alt="Training Block"></a>
                    <div class="contact-info">
                        <!-- <p><a class="mail-link" href="mailto:php@sgstechie.com">php@sgstechie.com</a></p> -->
                        <p><a class="mail-link"
                                href="mailto:support@trainingblockusa.com">support@trainingblockusa.com</a></p>
                        <a class="call-link text-white" href="tel:{{getSetting('phone-number')}}">{{getSetting('phone-number')}}</a>
                        <p>
                            <!--  <a class="refer-link" href="https://trainingblock.sgssys.info/login">Refer a friend</a>
                                     -->
                        </p>
                    </div>

                </div>
                <div class="col-md-3 mb-md-0 mb-4 text-md-left text-center">
                    <h3>Links</h3>
                      <ul class="nav footer-nav">
                        <li class="nav-item">
                            <a class="nav-link {{ (request()->segment(1) == 'aboutus') ? 'active' : '' }}" href="{{route('aboutus')}}">About</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ (request()->segment(1) == 'exploreservices') ? 'active' : '' }}" href="{{route('exploreservices')}}">Explore</a>
                        </li>
                        <li class="nav-item {{ (request()->segment(1) == 'blogs') ? 'active' : '' }}">
                            <a class="nav-link" href="{{route('blogs')}}">Blog</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ (request()->segment(1) == 'contactus') ? 'active' : '' }}" href="{{route('contactuspage')}}">Contact us</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ (request()->segment(1) == 'login' || request()->segment(1) == 'register') ? 'active' : '' }}" href="{{route('front.login')}}">Login</a>
                        </li>
			            <li class="nav-item">
                            <a class="nav-link {{ (request()->segment(1) == 'terms-conditions') ? 'active' : '' }}" href="{{route('terms.conditions')}}">Terms & Conditions</a>
                        </li>
                    </ul>
                </div>
                <div class="col-md-5 text-md-left text-center">
                    <h3>Newsletter</h3>
                    <div id="subscribe-css">
                        <p class="text-white subcribe-p">Enter your email address to register to our newsletter
                            subscription
                        </p>
                        <div class="subscribe-wrapper">
                            <div class="subscribe-form">
                                <form action='{{route("subcribesPost")}}' class='subscribe-form' method='POST'>
                                    @csrf
                                  <input autocomplete='off' required pattern="[^@\s]+@[^@\s]+\.[^@\s]+" class='subscribe-css-email-field' name='subscribe_email' id='subscribe_email' placeholder='Enter your Email' />
                                  <input class='subscribe-css-email-button' id='subscribe_submit' title='' type='button' value='Subscribe' />
                                </form>
                            </div>
                        </div>
                    </div>

                    <h3>Follow us</h3>

                    <div class="social-links ">

                        <a href="{{getSetting('facebook-link')}}" class="nav-link facebook1"
                            title="Facebook"><i class="fa-brands fa-facebook-f"></i></a>


                        <a href="{{getSetting('linkdin-link')}}" class="nav-link linkedin"
                            title="Linkedin"><i class="fa-brands fa-linkedin-in"></i></a>

                        <!--                        <li class="nav-item">
                            <a href="{{getSetting('twitter-link')}}" class="nav-link twitter" title="Twitter">twitter</a>
                        </li>-->

                        <a href="{{getSetting('insta-link')}}" class="nav-link insta" title="Instagram"><i
                                class="fa-brands fa-instagram"></i></a>

                    </div>


                </div>
            </div>
        </div>

    </footer>
    <div class="footer-copyright">
        <div class="container">
            <p>Copyright © {{date('Y')}} Training Block. All rights reserved.</p>
        </div>
    </div>

    <div id="site-main-mobile-menu" class="site-main-mobile-menu">
        <div class="site-main-mobile-menu-inner">
            <div class="mobile-menu-header">
                <div class="mobile-menu-logo">
                    <a href="{{ url('/')}}"><img src="{{ asset('images/logo.png') }}" width="80" alt=""></a>
                </div>
                <div class="mobile-menu-close">
                    <button class="toggle">
                        <i class="icon-top"></i>
                        <i class="icon-bottom"></i>
                    </button>
                </div>
            </div>
            <div class="mobile-menu-content">
                <nav class="site-mobile-menu">
                    <ul>
                    <li class="has-children position-static nav-item {{  request()->is('explore') ? 'active' : '' }} {{ request()->is('provider/*') ? 'active' : '' }}">
                    <a  href="#" class="nav-link"><span class="menu-text">Explore </span></a>
                    <span class="menu-toggle"><i class="fas fa-angle-down"></i></span>
                            <ul class="mega-menu">
                            <li class="virtualMobileMenu">
                                <a href="{{ url('/explore?virtual_in_both=Virtual Only') }}">Virtual</a>
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
                        </li>
                        <li class="business_menu">
                            <a class="nav-link" href="{{ route('businessPage')}}">For Providers</a>
                        </li>

                    </ul>
                </nav>
            </div>
        </div>
    </div>

<!-- Modal -->
<div class="modal fade" id="referFriendModal" tabindex="-1" role="dialog"  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Refer a Friend</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Refer a friend and get $5 discount on order!</p>
        <div class="input-group">
            @if(Auth::guard('front_auth')->user())
            <input type="text" id="referLinkInput" class="form-control"  readonly="readonly" value="{{ url('/register') . '/?ref=' . Auth::guard('front_auth')->user()->affiliate_id }}">
            @endif
            <div class="tooltip">
                <button class="btn-dark btn-sm" onclick="referFriend()" onmouseout="outFunc()">
                <span class="tooltiptext" id="referTooltip">Copy & Share</span>
                Copy link
                </button>
            </div>
        </div>
        
        <script>
            function referFriend() {
            var copyText = document.getElementById("referLinkInput");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            document.execCommand("copy");
            
            var tooltip = document.getElementById("referTooltip");
            tooltip.innerHTML = "Copied!";
            }

            function outFunc() {
            var tooltip = document.getElementById("referTooltip");
            tooltip.innerHTML = "Copy & Share";
            }
            function linkcount(link, id) {
        if (link != '') {
            $.ajax({
                url: '{{ url("advertisement/changecounter/") }}' + '/' + id,
                type: 'get',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function (data) {
                    var win = window.open(link, '_blank');
//                        win.focus();
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
    }

    
        </script>
      </div>
      <div class="modal-footer">
      @if(Auth::guard('front_auth')->user())
        <?php 
        echo Share::page(url('/register') . '/?ref=' . Auth::guard('front_auth')->user()->affiliate_id)
         ->facebook()
         ->twitter()
         ->linkedin()
        ?>
      @endif
      </div>
    </div>
  </div>
</div>

<style>
#referFriendModal #social-links ul li{
    display:inline-block;
    margin: 0px 10px;
}
#referFriendModal #social-links ul li a .fa::before{
    font-size:25px;
}
.fa-facebook-official{
    color: #3b5998;
}
/*.fa-linkedin{
 color:#0976b4;
} 
.fa-twitter{
 color:#55acee;
}*/
.fa-whatsapp{

}
#referFriendModal .form-control{
    height: auto;
    font-size: 12px;
}
.tooltip {
  position: relative;
  display: inline-block;
  opacity: 1;
}

.tooltip .tooltiptext {
  visibility: hidden;
  width: 140px;
  background-color: #555;
  color: #fff;
  text-align: center;
  border-radius: 6px;
  padding: 5px;
  position: absolute;
  z-index: 1;
  bottom: 150%;
  left: 50%;
  margin-left: -75px;
  opacity: 0;
  transition: opacity 0.3s;
}

.tooltip .tooltiptext::after {
  content: "";
  position: absolute;
  top: 100%;
  left: 50%;
  margin-left: -5px;
  border-width: 5px;
  border-style: solid;
  border-color: #555 transparent transparent transparent;
}

.tooltip:hover .tooltiptext {
  visibility: visible;
  opacity: 1;
}
</style>