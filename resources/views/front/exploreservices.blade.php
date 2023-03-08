@extends('front.layout.app')
@section('title', 'Explore Local Providers')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
<style type="text/css">
    @media screen and (min-width: 1000px) {
        .grid-container{display: grid; grid-gap: 10px; grid-template-columns: 50% 50%; grid-template-areas: 'search-area map-area' 'search-result map-area';}
    }
    @media screen and (max-width: 1000px) {
        .grid-container{display: grid; grid-template-areas: 'search-area'  'map-area' 'search-result';}
    }

    .search-area{ grid-area: search-area;}
    .map-area{ grid-area: map-area;}
    .search-result{ grid-area: search-result;}
    .map-continer{position: fixed;width: 100%;height: 100%;padding: 0;margin: 0;top: 0;}

</style>

<section class="inner-banner-section bg-primary">
    <div class="container">
        <div class="banner-content">
            <h1>Explore Local Providers</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Explore Local Providers</li>
                </ol>
            </nav>
        </div>
    </div>
</section> 
<section class="training-services-section">
    <div class="container-fluid">
        <div class="row col-md-12 grid-container" id="divSearch" style="min-height:100vh">
            <div class="search-area">
            <form class="searchform" method="get" autocomplete="off">
            <div class="row service-filters"> 
               <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
                    <div class="row">
                        @csrf
                        <input type="hidden" name="search_value" id="search_value" value="@if($request->search_value){{$request->search_value}}@endif" />
                        <!-- <input type="hidden" name="lat" value="@if($request->lat){{$request->lat}}@endif"> -->
                        <!-- <input type="hidden" name="lng" value="@if($request->lng){{$request->lng}}@endif"> -->
                        <input type="hidden" id="page" name="page" value="@if($request->page){{$request->page}}@else{{1}}@endif" />
                        <input type="hidden" name="exploreLocation" id="exploreLocation" value="@if($request->location){{$request->location}}@endif" disabled />

                        <input type="hidden" name="dragMap" id="dragMap" value="0" disabled />
                        
                        <div class="col-lg-6 col-sm-6">
                            <div class="form-group search-group">
                                <input type="text" name="keyword" id="keyword" class="form-control search-control" placeholder="Search by Keyword" value="@if($request->keyword){{$request->keyword}}@endif" />
                            </div>
                            <div style="display: none;" id="filter-error" class="error invalid-feedback">Please select any filter</div>
                        </div>
                        <div class="col-lg-6 col-sm-6">
                            <div class="form-group training_mode">
                                <select name="virtual_in_both" id="virtual_in_both" class="form-control">
                                    <option value="Both" <?php if($request->virtual_in_both == 'Both') echo 'selected' ?>>Both</option>
                                    <option value="In Person" <?php if($request->virtual_in_both == 'In Person') echo 'selected' ?>>In Person</option>
                                    <option value="Virtual Only" <?php if($request->virtual_in_both == 'Virtual Only') echo 'selected' ?>>Virtual Only</option>
                                </select>
                            </div>
                            
                        </div>
                        <div class="col-lg-6 col-sm-6">
                            <div class="form-group location-group">
                                <input type="text"  name='location' id="location" autocomplete="off" class="form-control location-control" placeholder="Search by Location" value="@if($request->location){{$request->location}} @endif" oninput="firstletterwhitespace(this)" />
                                <div id="locationlist"></div>  
                            </div>
                        </div>
                        
                        <div class="col-lg-6 col-sm-6">
                            <div class="form-group service-group">
                                <select name="services" id="services" class="form-control service-control">
                                    <option value="">Category</option>
                                    @foreach($services as $Trainer)
                                    <option @if($Trainer->id == $request->services)selected @endif value="{{$Trainer->id}}">{{$Trainer->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                       
                         <div class="col-lg-6 col-sm-6 search-miles" style="display:none;">
                            <div class="form-group search-group">
                                <!-- <input type="text" name="miles" id="miles" class="form-control search-control" placeholder="Search by miles" value="@if($request->miles){{$request->miles}}@endif" maxlength="5" /> -->
                                <select name="miles" id="miles" class="form-control search-control">
                                    <option value="10"  <?php if($request->miles == 10 || $request->miles ==  ''){ echo "selected"; } ?>>10 Miles</option>
                                    <option value="20"  <?php if($request->miles == 20){ echo "selected"; } ?>>20 Miles</option>
                                    <option value="50"  <?php if($request->miles == 50){ echo "selected"; } ?>>50 Miles</option>
                                    <option value="100"  <?php if($request->miles == 100){ echo "selected"; } ?>>100 Miles</option>
                                    <option value="200"  <?php if($request->miles == 200){ echo "selected"; } ?>>200 Miles</option>
                                </select>
                            </div>
                        </div> 
                        <div class="col-lg-6 col-sm-6">
                        <!-- <button type="button" class="btn btn-danger" id="update_location" style="background: #00ab91;border-color: #00ab91;" ><img src="{{ asset('images/precision.png') }}" style="width: auto;height: auto" class="lazyload" alt="search" /></button>     -->
                        <button type="button" class="btn " id="update_location" >Locate Me</button>
                        </div>
                        <div class="col-lg-12 d-flex justify-content-center mb-1">
                        <input type="submit" name="btnSearch" id="btnSearch" value="Search" class="btn btn-danger btn-lg" />
                        </div>
                        <div class="col-lg-12 d-flex justify-content-center mb-1">
                            <a href="{{ route('exploreservices') }}" class="reset_link" style="line-height: 46px;">Reset</a>
                        </div>
                    </div>
                </div>
            </div>
</form>

            </div>
            <div class="map-area">
                <div class="map-continer map-wrapper">
                <div class="map_overlay" style="display: none;"></div>
                    <div class="map_virtual_info" style="display: none;"><p>Map not in function for Virtual Only services</p></div>
                    <div class="move_map"> <input type="checkbox" class="controls check_box_style_map" name="chkCanDrag" id="chkCanDrag"><span>Search as I move the map</span></div>
                    <div class="zoom_map"><div style="background-color: white; border-color: gray; border-width: 1px; cursor: pointer; text-align: center; width: 45px; height: 70px;"><div class="zoom-plus" style="width: 40px; height: 36px; background-image: url('https://trainingblock.sgssys.info/public/front/images/map-plus.jpg');"></div><div style="position: relative; overflow: hidden; width: 30px; height: 1px; margin: 0px 5px; background-color: rgb(230, 230, 230); top: 0px;"></div><div class="zoom-minus" style="width: 40px; height: 36px; background-image: url('https://trainingblock.sgssys.info/public/front/images/map-minus.jpg');"></div></div></div>
                    <div id="map-continer" class="col-xl-6 col-lg-12 col-md-12 col-12 postion-relative" style="position: relative;overflow: hidden;height: 100vh;"></div>
                </div>
            </div>
            <div class="search-result right-section-wrapper">
                <div class="search-result-head"></div>
                <div class="search-result-list"></div>
                <div class="search-result-pagination"></div>
            </div>
        </div>
    </div>
</section>
@stop
@section('pagescript')
<style type="text/css">
    .trainerBio {
  overflow: hidden;
  text-overflow: ellipsis;
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
}
div#locationlist {
  position: absolute;
  top: 69px;
  width: 100%;
  z-index: 99;
}
.list-group-item {
  cursor: pointer;
  color: #000;
  background-color: #ccc;
}
.btn-danger:not(:disabled):not(.disabled).active:focus,
.btn-danger:not(:disabled):not(.disabled):active:focus,
.show > .btn-danger.dropdown-toggle:focus {
  box-shadow: 0 0 0 0.2rem rgb(0 171 145) !important;
}
.btn-danger,
.btn-danger:not(:disabled):not(.disabled).active,
.btn-danger:not(:disabled):not(.disabled):active,
.show > .btn-danger.dropdown-toggle {
  color: #fff;
  background-color: #00ab91 !important;
  border-color: #00ab91 !important;
}
.map_virtual_info {
  z-index: 3;
  background: #fff !important;
  box-shadow: rgb(0 0 0 / 12%) 0 6px 16px !important;
  border-radius: 8px !important;
}
.map_virtual_info {
  position: fixed;
  height: 34px;
  top: 50%;
  right: 1px;
  text-align: center;
  left: 64%;
  width: 346px;
  padding: 2px 7px;
}

.map_overlay {
  background: #2e2e2e78;
  position: fixed !important;
  width: 100%;
  top: 0;
  max-width: 50.333333%;
  bottom: 0;
  z-index: 1;
}
@media only screen and (max-width: 989px) {
  .map_overlay {
    background: #2e2e2e78;
    z-index: 1;
    position: absolute !important;
    width: 100%;
    top: 0;
    max-width: 100%;
    right: 0;
    bottom: 0;
  }
}
.move_map,
.move_map2 {
  height: 45px;
  z-index: 3;
  padding: 8px 12px;
  box-shadow: rgb(0 0 0 / 12%) 0 6px 16px !important;
  border-radius: 8px !important;
  background: #fff !important;
}
i.fas.fa-dumbbell {
  margin-right: 4px;
  font-size: 11px;
  color: #bfbfbf;
}
.postion-relative {
  position: relative;
}
.zoom_map {
  position: fixed;
  right: 0;
  top: 180px;
  z-index: 9;
}
.move_map {
  position: fixed;
  left: 68%;
  top: 180px;
}
.move_map2 {
  position: absolute;
  left: 40%;
  top: 35px;
}
.check_box_style_map {
  padding: 10px !important;
  margin-right: 6px;
  display: inline-block !important;
  border: 1px solid #b0b0b0 !important;
  height: 24px !important;
  width: 24px !important;
  background: #fff !important;
  text-align: center !important;
  overflow: hidden !important;
  vertical-align: top !important;
  border-radius: 14px !important;
  margin-top: 3px;
}
.training-services-section .card .card-body p {
  min-height: auto;
  margin: 0;
}
#map-input {
  margin-top: 130px;
  margin-left: 200px;
  padding: 5px;
}
#map-input2 {
  padding: 5px;
  margin-top: 5px;
}
.gmnoprint {
  display: none;
}
@media only screen and (max-width: 989px) {
.map_virtual_info {
    position: fixed;
    height: 34px;
    top: 44%;
    right: 1px;
    text-align: center;
    left: 64%;
    width: 300px;
    padding: 2px 7px;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%,-50%);
    font-size: 13px;
}
}

 </style>

