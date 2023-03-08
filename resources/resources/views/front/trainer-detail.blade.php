@extends('front.layout.app')
@section('title', $title)
@section('content')

<section class="inner-banner-section bg-primary">
		<div class="container">
			<div class="banner-content">
				<h1>@if(!empty($trainerData->business_name)){{$trainerData->business_name}} @else {{$trainerData->first_name}} {{$trainerData->last_name}} @endif</h1>
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
						<li class="breadcrumb-item"><a href="{{route('exploreservices')}}">Explore Training Services</a></li>
						<li class="breadcrumb-item active" aria-current="page">@if(!empty($trainerData->business_name)){{$trainerData->business_name}} @else {{$trainerData->first_name}} {{$trainerData->last_name}} @endif </li>
					</ol>
				</nav>
			</div>
		</div>
</section>

<section class="detail-img-list">
		<div class="container pl-lg-4 pr-lg-4">
			<div class="row justify-content-center">
				<?php 
					$TrainerPhotoSeq=[null,null,null,null];
					if(isset($TrainerPhoto[0]->image)){$imgPos =($TrainerPhoto[0]->position >-1? ($TrainerPhoto[0]->position -1) : 0); $TrainerPhotoSeq[$imgPos] = $TrainerPhoto[0];}
					if(isset($TrainerPhoto[1]->image)){$imgPos =($TrainerPhoto[1]->position >-1? ($TrainerPhoto[1]->position -1) : 1); $TrainerPhotoSeq[$imgPos] = $TrainerPhoto[1];}
					if(isset($TrainerPhoto[2]->image)){$imgPos =($TrainerPhoto[2]->position >-1? ($TrainerPhoto[2]->position -1) : 2); $TrainerPhotoSeq[$imgPos] = $TrainerPhoto[2];}
					if(isset($TrainerPhoto[3]->image)){$imgPos =($TrainerPhoto[3]->position >-1? ($TrainerPhoto[3]->position -1) : 3); $TrainerPhotoSeq[$imgPos] = $TrainerPhoto[3];}

				?>
				@foreach($TrainerPhotoSeq as $image)
				<div class="col-lg-3 col-md-6 col-sm-12 mb-4 mt-lg-0 pr-1 pl-1">
					 <div class="detail-img text-center dds">
						<?php
							$imagePath = '';
							if(isset($image->image))
							{	$imagePath = $image->image;
								$videoPath = $image->image;
								$attr="";
								if($image->is_video==1){
									$attr = 'video='.$videoPath.' video_type='. $image->is_video;
									$imagePath = preg_replace('/.mp4|.mov/i', '.jpg', $videoPath);
									$imagePath = preg_replace('/profile_video/i', 'profile_image', $imagePath);
								}elseif ($image->is_video==2) {
									$videoPath = str_replace("profile_thumb_", "" , $videoPath);
									$videoPath = explode("_", $videoPath);
									array_pop($videoPath);
									$videoPath = implode("", $videoPath);
									$attr = 'video='.$videoPath.' video_type='. $image->is_video;
								}
							}
						?>
						<?php if($imagePath !=''){?>
						<img src="{{asset('/front/profile/'.$imagePath)}}" alt="{{$imagePath}}" class="rounded w-100" style="max-height:210px;min-height: 210px;" {{$attr}}>
						<?php } else {?>
						<img src="{{asset('/front/images/details_default.png')}}" class="rounded w-100"  style="max-height:210px" alt="default details">
						<?php }?>
						<?php 
						if(isset($image) && $image->is_video){ ?>
						<!-- Newly added overlay -->
						<div class="overlay_img"></div>
						<div><img src="{{asset('/front/images/play.png')}}" class="play_btn" id="play_btn"></div>
						<!--  -->
					<?php } ?>
					</div>
				</div>
   @endforeach 
<!--    @if(count($TrainerPhoto) < 4 )
	@for($i=count($TrainerPhoto);$i< 4;$i++) 
   <div class="col-lg-3 col-md-6 col-sm-12 mb-4 mt-lg-0 pr-1 pl-1">
					<div class="detail-img text-center">
						<img src="{{asset('/front/images/details_default.png')}}" class="rounded w-100"  style="max-height:210px" alt="default details">
					</div>
				</div>
	@endfor
   @endif -->
			</div>
		</div>
	
	</section>

<!-- Tab Section -->

<div id="slideout" class="popped" style="right: 0;" >
	<div id="slidecontent">
	<div class="contact_div">
	  <ul class="offshore_list">
										<li> <img src="{{asset('/front/images/icon-location.png')}}" class="tick_icon">
											<a target="_blank" href="http://maps.google.com/?q={{ $trainerData->address_1." ".$trainerData->city." ".$trainerData->state. " ".$trainerData->zip_code }}">
													{{ ($trainerData->address_1) ? $trainerData->address_1: ""}},  {{ ($trainerData->address_2) ? $trainerData->address_2: ""}}
												</span><?php echo ($trainerData->address_1 != '' || $trainerData->address_2 != '') ? "<br>": "" ?>
											<span>{{ ($trainerData->city) ? $trainerData->city:""}}{{ ($trainerData->city && $trainerData->state) ? ", ": ""}}</span> 
											<span>{{ ($trainerData->state) ? $trainerData->state: ""}}{{ ($trainerData->state && $trainerData->zip_code) ? ", ": ""}}</span>
											<span>{{ ($trainerData->zip_code) ? $trainerData->zip_code: ""}}</span>
												</a>
										</li>
										<li class="bold_text"><img src="{{asset('/front/images/icon-phone.png')}}" class="tick_icon"><a href="tel:{{$trainerData->phone_number}}" class="phone_number">{{$trainerData->phone_number}}</a></li>
										@if(!empty($trainerData->website))
										<li><img src="{{asset('/front/images/icon-web.png')}}" class="tick_icon"><a target="_blank" href="{{ ($trainerData->website) ? addhttp($trainerData->website): ""}}">{{ ($trainerData->website) ? $trainerData->website: ""}}</a></li>
										@endif

										<li class="bold_text"><img src="{{asset('/front/images/icon-time.png')}}" class="tick_icon">Hours of Operations<br><span class="bold dayName">Monday : </span>
											<?php
											if($trainerData->day1 != ',' && !empty($trainerData->day1) && !$trainerData->day1 == NULL){ 
											$day1Result = explode(",", $trainerData->day1);
											$day1Count = count($day1Result);
											for ($i=0; $i < $day1Count; $i++) { ?>
											
											<?php
											
											if ( $i % 2 == 0 ) { ?>
												 <span class="no_bold">
													<?php
											  echo date("g:i A", strtotime($day1Result[$i]));
											}
											else {
											echo ' - '.date("g:i A", strtotime($day1Result[$i]));
										   if($i == $day1Count-1){
											 echo ".";
											 }
											 else {
												echo ",";
											 } ?>

											 </span>
										   <?php }     
								} 
							}
								else {
									echo '<span class="no_bold">Closed</span>';
								}
								 ?>
								 <br />
								   <span class="bold dayName">Tuesday : </span>
											<?php
											if($trainerData->day2 != ',' && !empty($trainerData->day2) && !$trainerData->day2 == NULL){ 
											$day2Result = explode(",", $trainerData->day2);
											$day2Count = count($day2Result);
											for ($i=0; $i < $day2Count; $i++) { ?>
										   
											<?php
											if ( $i % 2 == 0) { ?>
												 <span class="no_bold">
													<?php
											  echo date("g:i A", strtotime($day2Result[$i]));
											}
											else {
											echo ' - '.date("g:i A", strtotime($day2Result[$i]));
											if($i == $day2Count-1){
											 echo ".";
											 }
											 else {
												echo ",";
											 } ?>

											 </span>
										   <?php }     
								} 
							}
								else {
									echo '<span class="no_bold">Closed</span>';
								}
								 ?>

								 <br />
								   <span class="bold dayName">Wednesday : </span>
											<?php
											if($trainerData->day3 != ',' && !empty($trainerData->day3) && !$trainerData->day3 == NULL){  
											$day3Result = explode(",", $trainerData->day3);
											$day3Count = count($day3Result);
											for ($i=0; $i < $day3Count; $i++) { ?>
										   
											<?php
											if ( $i % 2 == 0) { ?>
												 <span class="no_bold">
													<?php
											  echo date("g:i A", strtotime($day3Result[$i]));
											}
											else {
											echo ' - '.date("g:i A", strtotime($day3Result[$i]));
											if($i == $day3Count-1){
											 echo ".";
											 }
											 else {
												echo ",";
											 } ?>

											 </span>
										   <?php }     
								} 
							}
								else {
									echo '<span class="no_bold">Closed</span>';
								}
								 ?>

								<br />
								   <span class="bold dayName">Thursday : </span>
											<?php
											if($trainerData->day4 != ',' && !empty($trainerData->day4) && !$trainerData->day4 == NULL){ 
											$day4Result = explode(",", $trainerData->day4);
											$day4Count = count($day4Result);
											for ($i=0; $i < $day4Count; $i++) { ?>
										   
											<?php
											if ( $i % 2 == 0) { ?>
												 <span class="no_bold">
													<?php
											  echo date("g:i A", strtotime($day4Result[$i]));
											}
											else {
											echo ' - '.date("g:i A", strtotime($day4Result[$i]));
											if($i == $day4Count-1){
											 echo ".";
											 }
											 else {
												echo ",";
											 } ?>

											 </span>
										   <?php }     
								} 
							}
								else {
									echo '<span class="no_bold">Closed</span>';
								}
								 ?>

								 <br />
								   <span class="bold dayName">Friday : </span>
											<?php
											if($trainerData->day5 != ',' && !empty($trainerData->day5) && !$trainerData->day5 == NULL){ 
											$day5Result = explode(",", $trainerData->day5);
											$day5Count = count($day5Result);
											for ($i=0; $i < $day5Count; $i++) { ?>
										   
											<?php
											if ( $i % 2 == 0) { ?>
												 <span class="no_bold">
													<?php
											  echo date("g:i A", strtotime($day5Result[$i]));
											}
											else {
											echo ' - '.date("g:i A", strtotime($day5Result[$i]));
											if($i == $day5Count-1){
											 echo ".";
											 }
											 else {
												echo ",";
											 } ?>

											 </span>
										   <?php }     
								} 
							}
								else {
									echo '<span class="no_bold">Closed</span>';
								}
								 ?>
								 <br />
								   <span class="bold dayName">Saturday : </span>
											<?php
											if($trainerData->day6 != ',' && !empty($trainerData->day6) && !$trainerData->day6 == NULL){  
											$day6Result = explode(",", $trainerData->day6);
											$day6Count = count($day6Result);
											for ($i=0; $i < $day6Count; $i++) { ?>
										   
											<?php
											if ( $i % 2 == 0) { ?>
												 <span class="no_bold">
													<?php
											  echo date("g:i A", strtotime($day6Result[$i]));
											}
											else {
											echo ' - '.date("g:i A", strtotime($day6Result[$i]));
											if($i == $day6Count-1){
											 echo ".";
											 }
											 else {
												echo ",";
											 } ?>

											 </span>
										   <?php }     
								} 
							}
								else {
									echo '<span class="no_bold">Closed</span>';
								}
								 ?>
								 <br />
								   <span class="bold dayName">Sunday : </span>
										   <?php
											if($trainerData->day7 != ',' && !empty($trainerData->day7) && !$trainerData->day7 == NULL){  
											$day7Result = explode(",", $trainerData->day7);
											$day7Count = count($day7Result);
											for ($i=0; $i < $day7Count; $i++) { ?>
										   
											<?php
											if ( $i % 2 == 0) { ?>
												 <span class="no_bold">
													<?php
											  echo date("g:i A", strtotime($day7Result[$i]));
											}
											else {
											echo ' - '.date("g:i A", strtotime($day7Result[$i]));
											if($i == $day7Count-1){
											 echo ".";
											 }
											 else {
												echo ",";
											 } ?>

											 </span>
										   <?php }     
								} 
							}
								else {
									echo '<span class="no_bold">Closed</span>';
								}
								 ?>
										</li>
										   
									</ul>
									<ul class="social_div">
										@if(!empty($trainerData->facebook))
									<li class="social_icon1"><a href="{{ ($trainerData->facebook) ? $trainerData->facebook: "javascript:void(0)"}}" {{ ($trainerData->facebook) ? '': "style=display:none"}} {{ ($trainerData->facebook) ? 'target=_blank': ""}} ><img src="{{asset('/front/images/face-book.svg')}}"></a></li>
									@endif
									@if(!empty($trainerData->linkedin))
									<li class="social_icon1"><a href="{{ ($trainerData->linkedin) ? $trainerData->linkedin: "javascript:void(0)"}}" {{ ($trainerData->linkedin) ? '': "style=display:none"}} {{ ($trainerData->linkedin) ? 'target=_blank': ""}} ><img src="{{asset('/front/images/linked.svg')}}"></a></li>
									@endif
									@if(!empty($trainerData->instagram))
									<li class="social_icon1"><a href="{{ ($trainerData->instagram) ? $trainerData->instagram: "javascript:void(0)"}}" {{ ($trainerData->instagram) ? '': "style=display:none"}} {{ ($trainerData->instagram) ? 'target=_blank': ""}}><img src="{{asset('/front/images/insta.svg')}}"></a></li>
									@endif
								</ul>
