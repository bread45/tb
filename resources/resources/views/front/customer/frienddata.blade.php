 @if(Auth::guard('front_auth')->user()->id == $user->id)
 @if($requestlist->count() > 0)
 <div class="request-list-main ">
    <h4 class="mt-0 mb-4 totalf">Friend Requests ({{$requestlist->count()}})</h4>
    <div class="row row-space-2 request-list mb-4">
        @foreach($requestlist as $friend)
        <div class="col-xl-6 mb-2">  
            <div class="user-info d-flex">
                <img class="user-image " style="height: 90px; width: 90px;" src="@if(isset($friend->user->photo) && !empty($friend->user->photo) && file_exists(public_path('front/profile/'.$friend->user->photo))) {{ asset('front/profile/'.$friend->user->photo)}} @else {{ asset('/front/images/details_default.png')}}  @endif"  alt = "image"> 
                <div class="user-name-btn ml-2"> 
                    @if($user->id == $friend->friend->id)
                    @php $name = $friend->user->first_name.'-'.$friend->user->last_name. '-'.$friend->user->id; @endphp
                    @else
                    @php $name = $friend->friend->first_name.'-'.$friend->friend->last_name. '-'.$friend->friend->id; @endphp
                    @endif
                    <a href="{{route('customer.newprofile',$name)}}"> 
                        <h4 class="user-name">  
                            @if($user->id == $friend->friend->id)
                            {{$friend->user->first_name}} {{$friend->user->last_name}}
                            @else
                            {{$friend->friend->first_name}} {{$friend->friend->last_name}}
                            @endif
                        </h4> 
                    </a>
                    <button class="btn btn-outline-primary" onclick="acceptrejectrequest('{{$friend->id}}', 'accept')">Accept</button>
                    <button class="btn btn-outline-danger" onclick="acceptrejectrequest('{{$friend->id}}', 'reject')">Reject</button>
                </div> 
            </div> 
            @if($friend->user->city != '' || $friend->user->state != '')
            <hr>
            <div class="location-taxt">  
                <p><i class="fas fa-map-marker-alt"></i> {{$friend->user->city}}</p>
            </div> 
            @endif
        </div> 
        @endforeach
    </div>
</div>
@endif
@endif

<div class="friend-list-main">
    <h4 class="mt-0 mb-4 totalf">Friends ({{$friendlist->count()}})</h4>
    @if($friendlist->count() > 0)
    <div class="row row-space-2 friend-list">
        @foreach($friendlist as $friend)
        @if($user->id == $friend->friend->id)
        @php $userdata = $friend->user;  @endphp             
        @else
        @php $userdata = $friend->friend;  @endphp
        @endif
        <div class="col-md-6 mb-4">  
            <div class="user-info d-flex">
                <img class="user-image"  src="@if(isset($userdata->photo) && !empty($userdata->photo) && file_exists(public_path('front/profile/'.$userdata->photo))) {{ asset('front/profile/'.$userdata->photo)}} @else {{ asset('/front/images/details_default.png')}}  @endif"  alt = "image">
                <div class="user-name-btn ml-2"> 
                    <a href="{{route('customer.newprofile',$userdata->first_name.'-'.$userdata->last_name.'-'.$userdata->id)}}"> 
                        <h4 class="user-name ">  
                            {{$userdata->first_name}} {{$userdata->last_name}} (<?php echo DB::table('messages')->where(["from_id" => $userdata->id, "status" => 'unread', "to_id" => $user->id])->count()?>)
                        </h4> 
                    </a>
                    
                </div>
            </div>  
            @if($userdata->city != '' || $userdata->state != '')
            <hr>
            <div class="location-taxt">  
                <p><i class="fas fa-map-marker-alt"></i> {{$userdata->city}} {{$userdata->state}}</p>
            </div> 
            @endif
        </div> 
        @endforeach
    </div>
    @else 
    <div class="col-lg-12 col-md-12 col-12 mb-4">
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        Sorry, We didn't find any data. 
    </div>
</div>
@endif 
</div>