<!-- <script src='https://maps.googleapis.com/maps/api/js?key=AIzaSyD0sBm7n3sKRiVvtBekP81GCR4r0cjmSDQ'></script> -->
 <!-- Load Leaflet from CDN -->
 <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
    integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
    crossorigin=""/>
  <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
    integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
    crossorigin=""></script>

  <!-- Load Esri Leaflet from CDN -->
  <script src="https://unpkg.com/esri-leaflet@3.0.7/dist/esri-leaflet.js"
    integrity="sha512-ciMHuVIB6ijbjTyEdmy1lfLtBwt0tEHZGhKVXDzW7v7hXOe+Fo3UA1zfydjCLZ0/vLacHkwSARXB5DmtNaoL/g=="
    crossorigin=""></script>

  <!-- Load Esri Leaflet Geocoder from CDN -->
  <link rel="stylesheet" href="https://unpkg.com/esri-leaflet-geocoder@3.1.2/dist/esri-leaflet-geocoder.css"
    integrity="sha512-IM3Hs+feyi40yZhDH6kV8vQMg4Fh20s9OzInIIAc4nx7aMYMfo+IenRUekoYsHZqGkREUgx0VvlEsgm7nCDW9g=="
    crossorigin="">
  <script src="https://unpkg.com/esri-leaflet-geocoder@3.1.2/dist/esri-leaflet-geocoder.js"
    integrity="sha512-8bfbGLq2FUlH5HesCEDH9UiuUCnBq0A84yYv+LkUNPk/C2z81PsX2Q/U2Lg6l/QRuKiT3y2De2fy9ZPLqjMVxQ=="
    crossorigin=""></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.js"></script>