</div>
</div>
<div id="clickme">
	 <div class="click-arrow"><span id="arrow"></span></div>
	  CONTACT INFO
	</div>
</div>


<section style="padding:20px 15px;">
		<div class="container">
		   <div class="row">
			   <div class="col-md-12 d-flex padding_none">
				<h3 class="tab_tilte text-uppercase">
					@if(!empty($trainerData->business_name))
					{{$trainerData->business_name}}
					 @else 
					 {{$trainerData->first_name}} {{$trainerData->last_name}} 
					 @endif
				</h3>

				  @php 
				  $recommendeds = array();
				  if(isset(Auth::guard('front_auth')->user()->id)){
				  $bussiness_name =  Auth::guard('front_auth')->user()->business_name;
				  } else {
				  $bussiness_name = '';
				  }
				  @endphp
						@if (isset(Auth::guard('front_auth')->user()->id))
			 
						@php $recommendeds = \App\RecommendedProviders::where('provider_id', $trainerData->id)->where('user_id', Auth::guard('front_auth')->user()->id)->first();@endphp
						@endif
						
						
						<?php if($trainerData->business_name != $bussiness_name){?>
						@if (empty($recommendeds))
						<div class="add-fav" id="add_enable"> <a onclick="addrecommended('{{$trainerData->id}}')" href="javascript:void(0)"  class="add-favorite" ><i class="heart fa fa-heart-o"></i></a><span class="tooltiptext">Add as Favorite</span> </div>
						<div class="add-fav" id="remove_enable" style="display:none"> <a onclick="removerecommended('{{$trainerData->id}}')" href="javascript:void(0)"  class="add-favorite" ><i class="heart fa fa-heart"></i></a><span class="tooltiptext">Remove from Favorite</span></div>
						@else
						<div class="add-fav" id="add_enable" style="display:none;"> <a onclick="addrecommended('{{$trainerData->id}}')" href="javascript:void(0)"  class="add-favorite"><i class="heart fa fa-heart-o"></i></a><span class="tooltiptext">Add as Favorite</span> </div>
						<div class="add-fav" id="remove_enable"> <a onclick="removerecommended('{{$trainerData->id}}')" href="javascript:void(0)"  class="add-favorite" id="remove_enable"><i class="heart fa fa-heart"></i></a><span class="tooltiptext">Remove from Favorite</span></div>
						
						@endif
						<?php }?>

						
							<div role="group" class="b-avatar-group mt-2 pt-50" id="favorited_by_athelete_hide">
					        <div class="b-avatar-group-inner" id="favorited_by_athelete">
					        <?php $i=1;?>
					        @if($recommended_authlete->count() > 0)
					        	@foreach($recommended_authlete as $recomm)
					        	@if($i < 4)
					        	
					        	<span class="b-avatar pull-up rounded-circle avatar_1">
						        	<span class="b-avatar-img">
						        		<a href="@if (isset($recomm->customer->user_role) && $recomm->customer->user_role == 'customer'){{route('customer.newprofile',$recomm->customer->first_name.'-'.$recomm->customer->last_name.'-'.$recomm->customer->id)}} @else {{url('provider/'.$recomm->customer->spot_description)}} @endif" title="@if(!empty($recomm->customer->business_name)){{$recomm->customer->business_name}} @else {{$recomm->customer->first_name}} {{$recomm->customer->last_name}} @endif"> 
						        			<img src="@if(isset($recomm->customer->photo) && !empty($recomm->customer->photo) && file_exists(public_path('front/profile/'.$recomm->customer->photo))) {{ asset('front/profile/'.$recomm->customer->photo)}} @else {{ asset('/front/images/details_default.png')}}  @endif">
						        		</a>
						        	</span>
					          	</span>
					          	
					          	@endif
					          	<?php $i = $i+1;?>
					          	@endforeach
					          	 @endif
					          	 
					          	@if($recommended_authlete->count() > 3)
					          <h6 class="align-self-center cursor-pointer ml-2 mb-0"> <a href="#" data-toggle="modal" data-target="#recommendedviewall"><span> {{$recommended_authlete->count()}} View All </span></a></h6>
					          @endif
					          
					        </div>
					      </div>
				     

				      <div id="favorited_count" style="margin-left: .5rem!important;"></div>
				<!-- <h6 class="tab_sub_title">Sports Dietitian. Olympic Trials Qualifier.</h6> -->
			   </div>
		   </div>


			<div class="row">
			   
				<nav>
					<div class="nav nav-tabs provider-nav" id="nav-tab" role="tablist">
						<a class="nav-link active" id="nav-businessbio-tab" data-toggle="tab" href="#nav-businessbio" role="tab" aria-controls="nav-businessbio" aria-selected="true">Business Bio</a>

						<a class="nav-link" id="nav-services-tab" data-toggle="tab" href="#nav-services" role="tab" aria-controls="nav-services" aria-selected="false">Services</a>

						<a class="nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-resources" role="tab" aria-controls="nav-contact" aria-selected="false">Resources</a>

						<a class="nav-link" id="nav-ratings-tab" data-toggle="tab" href="#nav-ratingreviews" role="tab" aria-controls="nav-contact" aria-selected="false">Ratings & Reviews</a>

						
						
					</div>
				</nav>
				<div class="tab-content" id="nav-tabContent">
					<div class="tab-pane bg-color_sect fade show active" id="nav-businessbio" role="tabpanel" aria-labelledby="nav-businessbio-tab">
						<div class="row">
						<div class="col-md-7 col-sm-6 col-xs-6"><p class="text_style_tab">
							@if(!empty($trainerData->bio))
						  {!! $trainerData->bio !!}
						  @endif 
						</p></div>

							 <div class="col-md-5 col-sm-6 col-xs-12">
								<!--<div class="contact_div">
									<ul class="offshore_list">
										<li> <img src="{{asset('/front/images/icon-location.png')}}" class="tick_icon">
											<a target="_blank" href="http://maps.google.com/?q={{ $trainerData->address_1." ".$trainerData->city." ".$trainerData->state. " ".$trainerData->zip_code }}">
													{{ ($trainerData->address_1) ? $trainerData->address_1: ""}} {{ ($trainerData->address_2) ? $trainerData->address_2: ""}}
												</span><?php //echo ($trainerData->address_1 != '' || $trainerData->address_2 != '') ? "<br>": "" ?>
											<span>{{ ($trainerData->city) ? $trainerData->city:""}}{{ ($trainerData->city && $trainerData->state) ? ", ": ""}}</span> 
											<span>{{ ($trainerData->state) ? $trainerData->state: ""}}{{ ($trainerData->state && $trainerData->zip_code) ? ", ": ""}}</span>
											<span>{{ ($trainerData->zip_code) ? $trainerData->zip_code: ""}}</span>
												</a>
										</li>
										<li class="bold_text"><img src="{{asset('/front/images/icon-phone.png')}}" class="tick_icon"><a href="tel:{{$trainerData->phone_number}}" class="phone_number">{{$trainerData->phone_number}}</a></li>
										@if(!empty($trainerData->website))
										<li><img src="{{asset('/front/images/icon-web.png')}}" class="tick_icon"><a target="_blank" href="{{ ($trainerData->website) ? addhttp($trainerData->website): ""}}">{{ ($trainerData->website) ? $trainerData->website: ""}}</a></li>
										@endif
										 @if(!empty($trainerData->fromDay) || !empty($trainerData->toDay) || !empty($TrainerFromTime) || !empty($TrainerToTime) )
										<li class="bold_text"><img src="{{asset('/front/images/icon-time.png')}}" class="tick_icon">Hour of Operations<br><span class="no_bold">{{ $trainerData->fromDay }} - {{ $trainerData->toDay }}<br>
											{{ $TrainerFromTime }} - {{ $TrainerToTime }}</span></li>
											 @endif
									</ul>
									<ul class="social_div">
										@if(!empty($trainerData->facebook))
									<li class="social_icon1"><a href="{{ ($trainerData->facebook) ? $trainerData->facebook: "javascript:void(0)"}}" {{ ($trainerData->facebook) ? '': "style=display:none"}} {{ ($trainerData->facebook) ? 'target=_blank': ""}} ><img src="{{asset('/front/images/face-book.svg')}}"></a></li>
									@endif
									@if(!empty($trainerData->linkedin))
									<li class="social_icon1"><a href="{{ ($trainerData->linkedin) ? $trainerData->linkedin: "javascript:void(0)"}}" {{ ($trainerData->linkedin) ? '': "style=display:none"}} {{ ($trainerData->linkedin) ? 'target=_blank': ""}} ><img src="{{asset('/front/images/linked.svg')}}"></a></li>
									@endif
									@if(!empty($trainerData->instagram))
									<li class="social_icon1"><a href="{{ ($trainerData->instagram) ? $trainerData->instagram: "javascript:void(0)"}}" {{ ($trainerData->instagram) ? '': "style=display:none"}} {{ ($trainerData->instagram) ? 'target=_blank': ""}}><img src="{{asset('/front/images/insta.svg')}}"></a></li>
									@endif
								</ul>
								</div>-->

								   
						<div class="contact-box-inner mt-3 about-content contact_div" id="favorited_by_provider_hide">
							<span class="contact-title text-uppercase">My Network</span>
							<div class="row" id="favorited_by_provider">  
							<?php $i = 1;?>
							@if($recommended_provider->count() > 0)
							@foreach($recommended_provider as $recomm)
							@if($i < 8)
							<div class="col-md-3 mb-2"> 
								<div class="user-info d-flex">
									 <div class="user-name-btn"> 
										<a href="@if (isset($recomm->customer->user_role) && $recomm->customer->user_role == 'customer'){{route('customer.newprofile',$recomm->customer->first_name.'-'.$recomm->customer->last_name.'-'.$recomm->customer->id)}} @else {{url('provider/'.$recomm->customer->spot_description)}} @endif" title="@if(!empty($recomm->customer->business_name)){{$recomm->customer->business_name}} @else {{$recomm->customer->first_name}} {{$recomm->customer->last_name}} @endif"> 
											<img class="user-image " style="height: 60px; width: 60px; object-fit: cover;border-radius: 50%" src="@if(isset($recomm->customer->photo) && !empty($recomm->customer->photo) && file_exists(public_path('front/profile/'.$recomm->customer->photo))) {{ asset('front/profile/'.$recomm->customer->photo)}} @else {{ asset('/front/images/details_default.png')}}  @endif">
									
										</a> 
									</div> 
								</div> 
							</div> 
							@endif
							<?php $i = $i+1;?>
							@endforeach
							@endif
							</div>
							<div id="provider_count">
							@if($recommended_provider->count() > 7)
								<a href="javascript:void(0)" data-toggle="modal" data-target="#recommendedviewallProvider" class="btn btn-outline-danger">View All</a> 
								@endif
								</div>
						</div>
						
						
						@if($Trainerdata->count() > 0)
						<div class="contact-box-inner mt-3 about-content contact_div">
							<span class="contact-title text-uppercase">Favorites</span>
							<div class="row" id="favoritess">  
							@foreach($Trainerdata as $Trainer)
							<div class="media mb-2" id="addrecommended_id_{{$Trainer->users->id}}" style="width: 100%;">
										<img class="d-flex mr-3 rounded-circle img-thumbnail thumb-lg" style="object-fit: cover;margin: 0 10px;" src="@if(isset($Trainer->users->photo) && !empty($Trainer->users->photo) && file_exists(public_path('front/profile/'.$Trainer->users->photo))) {{ asset('front/profile/'.$Trainer->users->photo)}} @else {{ asset('/front/images/details_default.png')}}  @endif" />
										<div class="media-body">
											<a href="{{url('provider/'.$Trainer->users->spot_description)}}" class="card-outer-link float-left">
												<h5 class="mt-0 font-18 mb-1">
													@if(!empty($Trainer->users->business_name))
													{{$Trainer->users->business_name}}
													@else
													{{$Trainer->users->first_name}}
													{{$Trainer->users->last_name}}
													@endif
												</h5>
												<p class="text-muted font-14"> {{$Trainer->users->city}}@if($Trainer->users->state != '' && $Trainer->users->city != ''), @endif {{$Trainer->users->state}}</p> 
											</a>
											@if(isset(Auth::guard('front_auth')->user()->id) && Auth::guard('front_auth')->user()->id == $Trainer->customer->id)
											<img onclick="removerecommended({{$Trainer->users->id}})" class="float-right" style="cursor: pointer;margin: 10px 15px;" src="{{asset('images/delete-icon.png')}}" alt="Rating" />
											@endif
										</div>
									</div>
							@php @endphp
							@endforeach
							</div> 
								
						</div>
						@endif

							</div> 
						
						
						</div>
						</div>

					<div class="tab-pane bg-color_sect fade" id="nav-services" role="tabpanel" aria-labelledby="nav-services-tab">
						@foreach($trainerData->services as $service)
						<div class="blog_row row">
							<div class="col-md-3">
								<img src="{{asset('front/images/'.$service->service->icon)}}" alt="{{$service->service->name}}" class="service_img">
							</div>
							<div class="col-md-9">
								<h3 class="blog_title">{{$service->name}}</h3>
								@if($service->price != "")
								<p class="text-danger mb-0">${{$service->price}}</p>
								@endif
								 <h5 class="status mb-2">{{$service->format}}</h5> 
								
								@if($service->price_weekly == 1)
								<h4 class="text-danger mb-0"> weekly</h4>
								@endif
								@if($service->price_monthly == 1)
								<h5 class="text-danger mb-0"> monthly</h5>
								@endif
								<p>@if(!empty($service->message)){!! \Illuminate\Support\Str::limit($service->message, 300, $end='...') !!}@endif</p>
							  
								<a href="javascript:void(0)" class="book_now" data-toggle="modal" data-target="#serviceDesc{{$service->id}}">VIEW MORE</a>
								@if(Auth::guard('front_auth')->user())
								<?php $service_automatic_order = DB::select('select * from orders where service_id="'.$service->id.'" and user_id="'.Auth::guard('front_auth')->user()->id.'"');
								$service_request_order = DB::select('select * from order_request where service_id="'.$service->id.'" and user_id="'.Auth::guard('front_auth')->user()->id.'"');
								?>
								@if($service->book_type == 1)
								
									<a href="{{url('service-book-now')}}/{{$service->id}}" class="book_now">BOOK NOW</a>
								
								@else
								@if(count($service_request_order) == 0)
								<a href="{{url('service-request-book-now')}}/{{$service->id}}" class="book_now" id="request_book_now">REQUEST TO BOOK</a>
								@endif
								@endif
 								
 								@endif
							</div>

						</div>
						@endforeach
						</div>


						<!-- Resource Tab -->

						 <div class="tab-pane bg-color_sect fade" id="nav-resources" role="tabpanel"
			aria-labelledby="nav-resources-tab">
			<div class="container">
				<form action="" method="post" id="search_resource_clear">
			  <div class="row justify-content-center" style="padding: 10px;">

				
				<div class="col-lg-4 col-sm-4">
				  <div class="form-group search-group">
					<input type="hidden" name="trainer_id" value="{{$trainerData->id}}">
					<input type="hidden" name="search" value="1" id="resource_search">
					<input type="hidden" name="provider_anme" value="{{ $name }}" id="provider_anme">
					<input type="text" name="keyword" id="keyword" class="form-control search-control"
					  placeholder="Search by Keyword" value="{{ $keyword }}">
				  </div>
				  <div style="display: none;" id="filter-error" class="error invalid-feedback">Please select any filter
				  </div>

				</div>

				<div class="col-lg-4 col-sm-4">
				  <div class="form-group service-group">
					@if($resourceCategory)
					<select name="category" id="category" class="form-control service-control">
					  <option value="">Category</option>
					  @foreach( $resourceCategory as $categor)
					  <option value="{{ $categor->name }}" @if($categor->name == $category) selected @endif>{{ $categor->name }}</option>
					  @endforeach
					</select>
					@endif
				  </div>
				</div>


				<div class="col-lg-4 col-sm-4">
				  <div class="form-group service-group">
					<select name="format" id="format" class="form-control service-control">
					  <option value="">Format</option>
					  <!--<option value="Image" @if($format == 'Image') selected @endif>Image</option>-->
					  <option value="Article" @if($format == 'Article') selected @endif>Article</option>
					  <option value="Video" @if($format == 'Video') selected @endif>Video</option>
					  <!--<option value="EBook" @if($format == 'EBook') selected @endif>EBook</option>-->
					</select>
				  </div>
				</div>
				<div class="clearfix"></div>
				<div class="row">
					<div class="col-md-12" align="center"><input type="submit" name="submit" value="Search" class="text-center btn btn-info" id="formSubmit" style="margin-right:5px;"></div>
					 <div class="col-md-12" align="center">
						<!-- <a href="" id="reset" onclick="resetForm()" class="reset_link">Reset</a> -->
						<button type="reset" class="reset_link" id="searchReset">Reset</button>
					</div>
				</div>

				
			   
				<!--<input type="submit" name="reset" id="reset" autofocus value="Reset" class="text-center btn btn-warning" onclick="">-->
			   

			@csrf
				
			  </div>
			</form>
			  <hr />
			  <div class="row">

				@if(isset($searchResources))
				@if(sizeof($searchResources)<1)
					<p class="text-center">No data Found</p>
				@endif
				@foreach($searchResources as $key => $resource)
				<div class="col-lg-4 col-md-6 col-sm-12 mb-4 ">
					   @if($resource->format == 'Article')
				  <div class="img_section"> <img src="{{ asset('/front/images/resource/'.$resource->image_name) }}" class="img_responsive resource-popup" data-src='resourceModal{{$key}}' data-format="{{ $resource->format }}">
					
				  </div>
					@elseif($resource->format == 'Video')
					<!--<div class="img_section"> <img src="{{ asset('/front/images/resource/'.$resource->image_name) }}" class="img_responsive resource-popup" data-src='resourceModal{{$key}}' data-format="{{ $resource->format }}">
						
				  </div>-->
				  <a href="#" data-toggle="modal" data-url="{{ asset('/front/images/resource/'.$resource->format_name) }}" data-target="#resource_details{{$resource->id}}" id="imgPopup"><div class="img_section"> <img src="{{ asset('/front/images/resource/'.$resource->image_name) }}" class="img_responsive resource-popup" data-src="{{ $resource->format_name }}" data-format="{{ $resource->format }}">     
					
				  </div></a>

				  <div class="modal fade" id="resource_details{{$resource->id}}" tabindex="-1" role="dialog" aria-labelledby="likes" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
                        <?php if($resource->format == 'Video'){
                          if (strpos($resource->format_name, 'youtube') > 0) { 

                            parse_str( parse_url( $resource->format_name, PHP_URL_QUERY ), $my_array_of_vars);
                            $v_id = $my_array_of_vars['v'];    
                            ?>
                              <iframe id="Geeks3" width="450" height="350" src="https://www.youtube.com/embed/<?php echo $v_id; ?>" frameborder="0" allowfullscreen></iframe>
                          <?php  }
                          else {

                          ?>
                       <iframe id="Geeks3" width="450" height="350" src="{{ $resource->format_name }}" frameborder="0" allowfullscreen></iframe>
                       <?php } } else {

                        ?>
                       <iframe src="" id="docframe" width="450" height="350" frameborder="0" allowtransparency="true"></iframe>
                       <?php }?>
                        </div>
                      </div>
                    </div>
                    </div>
					@else
					<a href="{{ asset('/front/images/resource/'.$resource->format_name) }}" target="_blank"><div class="img_section"> <img src="{{ asset('/front/images/resource/'.$resource->image_name) }}" class="img_responsive resource-popup" data-src="{{ asset('/front/images/resource/'.$resource->format_name) }}" data-format="{{ $resource->format }}">     
					
				  </div></a>
				   @endif
				  <div class="detail_img_tab">
					<span class="short_text">{{ \Carbon\Carbon::parse($resource->created_at)->diffForHumans() }}</span>
					<h3 class="img_tab_title">{{ $resource->title }}</h3>
					<h4 class="img_tab_subtitle">{{ $resource->name }}</h4>
					
					<?php if(strlen($resource->description) < 70){?>
					<p class="text_inner">{{ str_limit(strip_tags($resource->description), $limit = 70, $end = '...') }}</p>
					<?php } else {?>
					<p class="text_inner">{{ str_limit(strip_tags($resource->description), $limit = 70, $end = '...') }}<a href="#" data-toggle="modal" data-target="#readmoredesc{{$resource->id}}" style="color:#00a990;">Read More</a></p>
					<div class="modal fade" id="readmoredesc{{$resource->id}}" tabindex="-1" role="dialog" aria-labelledby="readmoredesc" aria-hidden="true">
					<div class="modal-dialog" role="document">
					  <div class="modal-content">
						<div class="modal-header">
						  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						  </button>
						</div>
						<div class="modal-body">
						
						  <h4 class="likes_name">{{ $resource->title }}</h4>
						  <p>{{ $resource->description }}</p>
						</div>
					  </div>
					</div>
					</div>
					<?php }?>
					 <?php 
					 $trainerName = DB::table('front_users')->where(["id" => $resource->trainer_id])->first();
					if($customerId !=0){
					$resource_count = DB::table('resource_count')->where(["resource_id" => $resource->id])->count();
					$resource_count_detail = DB::table('resource_count')->where(["user_id" => $customerId, "resource_id" => $resource->id])->get();
					} else {
					$resource_count = DB::table('resource_count')->where(["resource_id" => $resource->id])->count();
					$resource_count_detail = DB::table('resource_count')->where(["resource_id" => $resource->id])->get();
					}
				?>
					 <?php if($customerId !=0){?>
				   <li class="list_style">
				  <?php if($resource_count != 0){ if(count($resource_count_detail) !=0){ foreach ($resource_count_detail as $like) { if($like->likes == 1){?>
					  <img src="{{asset('/front/images/like.png')}}" class="icon_img" id="like_resource{{ $resource->id }}" onClick="like_resource({{ $resource->id }})">
					  <?php } else {?> 
					  <img src="{{asset('/front/images/like-light.png')}}" class="icon_img" id="like_resource{{ $resource->id }}" onClick="like_resource({{ $resource->id }})">
					  <?php } } } else {?>
					  <img src="{{asset('/front/images/like-light.png')}}" class="icon_img" id="like_resource{{ $resource->id }}" onClick="like_resource({{ $resource->id }})">
					  <?php } } else {?>
					  <img src="{{asset('/front/images/like-light.png')}}" class="icon_img" id="like_resource{{ $resource->id }}" onClick="like_resource({{ $resource->id }})">
					  <?php }
						$resource_like_count = DB::table('resource_count')->where(["resource_id" => $resource->id, "likes" => 1])->count();
						if($resource_like_count == 0){
					  ?>
						<p class="count_num" id="like_resource_count{{ $resource->id }}" onclick="like_resource_counts({{ $resource->id }});">{{ $resource_like_count }}</p>
						<?php } else {?>
						<p class="count_num" id="like_resource_count{{ $resource->id }}" onclick="like_resource_counts({{ $resource->id }});">{{ $resource_like_count }}</p>
						<?php }?>
					</li>
					<!-- LIke Modal -->
					<div class="modal fade" id="likes{{$resource->id}}" tabindex="-1" role="dialog" aria-labelledby="likes" aria-hidden="true">
					<div class="modal-dialog" role="document">
					  <div class="modal-content">
						<div class="modal-header">
						  <h5 class="modal-title" id="exampleModalLabel">Likes</h5>
						  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						  </button>
						</div>
						<div class="modal-body" id="resource_like_name{{$resource->id}}">
						<?php 
							$resource_like_name = DB::table('resource_count')->where(["resource_id" => $resource->id, "likes" => 1])->get();
							foreach($resource_like_name as $name){
						  ?>
						  <h4 class="likes_name">{{ $name->name }}</h4>
						  <?php }?>
						</div>
					  </div>
					</div>
					</div>
					<li class="list_style">
					 
					  <?php if($resource_count != 0){ if(count($resource_count_detail) !=0){ foreach ($resource_count_detail as $dislike) { if($dislike->dislike == 1){?>
					  <img src="{{asset('/front/images/dislike.png')}}" class="icon_img" id="dislike_resource{{ $resource->id }}" onClick="dislike_resource({{ $resource->id }})">
					  <?php } else {?> 
					  <img src="{{asset('/front/images/dislike-light.png')}}" class="icon_img" id="dislike_resource{{ $resource->id }}" onClick="dislike_resource({{ $resource->id }})">
					  <?php } } } else {?>
					  <img src="{{asset('/front/images/dislike-light.png')}}" class="icon_img" id="dislike_resource{{ $resource->id }}" onClick="dislike_resource({{ $resource->id }})">
					  <?php } } else {?>
					  <img src="{{asset('/front/images/dislike-light.png')}}" class="icon_img" id="dislike_resource{{ $resource->id }}" onClick="dislike_resource({{ $resource->id }})">
					  <?php }
						$resource_dislike_count = DB::table('resource_count')->where(["resource_id" => $resource->id, "dislike" => 1])->count();
						$resource_dislike_name = DB::table('resource_count')->where(["resource_id" => $resource->id, "dislike" => 1])->get();
						
						if($resource_dislike_count == 0){
						
					  ?>
						<p class="count_num" id="dislike_resource_count{{ $resource->id }}" onclick="dislike_resource_counts({{ $resource->id }});">{{ $resource_dislike_count }}</p>
						<?php } else {?>
						<p class="count_num" id="dislike_resource_count{{ $resource->id }}" onclick="dislike_resource_counts({{ $resource->id }});">{{ $resource_dislike_count }}</p>
						<?php }?>
					</li>
					<!-- DisLIke Modal -->
					<div class="modal fade" id="dislikes{{$resource->id}}" tabindex="-1" role="dialog" aria-labelledby="dislikes" aria-hidden="true">
					<div class="modal-dialog" role="document">
					  <div class="modal-content">
						<div class="modal-header">
						  <h5 class="modal-title" id="exampleModalLabel">Dislikes</h5>
						  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						  </button>
						</div>
						<div class="modal-body" id="resource_dislike_name{{$resource->id}}">
						<?php 
							
							
							foreach($resource_dislike_name as $dislike_name){
						  ?>
						  <h4 class="likes_name">{{ $dislike_name->name }}</h4>
						  <?php }?>
						</div>
					  </div>
					</div>
					</div>
					<li class="list_style">
					  <?php if($resource_count != 0){ if(count($resource_count_detail) !=0){ foreach ($resource_count_detail as $comments) { if($comments->comments != ''){?>
					  <a href="#" data-toggle="modal" data-target="#comments{{$resource->id}}"> <img src="{{asset('/front/images/comment.png')}}" class="icon_img"></a>
					  <?php } else {?> 
					  <a href="#" data-toggle="modal" data-target="#comments{{$resource->id}}"> <img src="{{asset('/front/images/comment-light.png')}}" class="icon_img"></a>
					  <?php } } } else {?>
					  <a href="#" data-toggle="modal" data-target="#comments{{$resource->id}}"> <img src="{{asset('/front/images/comment-light.png')}}" class="icon_img"></a>
					  <?php } } else {?>
					  <a href="#" data-toggle="modal" data-target="#comments{{$resource->id}}"> <img src="{{asset('/front/images/comment-light.png')}}" class="icon_img"></a>
					  <?php }
						$resource_comments_count = DB::table('resource_comments')->where('resource_id', '=', $resource->id)->where('comments', '!=', '')->count();
						$resource_comments_name = DB::table('resource_comments')->where('resource_id', '=', $resource->id)->where('comments', '!=', '')->orderBy('id', 'desc')->get();
						
						if($resource_comments_count == 0){
						
					  ?>
						<p class="count_num">0</p>
						<?php } else {?>
						<a href="#" data-toggle="modal" data-target="#comments{{$resource->id}}"><p class="count_num">{{ $resource_comments_count }}</p></a>
						<?php }?>
					</li>
					<div class="modal fade" id="comments{{$resource->id}}" tabindex="-1" role="dialog" aria-labelledby="comments" aria-hidden="true">
					<div class="modal-dialog" role="document">
					  <div class="modal-content">
						<div class="modal-header">
						  <h5 class="modal-title" id="exampleModalLabel">Comments</h5>
						  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						  </button>
						</div>
						<div class="modal-body">
						
						  <div class="p-2">
						  <form action="{{ route('resources-comment') }}" method="post">
						  
							<input type="hidden" name="resource_id" value="{{ $resource->id }}">
							<input type="hidden" name="provider_name" value="{{ $trainerName->spot_description }}">
							
							<div class="d-flex flex-row align-items-start"><textarea
								class="form-control ml-1 shadow-none textarea_comments" name="comments" required></textarea></div>
							<div class="mt-2 text-right">
							
								<input type="submit" class="btn btn-primary btn-sm shadow-none post_commet" value="Post comment">
								@csrf
								</div>
								</form>
						  </div>
						  
						  
						  <?php if($resource_comments_name){foreach($resource_comments_name as $comments){?>
						  <div class="comment_div">
							<h4>{{ $comments->name }} </h4>
							<span class="short_text">{{ \Carbon\Carbon::parse($comments->created_at)->diffForHumans() }}</span>
							<p>{{ $comments->comments }}</p>
						  </div>
						  <?php }}?>
						</div>
					  </div>
					</div>
				  </div>
					<li class="list_style">
					  
					  <div class="share-button sharer" style="display: block;">
						<a class="share-btn"> <img src="{{asset('/front/images/share-light.png')}}" class="icon_img">
						<p class="count_num">SHARE</p>
					  </a>
						<div class="social top center networks-5 ">

						  <a class="fbtn share facebook" href="#"><i class="fa fa-facebook"></i></a>

						  <a class="fbtn share twitter" href="#"><i class="fa fa-twitter"></i></a>

						  <a class="fbtn share envelope" href="#"><i class="fa fa-envelope"></i></a>
						</div>
					  </div>

					</li>
					<li class="list_style">
					  
					  <?php if($resource_count != 0){ if(count($resource_count_detail) !=0){ foreach ($resource_count_detail as $saved) { if($saved->saved == 1){?>
					  <img src="{{asset('/front/images/save.png')}}" class="icon_img" id="save_resource{{ $resource->id }}" onClick="save_resource({{ $resource->id }})"><p class="count_num_save" id="save_resource_name{{ $resource->id }}">UNSAVE</p>
					  <?php } else {?> 
					  <img src="{{asset('/front/images/save-light.png')}}" class="icon_img" id="save_resource{{ $resource->id }}" onClick="save_resource({{ $resource->id }})"><p class="count_num_save" id="save_resource_name{{ $resource->id }}">SAVE</p>
					  <?php } } } else {?>
					  <img src="{{asset('/front/images/save-light.png')}}" class="icon_img" id="save_resource{{ $resource->id }}" onClick="save_resource({{ $resource->id }})"><p class="count_num_save" id="save_resource_name{{ $resource->id }}">SAVE</p>
					  <?php } } else {?>
					  <img src="{{asset('/front/images/save-light.png')}}" class="icon_img" id="save_resource{{ $resource->id }}" onClick="save_resource({{ $resource->id }})"><p class="count_num_save" id="save_resource_name{{ $resource->id }}">SAVE</p>
					  <?php } ?>
					</li>
					<?php } else {?>
					<li class="list_style">
				  <?php if($resource_count != 0){ if(count($resource_count_detail) !=0){ foreach ($resource_count_detail as $like) { if($like->likes == 1){?>
					  <img src="{{asset('/front/images/like-light.png')}}" class="icon_img2">
					  <?php } else {?> 
					  <img src="{{asset('/front/images/like-light.png')}}" class="icon_img2">
					  <?php } } } else {?>
					  <img src="{{asset('/front/images/like-light.png')}}" class="icon_img2">
					  <?php } } else {?>
					  <img src="{{asset('/front/images/like-light.png')}}" class="icon_img2">
					  <?php }
						$resource_like_count = DB::table('resource_count')->where(["resource_id" => $resource->id, "likes" => 1])->count();
						if($resource_like_count == 0){
					  ?>
						<p class="count_num">{{ $resource_like_count }}</p>
						<?php } else {?>
						<a href="#" data-toggle="modal" data-target="#likes{{$resource->id}}"><p class="count_num">{{ $resource_like_count }}</p></a>
						<?php }?>
					</li>
					<!-- LIke Modal -->
					<div class="modal fade" id="likes{{$resource->id}}" tabindex="-1" role="dialog" aria-labelledby="likes" aria-hidden="true">
					<div class="modal-dialog" role="document">
					  <div class="modal-content">
						<div class="modal-header">
						  <h5 class="modal-title" id="exampleModalLabel">Likes</h5>
						  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						  </button>
						</div>
						<div class="modal-body">
						<?php 
							$resource_like_name = DB::table('resource_count')->where(["resource_id" => $resource->id, "likes" => 1])->get();
							foreach($resource_like_name as $name){
						  ?>
						  <h4 class="likes_name">{{ $name->name }}</h4>
						  <?php }?>
						</div>
					  </div>
					</div>
					</div>
					<li class="list_style">
					 
					  <?php if($resource_count != 0){ if(count($resource_count_detail) !=0){ foreach ($resource_count_detail as $dislike) { if($dislike->dislike == 1){?>
					  <img src="{{asset('/front/images/dislike-light.png')}}" class="icon_img2">
					  <?php } else {?> 
					  <img src="{{asset('/front/images/dislike-light.png')}}" class="icon_img2">
					  <?php } } } else {?>
					  <img src="{{asset('/front/images/dislike-light.png')}}" class="icon_img2">
					  <?php } } else {?>
					  <img src="{{asset('/front/images/dislike-light.png')}}" class="icon_img2">
					  <?php }
						$resource_dislike_count = DB::table('resource_count')->where(["resource_id" => $resource->id, "dislike" => 1])->count();
						$resource_dislike_name = DB::table('resource_count')->where(["resource_id" => $resource->id, "dislike" => 1])->get();
						
						if($resource_dislike_count == 0){
						
					  ?>
						<p class="count_num">0</p>
						<?php } else {?>
						<a href="#" data-toggle="modal" data-target="#dislikes{{$resource->id}}"><p class="count_num">{{ $resource_dislike_count }}</p></a>
						<?php }?>
					</li>
					<!-- DisLIke Modal -->
					<div class="modal fade" id="dislikes{{$resource->id}}" tabindex="-1" role="dialog" aria-labelledby="dislikes" aria-hidden="true">
					<div class="modal-dialog" role="document">
					  <div class="modal-content">
						<div class="modal-header">
						  <h5 class="modal-title" id="exampleModalLabel">Dislikes</h5>
						  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						  </button>
						</div>
						<div class="modal-body">
						<?php 
							
							
							foreach($resource_dislike_name as $dislike_name){
						  ?>
						  <h4 class="likes_name">{{ $dislike_name->name }}</h4>
						  <?php }?>
						</div>
					  </div>
					</div>
					</div>
					<li class="list_style">
					  <?php if($resource_count != 0){ if(count($resource_count_detail) !=0){ foreach ($resource_count_detail as $comments) { if($comments->comments != ''){?>
					  <img src="{{asset('/front/images/comment-light.png')}}" class="icon_img2">
					  <?php } else {?> 
					  <img src="{{asset('/front/images/comment-light.png')}}" class="icon_img2">
					  <?php } } } else {?>
					  <img src="{{asset('/front/images/comment-light.png')}}" class="icon_img2">
					  <?php } } else {?>
					  <img src="{{asset('/front/images/comment-light.png')}}" class="icon_img2">
					  <?php }
						$resource_comments_count = DB::table('resource_comments')->where('resource_id', '=', $resource->id)->where('comments', '!=', '')->count();
						$resource_comments_name = DB::table('resource_comments')->where('resource_id', '=', $resource->id)->where('comments', '!=', '')->orderBy('id', 'desc')->get();
						
						if($resource_comments_count == 0){
						
					  ?>
						<p class="count_num">0</p>
						<?php } else {?>
						<a href="#" data-toggle="modal" data-target="#comments{{$resource->id}}"><p class="count_num">{{ $resource_comments_count }}</p></a>
						<?php }?>
					</li>
					<div class="modal fade" id="comments{{$resource->id}}" tabindex="-1" role="dialog" aria-labelledby="comments" aria-hidden="true">
					<div class="modal-dialog" role="document">
					  <div class="modal-content">
						<div class="modal-header">
						  <h5 class="modal-title" id="exampleModalLabel">Comments</h5>
						  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						  </button>
						</div>
						<div class="modal-body">
						
						  <?php if($resource_comments_name){foreach($resource_comments_name as $comments){?>
						  <div class="comment_div">
							<h4>{{ $comments->name }} </h4>
							<span class="short_text">{{ \Carbon\Carbon::parse($comments->created_at)->diffForHumans() }}</span>
							<p>{{ $comments->comments }}</p>
						  </div>
						  <?php }}?>
						</div>
					  </div>
					</div>
				  </div>
					
					<?php }?>
				  </div>
				</div>
				@endforeach


				<!-- Resource Modal -->
