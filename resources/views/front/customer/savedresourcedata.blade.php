@if($resourcesaved->count() > 0)
<div class="row recommended-main">
<!-- <h2>Saved <span>({{$resourcesaved->count()}})</span></h2> -->
    @foreach($resourcesaved as $saved)

    <?php $resourcedata = DB::table('resource')->where(["id" => $saved->resource_id])->get();
                  foreach($resourcedata as $resource){
                    $spot_description = DB::table('front_users')->where(["id" => $resource->trainer_id])->first();
                  ?> 
    <div class="col-xl-12 mb-2">  
            <div class="user-info d-flex flex-wrap">
                 
                <div class="user-img-sec-saved">
    <div class="user-img-sec-saved">
        <a href="{{ url('/resource-details/')}}/{{ base64_encode($saved->resource_id) }}"> <div class="user-image-saved" style="background-image: url('@if(isset($resource->image_name) && !empty($resource->image_name)) {{ asset('front/images/resource/'.$resource->image_name)}} @else {{ asset('front/images/details_default.png')}}  @endif')"> 
        </div></a>
    </div>
</div>
                <div class="user-name-btn ml-2"> 
                    <a href="{{ url('/resource-details/')}}/{{ base64_encode($saved->resource_id) }}"> <h4 class="user-name">{{$resource->title}}</h4></a>
                    <a href="{{url('provider/'.$spot_description->spot_description)}}"> 
                        <p>{{$resource->name}}
                        </p> 
                    </a> 
                     
                </div>  
            </div> 
        </div> 
         <?php }?> 
    @endforeach
</div>
@else 
    <div class="col-lg-12 col-md-12 col-12 mb-4">
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        Sorry, We didn't find any data. 
    </div>
</div>
@endif 
<style>
.user-img-sec-saved {
    width: 100px;
    height: 100px;
}
.user-image-saved {
    background-size: cover;
    height: 100%;
    width: 100%;
    border-radius: 50%;
}
</style>