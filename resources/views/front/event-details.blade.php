@extends('front.layout.app')
@section('title', 'Event Details')
@section('content')
 
<section class="inner-banner-section bg-primary">
   <div class="container">
      <div class="banner-content">
         <h1>Event Details</h1>
         <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
               <li class="breadcrumb-item"><a href="index.html">Home</a></li>
               <li class="breadcrumb-item active" aria-current="page">Event Details</li>
            </ol>
         </nav>
      </div>
   </div>
</section>
<section class="event-info-section">
   <div class="container">
      <div class="row">
         <div class="col-lg-12 order-1 order-lg-0"> 
            <!--<a style="float:right;background-color: #00ab91;" class="btn btn-primary btn-lg" href="/calendar">Back</a>-->
         </div>
      </div>
      <div class="row">
         <div class="service-info-jhj">
            <div class="section-title mb-3">
               <h1 class="mb-3">{{ $event[0]->title }}</h1>
            </div>
         </div>
      </div>
      <div class="row event_detail_row">
         <div class="col-md-8">
            
            <div>
               <div class="event_detail_title event-detail-avatar">
               <div class="avatar-img">
                @if($event[0]->photo)
                <img class="main-avatar" src="{{ asset('front/profile/'.$event[0]->photo) }}">
                @else
                <img class="main-avatar" src="{{ asset('front/images/events_default.png') }}">
                @endif
              </div>               
                  <div>
                  <h6 class="hosted_by" style="font-weight: 700;">Hosted By : </h6>
                  <h4 style="font-weight: 400;"><a href="{{ url('/provider/'.$event[0]->spot_description) }}" style="text-decoration: underline;">{{ $event[0]->business_name }}</a></h4>
                  </div>
                  
               </div>
               
            </div>
            
            <div>
               <div class="event_detail_title">
                  <h4>Details :</h4>
                  <p class="ml-3"><?php echo $event[0]->description; ?></p>
               </div>
            </div>


          <div class="col-md-12">
          @if(Auth::guard('front_auth')->user() != null)
          <div class="event_actions">
            <?php 
                    $trainerName = DB::select('select email, business_name, spot_description from front_users where id ='.$event[0]->trainer_id);
                    if($customerId !=0){
                    $event_count = DB::table('event_count')->where(["event_id" => $event[0]->id])->count();
                    $event_count_detail = DB::table('event_count')->where(["user_id" => $customerId, "event_id" => $event[0]->id])->get();
                    } else {
                    $event_count = DB::table('event_count')->where(["event_id" => $event[0]->id])->count();
                    $event_count_detail = DB::table('event_count')->where(["event_id" => $event[0]->id])->get();
                    }

                ?>

                <li class="list_style">
                   
                  <?php if($event_count != 0){ if(count($event_count_detail) !=0){ foreach ($event_count_detail as $like) { if($like->likes == 1){?>
                      <img src="{{asset('/front/images/like.png')}}" class="icon_img" id="like_event{{ $event[0]->id }}" onClick="like_event({{ $event[0]->id }})">
                      <?php } else {?> 
                      <img src="{{asset('/front/images/like-light.png')}}" class="icon_img" id="like_event{{ $event[0]->id }}" onClick="like_event({{ $event[0]->id }})">
                      <?php } } } else {?>
                      <img src="{{asset('/front/images/like-light.png')}}" class="icon_img" id="like_event{{ $event[0]->id }}" onClick="like_event({{ $event[0]->id }})">
                      <?php } } else {?>
                      <img src="{{asset('/front/images/like-light.png')}}" class="icon_img" id="like_event{{ $event[0]->id }}" onClick="like_event({{ $event[0]->id }})">
                      <?php }
                        $event_like_count = DB::table('event_count')->where(["event_id" => $event[0]->id, "likes" => 1])->count();
                        if($event_like_count == 0){
                      ?>
                        <p class="count_num" id="like_event_count{{ $event[0]->id }}" onclick="like_event_counts({{ $event[0]->id }});">{{ $event_like_count }}</p>
                        <?php } else {?>
                        <p class="count_num" id="like_event_count{{ $event[0]->id }}" onclick="like_event_counts({{ $event[0]->id }});">{{ $event_like_count }}</p>
                        <?php }?>
                    </li>
                    <!-- LIke Modal -->
                    <div class="modal fade" id="likes{{$event[0]->id}}" tabindex="-1" role="dialog" aria-labelledby="likes" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLabel">Likes</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body" id="event_like_name{{$event[0]->id}}">
                        <?php 
                            $event_like_name = DB::table('event_count')->where(["event_id" => $event[0]->id, "likes" => 1])->orderBy('id', 'desc')->get();
                            foreach($event_like_name as $name){
                                  $profile_img = DB::table('front_users')->where(["id" => $name->user_id])->first();
                                  
                          if(isset($profile_img->spot_description)){
                              $spot_desc = $profile_img->spot_description;
                            } else {
                              $spot_desc = '';
                            }
                             if(isset($profile_img->user_role)){
                              $user_role = $profile_img->user_role;
                            } else {
                              $user_role = '';
                            }
                            
                          ?>
                          <a href="@if (Auth::guard('front_auth')->user() && $user_role == 'customer'){{route('customer.newprofile',$profile_img->first_name.'-'.$profile_img->last_name.'-'.$profile_img->id)}} @else {{url('provider/'.$spot_desc)}} @endif" >
                          <img src="@if(isset($profile_img->photo) && !empty($profile_img->photo)) {{ asset('front/profile/'.$profile_img->photo)}} @else {{ asset('front/images/details_default.png')}}  @endif" class="comments_profile">
                          <span class="likes_name">{{ $name->name }}</span>
                          <span class="short_text">{{ \Carbon\Carbon::parse($name->created_at)->diffForHumans() }}</span></a><br><br>
                          <?php }?>
                        </div>
                      </div>
                    </div>
                    </div>
                    <li class="list_style">
                     
                      <?php if($event_count != 0){ if(count($event_count_detail) !=0){ foreach ($event_count_detail as $dislike) { if($dislike->dislike == 1){?>
                      <img src="{{asset('/front/images/dislike.png')}}" class="icon_img" id="dislike_event{{ $event[0]->id }}" onClick="dislike_event({{ $event[0]->id }})">
                      <?php } else {?> 
                      <img src="{{asset('/front/images/dislike-light.png')}}" class="icon_img" id="dislike_event{{ $event[0]->id }}" onClick="dislike_event({{ $event[0]->id }})">
                      <?php } } } else {?>
                      <img src="{{asset('/front/images/dislike-light.png')}}" class="icon_img" id="dislike_event{{ $event[0]->id }}" onClick="dislike_event({{ $event[0]->id }})">
                      <?php } } else {?>
                      <img src="{{asset('/front/images/dislike-light.png')}}" class="icon_img" id="dislike_event{{ $event[0]->id }}" onClick="dislike_event({{ $event[0]->id }})">
                      <?php }
                        $event_dislike_count = DB::table('event_count')->where(["event_id" => $event[0]->id, "dislike" => 1])->count();
                        $event_dislike_name = DB::table('event_count')->where(["event_id" => $event[0]->id, "dislike" => 1])->orderBy('id', 'desc')->get();
                        
                        if($event_dislike_count == 0){
                        
                      ?>
                        <p class="count_num" id="dislike_event_count{{ $event[0]->id }}" onclick="dislike_event_counts({{ $event[0]->id }});">{{ $event_dislike_count }}</p>
                        <?php } else {?>
                        <p class="count_num" id="dislike_event_count{{ $event[0]->id }}" onclick="dislike_event_counts({{ $event[0]->id }});">{{ $event_dislike_count }}</p>
                        <?php }?>
                    </li>
                    <!-- DisLIke Modal -->
                    <div class="modal fade" id="dislikes{{$event[0]->id}}" tabindex="-1" role="dialog" aria-labelledby="dislikes" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLabel">Dislikes</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body" id="event_dislike_name{{$event[0]->id}}">
                        <?php 
                            
                            
                            foreach($event_dislike_name as $dislike_name){
                            $profile_img = DB::table('front_users')->where(["id" => $dislike_name->user_id])->first();
                          if(isset($profile_img->spot_description)){
                              $spot_desc = $profile_img->spot_description;
                            } else {
                              $spot_desc = '';
                            }
                             if(isset($profile_img->user_role)){
                              $user_role = $profile_img->user_role;
                            } else {
                              $user_role = '';
                            }
                          ?>

                          <a href="@if (Auth::guard('front_auth')->user()->user_role && $user_role == 'customer'){{route('customer.newprofile',$profile_img->first_name.'-'.$profile_img->last_name.'-'.$profile_img->id)}} @else {{url('provider/'.$spot_desc)}} @endif" >
                          <img src="@if(isset($profile_img->photo) && !empty($profile_img->photo)) {{ asset('front/profile/'.$profile_img->photo)}} @else {{ asset('front/images/details_default.png')}}  @endif" class="comments_profile">
                          <span class="likes_name">{{ $dislike_name->name }}</span>
                          <span class="short_text">{{ \Carbon\Carbon::parse($dislike_name->created_at)->diffForHumans() }}</span></a><br><br>
                          <?php }?>
                        </div>
                      </div>
                    </div>
                    </div>


                    <li class="list_style">
                      <?php if($event_count != 0){ if(count($event_count_detail) !=0){ foreach ($event_count_detail as $comments) { if($comments->comments != ''){?>
                      <a href="#" data-toggle="modal" data-target="#comments{{$event[0]->id}}"> <img src="{{asset('/front/images/comment.png')}}" class="icon_img"></a>
                      <?php } else {?> 
                      <a href="#" data-toggle="modal" data-target="#comments{{$event[0]->id}}"> <img src="{{asset('/front/images/comment-light.png')}}" class="icon_img"></a>
                      <?php } } } else {?>
                      <a href="#" data-toggle="modal" data-target="#comments{{$event[0]->id}}"> <img src="{{asset('/front/images/comment-light.png')}}" class="icon_img"></a>
                      <?php } } else {?>
                      <a href="#" data-toggle="modal" data-target="#comments{{$event[0]->id}}"> <img src="{{asset('/front/images/comment-light.png')}}" class="icon_img"></a>
                      <?php }
                        $event_comments_count = DB::table('event_comments')->where('event_id', '=', $event[0]->id)->where('comments', '!=', '')->count();
                        $event_comments_name = DB::table('event_comments')->where('event_id', '=', $event[0]->id)->where('comments', '!=', '')->orderBy('id', 'desc')->get();
                        
                        if($event_comments_count == 0){
                        
                      ?>
                        <p class="count_num">0</p>
                        <?php } else {?>
                        <a href="#" data-toggle="modal" data-target="#comments{{$event[0]->id}}"><p class="count_num">{{ $event_comments_count }}</p></a>
                        <?php }?>
                    </li>
                    <div class="modal fade" id="comments{{$event[0]->id}}" tabindex="-1" role="dialog" aria-labelledby="comments" aria-hidden="true">
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
                          <form action="{{ route('event-comment') }}" method="post" class="comment_submit">
                            <input type="hidden" name="event_id" value="{{ $event[0]->id }}">
                            <input type="hidden" name="event_url" value="{{ url('/event-details/')}}/{{ base64_encode($event[0]->id) }}">

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
                          </div>
                          
                          
                          <?php if($event_comments_name){foreach($event_comments_name as $comments){$profile_img = DB::table('front_users')->where(["id" => $comments->user_id])->first();
                          if(isset($profile_img->spot_description)){
                              $spot_desc = $profile_img->spot_description;
                            } else {
                              $spot_desc = '';
                            }
                          if(isset($profile_img->user_role)){
                              $user_role = $profile_img->user_role;
                            } else {
                              $user_role = '';
                            }
                          ?>
                          <div class="comment_div">
                          <a href="@if (Auth::guard('front_auth')->user()->user_role && $user_role == 'customer'){{route('customer.newprofile',$profile_img->first_name.'-'.$profile_img->last_name.'-'.$profile_img->id)}} @else {{url('provider/'.$spot_desc)}} @endif" >
                          <img src="@if(isset($profile_img->photo) && !empty($profile_img->photo)) {{ asset('front/profile/'.$profile_img->photo)}} @else {{ asset('front/images/details_default.png')}}  @endif" class="comments_profile">
                            <span>{{ $comments->name }} </span></a>
                            <span class="short_text">{{ \Carbon\Carbon::parse($comments->created_at)->diffForHumans() }}</span>
                            <p class="comment_content">{{ $comments->comments }}</p>
                          </div>
                          <?php }}?>
                        </div>
                      </div>
                    </div>
                    </div>


                                       
                    
            <li class="list_style">
               <div class="share-button sharer" style="display: block;">
                  <a class="share-btn">
                     <img src="{{asset('/front/images/share-light.png')}}" class="icon_img">
                     <p class="count_num">SHARE</p>
                  </a>
                  <div class="social top center networks-5 ">
                     <a class="fbtn share facebook" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo URL::current(); ?>;p[title]={{ $event[0]->title }}" target="_blank"><i class="fa fa-brands fa-facebook"></i></a> 
                     <a class="fbtn share twitter" href="https://twitter.com/intent/tweet?text={{ $event[0]->title }}&amp;url=<?php echo URL::current(); ?>" target="_blank"><i class="fa fa-brands fa-twitter"></i></a>
                     <a class="fbtn share envelope" href="mailto:?subject={{ $event[0]->title }}&amp;body=<?php echo URL::current(); ?>" target="_blank"><i class="fa fa-envelope"></i></a>
                  </div>
               </div>
            </li>
            <!--<li class="list_style">
               <img src="https://trainingblock.sgssys.info/public//front/images/save-light.png" class="icon_img" id="save_event<?php echo $event[0]->id?>" onclick="save_event(<?php echo $event[0]->id?>)">
               <p class="count_num_save" id="save_event_name<?php echo $event[0]->id?>">SAVE</p>
            </li>-->
          </div>

          @else

          <div class="event_actions">
            <?php 
                    $trainerName = DB::select('select email, business_name, spot_description from front_users where id ='.$event[0]->trainer_id);
                    if($customerId !=0){
                    $event_count = DB::table('event_count')->where(["event_id" => $event[0]->id])->count();
                    $event_count_detail = DB::table('event_count')->where(["user_id" => $customerId, "event_id" => $event[0]->id])->get();
                    } else {
                    $event_count = DB::table('event_count')->where(["event_id" => $event[0]->id])->count();
                    $event_count_detail = DB::table('event_count')->where(["event_id" => $event[0]->id])->get();
                    }

                ?>

                               <li class="list_style">
                  <?php if($event_count != 0){ if(count($event_count_detail) !=0){ foreach ($event_count_detail as $like) { if($like->likes == 1){?>
                      <a href="#" data-toggle="modal" data-target="#exampleModalCenter"><img src="{{asset('/front/images/like-light.png')}}" class="icon_img2"></a>
                      <?php } else {?> 
                      <a href="#" data-toggle="modal" data-target="#exampleModalCenter"><img src="{{asset('/front/images/like-light.png')}}" class="icon_img2"></a>
                      <?php } } } else {?>
                      <a href="#" data-toggle="modal" data-target="#exampleModalCenter"><img src="{{asset('/front/images/like-light.png')}}" class="icon_img2"></a>
                      <?php } } else {?>
                      <a href="#" data-toggle="modal" data-target="#exampleModalCenter"><img src="{{asset('/front/images/like-light.png')}}" class="icon_img2"></a>
                      <?php }
                        $event_like_count = DB::table('event_count')->where(["event_id" => $event[0]->id, "likes" => 1])->count();
                        if($event_like_count == 0){
                      ?>
                        <p class="count_num">{{ $event_like_count }}</p>
                        <?php } else {?>
                        <a href="#" data-toggle="modal" data-target="#likes{{$event[0]->id}}"><p class="count_num">{{ $event_like_count }}</p></a>
                        <?php }?>
                    </li>
                    <!-- LIke Modal -->
                    <div class="modal fade" id="likes{{$event[0]->id}}" tabindex="-1" role="dialog" aria-labelledby="likes" aria-hidden="true">
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
                            $event_like_name = DB::table('event_count')->where(["event_id" => $event[0]->id, "likes" => 1])->orderBy('id', 'desc')->get();
                            foreach($event_like_name as $name){
                            $profile_img = DB::table('front_users')->where(["id" => $name->user_id])->first();
                          ?>
                          <a href="{{ route('front.login')}}">
                          <img src="@if(isset($profile_img->photo) && !empty($profile_img->photo)) {{ asset('front/profile/'.$profile_img->photo)}} @else {{ asset('front/images/details_default.png')}}  @endif" class="comments_profile">
                          <span class="likes_name">{{ $name->name }}</span>
                          <span class="short_text">{{ \Carbon\Carbon::parse($name->created_at)->diffForHumans() }}</span></a><br><br>
                          <?php }?>
                        </div>
                      </div>
                    </div>
                    </div>
                    <li class="list_style">
                     
                      <?php if($event_count != 0){ if(count($event_count_detail) !=0){ foreach ($event_count_detail as $dislike) { if($dislike->dislike == 1){?>
                      <a href="#" data-toggle="modal" data-target="#exampleModalCenter"><img src="{{asset('/front/images/dislike-light.png')}}" class="icon_img2"></a>
                      <?php } else {?> 
                      <a href="#" data-toggle="modal" data-target="#exampleModalCenter"><img src="{{asset('/front/images/dislike-light.png')}}" class="icon_img2"></a>
                      <?php } } } else {?>
                      <a href="#" data-toggle="modal" data-target="#exampleModalCenter"><img src="{{asset('/front/images/dislike-light.png')}}" class="icon_img2"></a>
                      <?php } } else {?>
                      <a href="#" data-toggle="modal" data-target="#exampleModalCenter"><img src="{{asset('/front/images/dislike-light.png')}}" class="icon_img2"></a>
                      <?php }
                        $event_dislike_count = DB::table('event_count')->where(["event_id" => $event[0]->id, "dislike" => 1])->count();
                        $event_dislike_name = DB::table('event_count')->where(["event_id" => $event[0]->id, "dislike" => 1])->orderBy('id', 'desc')->get();
                        
                        if($event_dislike_count == 0){
                        
                      ?>
                        <p class="count_num">0</p>
                        <?php } else {?>
                        <a href="#" data-toggle="modal" data-target="#dislikes{{$event[0]->id}}"><p class="count_num">{{ $event_dislike_count }}</p></a>
                        <?php }?>
                    </li>
                    <!-- DisLIke Modal -->
                    <div class="modal fade" id="dislikes{{$event[0]->id}}" tabindex="-1" role="dialog" aria-labelledby="dislikes" aria-hidden="true">
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
                            
                            
                            foreach($event_dislike_name as $dislike_name){
                            $profile_img = DB::table('front_users')->where(["id" => $dislike_name->user_id])->first();
                          ?>
                          <a href="{{ route('front.login')}}">
                          <img src="@if(isset($profile_img->photo) && !empty($profile_img->photo)) {{ asset('front/profile/'.$profile_img->photo)}} @else {{ asset('front/images/details_default.png')}}  @endif" class="comments_profile">
                          <span class="likes_name">{{ $dislike_name->name }}</span>
                          <span class="short_text">{{ \Carbon\Carbon::parse($dislike_name->created_at)->diffForHumans() }}</span></a><br><br>
                          <?php }?>
                        </div>
                      </div>
                    </div>
                    </div>

                                        <li class="list_style">
                      <?php if($event_count != 0){ if(count($event_count_detail) !=0){ foreach ($event_count_detail as $comments) { if($comments->comments != ''){?>
                      <a href="#" data-toggle="modal" data-target="#exampleModalCenter"><img src="{{asset('/front/images/comment-light.png')}}" class="icon_img2"></a>
                      <?php } else {?> 
                      <a href="#" data-toggle="modal" data-target="#exampleModalCenter"><img src="{{asset('/front/images/comment-light.png')}}" class="icon_img2"></a>
                      <?php } } } else {?>
                      <a href="#" data-toggle="modal" data-target="#exampleModalCenter"><img src="{{asset('/front/images/comment-light.png')}}" class="icon_img2"></a>
                      <?php } } else {?>
                      <a href="#" data-toggle="modal" data-target="#exampleModalCenter"><img src="{{asset('/front/images/comment-light.png')}}" class="icon_img2"></a>
                      <?php }
                        $event_comments_count = DB::table('event_comments')->where('event_id', '=', $event[0]->id)->where('comments', '!=', '')->count();
                        $event_comments_name = DB::table('event_comments')->where('event_id', '=', $event[0]->id)->where('comments', '!=', '')->orderBy('id', 'desc')->get();
                        
                        if($event_comments_count == 0){
                        
                      ?>
                        <p class="count_num">0</p>
                        <?php } else {?>
                        <a href="#" data-toggle="modal" data-target="#comments{{$event[0]->id}}"><p class="count_num">{{ $event_comments_count }}</p></a>
                        <?php }?>
                    </li>
                    <div class="modal fade" id="comments{{$event[0]->id}}" tabindex="-1" role="dialog" aria-labelledby="comments" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLabel">Comments</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
                        
                          <?php if($event_comments_name){foreach($event_comments_name as $comments){$profile_img = DB::table('front_users')->where(["id" => $comments->user_id])->first();?>
                          <div class="comment_div">
                          <a href="{{ route('front.login')}}">
                          <img src="@if(isset($profile_img->photo) && !empty($profile_img->photo)) {{ asset('front/profile/'.$profile_img->photo)}} @else {{ asset('front/images/details_default.png')}}  @endif" class="comments_profile">
                            <span>{{ $comments->name }} </span></a>
                            <span class="short_text">{{ \Carbon\Carbon::parse($comments->created_at)->diffForHumans() }}</span>
                            <p class="comment_content">{{ $comments->comments }}</p>
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
                              <p>you must <a href="{{ route('trainer.events.list')}}" style="text-decoration: underline;color: #00ab91;">login</a></p>
                            </div>
                           
                          </div>
                        </div>
                      </div>
                     
                    
            <li class="list_style">
               <div class="share-button sharer" style="display: block;">
                  <a class="share-btn">
                     <img src="{{asset('/front/images/share-light.png')}}" class="icon_img">
                     <p class="count_num">SHARE</p>
                  </a>
                  <div class="social top center networks-5 ">
                     <a class="fbtn share facebook" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo URL::current(); ?>;p[title]={{ $event[0]->title }}" target="_blank"><i class="fa fa-brands fa-facebook"></i></a> 
                     <a class="fbtn share twitter" href="https://twitter.com/intent/tweet?text={{ $event[0]->title }}&amp;url=<?php echo URL::current(); ?>" target="_blank"><i class="fa fa-brands fa-twitter"></i></a>
                     <a class="fbtn share envelope" href="mailto:?subject={{ $event[0]->title }}&amp;body=<?php echo URL::current(); ?>" target="_blank"><i class="fa fa-envelope"></i></a>
                  </div>
               </div>
            </li>
            
          </div>
            
          @endif

            <?php $UserTCheck = Auth::guard('front_auth')->user();
            if($UserTCheck != null){
                  $UserTRole = $UserTCheck->user_role;
            }else{
              $UserTRole = "";
            }
             ?>

             @if( $UserTRole == "trainer")
                    <!-- Alert for Service -->
                    <div class="row mb-1">
                        <div class="col-12">
                    <div class="alert alert-danger alert-dismissible fade show">
                    These events are for athlete registration only. Please log out of your provider account and log in as an athlete if you would like to register for this event.
                       <!-- <button type="button" class="close" data-dismiss="alert">&times;</button>-->
                    </div>
                    </div>
                    </div>
              @endif

             
            <?php 
            // $servein = Auth::guard('front_auth')->user()->user_role;

            $UserCheck = Auth::guard('front_auth')->user();
            if($UserCheck == null){
                $attender_id = "";
                $attender_first = "";
                $attender_last = "";
                $attender_email = "";
                $UserRole = "";
                }else{
                $event_id = $event[0]->id;
                $trainer_id = $event[0]->trainer_id;
                $event_type = $event[0]->type;
                $attender_id = $UserCheck->id;
                $attender_first = $UserCheck->first_name;
                $attender_last = $UserCheck->last_name;
                $attender_email = $UserCheck->email;
                $UserRole = $UserCheck->user_role;

            }
            
            ?>
            @if($selfRegistered <= 0)
            @if($event[0]->members_allowed != NULL && $attendeesCount >= $event[0]->members_allowed)

                  @if($UserCheck != null)
                  @if($UserCheck->user_role != "trainer")
                    <!-- Alert for Service -->
                    <div class="row mb-1">
                        <div class="col-12">
                    <div class="alert alert-danger alert-dismissible fade show">
                    Event registration full.
                    </div>
                    </div>
                    </div>
                  @endif
                  @endif
            @endif
            @endif

            @if($selfRegistered > 0)
                    <!-- Alert for Service -->
                    <div class="row mb-1">
                        <div class="col-12">
                    <div class="alert alert-danger alert-dismissible fade show">
                    You have already registered for this event.
                    </div>
                    </div>
                    </div>
            @endif
            @if(($event[0]->type == "Paid" && $event[0]->stripe_user_id != null) || $event[0]->type == "Free")
              @if($UserCheck == null)
                <a href="{{ url('/event-detailss/'.base64_encode($event[0]->id) ) }}" class="btn btn-info" id="attend-btn">Attend</a>
                <!--<a href="{{ url('/login') }}" class="btn btn-info btn-lg" type="submit">Attend</a>-->
              @else
                <form id="attendForm" method="post" action="{{url('/event-details')}}">
                @csrf
                <input type="hidden" name="event_id" value="{{ $event_id }}">
                <input type="hidden" name="trainer_id" value="{{ $trainer_id }}">
                <input type="hidden" name="attender_id" value="{{ $attender_id }}">
                <input type="hidden" name="attender_first" value="{{ $attender_first }}">
                <input type="hidden" name="attender_last" value="{{ $attender_last }}">
                <input type="hidden" name="attender_email" value="{{ $attender_email }}">
                <input type="hidden" name="event_type" value="{{ $event_type }}">

                
                  @if($UserCheck->user_role == "customer" && ($event[0]->members_allowed == NULL || ($event[0]->members_allowed != NULL && $attendeesCount < $event[0]->members_allowed)))
                      @if($selfRegistered > 0)
                      <button class="btn btn-info" id="attend-btn" type="submit" disabled >Attend</button>
                      @else
                        @if($event[0]->type == "Free")
                            @if($event[0]->members_allowed != NULL && $attendeesCount > $event[0]->members_allowed)
                            <button type="submit" class="btn btn-info" id="attend-btn" disabled>Attend</button>  
                            @else                            
                            <button type="submit" class="btn btn-info" id="attend-btn" >Attend</button>  
                            
                            
                            @endif
                        @else
                            @if($event[0]->members_allowed != NULL && $attendeesCount > $event[0]->members_allowed)
                            <a href="{{ url('/event-register/'.base64_encode($event[0]->id)) }}" disabled class="btn btn-info" id="attend-btn" >Attend</a>
                            @else
                            <a href="{{ url('/event-register/'.base64_encode($event[0]->id)) }}" class="btn btn-info" id="attend-btn" >Attend</a>          
                            @endif
                        @endif      
                      @endif
                  @else
                  <button type="submit" class="btn btn-info" id="attend-btn" disabled >Attend</button>
                  @endif
                
                </form>
              @endif                
              @endif 
         </div>
         </div>
         <div class="col-md-4">
         <!--<div class="eamilUsNow">
         <button class="badge badge-success emailus_button" data-toggle="modal" data-target="#emailModal" style="border: none;padding: 6px;">Email Us</button>         
         </div>-->
            <div class="col-md-12 card">
               <div class="card-content">
                  <div class="card-body grp_grp">
                     <p class="grp_item">
                      <i class="fa-regular fa-folder icons"></i>
                     <span>{{ $event[0]->category }}</span>
                     </p>                   
                      @if($event[0]->venue == null)
                      @else
                      <p class="grp_item">
                      <?php $address = $event[0]->venue;
                       ?>
                      <i class="fa-solid fa-location-dot icons"></i>
                      <a target="_blank" id="locationlink" href="https://maps.google.com/?q=<?php echo $address; ?>">{{ $event[0]->venue }}</a>
                      </p>    
                      @endif                 
                      @if($event[0]->url == null)
                      @else
                      <p class="grp_item">
                      <?php $url = $event[0]->url;
                       ?>
                      <i class="fa fa-link icons"></i>
                      <a target="_blank" href="<?php echo $url; ?>">{{ $event[0]->url }}</a>
                      </p>    
                      @endif                 
                      <p class="grp_item">
                      <i class="fa-regular fa-clock icons"></i>
                      <span>
                      <?php echo date('F d, Y', strtotime($event[0]->start_date))  ?> <br />from <?php echo $event[0]->start_time ?> - <?php echo $event[0]->end_time ?>
                      <br>

                      <p class="grp_item">
                      <i class="fa-regular fa-money-bill-1 icons"></i>
                     <span>@if(isset($event[0]->cost)) $ {{$event[0]->cost}}  @else Free @endif</span>
                     </p>                       
                      </span>
                      </p>
                    
                  </div>
               </div>
            </div>

                        <?php  
                        
                        if($atendesCurs->count() == 0){
                          $display = "none";
                        }else{
                          $display = "block";
                        } 
                        
                        ?>
                        <div class="col-md-12 card recent-reg" style="display: <?php echo $display; ?>;" ><div class="card-content"><div class="card-body">
                        <h4 class="text-danger">Registered Attendees (<?php echo $atendesCurs->count(); ?>)</h4>        

                        <div class="reg-cust <?php if($atendesCurs->count() <= 4){ echo "scrollHide"; }?>">

                          @foreach($atendesCurs as $atendesCur)
                          <?php 
                          if (DB::table('front_users')->where('id', '=', $atendesCur->attender_id)->count() > 0) {
                          $userData =  DB::table('front_users')->where('id', '=', $atendesCur->attender_id)->get();
                          }
                          ?>
                          
                          <div class="chat">
                          @if(isset($userData))
                          @if($userData[0]->photo == null)
                          <div class="avatar-img"><img class="avatar" src="{{asset('/front/images/details_default.png')}}"></div>
                          @else
                          <div class="avatar-img"><img class="avatar" src="{{ asset('front/profile/'.$userData[0]->photo) }}"></div>
                          @endif
                          @if($userData[0]->user_role == 'trainer')
                          <div class="my-auto"><a href="/provider/{{ $userData[0]->spot_description }}" target="_blank" ><h6 class="font-weight-bold">{{ $userData[0]->first_name }} {{ $userData[0]->last_name }}</h6></a></div>
                          @else
                          <div class="my-auto"><a href="/profile/{{ $userData[0]->first_name }}-{{ $userData[0]->last_name }}-{{ $userData[0]->id }}" target="_blank" ><h6 class="font-weight-bold">{{ $userData[0]->first_name }} {{ $userData[0]->last_name }}</h6></a></div>
                          @endif
                          </div>
                          @endif
                          @endforeach

                        </div>
                        </div>               
            </div>

         </div>
         
      </div>
   </div>    
</section>
@stop
<style type="text/css">


#attend-btn {
    font-size: 14px !important;
    line-height: 1;
    height: auto;
    padding: 15px 50px;
}

  /* header.navbar.home-header {
    border-bottom: 1px solid #353d47 !important;
} */
#locationlink{
  text-decoration: underline !important;
}