@foreach($searchResources as $key => $resource)
@if($resource->format == 'Image')
<div class="modal fade" id="resourceModal{{$key}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitleSC" aria-hidden="true">
									<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
										<div class="modal-content">
										<div class="modal-body">
										<img src="{{ asset('/front/images/resource/'.$resource->format_name) }}" class="img-responsive" />
										
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
										</div>
										</div>
									</div>
							</div>
				   @endif
	@if($resource->format == 'Video')
							<div class="modal fade" id="resourceModal{{$key}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitleSC" aria-hidden="true">
									<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
										<div class="modal-content">
										<div class="modal-body">
										<iframe width="100%" height="315" id="videoPlayControl" src="{{ asset('/front/images/resource/'.$resource->format_name) }}" allowfullscreen></iframe>
										
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
										</div>
										</div>
									</div>
							</div>
@endif
@endforeach
<!-- Resource Modal -->



				
				@else
				@foreach($trainerResources as $key => $resource)

				<div class="col-lg-4 col-md-6 col-sm-12 mb-4 ">
					   @if($resource->format == 'Article')
				  <div class="img_section"> <img src="{{ asset('/front/images/resource/'.$resource->image_name) }}" class="img_responsive resource-popup" data-src='resourceModal{{$key}}' data-format="{{ $resource->format }}">
					
				  </div>
					@elseif($resource->format == 'Video')
					<!--<div class="img_section"> <img src="{{ asset('/front/images/resource/'.$resource->image_name) }}" class="img_responsive resource-popup" data-src='resourceModal{{$key}}' data-format="{{ $resource->format }}">
						
				  </div>-->
				  <!--<a href="{{ $resource->format_name }}" target="_blank">-->
				  <a href="#" data-toggle="modal" data-url="{{ asset('/front/images/resource/'.$resource->format_name) }}" data-target="#resource_details{{$resource->id}}" id="imgPopup"><div class="img_section"> <img src="{{ asset('/front/images/resource/'.$resource->image_name) }}" class="img_responsive resource-popup" data-src="{{ $resource->format_name }}" data-format="{{ $resource->format }}">     
					
				  </div></a>

				  <div class="modal fade" id="resource_details{{$resource->id}}" tabindex="-1" role="dialog" aria-labelledby="likes" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
                        <?php if($resource->format == 'Video'){
                          if (strpos($resource->format_name, 'youtube') > 0) { 

                            parse_str( parse_url( $resource->format_name, PHP_URL_QUERY ), $my_array_of_vars);
                            $v_id = $my_array_of_vars['v'];    
                            ?>
                              <iframe id="Geeks3" width="450" height="350" src="https://www.youtube.com/embed/<?php echo $v_id; ?>" frameborder="0" allowfullscreen></iframe>
                          <?php  }
                          else {

                          ?>
                       <iframe id="Geeks3" width="450" height="350" src="{{ $resource->format_name }}" frameborder="0" allowfullscreen></iframe>
                       <?php } } else {

                        ?>
                       <iframe src="" id="docframe" width="450" height="350" frameborder="0" allowtransparency="true"></iframe>
                       <?php }?>
                        </div>
                      </div>
                    </div>
                    </div>
					@else
					<a href="{{ asset('/front/images/resource/'.$resource->format_name) }}" target="_blank"><div class="img_section"> <img src="{{ asset('/front/images/resource/'.$resource->image_name) }}" class="img_responsive resource-popup" data-src="{{ asset('/front/images/resource/'.$resource->format_name) }}" data-format="{{ $resource->format }}">     
					
				  </div></a>
				   @endif

				  <div class="detail_img_tab">
					
					<span class="short_text">{{ \Carbon\Carbon::parse($resource->created_at)->diffForHumans() }}</span>
					<h3 class="img_tab_title">{{ $resource->title }}</h3>
					<h4 class="img_tab_subtitle">{{ $resource->name }}</h4>
					<?php if(strlen($resource->description) < 70){?>
					<p class="text_inner">{{ str_limit(strip_tags($resource->description), $limit = 70, $end = '...') }}</p>
					<?php } else {?>
					<p class="text_inner">{{ str_limit(strip_tags($resource->description), $limit = 70, $end = '...') }}<a href="#" data-toggle="modal" data-target="#readmoredesc{{$resource->id}}" style="color:#00a990;">Read More</a></p>
					<div class="modal fade" id="readmoredesc{{$resource->id}}" tabindex="-1" role="dialog" aria-labelledby="readmoredesc" aria-hidden="true">
					<div class="modal-dialog" role="document">
					  <div class="modal-content">
						<div class="modal-header">
						  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						  </button>
						</div>
						<div class="modal-body">
						
						  <h4 class="likes_name">{{ $resource->title }}</h4>
						  <p>{{ $resource->description }}</p>
						</div>
					  </div>
					</div>
					</div>
					<?php }?>
					<?php 
					$trainerName = DB::table('front_users')->where(["id" => $resource->trainer_id])->first();

					if($customerId !=0){
					$resource_count = DB::table('resource_count')->where(["resource_id" => $resource->id])->count();
					$resource_count_detail = DB::table('resource_count')->where(["user_id" => $customerId, "resource_id" => $resource->id])->get();
					} else {
					$resource_count = DB::table('resource_count')->where(["resource_id" => $resource->id])->count();
					$resource_count_detail = DB::table('resource_count')->where(["resource_id" => $resource->id])->get();
					}

				?>
					 <?php if($customerId !=0){?>
				   <li class="list_style">
				  <?php if($resource_count != 0){ if(count($resource_count_detail) !=0){ foreach ($resource_count_detail as $like) { if($like->likes == 1){?>
					  <img src="{{asset('/front/images/like.png')}}" class="icon_img" id="like_resource{{ $resource->id }}" onClick="like_resource({{ $resource->id }})">
					  <?php } else {?> 
					  <img src="{{asset('/front/images/like-light.png')}}" class="icon_img" id="like_resource{{ $resource->id }}" onClick="like_resource({{ $resource->id }})">
					  <?php } } } else {?>
					  <img src="{{asset('/front/images/like-light.png')}}" class="icon_img" id="like_resource{{ $resource->id }}" onClick="like_resource({{ $resource->id }})">
					  <?php } } else {?>
					  <img src="{{asset('/front/images/like-light.png')}}" class="icon_img" id="like_resource{{ $resource->id }}" onClick="like_resource({{ $resource->id }})">
					  <?php }
						$resource_like_count = DB::table('resource_count')->where(["resource_id" => $resource->id, "likes" => 1])->count();
						if($resource_like_count == 0){
					  ?>
						<p class="count_num" id="like_resource_count{{ $resource->id }}" onclick="like_resource_counts({{ $resource->id }});">{{ $resource_like_count }}</p>
						<?php } else {?>
						<p class="count_num" id="like_resource_count{{ $resource->id }}" onclick="like_resource_counts({{ $resource->id }});">{{ $resource_like_count }}</p>
						<?php }?>
					</li>
					<!-- LIke Modal -->
					<div class="modal fade" id="likes{{$resource->id}}" tabindex="-1" role="dialog" aria-labelledby="likes" aria-hidden="true">
					<div class="modal-dialog" role="document">
					  <div class="modal-content">
						<div class="modal-header">
						  <h5 class="modal-title" id="exampleModalLabel">Likes</h5>
						  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						  </button>
						</div>
						<div class="modal-body" id="resource_like_name{{$resource->id}}">
						<?php 
							$resource_like_name = DB::table('resource_count')->where(["resource_id" => $resource->id, "likes" => 1])->get();
							foreach($resource_like_name as $name){
						  ?>
						  <h4 class="likes_name">{{ $name->name }}</h4>
						  <?php }?>
						</div>
					  </div>
					</div>
					</div>
					<li class="list_style">
					 
					  <?php if($resource_count != 0){ if(count($resource_count_detail) !=0){ foreach ($resource_count_detail as $dislike) { if($dislike->dislike == 1){?>
					  <img src="{{asset('/front/images/dislike.png')}}" class="icon_img" id="dislike_resource{{ $resource->id }}" onClick="dislike_resource({{ $resource->id }})">
					  <?php } else {?> 
					  <img src="{{asset('/front/images/dislike-light.png')}}" class="icon_img" id="dislike_resource{{ $resource->id }}" onClick="dislike_resource({{ $resource->id }})">
					  <?php } } } else {?>
					  <img src="{{asset('/front/images/dislike-light.png')}}" class="icon_img" id="dislike_resource{{ $resource->id }}" onClick="dislike_resource({{ $resource->id }})">
					  <?php } } else {?>
					  <img src="{{asset('/front/images/dislike-light.png')}}" class="icon_img" id="dislike_resource{{ $resource->id }}" onClick="dislike_resource({{ $resource->id }})">
					  <?php }
						$resource_dislike_count = DB::table('resource_count')->where(["resource_id" => $resource->id, "dislike" => 1])->count();
						$resource_dislike_name = DB::table('resource_count')->where(["resource_id" => $resource->id, "dislike" => 1])->get();
						
						if($resource_dislike_count == 0){
						
					  ?>
						<p class="count_num" id="dislike_resource_count{{ $resource->id }}" onclick="dislike_resource_counts({{ $resource->id }});">{{ $resource_dislike_count }}</p>
						<?php } else {?>
						<p class="count_num" id="dislike_resource_count{{ $resource->id }}" onclick="dislike_resource_counts({{ $resource->id }});">{{ $resource_dislike_count }}</p>
						<?php }?>
					</li>
					<!-- DisLIke Modal -->
					<div class="modal fade" id="dislikes{{$resource->id}}" tabindex="-1" role="dialog" aria-labelledby="dislikes" aria-hidden="true">
					<div class="modal-dialog" role="document">
					  <div class="modal-content">
						<div class="modal-header">
						  <h5 class="modal-title" id="exampleModalLabel">Dislikes</h5>
						  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						  </button>
						</div>
						<div class="modal-body" id="resource_dislike_name{{$resource->id}}">
						<?php 
							
							
							foreach($resource_dislike_name as $dislike_name){
						  ?>
						  <h4 class="likes_name">{{ $dislike_name->name }}</h4>
						  <?php }?>
						</div>
					  </div>
					</div>
					</div>
					<li class="list_style">
					  
					  <?php if($resource_count != 0){ if(count($resource_count_detail) !=0){ foreach ($resource_count_detail as $comments) { if($comments->comments != ''){?>
					  <a href="#" data-toggle="modal" data-target="#comments{{$resource->id}}"> <img src="{{asset('/front/images/comment.png')}}" class="icon_img"></a>
					  <?php } else {?> 
					  <a href="#" data-toggle="modal" data-target="#comments{{$resource->id}}"> <img src="{{asset('/front/images/comment-light.png')}}" class="icon_img"></a>
					  <?php } } } else {?>
					  <a href="#" data-toggle="modal" data-target="#comments{{$resource->id}}"> <img src="{{asset('/front/images/comment-light.png')}}" class="icon_img"></a>
					  <?php } } else {?>
					  <a href="#" data-toggle="modal" data-target="#comments{{$resource->id}}"> <img src="{{asset('/front/images/comment-light.png')}}" class="icon_img"></a>
					  <?php }
						$resource_comments_count = DB::table('resource_comments')->where('resource_id', '=', $resource->id)->where('comments', '!=', '')->count();
						$resource_comments_name = DB::table('resource_comments')->where('resource_id', '=', $resource->id)->where('comments', '!=', '')->orderBy('id', 'desc')->get();
						
						if($resource_comments_count == 0){
						
					  ?>
						<p class="count_num">0</p>
						<?php } else {?>
						<a href="#" data-toggle="modal" data-target="#comments{{$resource->id}}"><p class="count_num">{{ $resource_comments_count }}</p></a>
						<?php }?>
					</li>
					<div class="modal fade" id="comments{{$resource->id}}" tabindex="-1" role="dialog" aria-labelledby="comments" aria-hidden="true">
					<div class="modal-dialog" role="document">
					  <div class="modal-content">
						<div class="modal-header">
						  <h5 class="modal-title" id="exampleModalLabel">Comments</h5>
						  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						  </button>
						</div>
						<div class="modal-body">
						
						  <div class="p-2">
						  <form action="{{ route('resources-comment') }}" method="post">
						  
							<input type="hidden" name="resource_id" value="{{ $resource->id }}">
							<input type="hidden" name="provider_name" value="{{ $trainerName->spot_description }}">
							
							<div class="d-flex flex-row align-items-start"><textarea
								class="form-control ml-1 shadow-none textarea_comments" name="comments" required></textarea></div>
							<div class="mt-2 text-right">
							
								<input type="submit" class="btn btn-primary btn-sm shadow-none post_commet" value="Post comment">
								@csrf
								</div>
								</form>
						  </div>
						  
						 
						  <?php if($resource_comments_name){foreach($resource_comments_name as $comments){?>
						  <div class="comment_div">
							<h4>{{ $comments->name }} </h4>
							<span class="short_text">{{ \Carbon\Carbon::parse($comments->created_at)->diffForHumans() }}</span>
							<p>{{ $comments->comments }}</p>
						  </div>
						  <?php }}?>
						</div>
					  </div>
					</div>
				  </div>
					<li class="list_style">
					  <div class="share-button sharer" style="display: block;">
						<a class="share-btn"> <img src="{{asset('/front/images/share-light.png')}}" class="icon_img">
						<p class="count_num">SHARE</p>
					  </a>
						<div class="social top center networks-5 ">

						  <a class="fbtn share facebook" href="#"><i class="fa fa-facebook"></i></a>

						  <a class="fbtn share twitter" href="#"><i class="fa fa-twitter"></i></a>

						  <a class="fbtn share envelope" href="#"><i class="fa fa-envelope"></i></a>
						</div>
					  </div>

					</li>
					<li class="list_style">
					  
					  <?php if($resource_count != 0){ if(count($resource_count_detail) !=0){ foreach ($resource_count_detail as $saved) { if($saved->saved == 1){?>
					  <img src="{{asset('/front/images/save.png')}}" class="icon_img" id="save_resource{{ $resource->id }}" onClick="save_resource({{ $resource->id }})"><p class="count_num_save" id="save_resource_name{{ $resource->id }}">UNSAVE</p>
					  <?php } else {?> 
					  <img src="{{asset('/front/images/save-light.png')}}" class="icon_img" id="save_resource{{ $resource->id }}" onClick="save_resource({{ $resource->id }})"><p class="count_num_save" id="save_resource_name{{ $resource->id }}">SAVE</p>
					  <?php } } } else {?>
					  <img src="{{asset('/front/images/save-light.png')}}" class="icon_img" id="save_resource{{ $resource->id }}" onClick="save_resource({{ $resource->id }})"><p class="count_num_save" id="save_resource_name{{ $resource->id }}">SAVE</p>
					  <?php } } else {?>
					  <img src="{{asset('/front/images/save-light.png')}}" class="icon_img" id="save_resource{{ $resource->id }}" onClick="save_resource({{ $resource->id }})"><p class="count_num_save" id="save_resource_name{{ $resource->id }}">SAVE</p>
					  <?php } ?>
					</li>
					<?php } else {?>
					<li class="list_style">
				  <?php if($resource_count != 0){ if(count($resource_count_detail) !=0){ foreach ($resource_count_detail as $like) { if($like->likes == 1){?>
					  <img src="{{asset('/front/images/like-light.png')}}" class="icon_img2">
					  <?php } else {?> 
					  <img src="{{asset('/front/images/like-light.png')}}" class="icon_img2">
					  <?php } } } else {?>
					  <img src="{{asset('/front/images/like-light.png')}}" class="icon_img2">
					  <?php } } else {?>
					  <img src="{{asset('/front/images/like-light.png')}}" class="icon_img2">
					  <?php }
						$resource_like_count = DB::table('resource_count')->where(["resource_id" => $resource->id, "likes" => 1])->count();
						if($resource_like_count == 0){
					  ?>
						<p class="count_num">{{ $resource_like_count }}</p>
						<?php } else {?>
						<a href="#" data-toggle="modal" data-target="#likes{{$resource->id}}"><p class="count_num">{{ $resource_like_count }}</p></a>
						<?php }?>
					</li>
					<!-- LIke Modal -->
					<div class="modal fade" id="likes{{$resource->id}}" tabindex="-1" role="dialog" aria-labelledby="likes" aria-hidden="true">
					<div class="modal-dialog" role="document">
					  <div class="modal-content">
						<div class="modal-header">
						  <h5 class="modal-title" id="exampleModalLabel">Likes</h5>
						  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						  </button>
						</div>
						<div class="modal-body">
						<?php 
							$resource_like_name = DB::table('resource_count')->where(["resource_id" => $resource->id, "likes" => 1])->get();
							foreach($resource_like_name as $name){
						  ?>
						  <h4 class="likes_name">{{ $name->name }}</h4>
						  <?php }?>
						</div>
					  </div>
					</div>
					</div>
					<li class="list_style">
					 
					  <?php if($resource_count != 0){ if(count($resource_count_detail) !=0){ foreach ($resource_count_detail as $dislike) { if($dislike->dislike == 1){?>
					  <img src="{{asset('/front/images/dislike-light.png')}}" class="icon_img2">
					  <?php } else {?> 
					  <img src="{{asset('/front/images/dislike-light.png')}}" class="icon_img2">
					  <?php } } } else {?>
					  <img src="{{asset('/front/images/dislike-light.png')}}" class="icon_img2">
					  <?php } } else {?>
					  <img src="{{asset('/front/images/dislike-light.png')}}" class="icon_img2">
					  <?php }
						$resource_dislike_count = DB::table('resource_count')->where(["resource_id" => $resource->id, "dislike" => 1])->count();
						$resource_dislike_name = DB::table('resource_count')->where(["resource_id" => $resource->id, "dislike" => 1])->get();
						
						if($resource_dislike_count == 0){
						
					  ?>
						<p class="count_num">0</p>
						<?php } else {?>
						<a href="#" data-toggle="modal" data-target="#dislikes{{$resource->id}}"><p class="count_num">{{ $resource_dislike_count }}</p></a>
						<?php }?>
					</li>
					<!-- DisLIke Modal -->
					<div class="modal fade" id="dislikes{{$resource->id}}" tabindex="-1" role="dialog" aria-labelledby="dislikes" aria-hidden="true">
					<div class="modal-dialog" role="document">
					  <div class="modal-content">
						<div class="modal-header">
						  <h5 class="modal-title" id="exampleModalLabel">Dislikes</h5>
						  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						  </button>
						</div>
						<div class="modal-body">
						<?php 
							
							
							foreach($resource_dislike_name as $dislike_name){
						  ?>
						  <h4 class="likes_name">{{ $dislike_name->name }}</h4>
						  <?php }?>
						</div>
					  </div>
					</div>
					</div>
					<li class="list_style">
					  <?php if($resource_count != 0){ if(count($resource_count_detail) !=0){ foreach ($resource_count_detail as $comments) { if($comments->comments != ''){?>
					  <img src="{{asset('/front/images/comment-light.png')}}" class="icon_img2">
					  <?php } else {?> 
					  <img src="{{asset('/front/images/comment-light.png')}}" class="icon_img2">
					  <?php } } } else {?>
					  <img src="{{asset('/front/images/comment-light.png')}}" class="icon_img2">
					  <?php } } else {?>
					  <img src="{{asset('/front/images/comment-light.png')}}" class="icon_img2">
					  <?php }
						$resource_comments_count = DB::table('resource_comments')->where('resource_id', '=', $resource->id)->where('comments', '!=', '')->count();
						$resource_comments_name = DB::table('resource_comments')->where('resource_id', '=', $resource->id)->where('comments', '!=', '')->orderBy('id', 'desc')->get();
						
						if($resource_comments_count == 0){
						
					  ?>
						<p class="count_num">0</p>
						<?php } else {?>
						<a href="#" data-toggle="modal" data-target="#comments{{$resource->id}}"><p class="count_num">{{ $resource_comments_count }}</p></a>
						<?php }?>
					</li>
					<div class="modal fade" id="comments{{$resource->id}}" tabindex="-1" role="dialog" aria-labelledby="comments" aria-hidden="true">
					<div class="modal-dialog" role="document">
					  <div class="modal-content">
						<div class="modal-header">
						  <h5 class="modal-title" id="exampleModalLabel">Comments</h5>
						  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						  </button>
						</div>
						<div class="modal-body">
					   
						  <?php if($resource_comments_name){foreach($resource_comments_name as $comments){?>
						  <div class="comment_div">
							<h4>{{ $comments->name }} </h4>
							<span class="short_text">{{ \Carbon\Carbon::parse($comments->created_at)->diffForHumans() }}</span>
							<p>{{ $comments->comments }}</p>
						  </div>
						  <?php }}?>
						</div>
					  </div>
					</div>
				  </div>

					
					<?php }?>
				  </div>
				</div>
				@endforeach

				<!-- Resource Modal -->
