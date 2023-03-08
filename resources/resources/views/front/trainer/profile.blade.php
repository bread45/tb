@extends('front.trainer.layout.trainer')
@section('title', 'Profile')
@section('content')

<div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
        <div class="container-fluid">
            <div class="page-title d-flex align-items-center justify-content-between mb-lg-4 mb-3 pb-lg-3 flex-wrap">
                <a href="javascript:void(0);" class="menu-trigger d-lg-none d-flex order-0">
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                </a>
                <h1 class="h3 text-muted font-weight-normal mb-0 mt-lg-0 mt-3 order-lg-0 order-2">Edit Profile</h1>

                @include('front.trainer.layout.includes.header')
            </div>

            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body p-lg-5">
                            @if (session('message'))
                            <div class="alert alert-success">
                                {{ session('message') }}
                            </div>
                            @endif
                            @if (session('warning'))
                            <div class="alert alert-warning">
                                {{ session('warning') }}
                            </div>
                            @endif
                            <form class="form-row add-service-form" method="POST" action="{{route('front.update.profile')}}" enctype="multipart/form-data" name="edit_profile" onsubmit="return validateform()">
                                @csrf
                                <input type="hidden" name="role_type" value="trainer" />
                                        
                                   
<!--                                <div class="col-lg-12 horizontal-line">
                                <hr/> 
                                </div>-->
                                <div class="form-group col-lg-6">
                                    <label for="name-input">Business Name</label>
                                    <input type="text" class="form-control" name="business_name" id="business-input" placeholder="enter business name" value="{{ $user->business_name}}">
                                    @if ($errors->has('business_name'))
                                    <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('business_name') }}</div>
                                    @endif
                                </div>
                                <div class="form-group col-lg-6">
                                   
                                </div>
                                <div class="col-lg-12 horizontal-line">
                                <hr/>
                                <!--<p>OR</p>-->
                                </div>
<!--                                <div class="form-group col-lg-6">
                                    <label for="name-input">First Name</label>
                                    <input type="text" class="form-control" name="first_name" id="name-input" placeholder="enter first name"  value="{{ $user->first_name}}">
                                    @if ($errors->has('first_name'))
                                    <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('first_name') }}</div>
                                    @endif
                                </div>
                                <div class="form-group col-lg-6">
                                    <label for="last-name-input">Last Name</label>
                                    <input type="text" class="form-control" name="last_name" id="last-name-input" placeholder="enter last name"  value="{{ $user->last_name}}">
                                    @if ($errors->has('last_name'))
                                    <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('last_name') }}</div>
                                    @endif
                                </div>-->
                               

                                
                                <div class="form-group col-lg-6">
                                    <label for="mail-input">Email</label>
                                    <input type="email" class="form-control" name="email" id="mail-input" placeholder="enter email" readonly required value="{{ $user->email}}">
                                </div>
                                <div class="form-group col-lg-6">
                                    <label for="number-input">Phone Number</label>
                                    <input type="tel" class="form-control" name="phone_number" id="number-input" placeholder="enter phone number" required pattern="^(\+\d{1,2}[\s.-]?)?\(?\d{3}\)?[\s.-]?\d{3}[\s.-]?\d{4}$" value="{{ $user->phone_number}}">
                                    @if ($errors->has('phone_number'))
                                    <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('phone_number') }}</div>
                                    @endif
                                </div>

                                 

 
                                <div class="form-group col-lg-6">
                                    <label for="address-input">Address Line 1</label>
                                    <input type="text" class="form-control" name="address_1" id="address-input" placeholder="enter address" value="{{ $user->address_1}}">
                                    @if ($errors->has('address_1'))
                                    <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('address_1') }}</div>
                                    @endif
                                </div>

                               


                                <div class="form-group col-lg-6">
                                    <label for="address1-input">Address Line 2</label>
                                    <input type="text" class="form-control" name="address_2" id="address1-input" placeholder="enter address" value="{{ $user->address_2}}">
                                    @if ($errors->has('address_2'))
                                    <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('address_2') }}</div>
                                    @endif
                                </div>
                                <div class="form-group col-lg-6">
                                    <label for="name-input">City</label>
                                    <input type="text" class="form-control" name="city" id="city-input" placeholder="enter city" value="{{ $user->city }}" required>
                                    @if ($errors->has('city'))
                                    <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('city') }}</div>
                                    @endif
                                    <!--<select class="form-control locationselect" multiple="" name="providerlocations[]" required="">
                                        
                                        @foreach($locations as $location)
                                        <option @if(in_array($location->id,$providerlocations)) selected="" @endif value="{{$location->id}}">{{$location->name}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('first_name'))
                                    <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('first_name') }}</div>
                                    @endif-->
                                </div>
<!--                                <div class="form-group col-lg-6">
                                    <label for="city-input">City</label>
                                    <input type="text" class="form-control" name="city" id="city-input" placeholder="enter city" value="{{ $user->city }}" required>
                                    @if ($errors->has('city'))
                                    <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('city') }}</div>
                                    @endif
                                </div>-->
                                <div class="form-group col-lg-6">
                                    <label for="state-input">State</label>
                                    <select name="state" class="form-control" id="state-input" required>
                                        <option value="">select state</option>
                                        @foreach ($states as $state)
                                        <option @if($user->state == $state->name) selected @endif value="{{$state->name}}">{{$state->name}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('state'))
                                    <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('state') }}</div>
                                    @endif
                                </div>
                                <div class="form-group col-lg-6">
                                    <label for="country-input">Country</label>
                                    <select name="country" class="form-control" id="country-input" required>
                                        <option value="">select country</option>
                                        <!-- @foreach ($countries as $country)
                                            <option @if($user->country == $country->country_name) selected @endif value="{{ $country->country_name }}">{{ $country->country_name }}</option>
                                        @endforeach -->
                                        <option @if($user->country == 'USA') selected @endif value="USA">USA</option>
                                    </select>
                                    @if ($errors->has('country'))
                                    <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('country') }}</div>
                                    @endif
                                </div>
                                
                                <div class="form-group col-lg-6">
                                    <label for="zipcode-input">Zip Code</label>
                                    <input type="text" class="form-control" name="zip_code" id="zipcode-input" placeholder="enter zip code" value="{{ $user->zip_code }}" pattern="^(\d{5})?$">
                                    @if ($errors->has('zip_code'))
                                    <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('zip_code') }}</div>
                                    @endif
                                </div>
                                <div class="form-group col-lg-6">
                                    <label for="fb-input">Facebook</label>
                                    <input type="url" class="form-control" name="fb" id="fb-input" placeholder="enter facebook url" value="{{ $user->facebook }}">
                                    @if ($errors->has('fb'))
                                    <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('fb') }}</div>
                                    @endif
                                </div>
                                <div class="form-group col-lg-6">
                                    <label for="inst-input">Instagram</label>
                                    <input type="url" class="form-control" name="insta" id="insta-input" placeholder="enter instagram url" value="{{ $user->instagram }}">
                                    @if ($errors->has('insta'))
                                    <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('insta') }}</div>
                                    @endif
                                </div>
                                <div class="form-group col-lg-6">
                                    <label for="linkedin-input">LinkedIn</label>
                                    <input type="url" class="form-control" name="linkedin" id="linkedin-input" placeholder="enter linkedin url" value="{{ $user->linkedin }}">
                                    @if ($errors->has('linkedin'))
                                    <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('linkedin') }}</div>
                                    @endif
                                </div>
