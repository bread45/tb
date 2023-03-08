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


                @foreach($resourceDetails as $images)
                
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
                           
                           <!--<a href="{{ asset('/front/images/resource/'.$images->format_name) }}">--><img class="img-fluid position_relative" style="position: relative;" src="{{ asset('/front/images/resource/'.$images->image_name) }}" alt="img_responsive"><!--</a>-->
                         <?php } else {
                        ?>

                          <a href="#" data-toggle="modal" data-url="{{ asset('/front/images/resource/'.$images->format_name) }}" data-target="#resource_details{{$images->id}}" id="imgPopup"><img class="img-fluid position_relative" style="position: relative;" src="{{ asset('/front/images/resource/'.$images->image_name) }}" alt="img_responsive"></a>
                        <?php } ?>

                          <!-- Image -->


                        </div>
                      </div>
                    </div>
                  </section>

                  <div class="modal fade" id="resource_details{{$images->id}}" tabindex="-1" role="dialog" aria-labelledby="likes" aria-hidden="true">
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

                  <section class="py-4">
                  <div class="container">
                    <div class="row justify-content-center">
                      <div class="col-md-10">
                        <!-- Subheading -->
                        <h6 class="mb-3 text-muted font-weight-bold">
                            By {{ $images->name }} / {{ date('F d, Y',strtotime($images->created_at)) }}
                        </h6>
                        <!--<span class="short_text">{{ \Carbon\Carbon::parse($images->created_at)->diffForHumans() }}</span>-->
                        <!-- Heading -->
                        <h3 class="mb-1 blog_title">{{ $images->title }}</h3>
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
                    <li class="list_style mr_lf">
                     
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
                    <li class="list_style mr_lf">
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
                    <li class="list_style mr_lf">
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
                    <li class="list_style mr_lf">
                      
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
                    <li class="list_style mr_lf">
                     
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
                    <li class="list_style mr_lf">
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
                  </div>
                </section>
                
                @endforeach

            
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