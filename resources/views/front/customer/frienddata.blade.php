 

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
            <a href="{{route('customer.newprofile',$userdata->first_name.'-'.$userdata->last_name.'-'.$userdata->id)}}"><div class="user-info d-flex">
                 <img class="user-image"  src="@if(isset($userdata->photo) && !empty($userdata->photo) && file_exists(public_path('front/profile/'.$userdata->photo))) {{ asset('front/profile/'.$userdata->photo)}} @else {{ asset('front/images/details_default.png')}}  @endif"  alt = "image"></a>
                <div class="user-name-btn ml-4"> 
                    
                        <a href="{{route('customer.newprofile',$userdata->first_name.'-'.$userdata->last_name.'-'.$userdata->id)}}"><h4 class="user-name mb-2">  
                            {{$userdata->first_name}} {{$userdata->last_name}}
                        </h4> </a>
                        @if(Auth::guard('front_auth')->user()->id == $user->id)
                        <img onclick="acceptrejectrequestindvidual(<?php echo $friend->id;?>)" style="cursor: pointer" src="https://trainingblockusa.com/public/images/delete-icon.png" alt="Rating">
                        @endif
                    
                </div>
            </div>   
            <!--@if($userdata->city != '' || $userdata->state != '')
            <hr>
            <div class="location-taxt">  
                <p><i class="fas fa-map-marker-alt"></i> {{$userdata->city}} {{$userdata->state}}</p>
            </div> 
            @endif-->
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

