    @extends('front.layout.app')
@section('title', 'Resource Library')
@section('content')
<section class="inner-banner-section bg-primary">
    <div class="container">
        <div class="banner-content">
            <h1>Resource Library</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Resource Library</li>
                </ol>
            </nav>
        </div>
    </div>
</section> 
<section class="page-content resource-library">
     <div class="container">

              <div class="row justify-content-center">

                 
                <input type="hidden" name="search" value="1">
                <div class="col-lg-4 col-sm-4">
                    <form action="{{ route('search-resource-library')}}" method="post">
                        <input type="hidden" name="search" value="1">
                  <div class="form-group search-group">
                    <input type="text" name="keyword" id="keyword" class="form-control search-control"
                      placeholder="Search by Keyword" value="{{ $keyword }}">
                  </div>
                  <div style="display: none;" id="filter-error" class="error invalid-feedback">Please select any filter
                  </div>

                </div>

                 <div class="col-lg-4 col-sm-4">
                  <div class="form-group service-group">
                    @if($ResourceCategory)
                    <select name="category" id="category" class="form-control service-control">
                      <option value="">Category</option>

                      @foreach( $ResourceCategory as $categor)
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
                    <div class="col-md-12" align="center">
                <input type="submit" name="submit" value="Search" class="text-center btn btn-info" style="margin-right:5px;">
              </div>
              <div class="col-md-12" align="center">
                
                <a href="{{ route('search-resource-library')}}" class="reset_link">Reset</a>
              </div>
            </div>
                @csrf
            </form>

              </div>
              <hr />

               @if(isset($searchResources))
                @if(sizeof($searchResources)<1)
                    <p class="text-center">No data Found</p>
                
                @else

                <div class="row">
                
                @foreach($searchResources as $images)
                
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
               
                <div class="col-lg-4 col-md-6 col-sm-12 mb-4 ">
                 <a href="{{ url('/resource-details/')}}/{{ base64_encode($images->id) }}">
                  @if($images->image_name != '')
                  <div class="img_section"> <img src="{{ asset('front/images/resource/'.$images->image_name) }}" class="img_responsive">
                    @else
                    <div class="img_section"> <img src="{{ asset('front/images/resource/details_default.png') }}" class="img_responsive">
                    @endif
                   @if($images->format == 'Video')
                    <div class="overlay_img"></div>
                    <div><img src="{{asset('front/images/play.png')}}" class="play_btn" id="play_btn"></div>
                    @endif
                  </div></a>

                  <div class="detail_img_tab">
                    <span class="short_text">{{ \Carbon\Carbon::parse($images->created_at)->diffForHumans() }}</span>
                    <h3 class="img_tab_title"><a href="{{ url('/resource-details/')}}/{{ base64_encode($images->id) }}">{{ $images->title }}</a></h3>
                    
                    <h4 class="img_tab_subtitle">@foreach($trainerName as $Name)
                    <a href="{{ url('/provider/')}}/{{ $Name->spot_description }}">
                    @endforeach{{ $images->name }}</a></h4>
                    
                    <?php if(strlen($images->description) < 70){?>
                    <p class="text_inner">{!! str_limit(strip_tags($images->description), $limit = 70, $end = '...') !!}</p>
                    <?php } else {?>
                    <p class="text_inner">{!! str_limit(strip_tags($images->description), $limit = 70, $end = '...') !!}<a href="#" data-toggle="modal" data-target="#readmoredesc{{$images->id}}" style="color:#00a990;">Read More</a></p>
                    <div class="modal fade" id="readmoredesc{{$images->id}}" tabindex="-1" role="dialog" aria-labelledby="readmoredesc" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
                        
                          <h4 class="likes_name">{{ $images->title }}</h4>
                          <p>{!! $images->description !!}</p>
                        </div>
                      </div>
                    </div>
                    </div>
                    <?php }?>
                    <?php if($customerId !=0){?>
                    </a>
                   <li class="list_style">
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

                          <a href="@if (Auth::guard('front_auth')->user()->user_role && $user_role == 'customer'){{route('customer.newprofile',$profile_img->first_name.'-'.$profile_img->last_name.'-'.$profile_img->id)}} @else {{url('provider/'.$spot_desc)}} @endif" >
                          <img src="@if(isset($profile_img->photo) && !empty($profile_img->photo)) {{ asset('front/profile/'.$profile_img->photo)}} @else {{ asset('front/images/details_default.png')}}  @endif" class="comments_profile">
                          <span class="likes_name">{{ $name->name }}</span>
                          <span class="short_text">{{ \Carbon\Carbon::parse($name->created_at)->diffForHumans() }}</span></a><br><br>
                          <?php }?>
                        </div>
                      </div>
                    </div>
                    </div>
                    <li class="list_style">
                     
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
                    </li>
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
                        
                          <div class="p-2">
                          <form action="{{ route('resource-comment') }}" method="post" class="comment_submit">
                          
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
                          </div>
                          
                          
                          <?php if($resource_comments_name){foreach($resource_comments_name as $comments){$profile_img = DB::table('front_users')->where(["id" => $comments->user_id])->first();
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
                  @if(Auth::guard('front_auth')->user()->user_role != 'trainer')
                    <li class="list_style">
                      <div class="share-button sharer" style="display: block;">
                        <a class="share-btn"> <img src="{{asset('front/images/share-light.png')}}" class="icon_img">
                        <p class="count_num">SHARE</p>
                      </a>
                        <div class="social top center networks-5 ">

                          <?php if($images->format == 'Video'){ ?>
                          <a class="fbtn share facebook" href="https://www.facebook.com/sharer/sharer.php?u={{ url('/resource-details/')}}/{{ base64_encode($images->id) }}&p[title]={{ $images->title }}" target="_blank" ><i class="fa fa-brands fa-facebook"></i></a> 
                          <?php } else { ?>
                            <a class="fbtn share facebook" href="https://www.facebook.com/sharer/sharer.php?u={{ url('/resource-details/')}}/{{ base64_encode($images->id) }}&p[title]={{ $images->title }}" target="_blank" ><i class="fa fa-brands fa-facebook"></i></a> 
                          <?php } ?>
                          <?php if($images->format == 'Video'){ ?>
                          <a class="fbtn share twitter" href="https://twitter.com/intent/tweet?text={{ $images->title }}&url={{ $images->format_name }}" target="_blank" ><i class="fa fa-brands fa-twitter"></i></a>
                          <?php } else { ?>
                            <a class="fbtn share twitter" href="https://twitter.com/intent/tweet?text={{ $images->title }}&url={{ asset('front/images/resource/'.$images->image_name) }}" target="_blank" ><i class="fa fa-brands fa-twitter"></i></a>
                            <?php } ?>

                            <a class="fbtn share envelope" href="mailto:?subject={{ $images->title }}&amp;body={{ url('/resource-details/')}}/{{ base64_encode($images->id) }}." target="_blank" ><i class="fa fa-envelope"></i></a>
                        </div>
                      </div>

                    </li>
                    <li class="list_style">
                      
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
                            $profile_img = DB::table('front_users')->where(["id" => $name->user_id])->first();
                          ?>
                          <a href="{{ route('resource-librarys')}}">
                          <img src="@if(isset($profile_img->photo) && !empty($profile_img->photo)) {{ asset('front/profile/'.$profile_img->photo)}} @else {{ asset('front/images/details_default.png')}}  @endif" class="comments_profile">
                          <span class="likes_name">{{ $name->name }}</span>
                          <span class="short_text">{{ \Carbon\Carbon::parse($name->created_at)->diffForHumans() }}</span></a><br><br>
                          <?php }?>
                        </div>
                      </div>
                    </div>
                    </div>
                    <li class="list_style">
                     
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
                            $profile_img = DB::table('front_users')->where(["id" => $dislike_name->user_id])->first();
                          ?>
                          <a href="{{ route('resource-librarys')}}">
                          <img src="@if(isset($profile_img->photo) && !empty($profile_img->photo)) {{ asset('front/profile/'.$profile_img->photo)}} @else {{ asset('front/images/details_default.png')}}  @endif" class="comments_profile">
                          <span class="likes_name">{{ $dislike_name->name }}</span>
                          <span class="short_text">{{ \Carbon\Carbon::parse($dislike_name->created_at)->diffForHumans() }}</span></a><br><br>
                          <?php }?>
                        </div>
                      </div>
                    </div>
                    </div>
                    <li class="list_style">
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
                    </li>
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
                        
                          <?php if($resource_comments_name){foreach($resource_comments_name as $comments){$profile_img = DB::table('front_users')->where(["id" => $comments->user_id])->first();?>
                          <div class="comment_div">
                          <a href="{{ route('resource-librarys')}}">
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
                            <p>you must <a href="{{ route('resource-librarys')}}" style="text-decoration: underline;color: #00ab91;">login</a></p>
                          </div>
                         
                        </div>
                      </div>
                    </div>
                   
                    <?php }?>
                  </div>
                </div>
                
                
                @endforeach

              </div>
              

              @endif
              @else
              <div class="row">
                
                @foreach($resourceImages as $images)
               
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
                
                <div class="col-lg-4 col-md-6 col-sm-12 mb-4 ">
                <a href="{{ url('/resource-details/')}}/{{ base64_encode($images->id) }}">
                  @if($images->image_name != '')
                  <div class="img_section"> <img src="{{ asset('front/images/resource/'.$images->image_name) }}" class="img_responsive">
                    @else
                    <div class="img_section"> <img src="{{ asset('front/images/resource/details_default.png') }}" class="img_responsive">
                    @endif
                   @if($images->format == 'Video')
                    <div class="overlay_img"></div>
                    <div><img src="{{asset('front/images/play.png')}}" class="play_btn" id="play_btn"></div>
                    @endif 
                  </div></a>

                  <div class="detail_img_tab">
                  
                    <span class="short_text">{{ \Carbon\Carbon::parse($images->created_at)->diffForHumans() }}</span>   
                    <h3 class="img_tab_title"><a href="{{ url('/resource-details/')}}/{{ base64_encode($images->id) }}">{{ $images->title }}</a></h3>
                    
                    <h4 class="img_tab_subtitle">@foreach($trainerName as $Name)
                <a href="{{ url('/provider/')}}/{{ $Name->spot_description }}">
                @endforeach{{ $images->name }}</a></h4>
                    <?php if(strlen($images->description) < 70){?>
                    <p class="text_inner">{!! str_limit(strip_tags($images->description), $limit = 70, $end = '...') !!}</p>
                    <?php } else {?>
                    <p class="text_inner">{!! str_limit(strip_tags($images->description), $limit = 70, $end = '...') !!}<a href="#" data-toggle="modal" data-target="#readmoredesc{{$images->id}}" style="color:#00a990;">Read More</a></p>
                    <div class="modal fade" id="readmoredesc{{$images->id}}" tabindex="-1" role="dialog" aria-labelledby="readmoredesc" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
                        
                          <h4 class="likes_name">{{ $images->title }}</h4>
                          <p>{!! $images->description !!}</p>
                        </div>
                      </div>
                    </div>
                    </div>
                    <?php }?>
                    
                    <?php if($customerId !=0){?>
                   <li class="list_style">
                  <?php if($resource_count != 0){ if(count($resource_count_detail) !=0){foreach ($resource_count_detail as $like) { if($like->likes == 1){?>
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

                          <a href="@if (Auth::guard('front_auth')->user()->user_role && $user_role == 'customer'){{route('customer.newprofile',$profile_img->first_name.'-'.$profile_img->last_name.'-'.$profile_img->id)}} @else {{url('provider/'.$spot_desc)}} @endif" >
                          <img src="@if(isset($profile_img->photo) && !empty($profile_img->photo)) {{ asset('front/profile/'.$profile_img->photo)}} @else {{ asset('front/images/details_default.png')}}  @endif" class="comments_profile">
                          <span class="likes_name">{{ $name->name }}</span>
                          <span class="short_text">{{ \Carbon\Carbon::parse($name->created_at)->diffForHumans() }}</span></a><br><br>
                          <?php }?>
                        </div>
                      </div>
                    </div>
                    </div>
                    <li class="list_style">
                     
                      <?php if($resource_count != 0){ if(count($resource_count_detail) !=0){foreach ($resource_count_detail as $dislike) { if($dislike->dislike == 1){?>
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
                          <span class="short_text">{{ \Carbon\Carbon::parse($dislike_name->created_at)->diffForHumans() }}</span><br><br>
                          <?php }?>
                        </div>
                      </div>
                    </div>
                    </div>
                    <li class="list_style">
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
                    </li>
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
                        
                          <div class="p-2">
                          <form action="{{ route('resource-comment') }}" method="post" class="comment_submit">
                          
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
                          </div>
                          
                          
                          <?php if($resource_comments_name){foreach($resource_comments_name as $comments){$profile_img = DB::table('front_users')->where(["id" => $comments->user_id])->first();
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
                  @if(Auth::guard('front_auth')->user()->user_role != 'trainer')
                    <li class="list_style">
                      <div class="share-button sharer" style="display: block;">
                        <a class="share-btn"> <img src="{{asset('front/images/share-light.png')}}" class="icon_img">
                        <p class="count_num">SHARE</p>
                      </a>
                        <div class="social top center networks-5 ">

                          <?php if($images->format == 'Video'){ ?>
                          <a class="fbtn share facebook" href="https://www.facebook.com/sharer/sharer.php?u={{ url('/resource-details/')}}/{{ base64_encode($images->id) }}&p[title]={{ $images->title }}" target="_blank" ><i class="fa fa-brands fa-facebook"></i></a> 
                          <?php } else { ?>
                            <a class="fbtn share facebook" href="https://www.facebook.com/sharer/sharer.php?u={{ url('/resource-details/')}}/{{ base64_encode($images->id) }}&p[title]={{ $images->title }}" target="_blank" ><i class="fa fa-brands fa-facebook"></i></a> 
                          <?php } ?>
                          <?php if($images->format == 'Video'){ ?>
                          <a class="fbtn share twitter" href="https://twitter.com/intent/tweet?text={{ $images->title }}&url={{ $images->format_name }}" target="_blank" ><i class="fa fa-brands fa-twitter"></i></a>
                          <?php } else { ?>
                            <a class="fbtn share twitter" href="https://twitter.com/intent/tweet?text={{ $images->title }}&url={{ asset('front/images/resource/'.$images->image_name) }}" target="_blank" ><i class="fa fa-brands fa-twitter"></i></a>
                            <?php } ?>
                          <a class="fbtn share envelope" href="mailto:?subject={{ $images->title }}&amp;body={{ url('/resource-details/')}}/{{ base64_encode($images->id) }}." target="_blank" ><i class="fa fa-envelope"></i></a>
                        </div>
                      </div>

                    </li>
                    <li class="list_style">
                      
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
                            $profile_img = DB::table('front_users')->where(["id" => $name->user_id])->first();
                          ?>
                          <a href="{{ route('resource-librarys')}}">
                          <img src="@if(isset($profile_img->photo) && !empty($profile_img->photo)) {{ asset('front/profile/'.$profile_img->photo)}} @else {{ asset('front/images/details_default.png')}}  @endif" class="comments_profile">
                          <span class="likes_name">{{ $name->name }}</span>
                          <span class="short_text">{{ \Carbon\Carbon::parse($name->created_at)->diffForHumans() }}</span></a><br><br>
                          <?php }?>
                        </div>
                      </div>
                    </div>
                    </div>
                    <li class="list_style">
                     
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
                            $profile_img = DB::table('front_users')->where(["id" => $dislike_name->user_id])->first();
                          ?>
                          <a href="{{ route('resource-librarys')}}">
                          <img src="@if(isset($profile_img->photo) && !empty($profile_img->photo)) {{ asset('front/profile/'.$profile_img->photo)}} @else {{ asset('front/images/details_default.png')}}  @endif" class="comments_profile">
                          <span class="likes_name">{{ $dislike_name->name }}</span>
                          <span class="short_text">{{ \Carbon\Carbon::parse($dislike_name->created_at)->diffForHumans() }}</span></a><br><br>
                          <?php }?>
                        </div>
                      </div>
                    </div>
                    </div>
                    <li class="list_style">
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
                    </li>
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
                        
                          <?php if($resource_comments_name){foreach($resource_comments_name as $comments){$profile_img = DB::table('front_users')->where(["id" => $comments->user_id])->first();?>
                          <div class="comment_div">
                          <a href="{{ route('resource-librarys')}}">
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
                            <p>you must <a href="{{ route('resource-librarys')}}" style="text-decoration: underline;color: #00ab91;">login</a></p>
                          </div>
                         
                        </div>
                      </div>
                    </div>
                   
                    <?php }?>
                  </div>
                  
                </div>
                
               
                @endforeach

              </div>


              @endif



            </div>
</section>
@stop
<style type="text/css">
  .facebook {
    background-color: #3b5998 !important;
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

    $(".comment_submit").on("submit", function(){
        
       $('.post_commet').hide();
      });
</script>
@endsection