@foreach($trainerResources as $key => $resource)
@if($resource->format == 'Image')
<div class="modal fade" id="resourceModal{{$key}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitleSC" aria-hidden="true">
									<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
										<div class="modal-content">
										<div class="modal-body">
										<img src="{{ asset('/front/images/resource/'.$resource->format_name) }}" class="img-responsive" />
										
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
										</div>
										</div>
									</div>
							</div>
	@endif
	@if($resource->format == 'Video')
							<div class="modal fade" id="resourceModal{{$key}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitleSC" aria-hidden="true">
									<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
										<div class="modal-content">
										<div class="modal-body">
										<!-- <iframe width="100%" height="315" id="videoPlayControl" src="{{ asset('/front/images/resource/'.$resource->format_name) }}" allowfullscreen></iframe> -->
										
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
										</div>
										</div>
									</div>
							</div>
@endif
@endforeach
<!-- Resource Modal -->

			
					@endif
			  </div>
			</div>
		  </div>




						<!-- End Resource Tab -->

						<!-- Review and ratings Tab -->
					<div class="tab-pane fade" id="nav-ratingreviews" role="tabpanel" aria-labelledby="nav-ratings-tab">

						 <div class="pl-2 pr-0 text-center write-button">
						<a href="{{ route('customer.review',base64_encode($trainerData->id)) }}"  class="btn btn-outline-danger reviewbtn">Write a Review</a>
			  
					  
							</div>
					   <!-- <rating slider  -->

						@if(!empty($trainerreviewData))
						  <div class="owl-carousel testimonial-carousel">
						@foreach($trainerreviewData->ratings as $trainer)     

						<div class="single-testimonial">
						  <div class="testimonials-wrapper">
							<div class="testimonials-img"><img src="@if(isset($trainer->user->photo) && !empty($trainer->user->photo)) {{ asset('front/profile/'.$trainer->user->photo)}} @else {{ asset('/front/images/details_default.png')}}  @endif">
							   </div>
							   <div class="testimonials-person-info">
								<h5>@if(!empty($trainer->user->business_name))
							{{$trainer->user->business_name}}
							@elseif(isset($trainer->user->first_name))
							{{$trainer->user->first_name}}
							{{$trainer->user->last_name}}
							@endif </h5>
									<ul class="nav rating-star">
								@for($i=1;$i<=$trainer->rating;$i++) 
								<li><img src="{{asset('images/star.png')}}" class="rating-star-img" alt="Rating" /></li>
								@endfor
								@for($i=5;$i>=$trainer->rating+1;$i--) 
								<li><img src="{{asset('images/star-blank.png')}}" alt="Rating" /></li>
								@endfor
							</ul>
								   <!-- <p class="retting-date">{{date('M',strtotime($trainer->created_at))}} {{date('d Y',strtotime($trainer->created_at))}}</p>                                                          -->
							  </div>
						  
							<h4>
								<p class="title_testi">{{$trainer->title}}</p>
								{{$trainer->description}}
							</h4>
							<p class="retting-date text-right pr-2">{{date('M',strtotime($trainer->created_at))}} {{date('d Y',strtotime($trainer->created_at))}}</p>                                                         
							
						  </div>
						</div>
						@endforeach
					  
					  </div>
					  @endif
						<!-- end rating slider -->
				</div>

				<!-- end of container -->
			</div>
		</div>
	</section>


 <!-- service model start -->
  @foreach($trainerData->services as $service)
							<div class="modal fade" id="serviceDesc{{$service->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitleSC" aria-hidden="true">
									<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
										<div class="modal-content">
										<div class="modal-header">
											<h5 class="modal-title" id="exampleModalLongTitleSC">{{$service->name}}</h5>
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
											</button>
										</div>
										<div class="modal-body">
										{!! $service->message  !!}
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
										</div>
										</div>
									</div>
							</div>
							@endforeach
