<!DOCTYPE html>
<html lang="en">
    <head>@include('front.layout.includes.resource-detail-head')</head>
    <body>
      <!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NZC4WTH"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->	

        <!--<div id="loader-wrapper">
            <div id="loader">
                <img src="{{ asset('images/loader.png') }}" alt="Loader">
            </div>
            <div class="loader-section section-left"></div>
            <div class="loader-section section-right"></div>
        </div>-->
        @include('front.layout.includes.header')

@section('title', 'Resource Library')

<section class="inner-banner-section bg-primary">
    <div class="container">
        <div class="banner-content">
            <h1><?php echo $resourceDetails[0]->title?></h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Resource Library</li>
                </ol>
            </nav>
        </div>
    </div>
</section> 


                @foreach($resourceDetails as $images)
                
                 <?php 
                    $trainerName = DB::select('select email, business_name, spot_description from front_users where id ='.$images->trainer_id);
                    
                    if($customerId !=0){
                    $resource_count = DB::table('resource_count')->where(["resource_id" => $images->id])->count();
                    $resource_count_detail = DB::table('resource_count')->where(["user_id" => $customerId, "resource_id" => $images->id])->get();
                    } else {
                    $resource_count = DB::table('resource_count')->where(["resource_id" => $images->id])->count();
                    $resource_count_detail = DB::table('resource_count')->where(["resource_id" => $images->id])->get();
                    }
                ?>
               
                
                  <!-- Image -->
                  <section class="pt-4 no_padding" style="padding: 0px;">
                    <div class="container">
                      <div class="row justify-content-center">
                        <div class="col-12 col-md-12">

                        <?php   
                        $name =explode(".", $images->format_name);
                        end($name);
                        $ext= current($name);
                        // echo "<pre>";print_r($ext); 
                        if ($images->format=="Article") { ?>
                           
                           <?php if(isset($images->big_image_name) && !empty($images->big_image_name)){?>
                             <img class="img-fluid position_relative" style="position: relative;" src="{{ asset('front/images/resource/'.$images->big_image_name) }}" alt="img_responsive">
                           <?php } else {?>
                            <img class="img-fluid position_relative" style="position: relative;" src="{{ asset('front/images/resource/'.$images->image_name) }}" alt="img_responsive">
                           <?php }?>

                         <?php } else {
                        ?>

                          <!--<a href="#" data-toggle="modal" data-url="{{ asset('front/images/resource/'.$images->format_name) }}" data-target="#resource_details{{$images->id}}" id="imgPopup"><img class="img-fluid position_relative" style="position: relative;" src="{{ asset('front/images/resource/'.$images->image_name) }}" alt="img_responsive"></a>-->

                          <?php if($images->format == 'Video'){
                          if (strpos($images->format_name, 'youtube') > 0) { 

                            parse_str( parse_url( $images->format_name, PHP_URL_QUERY ), $my_array_of_vars);
                            $v_id = $my_array_of_vars['v'];    
                            ?>
                              <iframe id="Geeks3" width="100%" height="500px" src="https://www.youtube.com/embed/<?php echo $v_id; ?>" frameborder="0" allowfullscreen></iframe>
                          <?php  }
                          else {
                            
                          ?>
                       
                       <video width="100%" height="600px" controls>
                        <source src="{{ $images->format_name }}" type="video/mp4">
                      </video>
                       <?php } } else {

                        ?>
                      <!-- <iframe src="" id="docframe" width="450" height="350" src="{{ $images->format_name }}" frameborder="0" allowtransparency="true"></iframe>-->
                       <?php }?>
                        <?php } ?>

                          <!-- Image -->


                        </div>
                      </div>
                    </div>
                  </section>

                  <div class="modal fade resModal" id="resource_details{{$images->id}}" tabindex="-1" role="dialog" aria-labelledby="likes" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
                        <?php if($images->format == 'Video'){
                          if (strpos($images->format_name, 'youtube') > 0) { 

                            parse_str( parse_url( $images->format_name, PHP_URL_QUERY ), $my_array_of_vars);
                            $v_id = $my_array_of_vars['v'];    
                            ?>
                              <iframe id="Geeks3" width="450" height="350" src="https://www.youtube.com/embed/<?php echo $v_id; ?>" frameborder="0" allowfullscreen></iframe>
                          <?php  }
                          else {
                            
                          ?>
                       
                       <video width="540" height="310" controls>
                        <source src="{{ $images->format_name }}" type="video/mp4">
                      </video>
                       <?php } } else {

                        ?>
                       <iframe src="" id="docframe" width="450" height="350" src="{{ $images->format_name }}" frameborder="0" allowtransparency="true"></iframe>
                       <?php }?>
                        </div>
                      </div>
                    </div>
                    </div>

                  <section class="py-4 resourceDescription">
                  <div class="container">
                    <div class="row justify-content-center">
                      <div class="col-md-12">
                        <!-- Subheading -->
                        <h6 class="mb-3 text-muted font-weight-bold">
                          @foreach($trainerName as $Name)
                        <a href="{{ url('/provider/')}}/{{ $Name->spot_description }}">@endforeach By {{ $images->name }}</a> / {{ date('F d, Y',strtotime($images->created_at)) }}
                        </h6>
                        <!--<span class="short_text">{{ \Carbon\Carbon::parse($images->created_at)->diffForHumans() }}</span>-->
                        <!-- Heading -->
                        <h3 class="mb-1 blog_title">{{ $images->title }}</h1>
                        <!--@foreach($trainerName as $Name)
                    <a href="{{ url('/provider/')}}/{{ $Name->spot_description }}">
                    @endforeach
                        <h4 class="blog_subtitle">{{ $images->name }}</h4></a>-->
                        <h4 class="blog_subtitle">{{ $images->subtitle }}</h4>
                          <p class="mb-3">
                        <p style="text-align:justify"><span style="font-size:11pt"><span style="font-family:Arial"><span
                                style="color:#000000">{!! $images->description !!}</span></span></span></p>


                        <?php if($customerId !=0){?>
                    
                   <li class="list_style new">
                  <?php if($resource_count != 0){ if(count($resource_count_detail) !=0){ foreach ($resource_count_detail as $like) { if($like->likes == 1){?>
                      <img src="{{asset('front/images/like.png')}}" class="icon_img" id="like_resource{{ $images->id }}" onClick="like_resource({{ $images->id }})">
                      <?php } else {?> 
                      <img src="{{asset('front/images/like-light.png')}}" class="icon_img" id="like_resource{{ $images->id }}" onClick="like_resource({{ $images->id }})">
                      <?php } } } else {?>
                      <img src="{{asset('front/images/like-light.png')}}" class="icon_img" id="like_resource{{ $images->id }}" onClick="like_resource({{ $images->id }})">
                      <?php } } else {?>
                      <img src="{{asset('front/images/like-light.png')}}" class="icon_img" id="like_resource{{ $images->id }}" onClick="like_resource({{ $images->id }})">
                      <?php }
                        $resource_like_count = DB::table('resource_count')->where(["resource_id" => $images->id, "likes" => 1])->count();
                        if($resource_like_count == 0){
                      ?>
                        <p class="count_num" id="like_resource_count{{ $images->id }}" onclick="like_resource_counts({{ $images->id }});">{{ $resource_like_count }}</p>
                        <?php } else {?>
                        <p class="count_num" id="like_resource_count{{ $images->id }}" onclick="like_resource_counts({{ $images->id }});">{{ $resource_like_count }}</p>
                        <?php }?>
                    </li>
                    <!-- LIke Modal -->
                    <div class="modal fade" id="likes{{$images->id}}" tabindex="-1" role="dialog" aria-labelledby="likes" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLabel">Likes</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body" id="resource_like_name{{$images->id}}">
                        <?php 
                            $resource_like_name = DB::table('resource_count')->where(["resource_id" => $images->id, "likes" => 1])->orderBy('id', 'desc')->get();
                            foreach($resource_like_name as $name){
                              $like_profile_img = DB::table('front_users')->where(["id" => $name->user_id])->first();
                          ?>
                          <a href="@if (Auth::guard('front_auth')->user()->user_role && $like_profile_img->user_role == 'customer'){{route('customer.newprofile',$like_profile_img->first_name.'-'.$like_profile_img->last_name.'-'.$like_profile_img->id)}} @else {{url('provider/'.$like_profile_img->spot_description)}} @endif" >
                          
                           <img src="@if(isset($like_profile_img->photo) && !empty($like_profile_img->photo)) {{ asset('front/profile/'.$like_profile_img->photo)}} @else {{ asset('front/images/details_default.png')}}  @endif" class="comments_profile">
                            
                          <span class="likes_name">{{ $name->name }}</span>
                          <span class="short_text">{{ \Carbon\Carbon::parse($name->created_at)->diffForHumans() }}</span></a><br/><br/>
                          
                          <?php }?>
                        </div>
                      </div>
                    </div>
                    </div>
                    <li class="list_style mr_lf">
                     
                      <?php if($resource_count != 0){ if(count($resource_count_detail) !=0){ foreach ($resource_count_detail as $dislike) { if($dislike->dislike == 1){?>
                      <img src="{{asset('front/images/dislike.png')}}" class="icon_img" id="dislike_resource{{ $images->id }}" onClick="dislike_resource({{ $images->id }})">
                      <?php } else {?> 
                      <img src="{{asset('front/images/dislike-light.png')}}" class="icon_img" id="dislike_resource{{ $images->id }}" onClick="dislike_resource({{ $images->id }})">
                      <?php } } } else {?>
                      <img src="{{asset('front/images/dislike-light.png')}}" class="icon_img" id="dislike_resource{{ $images->id }}" onClick="dislike_resource({{ $images->id }})">
                      <?php } } else {?>
                      <img src="{{asset('front/images/dislike-light.png')}}" class="icon_img" id="dislike_resource{{ $images->id }}" onClick="dislike_resource({{ $images->id }})">
                      <?php }
                        $resource_dislike_count = DB::table('resource_count')->where(["resource_id" => $images->id, "dislike" => 1])->count();
                        $resource_dislike_name = DB::table('resource_count')->where(["resource_id" => $images->id, "dislike" => 1])->orderBy('id', 'desc')->get();
                        
                        if($resource_dislike_count == 0){
                        
                      ?>
                        <p class="count_num" id="dislike_resource_count{{ $images->id }}" onclick="dislike_resource_counts({{ $images->id }});">{{ $resource_dislike_count }}</p>
                        <?php } else {?>
                        <p class="count_num" id="dislike_resource_count{{ $images->id }}" onclick="dislike_resource_counts({{ $images->id }});">{{ $resource_dislike_count }}</p>
                        <?php }?>
                    </li>
                    <!-- DisLIke Modal -->
                    <div class="modal fade" id="dislikes{{$images->id}}" tabindex="-1" role="dialog" aria-labelledby="dislikes" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLabel">Dislikes</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body" id="resource_dislike_name{{$images->id}}">
                        <?php 
                            
                            
                            foreach($resource_dislike_name as $dislike_name){
                              $dislike_profile_img = DB::table('front_users')->where(["id" => $dislike_name->user_id])->first();
                          ?>
                          <a href="@if (Auth::guard('front_auth')->user()->user_role && $dislike_profile_img->user_role == 'customer'){{route('customer.newprofile',$dislike_profile_img->first_name.'-'.$dislike_profile_img->last_name.'-'.$dislike_profile_img->id)}} @else {{url('provider/'.$dislike_profile_img->spot_description)}} @endif" >
                          
                            <img src="@if(isset($dislike_profile_img->photo) && !empty($dislike_profile_img->photo)) {{ asset('front/profile/'.$dislike_profile_img->photo)}} @else {{ asset('front/images/details_default.png')}}  @endif" class="comments_profile">
                            
                          <span class="likes_name">{{ $dislike_name->name }}</span>
                          <span class="short_text">{{ \Carbon\Carbon::parse($dislike_name->created_at)->diffForHumans() }}</span></a><br /><br/>
                          <?php }?>
                        </div>
                      </div>
                    </div>
                    </div>
                    <!-- <li class="list_style mr_lf">
                      <?php if($resource_count != 0){ if(count($resource_count_detail) !=0){ foreach ($resource_count_detail as $comments) { if($comments->comments != ''){?>
                      <a href="#" data-toggle="modal" data-target="#comments{{$images->id}}"> <img src="{{asset('front/images/comment.png')}}" class="icon_img"></a>
                      <?php } else {?> 
                      <a href="#" data-toggle="modal" data-target="#comments{{$images->id}}"> <img src="{{asset('front/images/comment-light.png')}}" class="icon_img"></a>
                      <?php } } } else {?>
                      <a href="#" data-toggle="modal" data-target="#comments{{$images->id}}"> <img src="{{asset('front/images/comment-light.png')}}" class="icon_img"></a>
                      <?php } } else {?>
                      <a href="#" data-toggle="modal" data-target="#comments{{$images->id}}"> <img src="{{asset('front/images/comment-light.png')}}" class="icon_img"></a>
                      <?php }
                        $resource_comments_count = DB::table('resource_comments')->where('resource_id', '=', $images->id)->where('comments', '!=', '')->count();
                        $resource_comments_name = DB::table('resource_comments')->where('resource_id', '=', $images->id)->where('comments', '!=', '')->orderBy('id', 'desc')->get();
                        
                        if($resource_comments_count == 0){
                        
                      ?>
                        <p class="count_num">0</p>
                        <?php } else {?>
                        <a href="#" data-toggle="modal" data-target="#comments{{$images->id}}"><p class="count_num">{{ $resource_comments_count }}</p></a>
                        <?php }?>
                    </li> -->
                    <div class="modal fade" id="comments{{$images->id}}" tabindex="-1" role="dialog" aria-labelledby="comments" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLabel">Comments</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
                        
                          <!-- <div class="p-2">
                          <form action="{{ route('resource-comment') }}" method="post">
                          
                            <input type="hidden" name="resource_id" value="{{ $images->id }}">
                            
                            <div class="d-flex flex-row align-items-start"><textarea
                                class="form-control ml-1 shadow-none textarea_comments" name="comments" required></textarea></div>
                            <div class="mt-2 text-right">
                            
                                <input type="submit" class="btn btn-primary btn-sm shadow-none post_commet" value="Post comment">
                                @csrf
                                </div>
                                </form>
                          </div> -->
                          
                          
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
                  @if(Auth::guard('front_auth')->user()->user_role != 'trainer')
                    <li class="list_style mr_lf">
                      <div class="share-button sharer" style="display: block;">
                        <a class="share-btn"> <img src="{{asset('front/images/share-light.png')}}" class="icon_img">
                        <p class="count_num">SHARE</p>
                      </a>
                        <div class="social top center networks-5 ">
                          <?php
                          
							$currentURL = URL::current();
						  ?>
                          <!-- <a class="fbtn share facebook" onClick="window.open('http://www.facebook.com/sharer.php?s=100&amp;p[title]=<?php //echo $title;?>&amp;p[summary]=<?php //echo $summary;?>&amp;p[url]=<?php //echo $url; ?>&amp;&p[images][0]=<?php //echo $image;?>', 'sharer', 'toolbar=0,status=0,width=548,height=325');" target="_parent" href="javascript: void(0)">
                            <i class="fa fa-facebook"></i>
                          </a> -->
                          
						  <!-- <a class="fbtn share facebook" href="https://www.facebook.com/sharer/sharer.php?u=<?php //echo $currentURL;?>&p[title]={{ $images->title }}"><i class="fa fa-facebook"></i></a>  -->
              <?php if($images->format == 'Video'){ ?>
                  <?php //echo $currentURL;?>          
                <a class="fbtn share facebook" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $currentURL; ?>&p[title]={{ $images->title }}" target="_blank" ><i class="fa fa-brands fa-facebook"></i></a> 
                <!-- <a class="fbtn share facebook" href="https://www.facebook.com/sharer/sharer.php?u={{ $images->format_name }}&p[title]={{ $images->title }}" target="_blank" ><i class="fa fa-facebook"></i></a>  -->
                          <?php } else { ?>
                            <a class="fbtn share facebook" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $currentURL; ?>" target="_blank" ><i class="fa fa-brands fa-facebook"></i></a> 
                            <!-- <a class="fbtn share facebook" href="https://www.facebook.com/sharer/sharer.php?u={{ asset('front/images/resource/'.$images->image_name) }}&p[title]={{ $images->title }}" target="_blank" ><i class="fa fa-facebook"></i></a>  -->
                          <?php } ?>
                          <?php if($images->format == 'Video'){ ?>
                          <a class="fbtn share twitter" href="https://twitter.com/intent/tweet?text={{ $images->title }}&url={{ $images->format_name }}" target="_blank" ><i class="fa fa-brands fa-twitter"></i></a>
                          <?php } else { ?>
                            <a class="fbtn share twitter" href="https://twitter.com/intent/tweet?text={{ $images->title }}&url={{ asset('front/images/resource/'.$images->image_name) }}" target="_blank" ><i class="fa fa-brands fa-twitter"></i></a>
                            <?php } ?>
                            <a class="fbtn share linkedin" href="http://www.linkedin.com/shareArticle?mini=true&url={{ asset('front/images/resource/'.$images->image_name) }}&title={{ $images->title }}" target="_blank" style="background: #0077b5;"><i class="fa fa-brands fa-linkedin"></i></a>
                            <!-- <a class="fbtn share envelope" href="mailto:?subject={{ $images->title }}&amp;body=<?php echo $currentURL;?>" target="_blank" ><i class="fa fa-envelope"></i></a> -->
                        </div>
                      </div>

                    </li>

                    <li class="list_style mr_lf">
                      
                      <?php if($resource_count != 0){ if(count($resource_count_detail) !=0){ foreach ($resource_count_detail as $saved) { if($saved->saved == 1){?>
                      <img src="{{asset('front/images/save.png')}}" class="icon_img" id="save_resource{{ $images->id }}" onClick="save_resource({{ $images->id }})"><p class="count_num_save" id="save_resource_name{{ $images->id }}">UNSAVE</p>
                      <?php } else {?> 
                      <img src="{{asset('front/images/save-light.png')}}" class="icon_img" id="save_resource{{ $images->id }}" onClick="save_resource({{ $images->id }})"><p class="count_num_save" id="save_resource_name{{ $images->id }}">SAVE</p>
                      <?php } } } else {?>
                      <img src="{{asset('front/images/save-light.png')}}" class="icon_img" id="save_resource{{ $images->id }}" onClick="save_resource({{ $images->id }})"><p class="count_num_save" id="save_resource_name{{ $images->id }}">SAVE</p>
                      <?php } } else {?>
                      <img src="{{asset('front/images/save-light.png')}}" class="icon_img" id="save_resource{{ $images->id }}" onClick="save_resource({{ $images->id }})"><p class="count_num_save" id="save_resource_name{{ $images->id }}">SAVE</p>
                      <?php } ?>
                    </li>
                    @endif
                    <?php } else {?>
                    <li class="list_style">
                  <?php if($resource_count != 0){ if(count($resource_count_detail) !=0){ foreach ($resource_count_detail as $like) { if($like->likes == 1){?>
                      <a href="#" data-toggle="modal" data-target="#exampleModalCenter"><img src="{{asset('front/images/like-light.png')}}" class="icon_img2"></a>
                      <?php } else {?> 
                      <a href="#" data-toggle="modal" data-target="#exampleModalCenter"><img src="{{asset('front/images/like-light.png')}}" class="icon_img2"></a>
                      <?php } } } else {?>
                      <a href="#" data-toggle="modal" data-target="#exampleModalCenter"><img src="{{asset('front/images/like-light.png')}}" class="icon_img2"></a>
                      <?php } } else {?>
                      <a href="#" data-toggle="modal" data-target="#exampleModalCenter"><img src="{{asset('front/images/like-light.png')}}" class="icon_img2"></a>
                      <?php }
                        $resource_like_count = DB::table('resource_count')->where(["resource_id" => $images->id, "likes" => 1])->count();
                        if($resource_like_count == 0){
                      ?>
                        <p class="count_num">{{ $resource_like_count }}</p>
                        <?php } else {?>
                        <a href="#" data-toggle="modal" data-target="#likes{{$images->id}}"><p class="count_num">{{ $resource_like_count }}</p></a>
                        <?php }?>
                    </li>
                    <!-- LIke Modal -->
                    <div class="modal fade" id="likes{{$images->id}}" tabindex="-1" role="dialog" aria-labelledby="likes" aria-hidden="true">
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
                            $resource_like_name = DB::table('resource_count')->where(["resource_id" => $images->id, "likes" => 1])->orderBy('id', 'desc')->get();
                           
                            foreach($resource_like_name as $name){
                              $like_profile_img = DB::table('front_users')->where(["id" => $name->user_id])->first();
                          ?>
                          <a href="{{ url('/resource-detailss/')}}/{{ base64_encode($images->id) }}">
                          <img src="@if(isset($like_profile_img->photo) && !empty($like_profile_img->photo)) {{ asset('front/profile/'.$like_profile_img->photo)}} @else {{ asset('front/images/details_default.png')}}  @endif" class="comments_profile">
                          <h4 class="likes_name">{{ $name->name }}</h4>
                          <span class="short_text">{{ \Carbon\Carbon::parse($name->created_at)->diffForHumans() }}</span></a><br />
                          <?php }?>
                        </div>
                      </div>
                    </div>
                    </div>
                    <li class="list_style mr_lf">
                     
                      <?php if($resource_count != 0){ if(count($resource_count_detail) !=0){ foreach ($resource_count_detail as $dislike) { if($dislike->dislike == 1){?>
                      <a href="#" data-toggle="modal" data-target="#exampleModalCenter"><img src="{{asset('front/images/dislike-light.png')}}" class="icon_img2"></a>
                      <?php } else {?> 
                      <a href="#" data-toggle="modal" data-target="#exampleModalCenter"><img src="{{asset('front/images/dislike-light.png')}}" class="icon_img2"></a>
                      <?php } } } else {?>
                      <a href="#" data-toggle="modal" data-target="#exampleModalCenter"><img src="{{asset('front/images/dislike-light.png')}}" class="icon_img2"></a>
                      <?php } } else {?>
                      <a href="#" data-toggle="modal" data-target="#exampleModalCenter"><img src="{{asset('front/images/dislike-light.png')}}" class="icon_img2"></a>
                      <?php }
                        $resource_dislike_count = DB::table('resource_count')->where(["resource_id" => $images->id, "dislike" => 1])->count();
                        $resource_dislike_name = DB::table('resource_count')->where(["resource_id" => $images->id, "dislike" => 1])->orderBy('id', 'desc')->get();
                        
                        if($resource_dislike_count == 0){
                        
                      ?>
                        <p class="count_num">0</p>
                        <?php } else {?>
                        <a href="#" data-toggle="modal" data-target="#dislikes{{$images->id}}"><p class="count_num">{{ $resource_dislike_count }}</p></a>
                        <?php }?>
                    </li>
                    <!-- DisLIke Modal -->
                    <div class="modal fade" id="dislikes{{$images->id}}" tabindex="-1" role="dialog" aria-labelledby="dislikes" aria-hidden="true">
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
                              $dislike_profile_img = DB::table('front_users')->where(["id" => $dislike_name->user_id])->first();
                          ?>
                          <a href="{{ url('/resource-detailss/')}}/{{ base64_encode($images->id) }}">
                           <img src="@if(isset($dislike_profile_img->photo) && !empty($dislike_profile_img->photo)) {{ asset('front/profile/'.$dislike_profile_img->photo)}} @else {{ asset('front/images/details_default.png')}}  @endif" class="comments_profile">
                          <span class="likes_name">{{ $dislike_name->name }}</span>
                          <span class="short_text">{{ \Carbon\Carbon::parse($dislike_name->created_at)->diffForHumans() }}</span></a><br /><br />
                          <?php }?>
                        </div>
                      </div>
                    </div>
                    </div>
                    <!-- <li class="list_style mr_lf">
                      <?php if($resource_count != 0){ if(count($resource_count_detail) !=0){ foreach ($resource_count_detail as $comments) { if($comments->comments != ''){?>
                      <a href="#" data-toggle="modal" data-target="#exampleModalCenter"><img src="{{asset('front/images/comment-light.png')}}" class="icon_img2"></a>
                      <?php } else {?> 
                      <a href="#" data-toggle="modal" data-target="#exampleModalCenter"><img src="{{asset('front/images/comment-light.png')}}" class="icon_img2"></a>
                      <?php } } } else {?>
                      <a href="#" data-toggle="modal" data-target="#exampleModalCenter"><img src="{{asset('front/images/comment-light.png')}}" class="icon_img2"></a>
                      <?php } } else {?>
                      <a href="#" data-toggle="modal" data-target="#exampleModalCenter"><img src="{{asset('front/images/comment-light.png')}}" class="icon_img2"></a>
                      <?php }
                        $resource_comments_count = DB::table('resource_comments')->where('resource_id', '=', $images->id)->where('comments', '!=', '')->count();
                        $resource_comments_name = DB::table('resource_comments')->where('resource_id', '=', $images->id)->where('comments', '!=', '')->orderBy('id', 'desc')->get();
                        
                        if($resource_comments_count == 0){
                        
                      ?>
                        <p class="count_num">0</p>
                        <?php } else {?>
                        <a href="#" data-toggle="modal" data-target="#comments{{$images->id}}"><p class="count_num">{{ $resource_comments_count }}</p></a>
                        <?php }?>
                    </li> -->
                    <div class="modal fade" id="comments{{$images->id}}" tabindex="-1" role="dialog" aria-labelledby="comments" aria-hidden="true">
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
                   
                   <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                      <div class="modal-dialog modal-sm" role="document">
                        
                        <div class="modal-content">
                          
                          <div class="modal-header">
                            <h4 class="modal-title w-100" id="myModalLabel"></h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body" style="height: 90px;text-align: center;overflow-y:hidden;">
                            <p>you must <a href="{{ url('/resource-detailss/')}}/{{ base64_encode($images->id) }}" style="text-decoration: underline;color: #00ab91;">login</a></p>
                          </div>
                         
                        </div>
                      </div>
                    </div>

                    <?php }?>

                      

                        

                      </div>
                    </div>
                  </div>
                </section>

                <section class="comments_section">
                <div class="container">
                    <div class="row justify-content-center">
                <div class="col-md-12">
                  <h2>Comments :  </h2>
                 <?php  if($customerId !=0){ ?>
                          <form action="{{ route('resource-detail-comment') }}" method="post" id="comment_submit">
                          
                            <input type="hidden" name="resource_id" value="{{ $images->id }}">
                            <input type="hidden" name="resource_url" value="{{ url('/resource-details/')}}/{{ base64_encode($images->id) }}">
                            @foreach($trainerName as $Name)
                            <input type="hidden" name="provider_email" value="{{ $Name->email }}">
                            <input type="hidden" name="provider_name" value="{{ $Name->business_name }}">
                            @endforeach
                            <div class="d-flex flex-row align-items-start"><textarea
                                class="form-control ml-1 shadow-none textarea_comments" name="comments" required></textarea></div>
                            <div class="mt-2 text-right">
                            
                           <input type="submit" class="btn btn-primary btn-sm shadow-none post_commet" value="Post comment">
                            @csrf
                            </div>
                            </form>
                          <?php 
                          
                          if($resource_comments_name){
                          $comment_count = 0;
                            foreach($resource_comments_name as$key => $comments){
                              $profile_img = DB::table('front_users')->where(["id" => $comments->user_id])->first();
                              $comment_count = $key;
                              if($key < 5){
                              ?>
                          <div class="comment_first_div comment_div">
                          <a href="@if (Auth::guard('front_auth')->user()->user_role && $profile_img->user_role == 'customer'){{route('customer.newprofile',$profile_img->first_name.'-'.$profile_img->last_name.'-'.$profile_img->id)}} @else {{url('provider/'.$profile_img->spot_description)}} @endif" >
                          <img src="@if(isset($profile_img->photo) && !empty($profile_img->photo)) {{ asset('front/profile/'.$profile_img->photo)}} @else {{ asset('front/images/details_default.png')}}  @endif" class="comments_profile">
                            <h4>{{ $comments->name }} </h4></a>
                            <span class="short_text">{{ \Carbon\Carbon::parse($comments->created_at)->diffForHumans() }}</span>
                            <p class="comment_content">{{ $comments->comments }}</p>
                          </div>
                          <?php }
                          else { ?>
                            <div class="comment_second_div comment_div comment_hide">
                            <a href="@if (Auth::guard('front_auth')->user()->user_role && $profile_img->user_role == 'customer'){{route('customer.newprofile',$profile_img->first_name.'-'.$profile_img->last_name.'-'.$profile_img->id)}} @else {{url('provider/'.$profile_img->spot_description)}} @endif" >
                            <img src="@if(isset($profile_img->photo) && !empty($profile_img->photo)) {{ asset('front/profile/'.$profile_img->photo)}} @else {{ asset('front/images/details_default.png')}}  @endif" class="comments_profile">
                            <h4>{{ $comments->name }} </h4></a>
                            <span class="short_text">{{ \Carbon\Carbon::parse($comments->created_at)->diffForHumans() }}</span>
                            <p class="comment_content">{{ $comments->comments }}</p>
                            </div>
                           <?php }  } 
                           // echo $comment_count;
                           if($comment_count >= 5) { ?>
                              <button id="comments_read_more" class="comments_read_more">Show More</button>
                         <?php  } 
                       
                        }
                      }
                        else {
                        ?>
                        <!-- <p>you must <a href="{{ route('front.login')}}" style="text-decoration: underline;color: #00ab91;">login</a></p> -->
                        <?php 
                          
                          if($resource_comments_name){
                          $comment_count = 0;
                            foreach($resource_comments_name as$key => $comments){
                              $profile_img = DB::table('front_users')->where(["id" => $comments->user_id])->first();
                              $comment_count = $key;
                              if($key < 5){
                              ?>
                          <div class="comment_first_div comment_div">
                         <a href="{{ url('/resource-detailss/')}}/{{ base64_encode($images->id) }}">
                          <img src="@if(isset($profile_img->photo) && !empty($profile_img->photo)) {{ asset('front/profile/'.$profile_img->photo)}} @else {{ asset('front/images/details_default.png')}}  @endif" class="comments_profile">
                            <h4>{{ $comments->name }} </h4></a>
                            <span class="short_text">{{ \Carbon\Carbon::parse($comments->created_at)->diffForHumans() }}</span>
                            <p class="comment_content">{{ $comments->comments }}</p>
                          </div>
                          <?php }
                          else { ?>
                            <div class="comment_second_div comment_div comment_hide">
                            <a href="{{ url('/resource-detailss/')}}/{{ base64_encode($images->id) }}">
                            <img src="@if(isset($profile_img->photo) && !empty($profile_img->photo)) {{ asset('front/profile/'.$profile_img->photo)}} @else {{ asset('front/images/details_default.png')}}  @endif" class="comments_profile">
                            <h4>{{ $comments->name }} </h4></a>
                            <span class="short_text">{{ \Carbon\Carbon::parse($comments->created_at)->diffForHumans() }}</span>
                            <p class="comment_content">{{ $comments->comments }}</p>
                            </div>
                           <?php }  } 
                           // echo $comment_count;
                           if($comment_count >= 5) { ?>
                              <button id="comments_read_more" class="comments_read_more">Show More</button>
                         <?php  } 
                       
                        }?>
                        <?php } ?>
 
                          
                          </div>
                          </div>
                          </div>
                </section>
                
                @endforeach

            

