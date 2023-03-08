<div class="row recommended-main">
    <h2>Notifications <span>({{$notificationdata->count()}})</span></h2>
    @foreach($notificationdata as $notification) 
     <div class="col-xl-12 mb-2">  
         <div class="user-info d-flex">
            <div class="user-img-sec">
<img class="user-image" src="@if(isset($notification->quotebyfrom->photo) && !empty($notification->quotebyfrom->photo) && file_exists(public_path('front/profile/'.$notification->quotebyfrom->photo))) {{ asset('front/profile/'.$notification->quotebyfrom->photo)}} @else {{ asset('/front/images/details_default.png')}}  @endif"  alt="image">
    
</div>
              <div class="ml-2">
                <h4>{{$notification->title}}</h4>
            <p>{{$notification->message}}</p>
            </div>   
         </div>
    </div> 
    @endforeach
</div>