<script>
    var objGeocoder = {};
    var objCurrentInfowindow={"close":function(){}};
    var aryBounds ={};
    var aryMarkers=[];
    var resultSet_Length=0;
    var max_rating = 5;
    $('#chkCanDrag').prop('checked', false);
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
    function firstletterwhitespace(input){
      if(/^\s/.test(input.value))
        input.value = '';
    }
    $(document).on('click', 'body', function(){  $('#locationlist').fadeOut(); });
    
    $(document).on('click', 'li.list-group-item', function(){  
        
        $('#location').val($(this).text());  
        $('#locationlist').fadeOut();  
        if($('#location').val($(this).text())!=''){$('.search-miles').show();}
    });


    $(document).on('click', '.pagination a', function(e){  
        var uri = window.location.toString();
            // if (uri.indexOf("?") > 0) {
            //     var clean_uri = uri.substring(0, uri.indexOf("?"));
            //     window.history.replaceState({}, document.title, clean_uri);
            // }
        e.preventDefault();
        $('body').removeClass('loaded');
        var url = $(this).attr('href');
        // getSearchData(url);
        var page= $(this).attr('href').split('page=')[1];
        var pageNumber= page.split('&')[0];
        var pageNo= $(this).text();
        // $('#page').val(pageNo);
        if(pageNo == 'Next'){
            $('#page').val(pageNumber);
        }
        if(pageNo == 'Previous'){
            $('#page').val(pageNumber);
        }
        if(typeof(page) != 'undefined' && page != ''){
            window.history.replaceState({}, document.title, url);
        }
        getSearchDataPagination(url,page);
    });
