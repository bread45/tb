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
                    $trainerName = DB::select('select spot_description from front_users where id ='.$images->trainer_id);
                    
                    if($customerId !=0){
                    $resource_count = DB::table('resource_count')->where(["resource_id" => $images->id])->count();
                    $resource_count_detail = DB::table('resource_count')->where(["user_id" => $customerId, "resource_id" => $images->id])->get();
                    } else {
                    $resource_count = DB::table('resource_count')->where(["resource_id" => $images->id])->count();
                    $resource_count_detail = DB::table('resource_count')->where(["resource_id" => $images->id])->get();
                    }
                ?>
               
                <div class="col-lg-4 col-md-6 col-sm-12 mb-4 ">
                 <a href="{{ url('/resource-details/')}}/{{ $images->id }}">
                  <div class="img_section"> <img src="{{ asset('/front/images/resource/'.$images->image_name) }}" class="img_responsive">
                   
                  </div></a>

                  <div class="detail_img_tab">
                    <span class="short_text">{{ \Carbon\Carbon::parse($images->created_at)->diffForHumans() }}</span>
                    <h3 class="img_tab_title"><a href="{{ url('/resource-details/')}}/{{ $images->id }}">{{ $images->title }}</a></h3>
                    
                    <h4 class="img_tab_subtitle">@foreach($trainerName as $Name)
                    <a href="{{ url('/provider/')}}/{{ $Name->spot_description }}">
                    @endforeach{{ $images->name }}</a></h4>
                    
                    <?php if(strlen($images->description) < 70){?>
                    <p class="text_inner">{{ str_limit(strip_tags($images->description), $limit = 70, $end = '...') }}</p>
                    <?php } else {?>
                    <p class="text_inner">{{ str_limit(strip_tags($images->description), $limit = 70, $end = '...') }}<a href="#" data-toggle="modal" data-target="#readmoredesc{{$images->id}}" style="color:#00a990;">Read More</a></p>
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
                          <p>{{ $images->description }}</p>
                        </div>
                      </div>
                    </div>
                    </div>
                    <?php }?>
                    <?php if($customerId !=0){?>
                    </a>
                   <li class="list_style">
                  <?php if($resource_count != 0){ if(count($resource_count_detail) !=0){ foreach ($resource_count_detail as $like) { if($like->likes == 1){?>
                      <img src="{{asset('/front/images/like.png')}}" class="icon_img" id="like_resource{{ $images->id }}" onClick="like_resource({{ $images->id }})">
                      <?php } else {?> 
                      <img src="{{asset('/front/images/like-light.png')}}" class="icon_img" id="like_resource{{ $images->id }}" onClick="like_resource({{ $images->id }})">
                      <?php } } } else {?>
                      <img src="{{asset('/front/images/like-light.png')}}" class="icon_img" id="like_resource{{ $images->id }}" onClick="like_resource({{ $images->id }})">
                      <?php } } else {?>
                      <img src="{{asset('/front/images/like-light.png')}}" class="icon_img" id="like_resource{{ $images->id }}" onClick="like_resource({{ $images->id }})">
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
                            $resource_like_name = DB::table('resource_count')->where(["resource_id" => $images->id, "likes" => 1])->get();
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
                      <img src="{{asset('/front/images/dislike.png')}}" class="icon_img" id="dislike_resource{{ $images->id }}" onClick="dislike_resource({{ $images->id }})">
                      <?php } else {?> 
                      <img src="{{asset('/front/images/dislike-light.png')}}" class="icon_img" id="dislike_resource{{ $images->id }}" onClick="dislike_resource({{ $images->id }})">
                      <?php } } } else {?>
                      <img src="{{asset('/front/images/dislike-light.png')}}" class="icon_img" id="dislike_resource{{ $images->id }}" onClick="dislike_resource({{ $images->id }})">
                      <?php } } else {?>
                      <img src="{{asset('/front/images/dislike-light.png')}}" class="icon_img" id="dislike_resource{{ $images->id }}" onClick="dislike_resource({{ $images->id }})">
                      <?php }
                        $resource_dislike_count = DB::table('resource_count')->where(["resource_id" => $images->id, "dislike" => 1])->count();
                        $resource_dislike_name = DB::table('resource_count')->where(["resource_id" => $images->id, "dislike" => 1])->get();
                        
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
                          ?>
                          <h4 class="likes_name">{{ $dislike_name->name }}</h4>
                          <?php }?>
                        </div>
                      </div>
                    </div>
                    </div>
                    <li class="list_style">
                      <?php if($resource_count != 0){ if(count($resource_count_detail) !=0){ foreach ($resource_count_detail as $comments) { if($comments->comments != ''){?>
                      <a href="#" data-toggle="modal" data-target="#comments{{$images->id}}"> <img src="{{asset('/front/images/comment.png')}}" class="icon_img"></a>
                      <?php } else {?> 
                      <a href="#" data-toggle="modal" data-target="#comments{{$images->id}}"> <img src="{{asset('/front/images/comment-light.png')}}" class="icon_img"></a>
                      <?php } } } else {?>
                      <a href="#" data-toggle="modal" data-target="#comments{{$images->id}}"> <img src="{{asset('/front/images/comment-light.png')}}" class="icon_img"></a>
                      <?php } } else {?>
                      <a href="#" data-toggle="modal" data-target="#comments{{$images->id}}"> <img src="{{asset('/front/images/comment-light.png')}}" class="icon_img"></a>
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
                          <form action="{{ route('resource-comment') }}" method="post">
                          
                            <input type="hidden" name="resource_id" value="{{ $images->id }}">
                            
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
                      <img src="{{asset('/front/images/save.png')}}" class="icon_img" id="save_resource{{ $images->id }}" onClick="save_resource({{ $images->id }})"><p class="count_num_save" id="save_resource_name{{ $images->id }}">UNSAVE</p>
                      <?php } else {?> 
                      <img src="{{asset('/front/images/save-light.png')}}" class="icon_img" id="save_resource{{ $images->id }}" onClick="save_resource({{ $images->id }})"><p class="count_num_save" id="save_resource_name{{ $images->id }}">SAVE</p>
                      <?php } } } else {?>
                      <img src="{{asset('/front/images/save-light.png')}}" class="icon_img" id="save_resource{{ $images->id }}" onClick="save_resource({{ $images->id }})"><p class="count_num_save" id="save_resource_name{{ $images->id }}">SAVE</p>
                      <?php } } else {?>
                      <img src="{{asset('/front/images/save-light.png')}}" class="icon_img" id="save_resource{{ $images->id }}" onClick="save_resource({{ $images->id }})"><p class="count_num_save" id="save_resource_name{{ $images->id }}">SAVE</p>
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
                            $resource_like_name = DB::table('resource_count')->where(["resource_id" => $images->id, "likes" => 1])->get();
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
                        $resource_dislike_count = DB::table('resource_count')->where(["resource_id" => $images->id, "dislike" => 1])->count();
                        $resource_dislike_name = DB::table('resource_count')->where(["resource_id" => $images->id, "dislike" => 1])->get();
                        
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

              </div>
              

              @endif
              @else
              <div class="row">
                
                @foreach($resourceImages as $images)
               
                <?php 
                    
                    $trainerName = DB::select('select spot_description from front_users where id ='.$images->trainer_id);
                    
                    if($customerId !=0){
                    $resource_count = DB::table('resource_count')->where(["resource_id" => $images->id])->count();
                    $resource_count_detail = DB::table('resource_count')->where(["user_id" => $customerId, "resource_id" => $images->id])->get();

                    } else {
                    $resource_count = DB::table('resource_count')->where(["resource_id" => $images->id])->count();
                    $resource_count_detail = DB::table('resource_count')->where(["resource_id" => $images->id])->get();
                    }

                    
                ?>
                
                <div class="col-lg-4 col-md-6 col-sm-12 mb-4 ">
                <a href="{{ url('/resource-details/')}}/{{ $images->id }}">
                  <div class="img_section"> <img src="{{ asset('/front/images/resource/'.$images->image_name) }}" class="img_responsive">
                    
                  </div></a>

                  <div class="detail_img_tab">
                  
                    <span class="short_text">{{ \Carbon\Carbon::parse($images->created_at)->diffForHumans() }}</span>   
                    <h3 class="img_tab_title"><a href="{{ url('/resource-details/')}}/{{ $images->id }}">{{ $images->title }}</a></h3>
                    
                    <h4 class="img_tab_subtitle">@foreach($trainerName as $Name)
                <a href="{{ url('/provider/')}}/{{ $Name->spot_description }}">
                @endforeach{{ $images->name }}</a></h4>
                    <?php if(strlen($images->description) < 70){?>
                    <p class="text_inner">{{ str_limit(strip_tags($images->description), $limit = 70, $end = '...') }}</p>
                    <?php } else {?>
                    <p class="text_inner">{{ str_limit(strip_tags($images->description), $limit = 70, $end = '...') }}<a href="#" data-toggle="modal" data-target="#readmoredesc{{$images->id}}" style="color:#00a990;">Read More</a></p>
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
                          <p>{{ $images->description }}</p>
                        </div>
                      </div>
                    </div>
                    </div>
                    <?php }?>
                    
                    <?php if($customerId !=0){?>
                   <li class="list_style">
                  <?php if($resource_count != 0){ if(count($resource_count_detail) !=0){foreach ($resource_count_detail as $like) { if($like->likes == 1){?>
                      <img src="{{asset('/front/images/like.png')}}" class="icon_img" id="like_resource{{ $images->id }}" onClick="like_resource({{ $images->id }})">
                      <?php } else {?> 
                      <img src="{{asset('/front/images/like-light.png')}}" class="icon_img" id="like_resource{{ $images->id }}" onClick="like_resource({{ $images->id }})">
                      <?php } } } else {?>
                      <img src="{{asset('/front/images/like-light.png')}}" class="icon_img" id="like_resource{{ $images->id }}" onClick="like_resource({{ $images->id }})">
                      <?php } } else {?>
                      <img src="{{asset('/front/images/like-light.png')}}" class="icon_img" id="like_resource{{ $images->id }}" onClick="like_resource({{ $images->id }})">
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
                            $resource_like_name = DB::table('resource_count')->where(["resource_id" => $images->id, "likes" => 1])->get();
                            foreach($resource_like_name as $name){
                          ?>
                          <h4 class="likes_name">{{ $name->name }}</h4>
                          <?php }?>
                        </div>
                      </div>
                    </div>
                    </div>
                    <li class="list_style">
                     
                      <?php if($resource_count != 0){ if(count($resource_count_detail) !=0){foreach ($resource_count_detail as $dislike) { if($dislike->dislike == 1){?>
                      <img src="{{asset('/front/images/dislike.png')}}" class="icon_img" id="dislike_resource{{ $images->id }}" onClick="dislike_resource({{ $images->id }})">
                      <?php } else {?> 
                      <img src="{{asset('/front/images/dislike-light.png')}}" class="icon_img" id="dislike_resource{{ $images->id }}" onClick="dislike_resource({{ $images->id }})">
                      <?php } } } else {?>
                      <img src="{{asset('/front/images/dislike-light.png')}}" class="icon_img" id="dislike_resource{{ $images->id }}" onClick="dislike_resource({{ $images->id }})">
                      <?php } } else {?>
                      <img src="{{asset('/front/images/dislike-light.png')}}" class="icon_img" id="dislike_resource{{ $images->id }}" onClick="dislike_resource({{ $images->id }})">
                      <?php }
                        $resource_dislike_count = DB::table('resource_count')->where(["resource_id" => $images->id, "dislike" => 1])->count();
                        $resource_dislike_name = DB::table('resource_count')->where(["resource_id" => $images->id, "dislike" => 1])->get();
                        
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
                          ?>
                          <h4 class="likes_name">{{ $dislike_name->name }}</h4>
                          <?php }?>
                        </div>
                      </div>
                    </div>
                    </div>
                    <li class="list_style">
                      <?php if($resource_count != 0){ if(count($resource_count_detail) !=0){ foreach ($resource_count_detail as $comments) { if($comments->comments != ''){?>
                      <a href="#" data-toggle="modal" data-target="#comments{{$images->id}}"> <img src="{{asset('/front/images/comment.png')}}" class="icon_img"></a>
                      <?php } else {?> 
                      <a href="#" data-toggle="modal" data-target="#comments{{$images->id}}"> <img src="{{asset('/front/images/comment-light.png')}}" class="icon_img"></a>
                      <?php } } } else {?>
                      <a href="#" data-toggle="modal" data-target="#comments{{$images->id}}"> <img src="{{asset('/front/images/comment-light.png')}}" class="icon_img"></a>
                      <?php } } else {?>
                      <a href="#" data-toggle="modal" data-target="#comments{{$images->id}}"> <img src="{{asset('/front/images/comment-light.png')}}" class="icon_img"></a>
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
                          <form action="{{ route('resource-comment') }}" method="post">
                          
                            <input type="hidden" name="resource_id" value="{{ $images->id }}">
                            
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
                      <img src="{{asset('/front/images/save.png')}}" class="icon_img" id="save_resource{{ $images->id }}" onClick="save_resource({{ $images->id }})"><p class="count_num_save" id="save_resource_name{{ $images->id }}">UNSAVE</p>
                      <?php } else {?> 
                      <img src="{{asset('/front/images/save-light.png')}}" class="icon_img" id="save_resource{{ $images->id }}" onClick="save_resource({{ $images->id }})"><p class="count_num_save" id="save_resource_name{{ $images->id }}">SAVE</p>
                      <?php } } } else {?>
                      <img src="{{asset('/front/images/save-light.png')}}" class="icon_img" id="save_resource{{ $images->id }}" onClick="save_resource({{ $images->id }})"><p class="count_num_save" id="save_resource_name{{ $images->id }}">SAVE</p>
                      <?php } } else {?>
                      <img src="{{asset('/front/images/save-light.png')}}" class="icon_img" id="save_resource{{ $images->id }}" onClick="save_resource({{ $images->id }})"><p class="count_num_save" id="save_resource_name{{ $images->id }}">SAVE</p>
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
                            $resource_like_name = DB::table('resource_count')->where(["resource_id" => $images->id, "likes" => 1])->get();
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
                        $resource_dislike_count = DB::table('resource_count')->where(["resource_id" => $images->id, "dislike" => 1])->count();
                        $resource_dislike_name = DB::table('resource_count')->where(["resource_id" => $images->id, "dislike" => 1])->get();
                        
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

              </div>


              @endif



            </div>
</section>
@stop
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
</script>
@endsection