<!-- model end -->




<!-- Tab Section -->

<!-- Video Modal -->

<div class="modal fade" id="videoPlayModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitleSC" aria-hidden="true">
									<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
										<div class="modal-content">
										<div class="modal-body">
										<!-- <video width="100%" controls >
											<source id="videoPlayControl" src="" type="video/mp4">
										</video> -->
										<iframe width="100%" height="315" id="videoPlayControl" src="" allowfullscreen></iframe>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
										</div>
										</div>
									</div>
							</div>

<!-- Video Modal -->




  <!-- model start -->
<div class="modal fade" id="recommendedviewall" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitleSC" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLongTitleSC">My Network</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row" id="recommendedviewallpopup">
				@foreach($recommended_authlete as $recomm)
							<div class="col-xl-6 mb-2">  
								<div class="user-info d-flex">
									<img class="user-image " style="height: 60px; width: 60px;" src="@if(isset($recomm->customer->photo) && !empty($recomm->customer->photo) && file_exists(public_path('front/profile/'.$recomm->customer->photo))) {{ asset('front/profile/'.$recomm->customer->photo)}} @else {{ asset('/front/images/details_default.png')}}  @endif">
									<div class="user-name-btn ml-2"  style="max-width:100%"> 
										<a href="{{route('customer.newprofile',$recomm->customer->first_name.'-'.$recomm->customer->last_name.'-'.$recomm->customer->id)}}"> 
											<h4 class="user-name m-0">  
												{{$recomm->customer->first_name}} {{$recomm->customer->last_name}}
											</h4>  

											@if($recomm->customer->city != '' || $recomm->customer->state != '')
											<div class="location-taxt">  
												<p><i class="fas fa-map-marker-alt"></i> {{$recomm->customer->city}} {{$recomm->customer->state }}</p>
											</div> 
											@endif
										</a> 
									</div> 
								</div> 

							</div> 
							@endforeach
			</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="recommendedviewallProvider" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitleSC" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLongTitleSC">My Network</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row" id="recommendedviewallProviderpopup">
				@foreach($recommended_provider as $recomm)
							<div class="col-xl-6 mb-2">  
								<div class="user-info d-flex">
									<img class="user-image " style="height: 60px; width: 60px;" src="@if(isset($recomm->customer->photo) && !empty($recomm->customer->photo) && file_exists(public_path('front/profile/'.$recomm->customer->photo))) {{ asset('front/profile/'.$recomm->customer->photo)}} @else {{ asset('/front/images/details_default.png')}}  @endif">
									<div class="user-name-btn ml-2"  style="max-width:100%"> 
										<a href="@if (isset($recomm->customer->user_role) && $recomm->customer->user_role == 'customer'){{route('customer.newprofile',$recomm->customer->first_name.'-'.$recomm->customer->last_name.'-'.$recomm->customer->id)}} @else {{url('provider/'.$recomm->customer->spot_description)}} @endif"> 
											<h4 class="user-name m-0">  
												{{$recomm->customer->first_name}} {{$recomm->customer->last_name}}
											</h4>  

											@if($recomm->customer->city != '' || $recomm->customer->state != '')
											<div class="location-taxt">  
												<p><i class="fas fa-map-marker-alt"></i> {{$recomm->customer->city}} {{$recomm->customer->state }}</p>
											</div> 
											@endif
										</a> 
									</div> 
								</div> 

							</div> 
							@endforeach
			</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- model end -->
