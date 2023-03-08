<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>

<div class="row" style="padding-bottom: 20px">
    <div class="col-xl-4 col-lg-12 col-md-12 col-12 mobile_view_map">
                    <div class="map-wrapper h-100" id="map2">
     
                        <!--<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3458.2529387289164!2d30.897572214794746!3d-29.91461953221006!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1ef7ab2a34083b9d%3A0xe6daca44282ff8b7!2sOcean%20View%20Ave%2C%20Silverglen%2C%20Chatsworth%2C%204092%2C%20South%20Africa!5e0!3m2!1sen!2sin!4v1585304204739!5m2!1sen!2sin" height="100%" width="100%" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>-->
                    </div>
               </div>
    <div class="col-xl-8 col-lg-12 col-md-12 col-12">

                  <div class="right-section-wrapper">
                       @if(isset($sponsorTrainerServicesdata) && $sponsorTrainerServicesdata->count() > 0)
        <div class="section-title text-left"> 
                    <h2 class="mb-0">Sponsored Results </h2>
                </div>
        @endif  
<div class="row">
    @if(isset($sponsorTrainerServicesdata) && $sponsorTrainerServicesdata->count() > 0)
    
    @foreach($sponsorTrainerServicesdata as $trainerservices)  
        <div class="col-lg-12 col-md-12 col-12 mb-4">
            <a href="{{url('provider/'.$trainerservices->trainer->spot_description)}}" class="card-outer-link">
            <div class="card"> 
                @php
                $TrainerPhoto = \App\TrainerPhoto::where('is_featured',1)->where('trainer_id',$trainerservices->trainer->id)->first();
                @endphp
                @if(!empty($TrainerPhoto))
                    <!--<img src="{{asset('front/images/services/'.$trainerservices->image)}}" alt="{{$trainerservices->name}}" />-->
                    <div class="card-image" style="background-image: url('{{asset('front/profile/'.$TrainerPhoto->image)}}');">
                                <!-- <img src="" alt="card-block" /> -->
                              </div>
                @elseif(!empty($trainerservices->trainer->photo))
                    <!--<img src="{{asset('front/images/services/'.$trainerservices->image)}}" alt="{{$trainerservices->name}}" />-->
                    <div class="card-image" style="background-image: url('{{asset('front/profile/'.$trainerservices->trainer->photo)}}');">
                                <!-- <img src="" alt="card-block" /> -->
                              </div>
                    @else
                    <div class="card-image" style="background-image: url('{{asset('images/Expert_01.jpg')}}');">
                                <!-- <img src="" alt="card-block" /> -->
                              </div>
                    <!--<img src="{{asset('images/Expert_01.jpg')}}" alt="{{$trainerservices->name}}" />-->
                @endif 
                <div class="card-body pb-3">
                    <div class="top-title-rating">
                        <h4 class="text-uppercase mb-2">
                    @if(!empty($trainerservices->trainer->business_name))
                        {{$trainerservices->trainer->business_name}}
                    @else
                       {{$trainerservices->trainer->first_name}}
                       {{$trainerservices->trainer->last_name}}
                    @endif
                    </h4>
                        <!--<div class="rating text-center">
                            <ul class="nav">
                                 <?php
                                        $rating = $trainerservices->ratting;
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
                            @if($trainerservices->ratting_count > 0)
                            <span class="text-center">({{$trainerservices->ratting_count}} Reviews)</span>
                             @endif
                        </div>-->
                    </div>
                    <h5 class="location mb-1">{{$trainerservices->trainer->address_1}}, {{$trainerservices->trainer->city}}</h5>
                    <h5 class="phone mb-2 phone_number{{$trainerservices->trainer->id}}">{{$trainerservices->trainer->phone_number}}</h5>
                     
                    @php
                     $services_name = '';
                    $servicedata = \App\TrainerServices::with('service')->where('trainer_id',$trainerservices->trainer->id)->groupby('service_id')->get();
                    foreach($servicedata as $service){
                       $services_name .= $service->service->name.', '; 
                    }
                    @endphp
                    <h5> <i class="fas fa-dumbbell"></i>
                    {{rtrim($services_name,', ')}}
                    </h5>    
                     
                     <!--<h5 class="status mb-2">{{$trainerservices->format}}</h5>-->
                      @if(!empty($trainerservices->trainer->headline))<p class="">{{ $trainerservices->trainer->headline }} </p>@endif
                         <script>                       
                       $('.phone_number{{$trainerservices->trainer->id}}').html(changeNumberFormat('{{$trainerservices->trainer->phone_number}}'));</script>                  
                  </div>
            
            </div>
        </a>
    </div>       
    @endforeach
    @else 

    @endif 
