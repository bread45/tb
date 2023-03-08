
@if(isset($recommended) && $recommended->count() > 0)
<div class="row recommended-main">
    @foreach($recommended as $recomme)
    <div class="col-xl-12 mb-2">  
            <div class="user-info d-flex flex-wrap">
                 
                <div class="user-img-sec">
    <div class="user-image" style="background-image: url(@if(isset($recomme->users->photo) && !empty($recomme->users->photo) && file_exists(public_path('front/profile/'.$recomme->users->photo))) {{ asset('front/profile/'.$recomme->users->photo)}} @else {{ asset('/front/images/details_default.png')}}  @endif)"> 
<a href="{{url('provider/'.$recomme->users->spot_description)}}"> </a>
    </div>
</div>
                <div class="user-name-btn ml-2"> 
                    <a href="{{url('provider/'.$recomme->users->spot_description)}}" class="float-left"> 
                        <h4 class="user-name">  
                            @if(!empty($recomme->users->business_name))
                    {{$recomme->users->business_name}}
                    @else
                    {{$recomme->users->first_name}}
                    {{$recomme->users->last_name}} 
                     @endif
                        </h4>
                        @if($recomme->users->city != '' || $recomme->users->state != '') 
            <div class="location-taxt">  
                <p><i class="fas fa-map-marker-alt"></i> {{$recomme->users->city}} {{$recomme->users->state}}</p>
            </div> 
            @endif
            <div class="rating text-left">
                            <ul class="nav">
                                <?php
                                        $rating = $recomme->ratting;
                                        $number_stars = calculate_stars(5, $rating);
                                        $full = $number_stars[0];
                                        $half = $number_stars[1];
                                        $gray = $number_stars[2];
                                    ?> 
                                    @for($i=0; $i<$full;$i++)
                                    <li> <img  src="{{asset('/front/images/star.png')}}" alt="Rating" /> </li>
                                @endfor
                                    @if($half)
                                    <li><img  src="{{asset('images/star-half.png')}}" alt="Rating" /></li>
                                    @endif
                                    @for($i=0;$i<$gray;$i++)
                                    <li><img  src="{{asset('images/star-blank.png')}}" alt="Rating" /></li>
                                @endfor
                            </ul>
                            @if($recomme->ratting_count > 0)
                            <span class="text-left">({{$recomme->ratting_count}} Reviews)</span>
                             @endif
                        </div>
                        
                    </a> 
                    @if(Auth::guard('front_auth')->user()->id == $user->id)
                    <img onclick="removerecommended({{$recomme->users->id}})" class="float-right" style="cursor: pointer" src="{{asset('images/delete-icon.png')}}" alt="Rating" />
            <!--<button class="btn btn-outline-danger profile-btn recommended-remove mt-4" onclick="removerecommended({{$recomme->users->id}})">Remove</button>-->
            @endif
                     
                </div> 
            </div> 
             
            
            
        </div> 
<!--    <div class="col-xl-12 col-sm-12 mb-5 recommended-data">
        <div class="bg-white rounded card  shadow-md py-5 px-4 text-center h-100"><img src="@if(isset($recomme->users->photo) && !empty($recomme->users->photo) && file_exists(public_path('front/profile/'.$recomme->users->photo))) {{ asset('front/profile/'.$recomme->users->photo)}} @else {{ asset('/front/images/details_default.png')}}  @endif" alt=""  style="height: 90px; width: 90px;" class="img-fluid rounded-circle m-auto img-thumbnail shadow-sm">
            <a href="{{url('provider/'.$recomme->users->spot_description)}}" class="card-outer-link mt-3">
                <h5 class="mb-0">
                    @if(!empty($recomme->users->business_name))
                    {{$recomme->users->business_name}}
                    @else
                    {{$recomme->users->first_name}}
                    {{$recomme->users->last_name}}
                    @endif
                </h5>
            </a>

            <span class="small text-uppercase text-muted">{{$recomme->users->city}} {{$recomme->users->state}}</span>

            
        </div>
    </div>-->

    @endforeach
</div>
@else 
<div class="col-lg-12 col-md-12 col-12 mb-4">
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        Sorry, We didn't find any data. 
    </div>
</div>
@endif 
