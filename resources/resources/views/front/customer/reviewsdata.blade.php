@if($retingdata->count() > 0)
<div class="review-list-main ">
    <h4 class="mt-0 mb-4 totalf">Reviews ({{$retingdata->count()}})</h4>
    <div class="row row-space-2 request-list mb-4">
        @foreach($retingdata as $friend)
        <div class="col-xl-6 mb-2">  
            <div class="user-info d-flex">
                <a href="{{url('provider/'.$friend->trainer->spot_description)}}"><img class="user-image"  src="@if(isset($friend->trainer->photo) && !empty($friend->trainer->photo) && file_exists(public_path('front/profile/'.$friend->trainer->photo))) {{ asset('front/profile/'.$friend->trainer->photo)}} @else {{ asset('/front/images/details_default.png')}}  @endif"></a>
                <div class="user-name-btn ml-2"> 
                    <a href="{{url('provider/'.$friend->trainer->spot_description)}}"> 
                        <h4 class="user-name">  
                             @if(!empty($friend->trainer->business_name))
                    {{$friend->trainer->business_name}}
                    @else
                    {{$friend->trainer->first_name}}
                    {{$friend->trainer->last_name}} 
                     @endif
                        </h4> 
                    </a> 
                    <div class="rating">
                        <ul class="nav">
                            @for($i=1;$i<=$friend->rating;$i++) 
                            <li><img src="{{asset('images/star.png')}}" alt="Rating" /></li>
                            @endfor
                            @for($i=5;$i>=$friend->rating+1;$i--) 
                            <li><img src="{{asset('images/star-blank.png')}}" alt="Rating" /></li>
                            @endfor
                        </ul>
                    </div>
                </div> 
            </div> 
            <div class="retting-taxt"> 
                <h5 class="retting-title">
                    {{$friend->title}} 
                </h5>
                <p> {{$friend->title}} </p>
                <!--<p>good</p>-->

            </div>
            @if($friend->trainer->city != '' || $friend->trainer->state != '')
            <hr>
            <div class="location-taxt">  
                <p><i class="fas fa-map-marker-alt"></i> {{$friend->trainer->city}}</p>
            </div> 
            @endif
        </div> 
        @endforeach
    </div>
</div>
@else 
<div class="col-lg-12 col-md-12 col-12 mb-4">
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        Sorry, We didn't find any data. 
    </div>
</div>
@endif 