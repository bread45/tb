@php 
$providerlocations = App\ProviderLocations::with('locations')->where('user_id',Auth::guard("front_auth")->user()->id)->get();
$sessionid = '';
if(Session::get('location_id')){
$sessionid = Session::get('location_id');
}
@endphp

<div class="profile-identity order-1 dropdown">
    <a href="#" class="dropdown-toggle" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background:none">
    @if(!empty(Auth::guard("front_auth")->user()->photo))

    <img src="{{asset('front/profile/'.Auth::guard('front_auth')->user()->photo)}}" alt="profile" class="mr-2">
   
    @else
    <img src="{{asset('front/images/details_default.png')}}" alt="profile" class="mr-2">
    
    @endif
        @if(!empty(Auth::guard("front_auth")->user()->business_name) )
        {{Auth::guard("front_auth")->user()->business_name}}
        @else
        {{Auth::guard("front_auth")->user()->first_name}} {{Auth::guard("front_auth")->user()->last_name}}
        @endif
    </a>
    <div class="dropdown-menu dropdown-menu-right mt-3" aria-labelledby="navbarDropdown">
                    
                    @if(Auth::guard('front_auth')->user()->user_role == 'trainer') 
                    <a class="dropdown-item {{ (\Request::route()->getName() == 'front.dashboard') ? 'active' : '' }}"  href="{{route('front.dashboard')}}">Dashboard</a>
                    
                    <a class="dropdown-item {{ (\Request::route()->getName() == 'front.profile') ? 'active' : '' }}"  href="{{url('provider/'.Auth::guard('front_auth')->user()->spot_description)}}">View Profile</a>
                    {{-- <a class="dropdown-item {{ (\Request::route()->getName() == 'trainer.order.history') ? 'active' : '' }}" href="{{route('trainer.order.history')}}">Order History</a> --}}
                    @endif
                    
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{route('front.logout')}}" onclick="event.preventDefault();
                                document.getElementById('customer-logout-form').submit();">Logout</a>
                    <form id="customer-logout-form" action="{{route('front.logout')}}" method="POST" style="display: none;">
                    @csrf                        
                    </form>
                </div>
</div>