<!--                                <div class="form-group col-lg-6">
                                    <label for="twitter-input">Twitter</label>
                                    <input type="url" class="form-control" name="twitter" id="twitter-input" placeholder="enter twitter url" value="{{ $user->twitter }}">
                                    @if ($errors->has('twitter'))
                                    <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('twitter') }}</div>
                                    @endif
                                </div>-->
                                <div class="form-group col-lg-6">
                                    <label for="website-input">Website</label>
                                    <input type="text" class="form-control" name="website" id="website-input" placeholder="enter website url" value="{{ $user->website }}">
                                    @if ($errors->has('website'))
                                    <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('website') }}</div>
                                    @endif
                                </div>
<div class="form-group col-lg-12">
                                            <?php
                                            $TrainerPhotoSeq=[null,null,null,null];
                                            if(isset($TrainerPhoto[0]->image)){$imgPos =($TrainerPhoto[0]->position >-1? ($TrainerPhoto[0]->position -1) : 0); $TrainerPhotoSeq[$imgPos] = $TrainerPhoto[0];}
                                            if(isset($TrainerPhoto[1]->image)){$imgPos =($TrainerPhoto[1]->position >-1? ($TrainerPhoto[1]->position -1) : 1); $TrainerPhotoSeq[$imgPos] = $TrainerPhoto[1];}
                                            if(isset($TrainerPhoto[2]->image)){$imgPos =($TrainerPhoto[2]->position >-1? ($TrainerPhoto[2]->position -1) : 2); $TrainerPhotoSeq[$imgPos] = $TrainerPhoto[2];}
                                            if(isset($TrainerPhoto[3]->image)){$imgPos =($TrainerPhoto[3]->position >-1? ($TrainerPhoto[3]->position -1) : 3); $TrainerPhotoSeq[$imgPos] = $TrainerPhoto[3];}
                                            // echo "<pre>";print_r($TrainerPhotoSeq); echo "</pre>";
                                            // echo "<pre>";print_r($TrainerPhoto); echo "</pre>";
                                            $TrainerPhoto0=[];
                                            if(isset($TrainerPhoto[0]->image)){$TrainerPhoto0= $TrainerPhoto[0];unset($TrainerPhoto[0]);}
                                            elseif(isset($TrainerPhoto[1]->image) && $TrainerPhoto[1]->is_video ==1){$TrainerPhoto0= $TrainerPhoto[1];unset($TrainerPhoto[1]);}
                                            elseif(isset($TrainerPhoto[2]->image) && $TrainerPhoto[2]->is_video ==1){$TrainerPhoto0= $TrainerPhoto[2];unset($TrainerPhoto[2]);}
                                            elseif(isset($TrainerPhoto[3]->image) && $TrainerPhoto[3]->is_video ==1){$TrainerPhoto0= $TrainerPhoto[3]; unset($TrainerPhoto[3]);}
                                            foreach ($TrainerPhoto as $key ) {
                                               // echo "<pre>"; print_r($key);
                                            }
                                            ?>
                                    <div class="row">
                                        <div class="col-lg-3">
                                        <label for="website-input">Profile Image or Video</label>
                                            @if(!isset($TrainerPhotoSeq[0]->image))
                                            
                                            
                                            <div class="trainer_video_div_1" style="display: none;"><input type="text" name="trainer_image[]" id="trainer_video_1" data-id="1" data-key="1" class="form-control trainer_video"></div>

                                            @endif
                                            <div class="upload-input">
                                                <div class="upload-input-img trainer_image_div_1" style="background-image:none;height: auto;margin: 0 auto;text-align: center;font-size: 24px;">
                                                    @if(isset($TrainerPhotoSeq[0]->image))
                                                    <?php
                                                        $imagePath = $TrainerPhotoSeq[0]->image;
                                                        $videoPath = $TrainerPhotoSeq[0]->image;
                                                        if($TrainerPhotoSeq[0]->is_video){
                                                            $imagePath = preg_replace('/.mp4|.mov/i', '.jpg', $videoPath);
                                                            $imagePath = preg_replace('/profile_video/i', 'profile_image', $imagePath);
                                                        }
                                                    ?>
                                                    <img  width="325" style="padding: 0px;" src="{{ asset('front/profile/'.$imagePath)}}" alt = "trainer">
                                                    <div  class="black-bg-section"><p>change</p></div>
                                                    
                                                    
                                                    
                                                    <div class="edit-link delete-image" data-id="{{$TrainerPhotoSeq[0]->id}}" style="font-size: 13px;color: #000;text-align: center;cursor: pointer;background-image: none">X</div> 
                                                    @else
                                                    <img   style="padding: 0px;" src="{{ asset('front/profile/placeholder_profile_video.png')}}" alt = "profile image">
                                                    
                                                    @endif
                                                </div>
                                                @if(isset($TrainerPhotoSeq[0]->image))
                                                <input type="file" name="trainer_image[{{$TrainerPhotoSeq[0]->id}}]" data-key="{{$TrainerPhotoSeq[0]->id}}" data-key="1" id="trainer_image_1" data-id="1" class="form-control trainer_image" >
                                                @else
                                                <input type="file" name="trainer_image[]" id="trainer_image_1" data-id="1" data-key="1" class="form-control trainer_image" >

                                            @endif

                                            </div>
                                            @if(!isset($TrainerPhotoSeq[0]->image))
                                                <input type="radio" name="video_type" id="video_type_File" value="File" checked="checked"><label style="font-size: 14px;margin-left: 3px;" for="video_type_File"> File System</label>
                                                <input type="radio" name="video_type" id="video_type_Cloud" value="Cloud"><label style="font-size: 14px;margin-left: 3px;" for="video_type_Cloud">Online URL</label>
                                            @endif
                                             @if(isset($TrainerPhotoSeq[0]->image))
                                            <div class="row">
                                                @if(!$TrainerPhotoSeq[0]->is_video == 1)
                                                        <div class="col-lg-12 d-flex mt-3">
                                                            <input type="checkbox"   name="featured_image[{{$TrainerPhotoSeq[0]->id}}]" id='featured_image_1' class='form-control featured_image mt-0 mr-2' @if($TrainerPhotoSeq[0]->is_featured == 1) checked="" @endif >
                                                            
                                                    <label for="featured_image_4" class="featured_text">Featured Image</label> 
                                                        </div>
                                                        @endif