</div>
</div>
</div>
</div>
<div class="row">
     
    <div class="col-xl-8 col-lg-12 col-md-12 col-12">
        
                  <div class="right-section-wrapper">
                        @if(isset($sponsorTrainerServicesdata) && $sponsorTrainerServicesdata->count() > 0)
        <div class="section-title text-left"> 
                    <h2 class="mb-0">All Results </h2>
                </div>
        @endif 
                    <div class="row">
    @if(isset($TrainerServicesdata) && $TrainerServicesdata->count() > 0)
    @php $p = 1; @endphp
    @foreach($TrainerServicesdata as $trainerservices) 
       <?php $p++; ?>   
        <div class="col-lg-12 col-md-12 col-12 mb-4 {{$TrainerServicesdata->count()}} {{$p}}">
            <a href="{{url('provider/'.$trainerservices->trainer->spot_description)}}" class="card-outer-link">
            <div class="card"> 
                @php
                $TrainerPhoto = \App\TrainerPhoto::where('is_featured',1)->where('trainer_id',$trainerservices->trainer->id)->first();
                @endphp
                @if(!empty($TrainerPhoto))
                    <!--<img src="{{asset('front/images/services/'.$trainerservices->image)}}" alt="{{$trainerservices->name}}" />-->
                    <div class="card-image" style="background-image: url('{{asset('front/profile/'.$TrainerPhoto->image)}}');">
                                <!-- <img src="" alt="card-block" /> -->
                              </div>
                @elseif(!empty($trainerservices->trainer->photo))
                    <!--<img src="{{asset('front/images/services/'.$trainerservices->image)}}" alt="{{$trainerservices->name}}" />-->
                    <div class="card-image" style="background-image: url('{{asset('front/profile/'.$trainerservices->trainer->photo)}}');">
                                <!-- <img src="" alt="card-block" /> -->
                              </div>
                    @else
                    <div class="card-image" style="background-image: url('{{asset('images/Expert_01.jpg')}}');">
                                <!-- <img src="" alt="card-block" /> -->
                              </div>
                    <!--<img src="{{asset('images/Expert_01.jpg')}}" alt="{{$trainerservices->name}}" />-->
                @endif 
                <div class="card-body pb-3">
                    <div class="top-title-rating">
                        <h4 class="text-uppercase mb-2">
                    @if(!empty($trainerservices->trainer->business_name))
                        {{$trainerservices->trainer->business_name}}
                    @else
                       {{$trainerservices->trainer->first_name}}
                       {{$trainerservices->trainer->last_name}}
                    @endif
                    </h4>
                        <div class="rating text-center">
                            <ul class="nav">
                                <?php
                                        $rating = $trainerservices->ratting;
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
                            @if($trainerservices->ratting_count > 0)
                            <span class="text-center">({{$trainerservices->ratting_count}} Reviews)</span>
                             @endif
                        </div>
                    </div>
                    <h5 class="location mb-1">{{$trainerservices->trainer->address_1}}@if($trainerservices->trainer->address_1 != '' && $trainerservices->trainer->city != ''), @endif {{$trainerservices->trainer->city}}</h5>
                    <h5 class="phone mb-2 phone_number{{$trainerservices->trainer->id}}">{{$trainerservices->trainer->phone_number}}</h5>
                      
                   @php
                     $services_name = '';
                    $servicedata = \App\TrainerServices::with('service')->where('trainer_id',$trainerservices->trainer->id)->groupby('service_id')->get();
                    foreach($servicedata as $service){
                       $services_name .= $service->service->name.', '; 
                    }
                    @endphp
                    <h5> <i class="fas fa-dumbbell"></i>
                    {{rtrim($services_name,', ')}}
                    </h5>
                    
                     
                     <!--<h5 class="status mb-2">{{$trainerservices->format}}</h5>-->
                     @if(!empty($trainerservices->trainer->headline))<p class="">{{ $trainerservices->trainer->headline }} </p>@endif
                       <script>                       
                       $('.phone_number{{$trainerservices->trainer->id}}').html(changeNumberFormat('{{$trainerservices->trainer->phone_number}}'));</script>          
                  </div>
            
            </div>
        </a>
    </div>        
    @if($TrainerServicesdata->count()-1  == $p || $TrainerServicesdata->count() == 1)
        <div class="col-lg-12 col-md-12 col-12 mb-4 adv_div_1 ">
            <div class="card"> 
                <?php
                $html = ''; 
                 if(!empty($advertisement)){
                     $url = url('public/sitebucket/advertisement/'.$advertisement->image);
                     $style = 'style="background-image: url('.$url.');cursor: pointer;height: 90px;max-width:100%"';
                     $onclick = 'onclick="linkcount(\''.$advertisement->url.'\', '.$advertisement->id.')"';
                     $html = '<div class="advertisement_image card-image" '.$onclick.' '.$style.' ></div>';
                 }
                 echo $html;
                ?>
    
            </div>
        </div>
        @endif 
   
    @endforeach
    @else 
    <div class="col-lg-12 col-md-12 col-12 mb-4">
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            No Local Providers Found 

        </div>
    </div>

    @endif 