<section class="calender-section" style="display: none">
		<div class="container">
			<div class="row">
				<div class="col-12 offset-0 col-lg-10 offset-lg-1">
					<div class="section-title text-center">
						<h5 class="text-danger">CALENDAR</h5>
						<h2>Availability</h2>
						<p>
						  @if(!empty($trainerData->bio))
						  {!! $trainerData->bio !!}
						  @endif  
						</p>
					</div>
				</div>
			</div>
			<div class="row">
				<di class="col-12 offset-0 col-lg-10 offset-lg-1">
					<div id="trainerCalendar" class="w-100"></div>
				</di>
			</div>
			 @if(Auth::guard('front_auth')->user())
				@if(Auth::guard('front_auth')->user()->user_role != 'trainer')
				<div class="row mt-5">
					<div class="col-12 text-center">  
						@if(!empty($TrainerAcountdata))
						<a href="{{route('customer.booknow',base64_encode($trainerData->id))}}" class="btn btn-outline-danger">BOOK NOW</a>
						@else
						<a href="" title="Not Available" class="btn btn-outline-danger book_now disabled">BOOK NOW</a>
						@endif       
					</div>
				</div>
				@endif
			@else
			<div class="row mt-5">
					<div class="col-12 text-center">  
					 @if(!empty($TrainerAcountdata))
						<a href="{{route('customer.booknow',base64_encode($trainerData->id))}}?booknow" class="btn btn-outline-danger">BOOK NOW</a>      
					 @else
					 <a href="" title="Not Available" class="btn btn-outline-danger book_now disabled">BOOK NOW</a>
					 @endif
					</div>
			</div>
			@endif 
			
		</div>