$('#miles').keypress(function (e) {    
    
    var charCode = (e.which) ? e.which : event.keyCode    
    if (String.fromCharCode(charCode).match(/[^0-9]/g)){return false;}

}); 
$("#miles").keyup(function(){
    var value = $(this).val();
    value = value.replace(/^(0*)/,"");
    $(this).val(value);
});
    $(document).ready(function () {
        if($('#location').val()!=''){
            $('.search-miles').show();
        }
        /*Page load*/
        $('ul.pagination li.active').addClass('disabled');

        if(!!window.performance && window.performance.navigation.type === 2){
            console.log('Reloading');
            window.location.reload();
        }

        //To bind location list
        $('#location').bind("keyup", function(){ 
           var query = $(this).val();  
           if(query.length)  
           {  
            $('.search-miles').show();
                $.ajax({  
                        url:"{{route('get.locationdata')}}",  
                        method:"POST",  
                        data:{search:query,"_token": "{{ csrf_token() }}"},  
                        success:function(data)  
                        {  
                            $('#locationlist').fadeIn();  
                            $('#locationlist').html(data); 
                        },
                            error: function (request, status, error) {
                            console.log(request. responseText);
                        }
  
                });  
           }  else {
            $('.search-miles').hide();
            $('#miles').val('');
            $('#miles')[0].selectedIndex = 0;
           }  
        });  

        $('#btnSearch').click(function (e) {
            var uri = window.location.toString();
            // if (uri.indexOf("?") > 0) {
            //     var clean_uri = uri.substring(0, uri.indexOf("?"));
            //     window.history.replaceState({}, document.title, clean_uri);
            // }
            //e.preventDefault(); //**** to prevent normal form submission and page reload
            var keyword = $('#keyword').val();
            var location = $('#location').val();
            var services = $('#services').val();
            var format = $('#format').val();
            
            $('#search_value').val(12);
            if(keyword == '' && location == '' && services == '' && format == ''){
                new PNotify({
                    title: 'Please select any one filter!.',
                            text: '',
                            type: 'Notice'
                    });
                return false;
            }
            $('#filter-error').hide();
            $('#page').val(1);
            $('body').removeClass('loaded');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            // getSearchData('{{route('exploreservices')}}?search=true');

        });


        $('.clearbnt').click(function (e) {

            e.preventDefault(); //**** to prevent normal form submission and page reload
            $('#keyword').val('');
            $('#location').val('');
            $('#services').val('');
            $('#format').val('');
            $('body').removeClass('loaded');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            getSearchData('{{route('exploreservices')}}?search=true');
        });
    getSearchData('{{route('exploreservices')}}?search=true');
});
function getSearchDataPagination(url){
    $.ajax({
        type: 'GET',
        url: url,
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        success: function (result) {
            setResult(JSON.parse(result));
            $('body').addClass('loaded');
            $(window).scrollTop(0);
        },
        error: function (xhr, ajaxOptions, thrownError) {}
    });
}
function getSearchData(url){
    $.ajax({
        type: 'GET',
        url: url,
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        data: $('.searchform :input').serialize(),
        success: function (result) {
            setResult(JSON.parse(result));
            $('body').addClass('loaded');
            $(window).scrollTop(0);
        },
        error: function (xhr, ajaxOptions, thrownError) {}
    });
}
function setResult(resultSet){
    window['resultSet'] = resultSet;
    var $search_result = ''
    resultSet_Length = resultSet.data.length;
    ClearallMarkers();
    var locationValue = $('#location').val();
    var categoryValue = $("#services option:selected").text();
    var categoryVal = $("#services").val();
    var categoryKeywords = {
        "15" : "Biomechanical and fitness testing",
        "1" : "chiropractor",
        "18" : "cycling coaching",
        "3" : "sports coach",
        "2" : "running coach",
        "17" : "triathlon coach",
        "14" : "running club",
        "7" : "massage therapist",
        "5" : "holistic doctor",
        "8" : "nutrition services",
        "9" : "gait analysis",
        "11" : "physical therapy services",
        "16" : "psychology services",
        "12" : "Recovery Tools",
        "13" : "strength training"
    };
    $search_result_head = `<h1 style="font-size: 1.4rem;padding-left: 20px;line-height: 34px;padding-bottom: 20px;">Best <span style="font-weight: 600;">`+categoryKeywords[categoryVal]+`</span> in <span style="font-weight: 600;">`+locationValue+`</span></h1>`;
    resultSet.data.forEach((curResult, idx)=>{
        strFullAddress = (curResult.address_1 ||"") + ', ' + (curResult.city ||"")+ ', ' + (curResult.state_code ||"");
        strFullAddress = strFullAddress.replace(/(, )+/g, ', ').replace(/(^, )|(, $)/g,'');
        if(curResult.address1_virtual == 1){
            providerAddress = strFullAddress+' (Virtual Only)';
        }
        else {
            providerAddress = strFullAddress;
        }
        objStars = calculate_stars(curResult.ratting);
        strStars ="";
		  var serviceName;
        if(curResult.service_name){
        serviceName = (curResult.service_name.split(',').join(', ')).trim();
        }
        for (let idx = 0; idx < objStars.full_stars; idx++) { strStars += '<li><img src="/public/images/star.png" alt="Rating"></li>';}
        if(objStars.half_stars){ strStars += '<li><img src="/public/images/star-half.png" alt="Rating"></li>';}
        for (let idx = 0; idx < objStars.gray_stars; idx++) { strStars += '<li><img src="/public/images/star-blank.png" alt="Rating"></li>';}
        var strResultContent= `<div class="col-lg-12 col-md-12 col-12 mb-4 10 2">
        <a href="/provider/${curResult.spot_description || ""}" class="card-outer-link">
                <div class="card"> 
                    <div class="card-image" style="background-image: url('/public/front/profile/${curResult.photo || "events_default.jpg"}');"></div>

                    <div class="card-body pb-3">
                        <div class="top-title-rating">
                        <h2 style="font-size:16px;font-weight:700;max-width: 227px;" class="text-uppercase mb-2">${curResult.business_name || ""}</h2>
                            <div class="rating text-center">
                                <ul class="nav">${strStars}</ul>
                                <span class="text-center">(${curResult.ratting_count || "0"} Reviews)</span>
                            </div>
                        </div>
                        <h3 style="font-size:16px;font-weight:600;" class="location mb-1">${providerAddress}</h3>
                        <h3 style="font-size:16px;font-weight:600;" class="phone mb-2 phone_number1551">${curResult.phone_number || ""}</h3>
                            <h3 style="font-size:16px;font-weight:600;"> <i class="fas fa-dumbbell"></i>
                            ${serviceName || ""}
                            </h3>  
                        <p class="">${curResult.headline || ""}</p>
                    </div>
                </div>
            </a>
        </div>`;
        var strInfoContent= `<div>
        <a href="/provider/${curResult.spot_description || ""}" class="card-outer-link">
                <div class="card">
                    <div class="card-image" style="background-image: url('/public/front/profile/${curResult.photo || "events_default.jpg"}');"></div> 
                    <div class="card-body pb-4" style="min-height:auto;max-height:auto">
                        <div class="top-title-rating">
                            <h4 class="text-uppercase mb-2">${curResult.business_name}</h4>
                        </div>
                        <h5 class="location mb-1">${providerAddress}</h5> 
                        <div class="rating"> 
                            <ul class="nav">${strStars}</ul>
                        </div> 
                    </div>
                </div>
            </a>
        </div>`;

        $search_result += strResultContent;
        strFullAddress = curResult.business_name + ', '  +strFullAddress+ ', US';
        var isVirtual = $('#virtual_in_both').val();
        if(isVirtual != 'Virtual Only'){
        if(curResult.address1_virtual != 1){
        AddMarker(strFullAddress, strInfoContent, idx,  resultSet.data.length);
        }
        }
    });
    var mapSeachParam = '';
    var currentPageURL = resultSet.first_page_url;
    var numberURLArray = currentPageURL.substring(0, currentPageURL.lastIndexOf("&") + 1);
    if($('#chkCanDrag:checked').length){mapSeachParam= '&lat='+objMap.getCenter().lat ()+'&lng='+objMap.getCenter().lng ()+'&mapzoom='+objMap.getZoom()+'&radius='+getRadius();}

    var strPagination = `
        <div class="row"><div class="col-12">
        <nav aria-label="Page navigation"><ul class="pagination justify-content-center">
            <li class="page-item item-prev"><a class="page-link prev-link" href="${resultSet.prev_page_url}${mapSeachParam}" tabinde="-1" aria-disabled="true"> Previous</a></li>`;
     for (let idx = 1; idx <= resultSet.last_page; idx++) {
       strPagination += `<li class="page-item item-${idx}"><a class="page-link" href="${numberURLArray}page=${idx}${mapSeachParam}">${idx}</a></li>`;
     }
    strPagination +=`            <li class="page-item item-next"><span><a class="page-link next-link" href="${resultSet.next_page_url}${mapSeachParam}">Next</a></span></li>
        </ul></nav>
        </div></div>
    `;
    strPagination = $(strPagination);
    $('.item-'+ resultSet.current_page, strPagination).replaceWith('<li class="page-item item-'+resultSet.current_page+' active disabled"><a class="page-link">'+resultSet.current_page+'</a></li>');
    if(!resultSet.prev_page_url) {$('.item-prev', strPagination).addClass('disabled');}
    if(!resultSet.next_page_url) {$('.item-next', strPagination).addClass('disabled');}

    if(resultSet_Length < 1){
        $('.search-result-list').html('<div class="col-lg-12 col-md-12 col-12 mb-4"><div class="alert alert-info alert-dismissible fade show" role="alert">No local providers found</div></div>');
        $('.search-result-head').html('');
        $('.search-result-pagination').html('');
    }
    else {
        if(locationValue != '' && categoryValue != '' && categoryValue != 'Category'){
            $('.search-result-head').html($search_result_head);
        }    
        $('.search-result-list').html($search_result);
        $('.search-result-pagination').html(strPagination);
    }
}
function AddMarker(strAddress, strInfoContent ,$idx, len){
    var objInfowindow = new google.maps.InfoWindow({"content": strInfoContent});
    getCoords(strAddress).then((geoResult)=>{ 
        latlng = geoResult.coords[0].geometry.location;
        objMarker = new google.maps.Marker({position: latlng, map: objMap});
        aryMarkers.push(objMarker);
        objMarker.addListener('click', function() {
            objCurrentInfowindow.close();
            objCurrentInfowindow = objInfowindow;
            objInfowindow.open(objMap, this);
        });
        aryBounds.extend(objMarker.getPosition());
        $idx++;
        // if($('#chkCanDrag:checked').length ==0 && $idx == resultSet_Length){
            if($('#chkCanDrag:checked').length ==0){
            setTimeout(function(){
            objMap.fitBounds(aryBounds);
            zoomChanged = objMap.getZoom();
            if(typeof(zoomChanged) != 'undefined' && zoomChanged > 10){
            objMap.setZoom(10);
            }
        },100);
        }
    });
}
function calculate_stars(rating){
    full_stars= Math.floor(rating);
    half_stars = Math.ceil(rating-full_stars);
    gray_stars = max_rating-(full_stars + half_stars);
    return {"full_stars" : full_stars, "half_stars" : half_stars, "gray_stars" : gray_stars};
}