</div>
                      {!! $TrainerServicesdata->links('pagination') !!} 
</div>
</div>
    <div class="col-xl-4 col-lg-12 col-md-12 col-12">
                    <div class="map-wrapper h-100" id="map">
                         
                        <!--<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3458.2529387289164!2d30.897572214794746!3d-29.91461953221006!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1ef7ab2a34083b9d%3A0xe6daca44282ff8b7!2sOcean%20View%20Ave%2C%20Silverglen%2C%20Chatsworth%2C%204092%2C%20South%20Africa!5e0!3m2!1sen!2sin!4v1585304204739!5m2!1sen!2sin" height="100%" width="100%" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>-->
                    </div>
               </div>
</div>

<style>
    i.fas.fa-dumbbell {
    margin-right: 4px;
    font-size: 11px;
    color: #BFBFBF;
}
.training-services-section .card .card-body p {
     min-height: auto; 
    margin: 0px;
}
</style>
<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC85aV3LZQDOR8LFPnH3TuWZ7wf24tOEm4&callback=initMap">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.js"></script>
<script>
    function changeNumberFormat(x){
        $value  = x.replace(/[^0-9\s]/gi, '');
        if($value.length > 10){
            x = $value.substr(0,9);
        }
        if($value.length > 3 && $value.length < 6){
            x = "("+$value.substr(0,3)+") "+$value.substr(3,3);
        }
        if($value.length > 5){
            x = "";
            x = "("+$value.substr(0,3)+") "+$value.substr(3,3)+"-"+$value.substr(6,4);
        }
        return x;
    }