<!--                                                         <div class="col-lg-12 d-flex mt-3"><input type="checkbox"   name="profile_image[{{$TrainerPhotoSeq[0]->id}}]" id='profile_image_4' class='form-control profile_image mt-0 mr-2' @if($TrainerPhotoSeq[0]->image == $user->photo) checked="" @endif >
                                                    <label for="profile_image_4" class="featured_text">Profile Image</label> 
                                                    </div> -->
                                                    </div>
                                             @endif
                                        </div>
                                        <div class="col-lg-3">
                                        <label for="website-input">Profile Image</label>
                                            <div class="upload-input">
                                                <div class="upload-input-img trainer_image_div_2" style="background-image:none;height: auto;margin: 0 auto;text-align: center;font-size: 24px;">
                                                    @if(isset($TrainerPhotoSeq[1]->image))
                                                    <img  width="325"  style="padding: 0px;" src="{{ asset('front/profile/'.$TrainerPhotoSeq[1]->image)}}" alt="trainer">
                                                    <div  class="black-bg-section"><p>change</p></div>
                                                    
                                                    
                                                    
                                                    <div class="edit-link delete-image" data-id="{{$TrainerPhotoSeq[1]->id}}" style="font-size: 13px;color: #000;text-align: center;cursor: pointer;background-image: none">X</div> 
                                                    @else
                                                    <img  style="padding: 0px;" src="{{ asset('front/profile/placeholder_profile.png')}}" alt="placeholder">
                                                    
                                                    @endif
                                                </div>
                                                 @if(isset($TrainerPhotoSeq[1]->image))
                                                <input type="file" name="trainer_image[{{$TrainerPhotoSeq[1]->id}}]" id="trainer_image_2" data-key="{{$TrainerPhotoSeq[1]->id}}" data-id="2" class="form-control trainer_image" >
                                                @else
                                                <input type="file" name="trainer_image[]" id="trainer_image_2" data-id="2" data-key="2" class="form-control trainer_image" >
                                            @endif
                                            </div>
                                            @if(isset($TrainerPhotoSeq[1]->image))
                                            <div class="row">
                                                        <div class="col-lg-12 d-flex mt-3"><input type="checkbox"   name="featured_image[{{$TrainerPhotoSeq[1]->id}}]" id='featured_image_2' class='form-control featured_image mt-0 mr-2' @if($TrainerPhotoSeq[1]->is_featured == 1) checked="" @endif >
                                                    <label for="featured_image_2" class="featured_text">Featured Image</label> </div>
<!--                                                        <div class="col-lg-12 d-flex mt-3"><input type="checkbox"   name="profile_image[{{$TrainerPhotoSeq[1]->id}}]" id='profile_image_2' class='form-control profile_image mt-0 mr-2' @if($TrainerPhotoSeq[1]->image == $user->photo) checked="" @endif >
                                                    <label for="profile_image_2" class="featured_text">Profile Image</label> 
                                                    </div>-->
                                                    </div>
                                            @endif
                                        </div>
                                        <div class="col-lg-3">
                                        <label for="website-input">Profile Image</label>
                                            <div class="upload-input">
                                                <div class="upload-input-img trainer_image_div_3" style="background-image:none;height: auto;margin: 0 auto;text-align: center;font-size: 24px;">
                                                    @if(isset($TrainerPhotoSeq[2]->image))
                                                    <img  width="325" style="padding: 0px;" src="{{ asset('front/profile/'.$TrainerPhotoSeq[2]->image)}}" alt = "trainer">
                                                    <div  class="black-bg-section"><p>change</p></div>
                                                    
                                                    
                                                    <div class="edit-link delete-image" data-id="{{$TrainerPhotoSeq[2]->id}}" style="font-size: 13px;color: #000;text-align: center;cursor: pointer;background-image: none">X</div> 
                                                    @else
                                                    <img  style="padding: 0px;" src="{{ asset('front/profile/placeholder_profile.png')}}" alt = "placeholder">
                                                    
                                                    @endif
                                                    </div>
                                                @if(isset($TrainerPhotoSeq[2]->image))
                                                <input type="file" name="trainer_image[{{$TrainerPhotoSeq[2]->id}}]" data-key="{{$TrainerPhotoSeq[2]->id}}" id="trainer_image_3" data-id="3" class="form-control trainer_image" >
                                                @else
                                                <input type="file" name="trainer_image[]" id="trainer_image_3" data-id="3" data-key="3" class="form-control trainer_image" >
                                             @endif
                                            </div>
                                            @if(isset($TrainerPhotoSeq[2]->image))
                                            <div class="row">
                                                        <div class="col-lg-12 d-flex mt-3">
                                                            <input type="checkbox"   name="featured_image[{{$TrainerPhotoSeq[2]->id}}]" id='featured_image_3' class='form-control featured_image mt-0 mr-2' @if($TrainerPhotoSeq[2]->is_featured == 1) checked="" @endif >
                                                    <label for="featured_image_3" class="featured_text">Featured Image</label> 
                                                        </div>
                                                    </div>
                                            @endif
                                        </div>
                                        
                                        <!-- Image Upload 4 -->
                                        <div class="col-lg-3">
                                        <label for="website-input">Profile Image</label>
                                    <div class="upload-input">
                                                <div class="upload-input-img trainer_image_div_4" style="background-image:none;height: auto;margin: 0 auto;text-align: center;font-size: 24px;">
                                                    @if(isset($TrainerPhotoSeq[3]->image))
                                                    <img  width="325" style="padding: 0px;" src="{{ asset('front/profile/'.$TrainerPhotoSeq[3]->image)}}" alt = "trainer">
                                                    <div  class="black-bg-section"><p>change</p></div>
                                                    
                                                    
                                                    <div class="edit-link delete-image" data-id="{{$TrainerPhotoSeq[3]->id}}" style="font-size: 13px;color: #000;text-align: center;cursor: pointer;background-image: none">X</div> 
                                                    @else
                                                    <img  style="padding: 0px;" src="{{ asset('front/profile/placeholder_profile.png')}}" alt = "placeholder">
                                                     
                                                    @endif
                                                    
                                    </div>
                                         
                                                @if(isset($TrainerPhotoSeq[3]->image))
                                                <input type="file" name="trainer_image[{{$TrainerPhotoSeq[3]->id}}]" data-key="{{$TrainerPhotoSeq[3]->id}}" id="trainer_image_4" data-id="4" class="form-control trainer_image" >
                                                @else
                                                <input type="file" name="trainer_image[]" id="trainer_image_4" data-id="4" data-key="4" class="form-control trainer_image" >
                                                @endif
                                </div>
                                            @if(isset($TrainerPhotoSeq[3]->image))
                                        <div class="row">
                                                        <div class="col-lg-12 d-flex mt-3"><input type="checkbox"   name="featured_image[{{$TrainerPhotoSeq[3]->id}}]" id='featured_image_4' class='form-control featured_image mt-0 mr-2' @if($TrainerPhotoSeq[3]->is_featured == 1) checked="" @endif >
                                                    <label for="featured_image_4" class="featured_text">Featured Image</label> 
                                                    </div>
<!--                                                        <div class="col-lg-12 d-flex mt-3"><input type="checkbox"   name="profile_image[{{$TrainerPhotoSeq[3]->id}}]" id='profile_image_4' class='form-control profile_image mt-0 mr-2' @if($TrainerPhotoSeq[3]->image == $user->photo) checked="" @endif >
                                                    <label for="profile_image_4" class="featured_text">Profile Image</label> 
                                                    </div>-->
                                                    </div>
                                         @endif
                                        </div>
                                    </div>                                    
                                </div>