.social {
  z-index: 100;
}
.scrollHide{
  overflow-y: hidden !important;
}
.fa{
  width: 25px;
}
.grp_item{
  display: flex;
  align-items: center!important;
}
.eamilUsNow{
  display: flex;
  justify-content: end;
}
.emailus_button{
      margin-bottom: 10px;
}

/* width */
::-webkit-scrollbar {
  width: 10px;
}

/* Track */
::-webkit-scrollbar-track {
  background: #f1f1f1; 
}
 
/* Handle */
::-webkit-scrollbar-thumb {
  background: #888; 
}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
  background: #555; 
}


.reg-cust{
  height: 280px;
  overflow: scroll;
  overflow-x: hidden;
}
.recent-reg{
  margin-top: 15px;
}
.avatar {
	width: 50px;
	height: 50px;
  border-radius: 40px;
  margin: 10px;
}
.icons  {
    font-size: 22px !important;
    margin-right: 15px;
    color: #787878;
}
.chat {
      display: flex;
  }

.event-detail-avatar{
  display: flex;
  align-items: center;  
}

  .event_actions {
    margin: 20px 0px;
}
.cost-badge{
  font-size: 100% !important;
}
.customer-info-section{
  margin: 15px;
}

.avatar-img{
  margin-right: 25px;
}