function getCoords(strAddress) {
      return new Promise(function (resolve, reject) {
            fetch("https://maps.googleapis.com/maps/api/geocode/json?address="+ encodeURIComponent(strAddress) +"&key=AIzaSyD0sBm7n3sKRiVvtBekP81GCR4r0cjmSDQ").then(x=>x.json()).then(function(objResult){
                resolve({ address: strAddress, coords: objResult.results })
            });

      });
   };


function ClearallMarkers(){while(aryMarkers.length >0){aryMarkers.pop().setMap(null);};}
</script>
<script type="text/javascript">
    function initMap(){
        objMap  = new google.maps.Map(document.getElementById('map-continer'), {zoom: 10, center:new google.maps.LatLng(40.016869, -105.279617), mapTypeControl: false,streetViewControl: false});
        google.maps.event.addListener(objMap, "dragend", function (e) {
            if($('#chkCanDrag:checked').length ==0 ){return;}
            $('#miles').val('');
            $('#miles')[0].selectedIndex = 0;
            $('#location').val('');
            var uri = window.location.toString();
            var splitUri = uri.split('&');
            var foundArray = splitUri.find(a =>a.includes("page"));
            var arrayIndex = splitUri.indexOf(foundArray);
            splitUri[arrayIndex] = 'page=1';
            uri = splitUri.join('&');
            if(typeof(page) != 'undefined' && page != ''){
            window.history.replaceState({}, document.title, uri);
            }
            $('#page').val(1);
            $.ajaxSetup({headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}});
            getSearchData('{{route('exploreservices')}}?lat='+objMap.getCenter().lat ()+'&lng='+objMap.getCenter().lng ()+'&mapzoom='+objMap.getZoom()+'&radius='+getRadius());
            $('.search-miles').css('display', 'none');
        });
        objGeocoder = new google.maps.Geocoder();
        aryBounds = new google.maps.LatLngBounds();
    }
    function getRadius(){
        var bounds = objMap.getBounds();
        var center = bounds.getCenter();
        var ne = bounds.getNorthEast();

        // r = radius of the earth in statute miles
        var r = 3363.0;  

        // Convert lat or lng from decimal degrees into radians (divide by 57.2958)
        var lat1 = center.lat() / 57.2958; 
        var lon1 = center.lng() / 57.2958;
        var lat2 = ne.lat() / 57.2958;
        var lon2 = ne.lng() / 57.2958;

        // distance = circle radius from center to Northeast corner of bounds
        var dis = r * Math.acos(Math.sin(lat1) * Math.sin(lat2) + 
          Math.cos(lat1) * Math.cos(lat2) * Math.cos(lon2 - lon1));
        // dis = dis-5;
        return dis;

    }

    // old scripts from sys