<div class="form-group col-lg-12">
                                    <label for="headline-input">HeadLine (150 characters or less)</label>
                                    <textarea class="form-control" rows="5" cols="8" name="headline" placeholder="Give us an interesting overview of you & your business" id="headline-input" maxlength="150" style="min-height:139px" required>{{ $user->headline  }}</textarea>
                                    @if ($errors->has('headline'))
                                    <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('headline') }}</div>
                                    @endif
                                </div>
                                <div class="form-group col-lg-12">
                                    <label  for="bio-input">Bio</label>
                                    <textarea class="form-control ckeditor" rows="5" cols="100" maxlength="700" name="bio" id="bio-input" data-ml="400" required>{{ $user->bio  }}</textarea>
                                    @if ($errors->has('bio'))
                                    <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('bio') }}</div>
                                    @endif
                                </div>
                                 <!-- Hours of operation -->

                                        <div class="row form-group" style="padding-left: 15px; ">
                                           <div class="col-lg-12">
                                               <label >Hours of Operation </label><br />
                                              
                                           </div>
                                           <div class="container">
                                            <!-- Monday -->
                                             <label >Monday </label><br />
                                             <div class="col-lg-12">
                                               
                                          <div class="multi-field-wrapper">
                                            <div class="multi-fields">
                                                 <?php
                                                 if($user->day1 != "," && $user->day1 != NULL){
                                                 $day1Result = explode(",", $user->day1);
                                                 $day1Count = count($day1Result);
                                                 for ($i=0; $i<$day1Count; $i++) { 
                                                    if ($i%2==0) {
                                            ?> 
                                                <div class="multi-field form-group">
                                                 <input type="time" name="day1[]" class="form-control w-25 p-3" value="{{ $day1Result[$i] }}" style="float: left;margin-right:5px;" >
                                             <?php } else { ?>
                                                 <input type="time" name="day1[]" class="form-control w-25 p-3" value="{{ $day1Result[$i] }}" style="float: left;">
                                             
                                                 <button type="button" class="btn btn-danger remove-field ">☓</button>
                                                 <button type="button" class="add-field btn btn-info">Add Field</button>
                                                 <div class="clearfix"></div>
                                                 
                                                 
                                               </div>
                                            <?php
                                            } 
                                       }
                                           }
                                           else { ?>

                                                <div class="multi-field form-group">
                                                 <input type="time" name="day1[]" class="form-control w-25 p-3" value=""  style="float: left;margin-right:5px;" >
                                            
                                                 <input type="time" name="day1[]" class="form-control w-25 p-3" value="" style="float: left;">
                                             
                                                 <button type="button" class="btn btn-danger remove-field">☓</button>
                                                 <button type="button" class="add-field btn btn-info">Add Field</button>
                                                 <div class="clearfix"></div>
                                                 
                                                 
                                               </div>
                                        <?php    } 
                                         ?>
                                             </div>
                                           
                                         </div>
                                        </div>
                                        <hr />
                                        <!-- Tuesday -->
                                             <label >Tuesday </label><br />
                                            <div class="col-lg-12">
                                          <div class="multi-field-wrapper">
                                            <div class="multi-fields">

                                                   <?php
                                                 if($user->day2 != "," && $user->day2 != NULL){
                                                 $day2Result = explode(",", $user->day2);
                                                 $day2Count = count($day2Result);
                                                  for ($i=0; $i<$day2Count; $i++) { 
                                                    if ($i%2==0) {
                                            ?> 
                                                <div class="multi-field form-group">
                                                 <input type="time" name="day2[]" class="form-control w-25 p-3" value="{{ $day2Result[$i] }}" style="float: left;margin-right:5px; " >
                                                  <?php } else { ?>
                                                 <input type="time" name="day2[]" class="form-control w-25 p-3" value="{{ $day2Result[$i] }}" style="float: left;" >
                                                 <button type="button" class="btn btn-danger remove-field">☓</button>
                                                 <button type="button" class="add-field btn btn-info">Add Field</button>
                                                 <div class="clearfix"></div>
                                               </div>
                                               <?php 
                                              }
                                           }
                                           }
                                           else { ?>

                                                <div class="multi-field form-group">
                                                 <input type="time" name="day2[]" class="form-control w-25 p-3"  style="float: left;margin-right:5px;" >
                                            
                                                 <input type="time" name="day2[]" class="form-control w-25 p-3" style="float: left;">
                                             
                                                 <button type="button" class="btn btn-danger remove-field">☓</button>
                                                 <button type="button" class="add-field btn btn-info">Add Field</button>
                                                 <div class="clearfix"></div>
                                                 
                                                 
                                               </div>
                                        <?php    } 
                                         ?>
                                             </div>
                                           
                                         </div>
                                        </div>
                                         <hr />
                                         <!-- Wednesday -->
                                          <label >Wednesday </label><br />
                                            <div class="col-lg-12">
                                          <div class="multi-field-wrapper">
                                            <div class="multi-fields">
                                                    <?php
                                                 if($user->day3 != "," && $user->day3 != NULL){
                                                 $day3Result = explode(",", $user->day3);
                                                 $day3Count = count($day3Result);
                                                for ($i=0; $i<$day3Count; $i++) { 
                                                    if ($i%2==0) {
                                            ?> 
                                                <div class="multi-field form-group">
                                                 <input type="time" name="day3[]" class="form-control w-25 p-3" value="{{ $day3Result[$i] }}" style="float: left;margin-right:5px; " >
                                                 <?php } else { ?>
                                                 <input type="time" name="day3[]" class="form-control w-25 p-3" value="{{ $day3Result[$i] }}" style="float: left;" >
                                                 <button type="button" class="btn btn-danger remove-field">☓</button>
                                                 <button type="button" class="add-field btn btn-info">Add Field</button>
                                                 <div class="clearfix"></div>
                                               </div>
                                                   <?php 
                                             }
                                           }
                                           }else { ?>

                                                <div class="multi-field form-group">
                                                 <input type="time" name="day3[]" class="form-control w-25 p-3"  style="float: left;margin-right:5px;" >
                                            
                                                 <input type="time" name="day3[]" class="form-control w-25 p-3" style="float: left;">
                                             
                                                 <button type="button" class="btn btn-danger remove-field">☓</button>
                                                 <button type="button" class="add-field btn btn-info">Add Field</button>
                                                 <div class="clearfix"></div>
                                                 
                                                 
                                               </div>
                                        <?php    } 
                                         ?>
                                             </div>
                                           
                                         </div>
                                        </div>
                                         <hr />

                                         <!-- Thursday -->
                                         <label >Thursday </label><br />
                                            <div class="col-lg-12">
                                          <div class="multi-field-wrapper">
                                            <div class="multi-fields">
                                                        <?php
                                                 if($user->day4 != "," && $user->day4 != NULL){
                                                 $day4Result = explode(",", $user->day4);
                                                 $day4Count = count($day4Result);
                                                for ($i=0; $i<$day4Count; $i++) { 
                                                    if ($i%2==0) {
                                            ?> 
                                                <div class="multi-field form-group">
                                                 <input type="time" name="day4[]" class="form-control w-25 p-3" value="{{ $day4Result[$i] }}" style="float: left;margin-right:5px; " >
                                                  <?php } else { ?>
                                                 <input type="time" name="day4[]" class="form-control w-25 p-3" value="{{ $day4Result[$i] }}" style="float: left;" >
                                                 <button type="button" class="btn btn-danger remove-field">☓</button>
                                                 <button type="button" class="add-field btn btn-info">Add Field</button>
                                                 <div class="clearfix"></div>
                                               </div>
                                                   <?php 
                                            }
                                           }
                                           }
                                           else { ?>

                                                <div class="multi-field form-group">
                                                 <input type="time" name="day4[]" class="form-control w-25 p-3"  style="float: left;margin-right:5px;" >
                                            
                                                 <input type="time" name="day4[]" class="form-control w-25 p-3" style="float: left;">
                                             
                                                 <button type="button" class="btn btn-danger remove-field">☓</button>
                                                 <button type="button" class="add-field btn btn-info">Add Field</button>
                                                 <div class="clearfix"></div>
                                                 
                                                 
                                               </div>
                                        <?php    } 
                                         ?>
                                             </div>
                                           
                                         </div>
                                        </div>
                                         <hr />

                                         <!-- Friday -->
                                          <label >Friday </label><br />
                                            <div class="col-lg-12">
                                          <div class="multi-field-wrapper">
                                            <div class="multi-fields">
                                                      <?php
                                                 if($user->day5 != "," && $user->day5 != NULL){
                                                 $day5Result = explode(",", $user->day5);
                                                 $day5Count = count($day5Result);
                                             for ($i=0; $i<$day5Count; $i++) { 
                                                    if ($i%2==0) {
                                            ?> 
                                                <div class="multi-field form-group">
                                                 <input type="time" name="day5[]" class="form-control w-25 p-3" value="{{ $day5Result[$i] }}" style="float: left;margin-right:5px; " >
                                                  <?php } else { ?>
                                                 <input type="time" name="day5[]" class="form-control w-25 p-3" value="{{ $day5Result[$i] }}" style="float: left;" >
                                                 <button type="button" class="btn btn-danger remove-field">☓</button>
                                                 <button type="button" class="add-field btn btn-info">Add Field</button>
                                                 <div class="clearfix"></div>
                                               </div>
                                                       <?php 
                                             };
                                           }
                                           }else { ?>

                                                <div class="multi-field form-group">
                                                 <input type="time" name="day5[]" class="form-control w-25 p-3"  style="float: left;margin-right:5px;" >
                                            
                                                 <input type="time" name="day5[]" class="form-control w-25 p-3" style="float: left;">
                                             
                                                 <button type="button" class="btn btn-danger remove-field">☓</button>
                                                 <button type="button" class="add-field btn btn-info">Add Field</button>
                                                 <div class="clearfix"></div>
                                                 
                                                 
                                               </div>
                                        <?php    } 
                                         ?>
                                             </div>
                                           
                                         </div>
                                        </div>
                                         <hr />

                                         <!-- Saturday -->
                                          <label >Saturday </label><br />
                                            <div class="col-lg-12">
                                          <div class="multi-field-wrapper">
                                            <div class="multi-fields">
                                                      <?php
                                                 if($user->day6 != "," && $user->day6 != NULL){
                                                 $day6Result = explode(",", $user->day6);
                                                 $day6Count = count($day6Result);
                                                for ($i=0; $i<$day6Count; $i++) { 
                                                    if ($i%2==0) {
                                            ?> 
                                                <div class="multi-field form-group">
                                                 <input type="time" name="day6[]" class="form-control w-25 p-3" value="{{ $day6Result[$i] }}" style="float: left;margin-right:5px; " >
                                                  <?php } else { ?>
                                                 <input type="time" name="day6[]" class="form-control w-25 p-3" value="{{ $day6Result[$i] }}" style="float: left;" >
                                                 <button type="button" class="btn btn-danger remove-field">☓</button>
                                                 <button type="button" class="add-field btn btn-info">Add Field</button>
                                                 <div class="clearfix"></div>
                                               </div>
                                                        <?php 
                                           }
                                           }
                                           }
                                           else { ?>

                                                <div class="multi-field form-group">
                                                 <input type="time" name="day6[]" class="form-control w-25 p-3"  style="float: left;margin-right:5px;" >
                                            
                                                 <input type="time" name="day6[]" class="form-control w-25 p-3" style="float: left;">
                                             
                                                 <button type="button" class="btn btn-danger remove-field">☓</button>
                                                 <button type="button" class="add-field btn btn-info">Add Field</button>
                                                 <div class="clearfix"></div>
                                                 
                                                 
                                               </div>
                                        <?php    } 
                                         ?>
                                             </div>
                                           
                                         </div>
                                        </div>
                                         <hr />

                                         <!-- Sunday -->
                                          <label >Sunday </label><br />
                                            <div class="col-lg-12">
                                          <div class="multi-field-wrapper">
                                            <div class="multi-fields">
                                                      <?php
                                                 if($user->day7 != "," && $user->day7 != NULL){
                                                 $day7Result = explode(",", $user->day7);
                                                 $day7Count = count($day7Result);
                                                for ($i=0; $i<$day7Count; $i++) { 
                                                    if ($i%2==0) {
                                            ?> 
                                                <div class="multi-field form-group">
                                                 <input type="time" name="day7[]" class="form-control w-25 p-3" value="{{ $day7Result[$i] }}" style="float: left;margin-right:5px; " >
                                                   <?php } else { ?>
                                                 <input type="time" name="day7[]" class="form-control w-25 p-3" value="{{ $day7Result[$i] }}" style="float: left;" >
                                                 <button type="button" class="btn btn-danger remove-field">☓</button>
                                                 <button type="button" class="add-field btn btn-info">Add Field</button>
                                                 <div class="clearfix"></div>
                                               </div>
                                                        <?php 
                                             }
                                           }
                                           }else { ?>

                                                <div class="multi-field form-group">
                                                 <input type="time" name="day7[]" class="form-control w-25 p-3"  style="float: left;margin-right:5px;" >
                                            
                                                 <input type="time" name="day7[]" class="form-control w-25 p-3" style="float: left;">
                                             
                                                 <button type="button" class="btn btn-danger remove-field">☓</button>
                                                 <button type="button" class="add-field btn btn-info">Add Field</button>
                                                 <div class="clearfix"></div>
                                                 
                                                 
                                               </div>
                                        <?php    } 
                                         ?>
                                             </div>
                                           
                                         </div>
                                        </div>
                                         <hr />

                                    </div>

                                        <!-- end of Hours of operation -->