@include('front.layout.includes.footer')
@include('front.layout.includes.script')
<style type="text/css">
  .facebook {
    background-color: #3b5998 !important;
}
</style>
<script>
$(document).ready(function() {
        $(".share-btn").click(function (e) {
        $('.networks-5').not($(this).next(".networks-5")).each(function () {
          $(this).removeClass("active");
        });

        $(this).next(".networks-5").toggleClass("active");
      });


      $('#imgPopup').click(function(){
        var url = $(this).attr('data-url');
        $('#docframe').attr('src', url );
      });
      
    });
    function like_resource(resource_id){
      
        $.ajax({
          url: "{{url('resource-like')}}/"+resource_id,
          type: 'GET',
          success: function(res) {
          
          if(res.like == 0 && res.dislike == 0){
            $("#like_resource"+resource_id).fadeIn("fast").attr('src','/publicfront/images/like-light.png');  
            $("#dislike_resource"+resource_id).fadeIn("fast").attr('src','/publicfront/images/dislike-light.png');   
            $('#dislike_resource_count'+resource_id).html(res.resource_dislike_count); 
            $('#like_resource_count'+resource_id).html(res.resource_like_count);
          } else if(res.like == 1 && res.dislike == 0){
            $("#like_resource"+resource_id).fadeIn("fast").attr('src','/publicfront/images/like.png');   
            $("#dislike_resource"+resource_id).fadeIn("fast").attr('src','/publicfront/images/dislike-light.png'); 
            $('#dislike_resource_count'+resource_id).html(res.resource_dislike_count); 
            $('#like_resource_count'+resource_id).html(res.resource_like_count);  
          } else if(res.like == 0 && res.dislike == 1){
            $("#dislike_resource"+resource_id).fadeIn("fast").attr('src','/publicfront/images/dislike.png');
            $("#like_resource"+resource_id).fadeIn("fast").attr('src','/publicfront/images/like-light.png');  
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
            $("#like_resource"+resource_id).fadeIn("fast").attr('src','/publicfront/images/like-light.png');  
            $("#dislike_resource"+resource_id).fadeIn("fast").attr('src','/publicfront/images/dislike-light.png');  
            $('#dislike_resource_count'+resource_id).html(res.resource_dislike_count); 
            $('#like_resource_count'+resource_id).html(res.resource_like_count);
          } else if(res.like == 1 && res.dislike == 0){
            $("#like_resource"+resource_id).fadeIn("fast").attr('src','/publicfront/images/like.png');   
            $("#dislike_resource"+resource_id).fadeIn("fast").attr('src','/publicfront/images/dislike-light.png'); 
            $('#dislike_resource_count'+resource_id).html(res.resource_dislike_count); 
            $('#like_resource_count'+resource_id).html(res.resource_like_count);  
          } else if(res.like == 0 && res.dislike == 1){
            $("#dislike_resource"+resource_id).fadeIn("fast").attr('src','/publicfront/images/dislike.png');
            $("#like_resource"+resource_id).fadeIn("fast").attr('src','/publicfront/images/like-light.png');  
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
              $("#save_resource"+resource_id).fadeIn("fast").attr('src','/publicfront/images/save-light.png');     
              $('#save_resource_name'+resource_id).html('SAVE');
            } else {
              $("#save_resource"+resource_id).fadeIn("fast").attr('src','/publicfront/images/save.png');    
              $('#save_resource_name'+resource_id).html('UNSAVE'); 
            }
          }
      });

    }
</script>
<script>
    $('.resModal').on('hidden.bs.modal', function (e) {
  // do something...
  $('.resModal iframe').attr("src", jQuery(".resModal iframe").attr("src"));
  $('.resModal video').attr("src", jQuery(".resModal iframe").attr("src"));
});
</script>
<script>
  $('#comments_read_more').on('click', function(){
    if($('.comment_second_div').hasClass('comment_hide')){
      $('.comment_second_div').removeClass('comment_hide');
      $(this).html('Show Less');
    }
    else {
      $('.comment_second_div').addClass('comment_hide');
      $(this).html('Show More');
    }
  });

  //$( ".post_commet" ).click(function() {
      $("#comment_submit").on("submit", function(){
        
       $('.post_commet').hide();
      });
  //});
</script>

 </body>
</html>
  