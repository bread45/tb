<nav class="sidebar card py-2 mb-4">
    <a class="navbar-brand" href="{{url('/')}}"><img src="{{asset('front/images/logo.png')}}" alt="Training Block" class="side_nav_logo"></a>
    <ul class="nav flex-column" id="nav_accordion">
        <li class="nav-item {{ (\Request::route()->getName() == 'front.dashboard') ? 'active' : '' }}">
            <a class="nav-link firstmnu" href="{{route('front.dashboard')}}">Dashboard</a>
        </li>
        <li class="nav-item {{ (\Request::route()->getName() == 'trainer.notifications' || \Request::route()->getName() == 'trainer.search.notifications') ? 'active' : '' }}">
            <a class="nav-link firstmnu" href="{{route('trainer.notifications')}}">Notifications (<?php echo DB::table('order_request')->where('status', '=', '1')->where('trainer_id', '=', Auth::guard('front_auth')->user()->id)->count();?>)</a>
        </li>
        <li class="nav-item {{ (\Request::route()->getName() == 'front.profile') ? 'active' : '' }}">
            <a class="nav-link firstmnu" href="{{route('front.profile')}}">Edit Profile</a>
        </li>
         <li class="nav-item has-submenu {{ (\Request::route()->getName() == 'services.list' || \Request::route()->getName() == 'service.add' || \Request::route()->getName() == 'service.edit' || \Request::route()->getName() == 'service.detail' || \Request::route()->getName() == 'trainer.scheduling' || \Request::route()->getName() == 'trainer.search.scheduling' || \Request::route()->getName() == 'trainer.month.annual.schedules' || \Request::route()->getName() == 'trainer.search.month.annual.schedules' || \Request::route()->getName() == 'front.coupon') || \Request::route()->getName() =='trainer.events.list' || \Request::route()->getName() =='trainer.events.add' || \Request::route()->getName() =='trainer.events.edit' || \Request::route()->getName() =='trainer.attendees.list' || \Request::route()->getName() =='trainer.attendees.find'  ? 'active' : '' }}">
            <a class="nav-link mainmnu" href="#">Services & Events <i class="fa fa-caret-down"></i> </a>
            <ul class="submenu collapse">
                <li class="nav-item submnu {{ (\Request::route()->getName() == 'services.list' || \Request::route()->getName() == 'service.add' || \Request::route()->getName() == 'service.edit' || \Request::route()->getName() == 'service.detail') ? 'active' : '' }}">
                    <a class="nav-link" href="{{route('services.list')}}">Services</a>
                </li>
                <li class="nav-item submnu {{ (\Request::route()->getName() == 'trainer.scheduling' || \Request::route()->getName() == 'trainer.search.scheduling') ? 'active' : '' }}">
                    <a class="nav-link" href="{{route('trainer.scheduling')}}">Calendar</a>
                </li>
                <li class="nav-item submnu {{ (\Request::route()->getName() == 'trainer.month.annual.schedules' || \Request::route()->getName() == 'trainer.search.month.annual.schedules') ? 'active' : '' }}">
                    <a class="nav-link" href="{{route('trainer.month.annual.schedules')}}">Memberships & Packages</a>
                </li>
                <li class="nav-item submnu {{ (\Request::route()->getName() == 'trainer.events.list' || \Request::route()->getName() == 'trainer.events.add' || \Request::route()->getName() == 'trainer.events.edit') ? 'active' : '' }}">
                    <a class="nav-link" href="{{route('trainer.events.list')}}">Events</a>
                </li>
                <li class="nav-item  submnu {{ (\Request::route()->getName() == 'trainer.attendees.list' || \Request::route()->getName() == 'trainer.attendees.find') ? 'active' : '' }}">
                <a class="nav-link" href="{{route('trainer.attendees.list')}}">Attendees</a>
                </li>
                <li class="nav-item submnu {{ (\Request::route()->getName() == 'front.coupon') ? 'active' : '' }}">
                    <a class="nav-link" href="{{route('front.coupon')}}">Promo Code</a>
                </li>
            </ul>
        </li>
        <?php //if(Auth::guard('front_auth')->user()->is_subscription == 1){?>
        <li class="nav-item {{ (\Request::route()->getName() == 'trainer.account.information') ? 'active' : '' }}">
            <a class="nav-link firstmnu" href="{{route('trainer.account.information')}}">Account Information</a>
        <?php //}?>
        </li><li class="nav-item {{ (\Request::route()->getName() == 'resource.list' || \Request::route()->getName() == 'resource.add' || \Request::route()->getName() == 'resource.edit' || \Request::route()->getName() == 'resource.detail') ? 'active' : '' }}">
            <a class="nav-link firstmnu" href="{{route('resource.list')}}">Resources</a>
        </li>
        <li class="nav-item {{ (\Request::route()->getName() == 'trainer.ratings.list') ? 'active' : '' }}">
            <a class="nav-link firstmnu" href="{{route('trainer.ratings.list')}}">Ratings & Reviews</a>
        </li>
        
    </ul>
</nav>