<!--                                <div class="form-group col-lg-4">
                                    <label for="number-input">Upload Image</label>
                                    <div class="profile upload-input" >
                                        @if(!empty($user->photo))
                                        <div class="edit-img">
                                            <img id="show_image" src="@if(isset($user->photo) && !empty($user->photo)) {{ asset('front/profile/'.$user->photo)}} @else {{ asset('front/images/yoga-checkout.jpg')}}  @endif " >
                                            <div class="edit-link">Edit Image</div> 
                                        </div>
                                        <input type="file" name="photo" id="photo-edit" class="form-control">

                                        @else
                                        <div class="upload-input-img upload-input-div" id="" style="background-image: url(../front/images/upload-file-img.png);">Upload file</div>
                                        <div class="edit-img" style="display:none;">
                                            <img id="show_image" src="" >
                                            <div class="edit-link">Edit Image</div> 
                                        </div>
                                        <input type="file" name="photo" id="photo-input" class="form-control">
                                        
                                        @endif

                                    </div>
                                    <span id="file_error"></span>
                                    @if ($errors->has('photo'))
                                    <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('photo') }}</div>
                                    @endif
                                </div>-->
                               
<!--                                <div class="form-group col-lg-6">
                                    <div class="gallery profile upload-input">
                                       @if($TrainerPhoto->count() > 0)
                                       @foreach($TrainerPhoto as $Trainerp)
                                       <div class="edit-img" style="float:left" id="trainer_image{{$Trainerp->id}}">
                                            <img width="200" height="150"  style="padding: 10px;border-radius:18px" src="{{ asset('front/profile/'.$Trainerp->image)}}"> 
                                            <div class="edit-link delete-image" data-id="{{$Trainerp->id}}" style="font-size: 13px;color: #5B94A5;text-align: center;cursor: pointer;background-image: none">X</div> 
                                        </div>
                                        @endforeach
                                       @endif 
                                    </div>
                                </div>-->
                                <div class="col-lg-12 d-flex justify-content-center">
                                    <input type="submit" value="Save Details" class="btn btn-danger btn-lg" />
                                </div>
                            </form>
                        </div>
                        <!--<section id="change_password" class="page-content login-register-page section-stripe">
                            <div class="pl-lg-5 pr-lg-5">
                                <div class="register-block">
                                    <div class="register-block-inner">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="title">
                                                    <h2>Connect Your Stripe</h2>
                                                </div>
                                            </div>
                                        </div> 
                                        <div class="sr-main"> 
                                            @if(empty($StripeAccountsdata))
                                                <a id="submit-stripe" href="javascript:void(0)"><img src="{{asset('images/blue-on-dark.png')}}" alt="trainer"></a>
                                            @else 
                                            <div class="">
                                                Your stripe account is connected.
                                              </div>
                                            @endif
                                        </div> 
                                    </div>
                                </div>
                            </div>
                        </section>-->
                        <hr>
                        <section id="change_password" class="page-content login-register-page" style="padding-top: 0px;padding-bottom:30px">
                            <div class="p-lg-5">
                                <div class="register-block">
                                    <div class="register-block-inner">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="title">
                                                    <h2>Change Password</h2>
                                                </div>
                                            </div>
                                        </div>
                                        @if (session('error'))
                                        <div class="alert alert-danger">
                                            {{ session('error') }}
                                        </div>
                                        @endif
                                        @if (session('success'))
                                        <div class="alert alert-success">
                                            {{ session('success') }}
                                        </div>
                                        @endif
                                        <form class="form-row" method="POST" action="{{route('trainer.change_password')}}#change_password" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="role_type" value="customer" />
                                            <div class="form-group col-lg-6">
                                                <label for="password-input">Current Password</label>
                                                <input type="password" class="form-control" name="old_password" id="old_password-input" placeholder="current password" value="" required="">
                                                @if ($errors->has('old_password'))
                                                <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('old_password') }}</div>
                                                @endif
                                            </div>
                                            <div class="form-group col-lg-6">
                                                <label for="password-input">New Password</label><span style="float: right;font-size: 12px;">Minimum 8 characters</span>
                                                <input type="password" class="form-control" name="password" id="password-input" placeholder="new password" value="" required="" minlength="8">
                                                @if ($errors->has('password'))
                                                <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('password') }}</div>
                                                @endif
                                            </div>
                                            <div class="form-group col-lg-6">
                                                <label for="confirm-password-input">Confirm Password</label>
                                                <input type="password" class="form-control" name="confirm_password" id="confirm-password-input" placeholder="confirm password" value="" required="">
                                                @if ($errors->has('confirm_password'))
                                                <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('confirm_password') }}</div>
                                                @endif
                                            </div>
                                            <div class="col-lg-12 d-flex justify-content-center">
                                                <input type="submit" value="Save Details" class="btn btn-danger btn-lg" />
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>

            </div>

            @include('front.trainer.layout.includes.footer')

        </div>
    </div>