</section>



<section class="expert-guidance-section">
		<div class="container">
			<div class="section-title text-center">
				<h5 class="text-danger">THE FUTURE OF TRAINING</h5>
				<h2>THE FUTURE OF TRAINING IS HERE</h2>
				<p>Gone are the days of training alone. Of subpar performances. Of not leaving it all out there. Training Block recognizes that every sport is a team sport: every athlete has a team behind them that pushes them to greatness. Whether it be a trusted coach, physical therapist, nutritionist, or other sports provider, find the provider you need for your unique goals on Training Block.</p>
			</div>
		</div>
		<div class="expert-guidance-slider">
		@foreach($trainerList as $trainer)
			<div class="slide">
				<a href="{{url('provider/'.$trainer->spot_description)}}">
					<div class="slide-inner">
						<div class="slide-img">
							@php
				$TrainerPhoto = \App\TrainerPhoto::where('is_featured',1)->where('trainer_id',$trainer->id)->first();
				@endphp
				@if(!empty($TrainerPhoto))
				<img src="@if(!empty($TrainerPhoto->image)){{asset('front/profile/'.$TrainerPhoto->image)}} @endif" alt="Expert Guidance" />

				@elseif(!empty($trainer->photo))
				<img src="@if(!empty($trainer->photo)){{asset('front/profile/'.$trainer->photo)}} @endif" alt="Expert Guidance" />

				@else
				<img src="{{asset('images/Expert_01.jpg')}}" alt="Expert Guidance" />

				@endif 
													</div>
						<div class="slide-content">
							<h4 class="text-uppercase mb-sm-3">
							@if(!empty($trainer->business_name))
							{{$trainer->business_name}}
							@else
							{{$trainer->first_name}}
							{{$trainer->last_name}}
							@endif
							</h4>
							<h5 class="location">
							{{$trainer->address_1}}
							{{$trainer->city}}
							{{$trainer->state}}
							{{$trainer->country}}
							</h5>
							<p>{!! \Illuminate\Support\Str::limit(strip_tags(htmlspecialchars_decode($trainer->bio)), 70, $end=' ...') !!}</p>
							<div class="rating">
								<ul class="nav">
								<?php
										$rating = $trainer->ratting;
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
							</div>
						</div>
					</div>
				</a>
			</div>
			@endforeach
		</div>
</section>  
<div class="videomodal" id="video-modal">
	
</div>
<?php
//dd($eventData);
 ?>

@stop
@section('pagescript')
<style> 
	.videomodal {
  position: fixed;
  display: none;
  padding: 20px;
  box-shadow: 0 0 10px 10px #ccc;
  z-index: 100;
  width: 400px;
  height: 400px;
  left: 50%;
  margin-left: -200px;
  background: #000;
  top: 50%;
  margin-top: -200px; 
  border-radius: 10px;
  transition: all 0.5s ease-in;  
}
.serviceDescription{
	overflow: hidden;
	text-overflow: ellipsis;
	display: -webkit-box;
	-webkit-line-clamp: 2;
	-webkit-box-orient: vertical;
}
.book_now.disabled{
  pointer-events: none;
  cursor: default;
  border: solid 2px #898989;
  color: #898989;
}
a.book_now.disabled:hover:after {
  content: attr(title);
  background: #fff;
  /* padding: 5px 12px;
  border: solid 1px #ddd; */
  position: absolute;
  /* bottom: 100%; */
  left: 50%;
  transform: translateX(-50%);
}
.service-image-wrap .card-body {
	padding-top: 25px;
	min-height: 270px;
	max-height: 270px;
}
.mr-20{
	margin-right:20px;
}

/*@media only screen and (max-width: 1200px) and (min-width: 991px){
	.detail-img img{
		max-height: 147px !important;
		min-height: 147px !important;
	}
}*/
.dayName {
	color: #00ab91;
	font-weight: normal;
}
.heart {
  font-size: 25px;
	color:red;
	line-height: 2;
}
.add-fav .tooltiptext {
  visibility: hidden;
  /*width: 120px;*/
  background-color: #1e2732;
  color: #fff;
  text-align: center;
  border-radius: 6px;
  padding: 0 10px;
  position: absolute;
  z-index: 1;
  top: 7px;
  margin-left: 3px;
}

.add-fav:hover .tooltiptext {
  visibility: visible;
}
.add-fav {
	width: auto;
	display: inline-block;
}
</style>
<link rel="stylesheet" href="{{ asset('../front/css/calendar.min.css') }}">
<script src="{{ asset('../front/js/calendar.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('../front/js/rAF.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('../front/js/ResizeSensor.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('../front/js/sticky-sidebar.min.js') }}"></script>

<script>
$(document).ready(function() {

<?php if($recommended_provider->count() == 0){?>
$('#favorited_by_provider_hide').hide();
<?php } else {?>
$('#favorited_by_provider_hide').show();
<?php }?>
<?php if($recommended_authlete->count() == 0){?>
$('#favorited_by_athelete_hide').hide();
<?php } else {?>
$('#favorited_by_athelete_hide').show();
<?php }?>
		hrefurl=$(location).attr("href");
		last_part=hrefurl.substr(hrefurl.lastIndexOf('#') + 1);
		
		if(last_part !=''){
			$('.nav-tabs a[href="#' + last_part + '"]').tab('show');
		}

		if(last_part == 'nav-resourcess'){
			$('.nav-tabs a[href="#nav-resources"]').tab('show');
		}

		$(".share-btn").click(function (e) {
		$('.networks-5').not($(this).next(".networks-5")).each(function () {
		  $(this).removeClass("active");
		});

		$(this).next(".networks-5").toggleClass("active");
	  });
	});
	$('#videoPlayModal').one('hidden.bs.modal, hide.bs.modal', function(){$('#videoPlayControl').attr('src',''); });
	var videoThumb = $('#play_btn').parent().parent().find("img");
	$('#play_btn').on('click', function(){
		var video_src = videoThumb.attr("video");
		var VideoSource ="";
		if(videoThumb.attr("video_type")==1){
			VideoSource ="{{ url('public/front/profile') }}"+'/'+video_src;
		}else{VideoSource = "//www.youtube.com/embed/"+video_src+"?autoplay=1";}
		$('#videoPlayControl').attr('src', VideoSource);
		$('#videoPlayModal').modal('show');
	})