// Initialize and add the map
function initMap() {
  // The location of Uluru
  geocoder = new google.maps.Geocoder();
  var uluru = {lat: 30.332184, lng: -81.655647}
  // The map, centered at Uluru
  
  // The marker, positioned at Uluru
  //var marker = new google.maps.Marker({position: uluru, map: map});
  
  @if(isset($TrainerServicesdata) && $TrainerServicesdata->count() > 0)
          bounds = new google.maps.LatLngBounds();
           if($(window).width() <= 1200){
              map  = new google.maps.Map(
        document.getElementById('map2'), {zoom: 4, center: uluru,mapTypeControl: false,streetViewControl: false});
           
            }else{
        map  = new google.maps.Map(
        document.getElementById('map'), {zoom: 4, center: uluru,mapTypeControl: false,streetViewControl: false});
            }
        
          @foreach($TrainerServicesdata as $trainerservices)
          @php
                $TrainerPhoto = \App\TrainerPhoto::where('is_featured',1)->where('trainer_id',$trainerservices->trainer->id)->first();
                @endphp
          var address = "{{$trainerservices->trainer->address_1}}, {{$trainerservices->trainer->city}}";
          var contentString = '<a href="{{url('provider/'.$trainerservices->trainer->spot_description)}}" class="card-outer-link"><div class="card"> @if(!empty($trainerservices->image))                    <div class="card-image" style="background-image: url({{asset("front/images/services/".$trainerservices->image)}});">                                     </div>                     @elseif(!empty($trainerservices->trainer->photo))                    <div class="card-image" style="background-image: url({{asset("front/profile/".$trainerservices->trainer->photo)}});">                                     </div>                     @else                      <div class="card-image" style="background-image: url({{asset("images/Expert_01.jpg")}});">                                                            </div>               @endif <div class="card-body pb-4" style="min-height:auto;max-height:auto"><div class="top-title-rating">                        <h4 class="text-uppercase mb-2">                    @if(!empty($trainerservices->trainer->business_name))                        {{$trainerservices->trainer->business_name}}                    @else                       {{$trainerservices->trainer->first_name}}                       {{$trainerservices->trainer->last_name}}                    @endif                    </h4>                                          </div><h5 class="location mb-1">{{$trainerservices->trainer->address_1}}, {{$trainerservices->trainer->city}}</h5> <div class="rating">                            <ul class="nav">                                @for($i=1;$i<=$trainerservices->ratting;$i++)                                 <li><img src="{{asset('images/star.png')}}" alt="Rating" /></li>                                @endfor                                @for($i=5;$i>=$trainerservices->ratting+1;$i--)                                 <li><img src="{{asset('images/star-blank.png')}}" alt="Rating" /></li>                                @endfor                            </ul>                        </div> </div></div></a>';        

          codeAddress(address,contentString);
     @endforeach
          @foreach($sponsorTrainerServicesdata as $trainerservices)
          var address = "{{$trainerservices->trainer->address_1}}, {{$trainerservices->trainer->city}}";
          var contentString = '<a href="{{url('provider/'.$trainerservices->trainer->spot_description)}}" class="card-outer-link"><div class="card"> @if(!empty($trainerservices->image))                    <div class="card-image" style="background-image: url({{asset("front/images/services/".$trainerservices->image)}});">                                     </div>                     @elseif(!empty($trainerservices->trainer->photo))                    <div class="card-image" style="background-image: url({{asset("front/profile/".$trainerservices->trainer->photo)}});">                                     </div>                     @else                      <div class="card-image" style="background-image: url({{asset("images/Expert_01.jpg")}});">                                                            </div>               @endif <div class="card-body pb-4" style="min-height:auto;max-height:auto"><div class="top-title-rating">                        <h4 class="text-uppercase mb-2">                    @if(!empty($trainerservices->trainer->business_name))                        {{$trainerservices->trainer->business_name}}                    @else                       {{$trainerservices->trainer->first_name}}                       {{$trainerservices->trainer->last_name}}                    @endif                    </h4>                                          </div><h5 class="location mb-1">{{$trainerservices->trainer->address_1}}, {{$trainerservices->trainer->city}}</h5> <div class="rating">                            <ul class="nav">                                @for($i=1;$i<=$trainerservices->ratting;$i++)                                 <li><img src="{{asset('images/star.png')}}" alt="Rating" /></li>                                @endfor                                @for($i=5;$i>=$trainerservices->ratting+1;$i--)                                 <li><img src="{{asset('images/star-blank.png')}}" alt="Rating" /></li>                                @endfor                            </ul>                        </div> </div></div></a>';        

          codeAddress(address,contentString);
     @endforeach
     
         
           @endif 
    }
    var lastOpenedInfoWindow = '';
function codeAddress(address,contentString) {

    geocoder.geocode({ 'address': address }, function (results, status) { 
        if (status == 'OK') {
            var latLng = {lat: results[0].geometry.location.lat (), lng: results[0].geometry.location.lng ()};
         

  var infowindow = new google.maps.InfoWindow({
    content: contentString
  });
            var marker = new google.maps.Marker({
                position: latLng,
                map: map
            }); 
            bounds.extend(marker.position);
            var zoom = map.getZoom();
            map.setZoom(zoom > 4 ? 4 : zoom);
            map.fitBounds(bounds);
            marker.addListener('click', function() {
                hideAllInfoWindows(map);
    infowindow.open(map, marker);
    lastOpenedInfoWindow = infowindow;
  });
      
        } else {
            console.log('Geocode was not successful for the following reason: ' + status);
        }
    });
}
function hideAllInfoWindows(map) {
      if (lastOpenedInfoWindow) {
            lastOpenedInfoWindow.close();
        }
    }

$( document ).ready(function() {
    $('ul.pagination li.active').addClass('disabled');
    var keyword = $('#keyword').val();
    var location = $('#location').val();
    var services = $('#services').val();
    
   
 var cookieValue = $.cookie("locations");

    if(cookieValue == undefined){

       if (confirm('Use the current location')) {
              
              $('#location').val('Jacksonville, Fl');
              
              $.cookie("locations", "Jacksonville, Fl", { expires: 1 });
            } else {
              $('#location').removeAttr('value');
              
              $.cookie("locations", "empty", { expires: 1 });
              
            }
    } else {
        if(cookieValue == 'empty'){
            $('#location').removeAttr('value');
            
        } else {
            $('#location').val('Jacksonville, Fl');
           
        }
    }
});


</script>
 