</div>
<div id="uploadimageModal" class="modal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                    <h4 class="modal-title">Upload & Crop Image</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                
            </div>
                    <input type="hidden" name="profile_type" value="image" class="profile_type">
            <div class="modal-body">
                <div class="row">
                            <div class="col-md-12 text-center">
                                      <div id="image_demo" ></div>
                            </div> 
                    </div>
<!--                <div class="row">
                            <div class="col-md-12 text-center"> 
                                      <button class="btn btn-success crop_image">Crop & Upload Image</button>
                            </div>
                    </div>-->
                    
            </div>
            <div class="modal-footer">
                <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
                        <button class="btn btn-success crop_image">Upload</button>
            </div>
        </div>
    </div>
</div>
<style>
    .snapshot-generator{border: 1px solid red;}
    .upload-input-img{
        background-image: none;
    }
    .upload-input{
        background-image: none;
        cursor: pointer;
    }
    .black-bg-section span {
    padding-right: 10px;
}
.black-bg-section {
    background-color: rgba(37,7,38,.5);
    text-align: center;
    position: absolute;
    bottom: 0px;
    width: 100%;
    max-width: 325px;
    height: 50px;
    display: flex;
    justify-content: center;
    align-items: center;
    transform: scale(0);
    transition: all ease .7s;
}
.black-bg-section p {
    font-size: 14px;
    letter-spacing: 0;
    line-height: 27px;
    color: #fff;
    font-family: "Open Sans";
    font-weight: 400;
    text-transform: uppercase;
}
.upload-input:hover .black-bg-section {
    transform: scale(1);
    cursor: pointer;
}
input.form-control.featured_image,input.form-control.profile_image {
    height: auto;
    margin-top: 22px;
    float: left;
    width: auto;
}

    .edit-link.delete-image{
            display: block;
    width: 25px;
    height: 25px;
    border-radius: 50%;
    background-color: #dc3545;
    transition: all .5s;
    font-size: 0;
    background-position: center center;
    background-repeat: no-repeat;
    position: absolute; 
    top: 5px;
    z-index: 99;
    right: 4px;
    }
label.featured_text {
    font-size: 15px;
}
.add-service-form .upload-input .upload-input-img{
        /*margin-bottom: 63px !important;*/
    /*width: 3C25px;*/
    border: none;
}
.multi-fields .form-group {
    margin-bottom: 10px !important;
}
.remove-field {
    margin: 5px;
}
</style>
@endsection
@section('pagescript')
<link rel="stylesheet" type="text/css" href="{{ asset('../front/css/croppie.css') }}"> 
<script src="{{ asset('../front/js/croppie.js') }}"></script> 
<script src="//cdn.ckeditor.com/4.13.0/full/ckeditor.js"></script>
<!-- <script src="{{ asset('../front/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('../front/ckeditor/adapters/jquery.js') }}"></script> -->