$( document ).ready(function() {
    $('ul.pagination li.active').addClass('disabled');
    var keyword = $('#keyword').val();
    var location = $('#location').val();
    var services = $('#services').val();
    $('.zoom-minus, .zoom-plus').click(function(event){
        var currZoom = objMap.getZoom();
        if($(event.currentTarget).hasClass('zoom-plus')){
            currZoom++;
        }else{
            currZoom--;
        }
        $('#page').val(1);
        objMap.setZoom(currZoom);
        
        if($('#chkCanDrag:checked').length ==0 ){return;}
        $('#miles').val('');
        $('#location').val('');
        $('#miles')[0].selectedIndex = 0;
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}});
        getSearchData('{{route('exploreservices')}}?lat='+objMap.getCenter().lat ()+'&lng='+objMap.getCenter().lng ()+'&mapzoom='+objMap.getZoom()+'&radius='+getRadius());
        $('.search-miles').css('display', 'none');
        
    });
    if($('#virtual_in_both').val() == 'Virtual Only'){
        //  $('#chkCanDrag').attr('disabled', 'disabled');
        $('.move_map').css('display', 'none'); 
         $('#chkCanDrag').prop('checked', false);
        //  $('#chkCanDrag2').attr('disabled', 'disabled');
        //  $('#chkCanDrag2').prop('checked', false);
         $('.map_overlay').css('display', 'block');
         $('.map-wrapper').css('z-index', '-1');
         
        $('.map_virtual_info').css('display', 'block');
        $('.move_map1').css('display', 'none');

    }
    else 
    { 
        $('.move_map').css('display', 'block'); 
        // $('#chkCanDrag2').removeAttr('disabled'); 
        $('.map_overlay').css('display', 'none');
        $('.map-wrapper').css('z-index', '0');

        $('.map_virtual_info').css('display', 'none');
        $('.move_map1').css('display', 'block');
    }
 
});