$("#reset").click(function(){
$('#resource_search').val(2);

});

	$('.resource-popup').on('click', function(e){

		var file_type = $(this).attr('data-format');
		var file_src = $(this).attr('data-src');
		var id = "#"+file_src;
		$(id).modal('show');
			
	   
	});




	$(document).on('click', '.close-video', function(event) {
			var wrapper = $("#video-modal");
			$(wrapper).html('').hide(); 
			});
		$(document).on('click', '.provider-bio a', function(event) {
			var href= $(this).attr("href"); 
		   var matches = href.match(/^(?:https?:\/\/)?(?:www\.)?youtube\.com\?(?=.*v=((\w|-){11}))(?:\S+)?$/);
if (href.indexOf('https://www.youtube.com') > -1) 
{
   var wrapper = $("#video-modal");
		   
			var  customFrame =  '<a style="color: #fff;float: right;cursor: pointer;position: absolute;top: 0;right: 10px;" class="close-video">X</a><iframe src='+href+' width="100%" height="100%" frameborder="0" frameborder="0" allowfullscreen></iframe>';
			 $(wrapper).html(customFrame).show();  
			event.preventDefault();  
}
		   
		});
		function removerecommended(id) {
	if (confirm("Are you sure?")){
		@if (isset(Auth::guard('front_auth')->user()->user_role))
		 @if (Auth::guard('front_auth')->user()->user_role == 'customer')
				var url = '{{route("customer.addrecommended")}}';
			@else
				var url = '{{route("trainer.addrecommended")}}';
				@endif
	$.ajax({
	type: 'POST',
			url: url,
			data: {id: id, 'type':'remove'},
			headers: {
			'X-CSRF-TOKEN': '{{ csrf_token() }}'
			},
			success: function (result) {
			var data = $.parseJSON(result);
					if (data.status){
			new PNotify({
			title: data.message,
					text: '',
					type: 'success'
			});
					$('#addrecommended_id_' + id).fadeOut(300, function(){ $(this).remove(); });
					 //$( ".heart.fa" ).removeClass( "fa-heart-o" ).addClass( "fa-heart" );
					
				   
			} else{
			new PNotify({
			title: data.message,
					text: '',
					type: 'error'
			});
			}
			$('#remove_enable').hide();
					$('#add_enable').show();
			//$(".heart.fa").removeClass("fa-heart-o");

			//$(".heart.fa").addClass("fa-heart");
			//alert(data.authlete_popup_count);
					if(data.athelete_count ==0){
						$("#favorited_by_athelete_hide").hide();

					} else {
						$("#favorited_by_athelete_hide").show();
						$("#favorited_by_athelete").html(data.favorited_by_athelete);
						if(data.authlete_popup_count !=1){
						
							$("#favorited_count").html(data.authlete_popup_count);
							$("#recommendedviewallpopup").html(data.authlete_popup_details);
							
						}
					}

					

					if(data.provider_count ==0){
						$("#favorited_by_provider_hide").hide();
					} else {
						$("#favorited_by_provider_hide").show();
						$("#favorited_by_provider").html(data.favorited_by_provider);
						if(data.provider_popup_count !=1){
						
							$("#provider_count").html(data.provider_popup_count);
							$("#recommendedviewallProviderpopup").html(data.provider_popup_details);
							
						}
					}
					 
					
					 //$("#favoritess").html(data.favoritess);
					 //location.reload();
					return true;
			},
			error: function (xhr, ajaxOptions, thrownError) {

			}
	});
	@else
				window.location.href = "{{url('login')}}";
		@endif
	}
	}
function addrecommended(id) {
		@if (isset(Auth::guard('front_auth')->user()->user_role))
			@if (Auth::guard('front_auth')->user()->user_role == 'customer')
				var url = '{{route("customer.addrecommended")}}';
			@else
				var url = '{{route("trainer.addrecommended")}}';
				@endif
			   
		$.ajax({
			type: 'POST',
					url: url,
					data: {id: id, 'type':'add'},
					headers: {
					'X-CSRF-TOKEN': '{{ csrf_token() }}'
					},
					success: function (result) {
					var data = $.parseJSON(result);
							if (data.status){
					new PNotify({
					title: data.message,
							text: '',
							type: 'success'
					});
						 $('#addrecommended_id_' + id).fadeOut(300, function(){ $(this).remove(); }); 
					} else{
					new PNotify({
					title: data.message,
							text: '',
							type: 'error'
					});
					}
					$('#remove_enable').show();
					$('#add_enable').hide();
					//$( ".heart.fa" ).removeClass( "fa-heart" ).addClass( "fa-heart-o" );
					//$(".heart.fa").removeClass("fa-heart");
					//$(".heart.fa").addClass("fa-heart-o");
					
					if(data.athelete_count ==0){
						$("#favorited_by_athelete_hide").hide();

					} else {
					
						$("#favorited_by_athelete_hide").show();
						$("#favorited_by_athelete").html(data.favorited_by_athelete);
						if(data.authlete_popup_count !=1){
						
							$("#favorited_count").html(data.authlete_popup_count);
							$("#recommendedviewallpopup").html(data.authlete_popup_details);
							//$("#recommendedviewall").modal();
						}
					}


					if(data.provider_count ==0){
						$("#favorited_by_provider_hide").hide();
					} else {
					
						$("#favorited_by_provider_hide").show();
						$("#favorited_by_provider").html(data.favorited_by_provider);
						if(data.provider_popup_count !=1){
						
							$("#provider_count").html(data.provider_popup_count);
							$("#recommendedviewallProviderpopup").html(data.provider_popup_details);
							
						}
					}
					//$("#favoritess").html(data.favoritess);
					//location.reload();
					 return true;

					},
					error: function (xhr, ajaxOptions, thrownError) {

					}
			});
	 
			@else
				window.location.href = "{{url('login')}}";
		@endif
			 
			}
	$('.phone_number').html(changeNumberFormat('{{$trainerData->phone_number}}'));
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

	$(".rettingclass").click(function() {
		$('html, body').animate({
			scrollTop: $(".reviews-retting-section").offset().top
		}, 2000);
	});

	$(document).on('click', '.review-btn', function(event) {
		@if(Auth::guard('front_auth')->user())
			@if(Auth::guard('front_auth')->user()->user_role == 'trainer')
				alert("Provider can not be give a review to other provider !");
				event.preventDefault();
			@endif
		@endif
	});
/* Calendar Customization */
document.addEventListener('DOMContentLoaded', function() {
	var calendarEl = document.getElementById('trainerCalendar');

	var calendar = new FullCalendar.Calendar(calendarEl, {
		plugins: [ 'interaction', 'dayGrid', 'timeGrid', 'list' ],
		timeZone: 'UTC',
		defaultView: 'dayGridMonth',
		header: {
		left: 'prev,next today',
		center: 'title',
		right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
		},
		editable: true,
		eventLimit: true,
		
		/* EVENT FORMAT */
		events: <?php echo $eventData; ?>,
		eventRender: function (info) {
		   var title = info.event.title;
		   var desc = info.event._def.extendedProps.description;
	  
			$(info.el).popover({
			  html: true, 
			  title: title,
			  placement:'top',
			  trigger : 'hover',
			  content: desc,
			  container:'body',
			}).popover('show');
		}
	});

	calendar.render();
});
var a = new StickySidebar('#sidebar', {
			topSpacing: 90,
			bottomSpacing: 20,
			containerSelector: '.container',
			innerWrapperSelector: '.sidebar__inner'
		});

// rating slider

   $(function() {
		  $('.owl-carousel.testimonial-carousel').owlCarousel({
			nav: true,
			navText: ['<i class="fa fa-chevron-left"></i>', '<i class="fa fa-chevron-right"></i>'],
			dots: false,
			responsive: {
			  0: {
				items: 1,
			  },
			  750: {
				items: 2,
			  }
			}
		  });
		});

function like_resource(resource_id){
	  
		$.ajax({
		  url: "{{url('resource-like')}}/"+resource_id,
		  type: 'GET',
		  success: function(res) {
		  
		  if(res.like == 0 && res.dislike == 0){
			$("#like_resource"+resource_id).fadeIn("fast").attr('src','/public/front/images/like-light.png');  
			$("#dislike_resource"+resource_id).fadeIn("fast").attr('src','/public/front/images/dislike-light.png');   
			$('#dislike_resource_count'+resource_id).html(res.resource_dislike_count); 
			$('#like_resource_count'+resource_id).html(res.resource_like_count);
		  } else if(res.like == 1 && res.dislike == 0){
			$("#like_resource"+resource_id).fadeIn("fast").attr('src','/public/front/images/like.png');   
			$("#dislike_resource"+resource_id).fadeIn("fast").attr('src','/public/front/images/dislike-light.png'); 
			$('#dislike_resource_count'+resource_id).html(res.resource_dislike_count); 
			$('#like_resource_count'+resource_id).html(res.resource_like_count);  
		  } else if(res.like == 0 && res.dislike == 1){
			$("#dislike_resource"+resource_id).fadeIn("fast").attr('src','/public/front/images/dislike.png');
			$("#like_resource"+resource_id).fadeIn("fast").attr('src','/public/front/images/like-light.png');  
			$('#dislike_resource_count'+resource_id).html(res.resource_dislike_count); 
			$('#like_resource_count'+resource_id).html(res.resource_like_count);
		  }
				 
			
			$('#resource_like_name'+resource_id).html(res.like_name);
			$('#resource_dislike_name'+resource_id).html(res.dislike_name);
			  
		  }
	  });

	}

	function like_resource_counts(resource_id){
	  var like_resource_counts = $('#like_resource_count'+resource_id).html();

	  if(like_resource_counts !=0){
		$('#likes'+resource_id).modal('show');
	  }

	}

	function dislike_resource(resource_id){
	  
		$.ajax({
		  url: "{{url('resource-dislike')}}/"+resource_id,
		  type: 'GET',
		  success: function(res) {
		  
		  if(res.like == 0 && res.dislike == 0){
			$("#like_resource"+resource_id).fadeIn("fast").attr('src','/public/front/images/like-light.png');  
			$("#dislike_resource"+resource_id).fadeIn("fast").attr('src','/public/front/images/dislike-light.png');  
			$('#dislike_resource_count'+resource_id).html(res.resource_dislike_count); 
			$('#like_resource_count'+resource_id).html(res.resource_like_count);
		  } else if(res.like == 1 && res.dislike == 0){
			$("#like_resource"+resource_id).fadeIn("fast").attr('src','/public/front/images/like.png');   
			$("#dislike_resource"+resource_id).fadeIn("fast").attr('src','/public/front/images/dislike-light.png'); 
			$('#dislike_resource_count'+resource_id).html(res.resource_dislike_count); 
			$('#like_resource_count'+resource_id).html(res.resource_like_count);  
		  } else if(res.like == 0 && res.dislike == 1){
			$("#dislike_resource"+resource_id).fadeIn("fast").attr('src','/public/front/images/dislike.png');
			$("#like_resource"+resource_id).fadeIn("fast").attr('src','/public/front/images/like-light.png');  
			$('#dislike_resource_count'+resource_id).html(res.resource_dislike_count); 
			$('#like_resource_count'+resource_id).html(res.resource_like_count);
		  }
				 
			$('#resource_like_name'+resource_id).html(res.like_name);
			$('#resource_dislike_name'+resource_id).html(res.dislike_name);
			  
		  }
	  });

	}

	function dislike_resource_counts(resource_id){
	  var dislike_resource_counts = $('#dislike_resource_count'+resource_id).html();

	  if(dislike_resource_counts !=0){
		$('#dislikes'+resource_id).modal('show');
	  }

	}

	function save_resource(resource_id){
	  
		$.ajax({
		  url: "{{url('resource-save')}}/"+resource_id,
		  type: 'GET',
		  success: function(res) {
		  
			if(res.saved == 0){
			  $("#save_resource"+resource_id).fadeIn("fast").attr('src','/public/front/images/save-light.png');     
			  $('#save_resource_name'+resource_id).html('SAVE');
			} else {
			  $("#save_resource"+resource_id).fadeIn("fast").attr('src','/public/front/images/save.png');    
			  $('#save_resource_name'+resource_id).html('UNSAVE'); 
			}
		  }
	  });

	}

$("#reset").keypress(function(event){
alert('saasas');
	if(event.keyCode == 13) {
		alert('assaas');
	}
});
</script>

<script type="text/javascript">
	$('#searchReset').on('click', function(){
		if($('#keyword').val() != '' || $('#category').val() != '' || $('#format').val() != ''){
		$('#category option:selected').removeAttr('selected');
		$('#format option:selected').removeAttr('selected');
		$('#keyword').removeAttr('value');
		$('#search_resource_clear')[0].reset();
		$('#formSubmit').click();
	}
	});    

	$('#request_book_now').click(function(){
    var request = confirm("Are you sure you want to request to book?");
    if(request == true){
    	alert('You will receive an email confirmation once this provider has reviewed your information and confirmed or denied this request.');
    	return true;
    } else {
    	return false;
    }
});      
</script>


@endsection