<script>
  
    CKEDITOR.config.height = '300px';   // CSS unit (percent).
    CKEDITOR.config.enterMode = CKEDITOR.ENTER_BR;
    CKEDITOR.config.shiftEnterMode = CKEDITOR.ENTER_BR;
   
    CKEDITOR.config.toolbar = 'Basic';

    CKEDITOR.config.toolbar_Basic =
[
    ['Bold', 'Italic','Underline', '-', 'Link', 'Unlink']
];
// $(window).on('load', function (){
//     $( '#bio-input' ).ckeditor();
// });

// $(function () {
//         var myEditor = $('#bio-input');
//         myEditor.ckeditor({ 
//         height: 300, 
//         extraPlugins: 'wordcount,notification', 
//         maxLength: 700, 
//         toolbar: 'TinyBare', 
//         toolbar_TinyBare: [
//              ['Bold','Italic','Underline'],
//              ['Undo','Redo'],['Cut','Copy','Paste'],
//              ['NumberedList','BulletedList','Table'],['CharCount']
//         ] 
//         });
//     });
function validateform(){  
    var first_name=document.edit_profile.first_name.value;  
    var last_name=document.edit_profile.last_name.value; 
    var business_name=document.edit_profile.business_name.value;
   
    if(first_name.length+business_name.length == 0){
        new PNotify({
                        title: 'Warning',
                        text: 'Please fill in the business name field!',
                        type: 'Notice'
                         });
        return false;
    }
        if((first_name.length+last_name.length+business_name.length < 3) || (first_name.length+last_name.length+business_name.length > 50)){  
            //alert("name or business name lenght should be between 3-50 characters");
           new PNotify({
                        title: 'Warning',
                        text: 'first name & last name lenght should be between 3-50 characters',
                        type: 'Notice'
                         });
        return false;  
        } 
}

$(function(){


    $('.multi-field-wrapper').each(function() {
    var $wrapper = $('.multi-fields', this);
    $(".add-field", $(this)).click(function(e) {
        $('.multi-field:first-child', $wrapper).clone(true).appendTo($wrapper).find('input').val('').focus();
    });
    $('.multi-field .remove-field', $wrapper).click(function() {
        if ($('.multi-field', $wrapper).length > 1)
            $(this).parent('.multi-field').remove();
    });
});




    // var $inputs = $('input[name=business_name],input[name=first_name]');
    // $inputs.on('input', function () {
    //     // Set the required property of the other input to false if this input is not empty.
    //     $inputs.not(this).prop('required', !$(this).val().length);
    // });

    $('.locationselect').select2({
            placeholder: "Select Locations",
            maximumSelectionLength: 5,
            language: { 
                maximumSelected: function (e) {
                    var t = "You can only select " + e.maximum + " Locations"; 
                    return t;
                }
            }
        });
    $("input[name=phone_number]")[0].oninvalid = function () {
        this.setCustomValidity("Please enter a valid phone number");
    };
    $("input[name=phone_number]")[0].oninput= function () {
        this.setCustomValidity("");
    };
    $("#zipcode-input")[0].oninvalid = function () {
        this.setCustomValidity("Please enter a valid zip code");
    };
    $("#zipcode-input")[0].oninput= function () {
        this.setCustomValidity("");
    };
    $image_crop = $('#image_demo').croppie({
        enableExif: true,
        viewport: {
          width:325,
          height:210,
          type:'square' //circle
        },
        boundary:{
          width:300,
          height:300
        }
      });
    $('[name=video_type]').click(function(){
        if(this.value== "Cloud"){$('.trainer_video_div_1').show(); $('.trainer_image_div_1').hide();}else{$('.trainer_image_div_1').show(); $('.trainer_video_div_1').hide();}
    });
    var getVideoID = function($url){
        $videoid = $url.match("[\\?&]v=([^&#]*)");
        return ( $videoid === null  ? $url : $videoid[1]);
    }
    $('#trainer_video_1').on('change',function(){
        $url= $(this).val();
       // alert($(this).val());
        $videoid = getVideoID($url);
        var profile_type = $('.profile_type').val('cloud_video').val();
        datakey = $(this).data('key');
        $videoid="https://img.youtube.com/vi/"+$videoid+"/mqdefault.jpg"
        $data = {"image": $videoid,'datakey':datakey,"profile_type":profile_type,"_token": "{{ csrf_token() }}"};
        if($('.profile_type').val() == "video"){$data['video']= $('.profile_type').data('video-src');}
        $.ajax({
            url:"{{route('front.update.profile.thumbnail')}}",
            type: "POST",
            data:$data,
            success:function(data)
            {
                dataid=1;
                $image_crop.croppie('bind', {
                    url: data
                }).then(function(){
                    console.log('jQuery bind complete');
                });
                $('#uploadimageModal').modal('show');
            }
        });
    
        
        // var $cloudimage= $('<img class="snapshot-generator" src="https://img.youtube.com/vi/tgbNymZ7vqY/0.jpg">').appendTo('body');
        // var $canvas = $( '<canvas class="snapshot-generator"></canvas>' ).appendTo(document.body)[0];

        // $cloudimage.on('load', function(event){
        //     debugger;
        //     console.info(event);
        //     $canvas.height = this.height;
        //     $canvas.width = this.width;
        //     $canvas.getContext('2d').drawImage(this, 0, 0);
        //     $result = $canvas.toDataURL('image/jpeg', 1);
        //     $video.remove();
        //     $($canvas).remove();

        // });
        // // imagesPreview(this, 'div.gallery');
    });
    var getCloudImgResult = function(){
        console.info($(this).width(), '   -    ', $(this).height());
    };
    var getImgResult =function(event,fileType){
        return new Promise(function(resolve, reject) {
            const $snapshot_second=5;
            var $result =event.target.result;
            $('.profile_type').data('video-src', '');
            if(['video/mp4', 'video/quicktime'].includes(fileType)){
                $('.profile_type').data('video-src', $result);
                var $canvas = $( '<canvas class="snapshot-generator"></canvas>' ).appendTo(document.body)[0];
                var $video = $( '<video muted class="snapshot-generator"></video>' ).appendTo(document.body);
                var step_2_events_fired = 0;
                $video.one('loadedmetadata loadeddata suspend', function() {
                    if (++step_2_events_fired == 3) {
                        $video.one('seeked', function() {
                            $canvas.height = this.videoHeight;
                            $canvas.width = this.videoWidth;
                            $canvas.getContext('2d').drawImage(this, 0, 0);
                            $result = $canvas.toDataURL('image/jpeg', 1);
                            $video.remove();
                            $($canvas).remove();
                            resolve($result);
                        }).prop('currentTime', $snapshot_second);
                    }
                }).prop('src', $result);
                
            }else{resolve($result);}
      }).then(function( result) {
        return result;
    });
    }
    var imagesPreview = function(input, placeToInsertImagePreview) {
        dataid = $(input).data('id');
        datakey = $(input).data('key');
        placeToInsertImagePreview = 'trainer_image_div_'+dataid;
        if (input.files) {
            var filesAmount = input.files.length;

            for (i = 0; i < filesAmount; i++) {
                var reader = new FileReader(); 
                var file_size = input.files[i].size;
                const  fileType = input.files[i]['type']; 
                var validImageTypes = ['image/jpg', 'image/jpeg', 'image/png', 'video/mp4', 'video/quicktime'];
                if (!validImageTypes.includes(fileType)) {
                    //$("."+placeToInsertImagePreview).append("<p style='color:#FF0000'>File type should be jpeg/png/jpg.</p>");
                    $(input).after("<p style='color:#FF0000'>File type should be jpeg/png/jpg.</p>");
                    $(input).val('').clone(true);
                    return false;
                }
                else if(file_size>10485760) {
                        $(input).after("<p style='color:#FF0000'>File size should not be greater than 10MB</p>");
                        //$(placeToInsertImagePreview).css("border-color","#FF0000");
                        $(input).val('').clone(true);
                        return false;
                }else{
                     reader.onload = function(event) {
                         var $image_result;
                         getImgResult(event, fileType).then(function(result){
                             $image_result = result;
                             // debugger;
                             $image_crop.croppie('bind', {
                                url: $image_result
                              }).then(function(){
                                console.log('jQuery bind complete');
                              });
                          });
                         //$("."+placeToInsertImagePreview).html('');
//                         $("."+placeToInsertImagePreview).css("background-image", "url("+event.target.result+")");
                               // $($.parseHTML('<img width="325" height="210" style="padding: 0px;"><div  class="black-bg-section"><p>change</p></div>')).attr('src', event.target.result).appendTo("."+placeToInsertImagePreview);
                            }
                            reader.readAsDataURL(input.files[i]);
                            $('#uploadimageModal').modal('show');
                            $('.profile_type').val(fileType.split('/')[0]);
                }
                
            }
        }

    };
    var postProfile= function(response){
    var profile_type = $('.profile_type').val();
    $data = {"image": response,"profile_type":profile_type,"_token": "{{ csrf_token() }}"};
    if(profile_type == "cloud_video"){
        datakey = $('#trainer_video_1').data('key');
        $data['videoid'] = getVideoID($('#trainer_video_1').val());
    }
    $data['datakey'] = datakey;
    $data['dataid'] = dataid;
    if($('.profile_type').val() == "video"){$data['video']= $('.profile_type').data('video-src');}
    $.ajax({
        url:"{{route('front.update.profile.images')}}",
        type: "POST",
        data:$data,
        success:function(data)
        {
           var data = $.parseJSON(data);
            $('#uploadimageModal').modal('hide');
            if($('.profile_type').val() == 'image'){
           var placeToInsertImagePreview = 'trainer_image_div_'+dataid;
          $("."+placeToInsertImagePreview).html('');
                $($.parseHTML('<img width="325" style="padding: 0px;"><div  class="black-bg-section"><p>change</p></div><div class="edit-link delete-image" data-id="'+data.id+'" style="font-size: 13px;color: #000;text-align: center;cursor: pointer;background-image: none">X</div> ')).attr('src', data.path).appendTo("."+placeToInsertImagePreview);
            }else{
                $('#show_image').attr('src', data.path);
                location.reload();
        }
        }
      });
    }
$('.crop_image').click(function(event){
    $image_crop.croppie('result', {
      type: 'canvas',
      size: 'viewport'
    }).then(function(response){postProfile(response)})
  });
    $('.trainer_image').on('change', function() {
    //$('div.gallery').html('');
    
        imagesPreview(this, 'div.gallery');
});
});
function readURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    
    reader.onload = function(e) {
      $image_crop.croppie('bind', {
        url: e.target.result
      }).then(function(){
        console.log('jQuery bind complete');
      });
    }
    
    reader.readAsDataURL(input.files[0]); // convert to base64 string
    $('#uploadimageModal').modal('show');
    $('.profile_type').val('profile');
    datakey = 0;
  }
}
   $(document).on('click', '.delete-image', function () {
        var id = $(this).attr('data-id');
        var datah = $(this);
        if(confirm('Are you sure?')){
        $.ajax({
            url: '{{ url('trainer/trainer-image-delete/') }}/'+id,
            type: 'get', 
            success: function (result) {
//                 $('#trainer_image'+id).remove();
            datah.parent('div').html('<img width="325" height="auto" style="padding: 0px;" src="{{ asset("front/profile/placeholder_profile.png")}}" alt="placeholder">');
            location.reload();
            }
        });
    } 
    });
    
    $(".featured_image").click(function() {
        var checked = $(this).is(':checked');
      
    if (checked){
        $( ".featured_image" ).prop( "checked", false );
        $(this).prop( "checked", true );
    } 
    });
    $(".profile_image").click(function() {
        var checked = $(this).is(':checked');
      
    if (checked){
        $( ".profile_image" ).prop( "checked", false );
        $(this).prop( "checked", true );
    } 
    });