$('#chkCanDrag').on('change', function(){
        if($(this).is(':checked')){
            $('#location').val('');
        }
    });
    // $('#chkCanDrag2').on('change', function(){
    //     if($(this).is(':checked')){
    //         $('#location').val('');
    //     }
    // });


$('select#virtual_in_both').on('change', function(){ 
    if($('#virtual_in_both').val() == 'Virtual Only'){
        //  $('#chkCanDrag').attr('disabled', '');
         $('.move_map').css('display', 'none'); 
        //  $('#chkCanDrag').prop('checked', false);
        //  $('#chkCanDrag2').attr('disabled', '');
        //  $('#chkCanDrag2').prop('checked', false);
         $('.map_overlay').css('display', 'block');
        $('.map-wrapper').css('z-index', '-1'); 

        $('.map_virtual_info').css('display', 'block');
        $('.move_map1').css('display', 'none');
    } 
    else 
    { 
        // $('#chkCanDrag').removeAttr('disabled');
        $('.move_map').css('display', 'block'); 
        // $('#chkCanDrag2').removeAttr('disabled');
        $('.map_overlay').css('display', 'none');
        $('.map-wrapper').css('z-index', '0'); 

        $('.map_virtual_info').css('display', 'none');
        $('.move_map1').css('display', 'block');

    }
});
 