.form-group label {
  margin-bottom: 5px !important;
}
.facebook {
    background-color: #3b5998 !important;
}
.main-avatar {
  width: 150px;
  height: auto;
  border-radius: 10px;
  margin: 20px 0;
}

@media only screen and (max-width: 600px) {
  .total-card {
    margin-top: 20px;
  }
}

.recurring_label {
    line-height : 3
}
.form-control.membership_price{
    width: 30% !important;
    float: left;
    margin-right: 10px;
}
#slot-input option:disabled{
    color: darkgray; 
}
#show_data{
 position:sticky;
 height:100%;
 top:0;
}
.ajax-loader {
   visibility: hidden;
   position: absolute;
   bottom: 3%;
}

.ajax-loader img {
  position: relative;
  top:50%;
  left:50%;
}
    /* Payment form  */
    #payment-form .card{
        border: 0;
        display: block;
        border-radius: 0;
        box-shadow: none;
        overflow: auto;
    }
    #payment-form .hide{
        display: none !important;
    }
   
    .text_loaders h1{
       
        display: block;
        position: absolute;
        top: 25%;
        width: 100%;
        z-index: 1001;
        text-align: center;
        display: flex;
        color: #00ab91;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }
</style>
@section('pagescript')
<script>
$(document).ready(function() {
 
        $(".share-btn").click(function (e) {
        $('.networks-5').not($(this).next(".networks-5")).each(function () {
          $(this).removeClass("active");
        });

        $(this).next(".networks-5").toggleClass("active");
      });

      $("#attendForm").on('submit', function (e) {
          $('#attend-btn').attr('disabled', '');
      });
      
    });
    function like_event(event_id){
      
        $.ajax({
          url: "{{url('event-like')}}/"+event_id,
          type: 'GET',
          success: function(res) {
          
          if(res.like == 0 && res.dislike == 0){
            $("#like_event"+event_id).fadeIn("fast").attr('src','/public/front/images/like-light.png');  
            $("#dislike_event"+event_id).fadeIn("fast").attr('src','/public/front/images/dislike-light.png');   
            $('#dislike_event_count'+event_id).html(res.event_dislike_count); 
            $('#like_event_count'+event_id).html(res.event_like_count);
          } else if(res.like == 1 && res.dislike == 0){
            $("#like_event"+event_id).fadeIn("fast").attr('src','/public/front/images/like.png');   
            $("#dislike_event"+event_id).fadeIn("fast").attr('src','/public/front/images/dislike-light.png'); 
            $('#dislike_event_count'+event_id).html(res.event_dislike_count); 
            $('#like_event_count'+event_id).html(res.event_like_count);  
          } else if(res.like == 0 && res.dislike == 1){
            $("#dislike_event"+event_id).fadeIn("fast").attr('src','/public/front/images/dislike.png');
            $("#like_event"+event_id).fadeIn("fast").attr('src','/public/front/images/like-light.png');  
            $('#dislike_event_count'+event_id).html(res.event_dislike_count); 
            $('#like_event_count'+event_id).html(res.event_like_count);
          }
                 
            
            $('#event_like_name'+event_id).html(res.like_name);
            $('#event_dislike_name'+event_id).html(res.dislike_name);
              
          }
      });

    }

    function like_event_counts(event_id){
      var like_event_counts = $('#like_event_count'+event_id).html();

      if(like_event_counts !=0){
        $('#likes'+event_id).modal('show');
      }

    }

    function dislike_event(event_id){
      
        $.ajax({
          url: "{{url('event-dislike')}}/"+event_id,
          type: 'GET',
          success: function(res) {
          
          if(res.like == 0 && res.dislike == 0){
            $("#like_event"+event_id).fadeIn("fast").attr('src','/public/front/images/like-light.png');  
            $("#dislike_event"+event_id).fadeIn("fast").attr('src','/public/front/images/dislike-light.png');  
            $('#dislike_event_count'+event_id).html(res.event_dislike_count); 
            $('#like_event_count'+event_id).html(res.event_like_count);
          } else if(res.like == 1 && res.dislike == 0){
            $("#like_event"+event_id).fadeIn("fast").attr('src','/public/front/images/like.png');   
            $("#dislike_event"+event_id).fadeIn("fast").attr('src','/public/front/images/dislike-light.png'); 
            $('#dislike_event_count'+event_id).html(res.event_dislike_count); 
            $('#like_event_count'+event_id).html(res.event_like_count);  
          } else if(res.like == 0 && res.dislike == 1){
            $("#dislike_event"+event_id).fadeIn("fast").attr('src','/public/front/images/dislike.png');
            $("#like_event"+event_id).fadeIn("fast").attr('src','/public/front/images/like-light.png');  
            $('#dislike_event_count'+event_id).html(res.event_dislike_count); 
            $('#like_event_count'+event_id).html(res.event_like_count);
          }
                 
            $('#event_like_name'+event_id).html(res.like_name);
            $('#event_dislike_name'+event_id).html(res.dislike_name);
              
          }
      });

    }

    function dislike_event_counts(event_id){
      var dislike_event_counts = $('#dislike_event_count'+event_id).html();

      if(dislike_event_counts !=0){
        $('#dislikes'+event_id).modal('show');
      }

    }

    function save_event(event_id){
      
        $.ajax({
          url: "{{url('event-save')}}/"+event_id,
          type: 'GET',
          success: function(res) {
          
            if(res.saved == 0){
              $("#save_event"+event_id).fadeIn("fast").attr('src','/public/front/images/save-light.png');     
              $('#save_event_name'+event_id).html('SAVE');
            } else {
              $("#save_event"+event_id).fadeIn("fast").attr('src','/public/front/images/save.png');    
              $('#save_event_name'+event_id).html('UNSAVE'); 
            }
          }
      });

    }

    $(".comment_submit").on("submit", function(){
        
       $('.post_commet').hide();
      });
</script>



@endsection