$("#photo-edit").change(function() {
    var file_size = $('#photo-edit')[0].files[0].size;
    const  fileType = $('#photo-edit')[0].files[0]['type'];
    //alert(fileType);
    const validImageTypes = ['image/jpg', 'image/jpeg', 'image/png'];
    if (!validImageTypes.includes(fileType)) {
        $("#file_error").html("<p style='color:#FF0000'>File type should be jpeg/png/jpg.</p>");
        return false;
    }
    else if(file_size>10485760) {
            $("#file_error").html("<p style='color:#FF0000'>File size should not be greater than 10MB</p>");
            $(".file_upload1").css("border-color","#FF0000");
            return false;
    }else{
        $("#file_error").html("");
        readURL(this);
    }
}); 
let elmButton = document.querySelector("#submit-stripe");

if (elmButton) {
  elmButton.addEventListener(
    "click",
    e => {
      elmButton.setAttribute("disabled", "disabled");
$.ajax({
            url: '{{ route('stripecreate.geturl') }}',
            type: 'GET',
            success: function (result) {
              
                 window.open(result, '_blank');
            }, error: function () {
                toastr.error("Permission Denied!");
            }
        });
//      fetch("{{ route('stripecreate.geturl') }}", {
//        method: "GET",
//        headers: {
//          "Content-Type": "application/json"
//        }
//      })
//         .then(data => {
//          if (data) {
//              alert(data);
//            window.location = data;
//          } else {
//            elmButton.removeAttribute("disabled");
//            elmButton.textContent = "<Something went wrong>";
//            console.log("data", data);
//          }
//        });
    },
    false
  );
}
$("#photo-input").change(function() {
    var file_size = $('#photo-input')[0].files[0].size;
    const  fileType = $('#photo-input')[0].files[0]['type'];
    //alert(file_size);
    const validImageTypes = ['image/jpg', 'image/jpeg', 'image/png'];
    if (!validImageTypes.includes(fileType)) {
        $("#file_error").html("<p style='color:#FF0000'>File type should be jpeg/png/jpg.</p>");
        //alert(file_size);
        return false;
    }
    else if(file_size>5000000) {
            $("#file_error").html("<p style='color:#FF0000'>File size should not be greater than 5MB</p>");
            $(".file_upload1").css("border-color","#FF0000");
            return false;
    }else{
        $("#file_error").html("");
        readURL(this);
        $('.edit-img').show();
        $('.upload-input-div').hide();
        //$('#photo-input').hide();
    }
});
setTimeout(function() {
    $('.alert-success').fadeOut('fast');
}, 5000);
</script>
@endsection