//    get current location
 $( document ).ready(function() {
  /*  $('ul.pagination li.active').addClass('disabled');*/
    var keyword = $('#keyword').val();
    var location = $('#location').val();
    var services = $('#services').val();
    
    var cookieValue = $.cookie("locations");
    $('#update_location').on('click', function() {

    $.removeCookie("locations");
    cookieValue = $.cookie("locations");
    console.log(cookieValue);
    if (cookieValue == undefined) {

        if (confirm('Use the current location')) {


            if (navigator.geolocation) {
                var locationAddress = "";
                navigator.geolocation.getCurrentPosition(zoomToLocation, locationError);
            } else {
                alert("Browser doesn't support Geolocation. Visit http://caniuse.com to see browser support for the Geolocation API.");
            }

            function locationError(error) {
                //error occurred so stop watchPosition
                if (navigator.geolocation) {
                    navigator.geolocation.clearWatch(watchId);
                }
                switch (error.code) {
                    case error.PERMISSION_DENIED:
                        console.log("Location not provided");
                        break;

                    case error.POSITION_UNAVAILABLE:
                        console.log("Current location not available");
                        break;

                    case error.TIMEOUT:
                        console.log("Timeout");
                        break;

                    default:
                        console.log("unknown error");
                        break;
                }
            }


            var geocodeService = L.esri.Geocoding.geocodeService({
                apikey: "AAPK1468e59aa8d84c5192603f17da8fd233I2bNwgowIbomirjinrZpX_WkqkHFW7RroqgqRM-FDCoOu07ZxZAFSN3sN0SdzFsb"
            });

            function zoomToLocation(location) {
                var latlng = {
                    lat: location.coords.latitude,
                    lng: location.coords.longitude
                };
                geocodeService.reverse().latlng(latlng).run(function(error, result) {
                    if (error) {
                        return;
                    }
                    var locationAddress = result.address.City + ', ' + result.address.RegionAbbr;
                    var locationLat = result.latlng.lat;
                    var locationLng = result.latlng.lng;

                    if (locationAddress) {
                        $.cookie("locations", locationAddress);
                        $.cookie("locationsLat", locationLat);
                        $.cookie("locationsLng", locationLng);
                        $('#location').val(locationAddress);
                        $('.search-miles').show();
                        // $('input[name=submit]').click();
                    } else {
                        $.cookie("locations", "");
                        $.cookie("locationsLat", "");
                        $.cookie("locationsLng", "");
                        $('#location').val('');
                        $('.search-miles').hide();
                        // $('input[name=submit]').click();
                    }

                });
            };


        } else {
            $.cookie("locations", "");
            $.cookie("locationsLat", "");
            $.cookie("locationsLng", "");
            $('.location-placeholder').css('display', 'block');
            $('#location').val('');
            $('.search-miles').css('display', 'none');
            $('.search-miles input').val('');
            // $('input[name=submit]').click();
        }
    } else {
        cookieValue = $.cookie("locations");
        if(cookieValue == ''){
         $('#searchform #location').removeAttr('value');
         $('.location-placeholder').css('display', 'block');
         } else {
             var postLocation = '<?php echo $request->post('location'); ?>';
                var postVirtual = '<?php echo $request->post('virtual_in_both'); ?>';
                var search_value = $('#search_value').val();
                if(postLocation == '' && typeof(postLocation) != undefined){
                    if((postVirtual == 'Virtual Only' && typeof(postLocation) != undefined)){
                        $('#location').val('');
                        $('.search-miles').css('display', 'none');
                    }else{
                        if(search_value == ''){
                        $('#location').val(cookieValue);
                        $('.search-miles').css('display', 'block');
                        }
                    }
                }
                else {
                    $('#location').val(postLocation);
                }
                
         }
    }
    <?php if(isset($_GET['location'])){ ?>
       var getLocation = '<?php echo $_GET['location']; ?>';
        if(getLocation == ''){
            $('#location').val('');
            $('.search-miles').css('display', 'none');
        }
    <?php } ?>
});
});
   cookieValue = $.cookie("locations");
    if (cookieValue == '') {
            $('#searchform #location').removeAttr('value');
            $('.location-placeholder').css('display', 'block');
        } else {
            
        var search_value = $('#search_value').val();
        var location_value = $('#exploreLocation').val();
        var postLocation = '<?php echo $request->post('location'); ?>';
        var postVirtual = '<?php echo $request->post('virtual_in_both'); ?>';
        if(postLocation == '' && typeof(postLocation) != undefined){
        if((postVirtual == 'Virtual Only' && typeof(postLocation) != undefined)){
            $('#location').val('');
            $('.search-miles').css('display', 'none');
            $('#virtual_in_both').val(postVirtual);
        }
        else {
        if (search_value == '' && location_value == '') {
            $('#location').val(cookieValue);
        }
    }
}
else {
    $('#location').val(postLocation);
    }
        }
    <?php if(isset($_GET['location'])){ ?>
    var getLocation = '<?php echo $_GET['location']; ?>';
    if(getLocation == ''){
        $('#location').val('');
        $('.search-miles').css('display', 'none');
    }
    <?php } ?>
    var postServices = '<?php echo $request->post('services'); ?>';
    if((typeof(postServices) != undefined && postServices != '')){
        $('#services').val(postServices);
    }
</script>
<script async src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD0sBm7n3sKRiVvtBekP81GCR4r0cjmSDQ&libraries=places&callback=initMap"></script>
@endsection