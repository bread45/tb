<div class="row recommended-main">
<h2>Saved <span>({{$resourcesaved->count()}})</span></h2>
    @foreach($resourcesaved as $saved)

    <?php $resourcedata = DB::table('resource')->where(["id" => $saved->resource_id])->get();
                  foreach($resourcedata as $resource){
                  ?> 
    <div class="col-xl-12 mb-2">  
            <div class="user-info d-flex flex-wrap">
                 
                <div class="user-img-sec-saved">
    <div class="user-img-sec-saved">
        <div class="user-image-saved" style="background-image: url('@if(isset($resource->image_name) && !empty($resource->image_name)) {{ asset('front/images/resource/'.$resource->image_name)}} @else {{ asset('/front/images/details_default.png')}}  @endif')"> 
        </div>
    </div>
</div>
                <div class="user-name-btn ml-2"> 
                    <a href="{{ url('resource-library-search/'.$saved->resource_id)}}"> 
                        <h4 class="user-name">{{$resource->name}}
                        </h4> 
                    </a> 
                     <p>{{$resource->title}}</p>
                </div> 
            </div> 
        </div> 
         <?php }?> 
    @endforeach
</div>

<style>
.user-img-sec-saved {
    width: 100px;
    height: 100px;
}
.user-image-saved {
    background-size: cover;
    height: 100%;
    width: 100%;
}
</style>