@extends('front.trainer.layout.trainer')
@section('title', 'Add Resource')
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
                        <h1 class="h3 text-muted font-weight-normal mb-0 mt-lg-0 mt-3 order-lg-0 order-2">Add / Edit Resource</h1>
                        
                        @include('front.trainer.layout.includes.header')
                    </div>

                    @if($providerStatus[0]->is_subscription)
                    @if($trailingProviderOrders < 1)
                    @if($providerOrdersCount < 1)
                    <div class="popup popup-danger text-center" role="alert">
                    Your subscription is cancelled and your page will not be visible to the public until you activate your account again, You can reactivate your subscription in the account information tab.
                    </div>
                    @endif
                    @endif
                    @endif
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body p-lg-5">
                                @if (session('message'))
                                    <div class="alert alert-success">
                                        {{ session('message') }}
                                    </div>
                                @endif
                                @if (session('error'))
                                    <div class="alert alert-danger">
                                        {{ session('error') }}
                                    </div>
                                @endif
                                
                                <form class="form-row add-resource-form" method="POST" action="{{route('resource.store')}}" enctype='multipart/form-data'>
                                        @csrf
                                        <input type="hidden" name="resource_image_name" id="resource_image_name">
                                        <input type="hidden" name="youtube_error" id="youtube_error">
                                        <input type="hidden" name="resource_image_path" id="resource_image_path" value="@if(isset($trainerResource->image_name) && !empty($trainerResource->image_name)) {{ asset('front/images/resource/'.$trainerResource->image_name)}} @else {{ asset('front/images/details_default.png')}} @endif">
                                        <input type="hidden" name="resource_video_path" id="resource_video_path" value="@if(isset($trainerResource->format_name)) {{ $trainerResource->format_name}}  @endif">
                                        <input type="hidden" name="resourceId" value="@if(isset($trainerResource->id)) {{ $trainerResource->id }} @endif" />
                                        <input type="hidden" name="videoTypes" id="videoTypes" value="@if(isset($trainerResource->type)) {{ $trainerResource->type }} @endif" />
                                        
                                        <div class="form-group col-lg-6">
                                            <label for="title-input">Title</label> 
                                            <input type="text" class="form-control" name="title" required id="name-input" placeholder="enter resource title" value="@if(isset($trainerResource->title)){{ $trainerResource->title }}@else{{old('title')}}@endif">
                                            @if ($errors->has('title'))
                                                <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('title') }}</div>
                                            @endif
                                        </div>
                                        <div class="form-group col-lg-6">
                                            <label for="subtitle-input">Subtitle</label> 
                                            <input type="text" class="form-control" name="subtitle" required id="subtitle-input" placeholder="enter resource subtitle" value="@if(isset($trainerResource->subtitle)){{ $trainerResource->subtitle }}@else{{old('subtitle')}}@endif">
                                            @if ($errors->has('subtitle'))
                                                <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('subtitle') }}</div>
                                            @endif
                                        </div>
                                        <div class="form-group col-lg-4">
                                            <label for="resource-input">RESOURCE CATEGORY</label>
                                            <select name="resource_category" class="form-control" required id="resource-input">
                                                <option value="">select resource category</option>
                                                @foreach($resources_category as $category)

                                                 <option @if(isset($trainerResource->category)) @if($trainerResource->category == $category->name) selected @endif @else @if(old('resource_category') == $category->name) selected @endif @endif value="{{ $category->name }}" >{{ $category->name }}</option> 
                                                @endforeach
                                                
                                            </select>
                                            @if ($errors->has('resource'))
                                                <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('resource') }}</div>
                                            @endif
                                        </div>
                                        
                                       
                                        <div class="form-group col-lg-4">
                                            <label for="format-input">Format</label>
                                            <select name="format" class="form-control" required id="format-input">
                                                <option value="">select your format</option>
                                                @foreach($formats as $format)
                                                 <option @if(isset($trainerResource->format)) @if($trainerResource->format == $format) selected @endif @else @if(old('format') == $format) selected @endif @endif value="{{ $format }}" >{{ $format }}</option> 
                                                @endforeach
                                            </select>
                                            @if ($errors->has('format'))
                                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('format') }}</div>
                                            @endif
                                        </div>
                                        
                                        @if(isset($trainerResource->format))
                                        @if($trainerResource->format == 'Video')
                                        <!--<div class="form-group col-lg-6" id="video-upload" style="display:none">
                                            <label for="number-input">Upload</label>
                                            <div class="upload-input">
                                                
                                                <input type="file" name="upload" id="resource_image_tmp" required class="form-control">
                                                @if ($errors->has('upload'))
                                                <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('upload') }}</div>
                                                @endif
                                            </div>
                                        </div>-->
                                        <div class="form-group col-lg-4" id="video-url">
                                            <!--<label for="name-input">URl</label> <br />-->
                                            <div class="form-check form-check-inline">
                                              <input class="form-check-input videoType" type="radio" name="videoType" id="videofileUrl" value="1" @if(isset($trainerResource->type))@if($trainerResource->type==1) checked @else @endif @endif>
                                              <label class="form-check-label" for="videoCheckbox1">Cloud URL</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                              <input class="form-check-input videoType" type="radio" name="videoType" id="videoonlineUrl" value="2" @if(isset($trainerResource->type))@if($trainerResource->type==2) checked @else @endif @endif>
                                              <label class="form-check-label" for="videoCheckbox2">File System</label>
                                            </div>
                                            <input type="text" class="form-control" name="url" id="url-input" required placeholder="enter video url" value="@if(isset($trainerResource->format_name))@if($trainerResource->type==1){{ $trainerResource->format_name }} @endif @endif">
                                            @if ($errors->has('url'))
                                                <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('url') }}</div>
                                            @endif
                                            <input type="file" class="form-control" name="videoUrl" id="videofile_input"  style="display: none;">
                                            <span id="videofile_format" style="display:none;">(only mp4 file format is supported)</span>
                                            @if ($errors->has('videoUrl'))
                                                <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('videoUrl') }}</div>
                                            @endif
                                        </div>
                                        @else
                                        <!--<div class="form-group col-lg-6" id="video-upload">
                                            <label for="number-input">Upload</label>
                                            <div class="upload-input">
                                                
                                                <input type="file" name="upload" id="resource_image_tmp" class="form-control">
                                                @if ($errors->has('upload'))
                                                <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('upload') }}</div>
                                                @endif
                                            </div>
                                        </div>-->
                                        <div class="form-group col-lg-4" id="video-url" style="display:none">
                                            <!--<label for="name-input">URl</label> <br />-->
                                            <div class="form-check form-check-inline">
                                              <input class="form-check-input videoType" type="radio" name="videoType" id="videofileUrl" value="1" @if(isset($trainerResource->type))@if($trainerResource->type==1) checked @else checked @endif @endif>
                                              <label class="form-check-label" for="videoCheckbox1">Cloud URL</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                              <input class="form-check-input videoType" type="radio" name="videoType" id="videoonlineUrl" value="2" @if(isset($trainerResource->type))@if($trainerResource->type==2) checked @else @endif @endif>
                                              <label class="form-check-label" for="videoCheckbox2">File System</label>
                                            </div>
                                            <input type="text" class="form-control" name="url" id="url-input" required placeholder="enter video url" value="">
                                            @if ($errors->has('url'))
                                                <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('url') }}</div>
                                            @endif
                                            <input type="file" class="form-control" name="videoUrl" id="videofile_input"  style="display: none;">
                                            <span id="videofile_format" style="display:none;">(only mp4 file format is supported)</span>
                                            @if ($errors->has('videoUrl'))
                                                <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('videoUrl') }}</div>
                                            @endif
                                        </div>
                                        @endif
                                        @else
                                        <!--<div class="form-group col-lg-6" id="video-upload" style="display:none">
                                            <label for="number-input">Upload</label>
                                            <div class="upload-input">
                                                
                                                <input type="file" name="upload" id="resource_image_tmp" required class="form-control">
                                                @if ($errors->has('upload'))
                                                <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('upload') }}</div>
                                                @endif
                                            </div>
                                        </div>-->

                                        <div class="form-group col-lg-4" id="video-url" style="display:none">
                                            <!--<label for="name-input">URl</label> <br />-->
                                            <div class="form-check form-check-inline">
                                              <input class="form-check-input videoType" type="radio" name="videoType" id="videofileUrl" value="1" checked >
                                              <label class="form-check-label" for="videoCheckbox1">Cloud URL</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                              <input class="form-check-input videoType" type="radio" name="videoType" id="videoonlineUrl" value="2" @if(isset($trainerResource->type))@if($trainerResource->type==2) checked @else @endif @endif>
                                              <label class="form-check-label" for="videoCheckbox2">File System</label>
                                            </div>
                                            <input type="text" class="form-control" name="url" id="url-input" required placeholder="enter video url" value="@if(isset($trainerResource->format_name))@if($trainerResource->type==1){{ $trainerResource->format_name }}@else{{old('url')}}@endif @endif">
                                            @if ($errors->has('url'))
                                                <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('url') }}</div>
                                            @endif

                                            <input type="file" class="form-control" name="videoUrl" id="videofile_input"  style="display: none;">
                                            <span id="videofile_format" style="display:none;">(only mp4 file format is supported)</span>
                                            @if ($errors->has('videoUrl'))
                                                <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('videoUrl') }}</div>
                                            @endif
                                        </div>
                                        @endif
                                        @if(isset($trainerResource->format))
                                        <div class="form-group col-lg-4" id="upload_image_show">
                                            <label for="number-input">Image Upload</label>
                                            <div class="upload-input">
                                                
                                                <input type="file" name="image_upload" id="image_upload" class="form-control image_upload">
                                                @if ($errors->has('image_upload'))
                                                <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('image_upload') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        @else
                                        <div class="form-group col-lg-4" id="upload_image_show">
                                            <label for="number-input">Image Upload</label>
                                            <div class="upload-input">
                                                
                                                <input type="file" name="image_upload" id="image_upload" required class="form-control image_upload">
                                                @if ($errors->has('image_upload'))
                                                <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('image_upload') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        @endif
                                        @if(isset($trainerResource->format)) 
                                        <div class="form-group" style="width: 320px;padding: 15px;position: relative;">
                                            <label for="edit-input"></label>
                                            <div class="edit-img">
                                                @if($trainerResource->format == 'Image')
                                                <img id="show_image" src="@if(isset($trainerResource->format_name) && !empty($trainerResource->format_name)) {{ asset('front/images/resource/'.$trainerResource->format_name)}} @else {{ asset('front/images/details_default.png')}}  @endif " alt="Yoga">
                                                   
                                            @endif
                                            @if($trainerResource->format == 'Article')
                                                <img id="show_image" src="@if(isset($trainerResource->image_name) && !empty($trainerResource->image_name)) {{ asset('front/images/resource/'.$trainerResource->image_name)}} @else {{ asset('front/images/details_default.png')}}  @endif " alt="Yoga">
                                                  <!--<a href="{{ asset('front/images/resource/'.$trainerResource->image_name)}}" target="_blank" class="edit-link">View Article</a>  -->
                                            @endif
                                            @if($trainerResource->format == 'EBook')
                                                <img id="show_image" src="@if(isset($trainerResource->format_name) && !empty($trainerResource->format_name)) {{ asset('front/images/document.jpg')}} @else {{ asset('front/images/details_default.png')}}  @endif " alt="Yoga">
                                                  <a href="{{ asset('front/images/resource/'.$trainerResource->format_name)}}" target="_blank" class="edit-link">View EBook</a>  
                                            @endif
                                            @if($trainerResource->format == 'Video')
                                               @if($trainerResource->type == '1')
                                                    <img id="show_image" src="@if(isset($trainerResource->image_name) && !empty($trainerResource->image_name)) {{ asset('front/images/resource/'.$trainerResource->image_name)}} @else {{ asset('front/images/details_default.png')}}  @endif " alt="Yoga" style="max-width: 100%;object-fit: cover;">
                                                      <a href="#" data-toggle="modal" data-target="#resource_details{{$trainerResource->id}}" id="imgPopup">
                                                    <div><img src="{{asset('front/images/play.png')}}" class="play_btn_resource"></div></a>

                                                @else
                                                    <img id="show_image" src="@if(isset($trainerResource->image_name) && !empty($trainerResource->image_name)) {{ asset('front/images/resource/'.$trainerResource->image_name)}} @else {{ asset('front/images/details_default.png')}}  @endif " alt="Yoga" style="max-width: 100%;object-fit: cover;">
                                                      <a href="#" data-toggle="modal" data-target="#resource_details{{$trainerResource->id}}" id="imgPopup">
                                                    <div><img src="{{asset('front/images/play.png')}}" class="play_btn_resource"></div></a>

                                                @endif
                                                
                                            @endif 
                                            </div>
                                        </div>
                                        @endif
                                        
                                        
                                        <!-- <div class="form-group col-lg-6">
                                            <label for="tag-input">Tag</label> 
                                            <input type="text" class="form-control input-lg" name="tag" required id="tag-input" placeholder="" autocomplete="off" value="@if(isset($trainerResource->tags)){{ $trainerResource->tags }}@else{{old('tag')}}@endif">
                                            @if ($errors->has('tag'))
                                                <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('tag') }}</div>
                                            @endif
                                        </div> -->
                                         
                                        <div class="form-group col-lg-12">
                                            <label for="massage-inputss">Body content </label>
                                            <textarea class="form-control ckeditor" name="message" id="massage-input" maxlength=""placeholder="enter message here..." required>@if(isset($trainerResource->description)){{$trainerResource->description}}@else{{old('message')}}@endif</textarea>
                                            @if ($errors->has('message'))
                                                <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('message') }}</div>
                                            @endif
                                        </div>

                                        <div class="form-group col-lg-12">
                                            <label for="keyTag-input">Keyword Tags</label> 
                                            <input type="text" class="form-control" name="keyTag" required id="keyTag-input" placeholder="enter keywords separated by commas; ex: mobility exercises, strength training, marathon nutrition, etc." value="@if(isset($trainerResource->keyword)){{ $trainerResource->keyword }}@else{{old('keyword')}}@endif">
                                        </div>
                                        
                                        <div class="col-lg-12 d-flex justify-content-center mb-1">
                                            <input type="submit" id="resource-submit" value="Publish" class="btn btn-danger btn-lg" style="margin-right:5px;" />
                                            </div>
                                            <div class="col-lg-12 d-flex justify-content-center mb-1">
                                            <button type="button" class="btn no-border bg-transparent preveiw_button" data-toggle="modal"
                      data-target=".bd-example-modal-lg">
                      Preview</button>
                                        </div>

                                    </form>
                                 
                                </div>
                            </div>
                        </div>
                    </div>

                    @include('front.trainer.layout.includes.footer')

                </div>
            </div>
        </div>

        <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Preview</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <section class="pt-4 no_padding">
          <div class="container">
            <div class="row justify-content-center">
              <div class="col-12 col-md-10">
              @if(isset($trainerResource->format)) 
                @if($trainerResource->format == 'Image')
                    <img class="img-fluid position_relative" src="@if(isset($trainerResource->format_name) && !empty($trainerResource->format_name)) {{ asset('front/images/resource/'.$trainerResource->format_name)}} @else {{ asset('front/images/details_default.png')}}  @endif" alt="...">
                @endif
                @if($trainerResource->format == 'Video')
                    <div style="width: 320px;padding: 15px;position: relative;">
                    <a href="#" id="resource_url_perview" data-toggle="modal" data-target="#resource_details{{$trainerResource->id}}" id="imgPopup"><img class="img-fluid position_relative" src="@if(isset($trainerResource->image_name) && !empty($trainerResource->image_name)) {{ asset('front/images/resource/'.$trainerResource->image_name)}} @else {{ asset('front/images/details_default.png')}}  @endif" alt="..." style="object-fit: cover;"><div><img src="{{asset('/front/images/play.png')}}" class="play_btn_perview"></div></a>
                    <img class="add-img-fluid position_relative" style="display:none;" src="@if(isset($trainerResource->image_name) && !empty($trainerResource->image_name)) {{ asset('front/images/resource/'.$trainerResource->format_name)}} @else {{ asset('front/images/details_default.png')}}  @endif" alt="..."></div>
                    
                @endif  
                @if($trainerResource->format == 'Article')
                    <a href="#" id="resource_url_perview" style="display:none;" data-toggle="modal" data-target="#resource_details{{$trainerResource->id}}" id="imgPopup"><img class="img-fluid position_relative" src="@if(isset($trainerResource->format_name) && !empty($trainerResource->format_name)) {{ asset('front/images/resource/'.$trainerResource->image_name)}} @else {{ asset('front/images/details_default.png')}}  @endif" alt="..."></a>
                     <img class="add-img-fluid position_relative" src="@if(isset($trainerResource->image_name) && !empty($trainerResource->image_name)) {{ asset('front/images/resource/'.$trainerResource->image_name)}} @else {{ asset('front/images/details_default.png')}} @endif" alt="...">
                @endif 
                @if($trainerResource->format == 'EBook')
                    <a href="{{ asset('front/images/resource/'.$trainerResource->format_name)}}" target="_blank" id="resource_url_perview"><img class="img-fluid position_relative" src="@if(isset($trainerResource->format_name) && !empty($trainerResource->format_name)) {{ asset('front/images/document.jpg')}} @else {{ asset('front/images/details_default.png')}}  @endif" alt="..."></a>
                @endif

              @else
              <a id="resource_url_perview" data-toggle="modal" data-target="#perview_resource_details" id="imgPopup"><img class="img-fluid position_relative" src="@if(isset($trainerResource->format_name) && !empty($trainerResource->format_name)) {{ asset('front/images/resource/'.$trainerResource->format_name)}} @else {{ asset('front/images/details_default.png')}}  @endif" alt="..."></a>
              <img class="add-img-fluid position_relative" src="@if(isset($trainerResource->format_name) && !empty($trainerResource->format_name)) {{ asset('front/images/resource/'.$trainerResource->format_name)}} @else {{ asset('front/images/details_default.png')}}  @endif" alt="...">
              @endif
                

                <!-- Image -->


              </div>
            </div>
          </div>

           
        </section>
        <section class="py-4">
          <div class="container">
            <div class="row justify-content-center">
              <div class="col-12 col-md-10">
                
                <!-- Heading -->
                <h3 class="mb-1 blog_title">@if(isset($trainerResource->title)){{ $trainerResource->title }}@else{{old('title')}}@endif</h3>
                <h4 class="blog_subtitle">@if(isset($trainerResource->subtitle)){{ $trainerResource->subtitle }}@else{{old('subtitle')}}@endif</h4>
                <h4 class="blog_subtitles"><?php echo Auth::guard('front_auth')->user()->business_name;?></h4>

                <p class="mb-3">
                <p style="text-align:justify;overflow-wrap: anywhere;" class="blog_desc"><span style="font-size:11pt"><span style="font-family:Arial"><span
                        style="color:#000000">@if(isset($trainerResource->description)){{strip_tags(htmlspecialchars_decode($trainerResource->description))}}@else{{old('message')}}@endif</span></span></span></p>

                

              </div>
            </div>
          </div>
        </section>

      </div>
    </div>
  </div>

  <div id="uploadimageModal" class="modal" role="dialog" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                    <h4 class="modal-title">Upload & Crop Image</h4>
                <button type="button" class="close crop_close" data-dismiss="modal">&times;</button>
                
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

@if(isset($trainerResource->format))
         <div class="modal fade resModal" id="resource_details{{$trainerResource->id}}" tabindex="-1" role="dialog" aria-labelledby="likes" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
                        <?php if($trainerResource->format == 'Video'){
                          if (strpos($trainerResource->format_name, 'youtube') > 0) { 

                            parse_str( parse_url( $trainerResource->format_name, PHP_URL_QUERY ), $my_array_of_vars);
                            $v_id = $my_array_of_vars['v'];    
                            ?>
                              <iframe id="youtube_Geeks3" width="450" height="350" src="https://www.youtube.com/embed/<?php echo $v_id; ?>" frameborder="0" allowfullscreen></iframe>
                              <video width="450" height="350" controls style="display:none;" id="video_Geeks3">
                                <source src="" type="video/mp4" id="video_Geeks3">
                              </video>
                          <?php  }
                          else {

                          ?>
                       <iframe id="youtube_Geeks3" width="450" height="350" src="" frameborder="0" allowfullscreen style="display:none;"></iframe>
                       <video width="450" height="350" controls id="video_Geeks3">
                        <source src="{{ $trainerResource->format_name }}" type="video/mp4" id="video_Geeks3">
                      </video>
                       <?php } } else {

                        ?>
                       <iframe id="youtube_Geeks3" width="450" height="350" src="" frameborder="0" allowfullscreen style="display:none;"></iframe>
                       <video width="450" height="350" controls id="video_Geeks3">
                        <source src="" type="video/mp4" id="video_Geeks3">
                      </video>
                       <?php }?>
                        </div>
                      </div>
                    </div>
                    </div>
                    @endif


                    <div class="modal fade resModal" id="perview_resource_details" tabindex="-1" role="dialog" aria-labelledby="likes" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
                        
                       <iframe id="youtube_Geeks3" width="450" height="350" src="" frameborder="0" allowfullscreen style="display:none;"></iframe>
                       <video width="450" height="350" controls id="video_Geeks3">
                        <source src="" type="video/mp4" id="video_Geeks3">
                      </video>
                       
                        </div>
                      </div>
                    </div>
                    </div>
@endsection

@section('pagescript')
<link rel="stylesheet" type="text/css" href="{{ asset('../front/css/croppie.css') }}"> 
<script src="{{ asset('../front/js/croppie.js') }}"></script> 
<script src="//cdn.ckeditor.com/4.13.0/full/ckeditor.js"></script>
<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.7/jquery.validate.min.js"></script>
<!-- <script src="{{ asset('../front/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('../front/ckeditor/adapters/jquery.js') }}"></script> -->
<style>
label.radio-inline{
  margin: 0 10px 0 0;
}
.checkRecurring{
 margin-right: 8px;  
 float: right;
 margin: 2px 10px;
}
.play_btn_resource{
    border-radius: 50%;
    display: block;
    position: absolute;
    left: calc(50% - 32px);
    top: calc(50% - 18px);
    overflow: hidden;
    cursor: pointer;
}
.play_btn_perview{
        border-radius: 50%;
    display: block;
    position: absolute;
    left: calc(50% - 32px);
    top: calc(50% - 18px);
    overflow: hidden;
    cursor: pointer;
}
</style>
<script>


CKEDITOR.config.height = '300px';   // CSS unit (percent).
    CKEDITOR.config.enterMode = CKEDITOR.ENTER_BR;
    CKEDITOR.config.shiftEnterMode = CKEDITOR.ENTER_BR;
   
    CKEDITOR.config.toolbar = 'Basic';

    CKEDITOR.config.toolbar_Basic =
[
    ['Bold', 'Italic','Underline', '-', 'Link', 'Unlink']
];

var editor = CKEDITOR.replace( 'massage-input', {
    language: 'en',
    extraPlugins: 'notification'
});

editor.on( 'required', function( evt ) {
    editor.showNotification( 'This field is required.', 'warning' );
    
    evt.cancel();
} );

$( ".btn-danger" ).click(function() {
  var editorData= CKEDITOR.instances['massage-input'].getData();
  if(editorData !=''){
    return true;
  } else {
  
  setTimeout(function(){ $('#cke_notifications_area_massage-input').hide(); }, 300);
    
  }
});

$('#format-input').on('change', function() {
   var format_value = this.value;
   if(format_value == 'Video'){
        $('#video-upload').hide();
        $('#upload_image_show').hide();
        $('#image_upload').prop('required',false);
        $('#video-url').show();
        $('#resource_image').prop('required',false);
        $('#url-input').prop('required',true);
   } else if(format_value == 'Image' || format_value == 'Article' || format_value == 'EBook'){
        
        $('#video-upload').show();
        $('#upload_image_show').show();
        $('#video-url').hide();
        $('#image_upload').prop('required',true);
        $('#resource_image').prop('required',true);
        $('#url-input').prop('required',false);
        
   }
});

$('.videoType').on('change', function(){
    var videoType = $(this).val();
    if(videoType == '2'){
        $('#videofile_input').css('display', 'block');
        $('#videofile_format').css('display', 'block');
        $('#url-input').css('display', 'none');
        $('#url-input').removeAttr('required');
        $('#videofile_input').attr('required', '');
    }
    else {
    var myStr = $('#url-input').val();
        var trimStr = $.trim(myStr);
    if(trimStr == ''){
            $('#url-input').val(trimStr);
        } 
        $('#videofile_input').css('display', 'none');
        $('#videofile_format').css('display', 'none');
        $('#url-input').css('display', 'block');
        $('#videofile_input').removeAttr('required');
        $('#url-input').attr('required', 'true');
    }
});

/*$('#type-input').on('change', function() {
   var type_value = this.value;
   
   if(type_value == 'video'){
        $('#video-upload').show();
        $('#video-url').hide();
        $('#url-input').prop('required',false);
   } else {
   
        $('#video-upload').hide();
        $('#video-url').show().prop('required',true);
   }
});*/
function readURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    
    reader.onload = function(e) {
      $('#show_image').attr('src', e.target.result);
    }
    
    reader.readAsDataURL(input.files[0]); // convert to base64 string
  }
}

$("#image_upload").change(function() {
  readURL(this);
});

var getVideoID = function($url){

        $videoid = $url.match("[\\?&]v=([^&#]*)");
        return ( $videoid === null  ? $url : $videoid[1]);
    }
    $('#url-input').on('change',function(){
        $url= $(this).val();
        
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
                dataid=4;
                $image_crop.croppie('bind', {
                    url: data
                }).then(function(){
                    console.log('jQuery bind complete');
                });
                $('#uploadimageModal').modal('show');
            },
              error: function(XMLHttpRequest, textStatus, errorThrown) {
                 alert("Please enter valid video url");
                 $('#url-input').val('');
                 $('#url-input').focus();
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

$( document ).ready(function() {


var videoType = $('#videoTypes').val();

    if(videoType == 2){
        $('#videofile_input').css('display', 'block');
        $('#videofile_format').css('display', 'block');
        $('#url-input').css('display', 'none');
        $('#url-input').removeAttr('required');
        $('#videofile_input').removeAttr('required');
    }
    else {

        $('#videofile_input').css('display', 'none');
        $('#videofile_format').css('display', 'none');
        $('#url-input').css('display', 'block');
        $('#videofile_input').removeAttr('required');
        $('#url-input').removeAttr('required', '');
    }


var postProfile= function(response){
    var profile_type = $('.profile_type').val();
   
    $data = {"image": response,"profile_type":profile_type,"_token": "{{ csrf_token() }}"};
    if(profile_type == "cloud_video"){
        datakey = $('#url-input').data('key');
        $data['videoid'] = getVideoID($('#url-input').val());
    }
    $data['datakey'] = datakey;
    $data['dataid'] = 11111;
    if($('.profile_type').val() == "video"){$data['video']= $('.profile_type').data('video-src');}
    $.ajax({
        url:"{{route('front.update.resource.images')}}",
        type: "POST",
        data:$data,
        success:function(data)
        {
           var data = $.parseJSON(data);
            $('#uploadimageModal').modal('hide');
            if($('.profile_type').val() == 'image'){
           var placeToInsertImagePreview = 'image_upload';
          $("."+placeToInsertImagePreview).html('');
                $($.parseHTML('<img width="325" style="padding: 0px;"><div  class="black-bg-section"><p>change</p></div><div class="edit-link delete-image" data-id="'+data.id+'" style="font-size: 13px;color: #000;text-align: center;cursor: pointer;background-image: none">X</div> ')).attr('src', data.path).appendTo("."+placeToInsertImagePreview);

                $('#resource_image_name').val(data.file_name);
                $('#resource_image_path').val(data.path);
            }else{
                $('#show_image').attr('src', data.path);
                $('#resource_image_name').val(data.file_name);
                $('#resource_image_path').val(data.path);
                $('#resource_video_path').val(data.video_name);
                //location.reload();
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

 $('#image_upload, #videofile_input').on('change', function() {
    //$('div.gallery').html('');
    
        imagesPreview(this, 'div.gallery');
});

var format_value = $('#format-input').val();
if(format_value){
   if(format_value == 'Video'){
        $('#video-upload').hide();
        $('#video-url').show();
        $('#upload_image_show').hide();
        $('#resource_image').prop('required',false);
        $('#url-input').prop('required',true);
   } else if(format_value == 'Image' || format_value == 'Article' || format_value == 'EBook'){
        
        $('#video-upload').show();
        $('#video-url').hide();
        $('#upload_image_show').show();
        //$('#resource_image').prop('required',true);
        $('#url-input').prop('required',false);
        
   }
}
$('#tag-input-tokenfield').prop('required',true);
if ($('input.checkRecurring').is(':checked')) { //console.log("checked");
    $('.recurring').show();
    $('#weekly-input').prop('required', true);
    //$('#monthly-input').prop('required', true);
    $('.singlePrice').hide();
    //$('#sprice-input').prop('required', false);
}else{
    $('.recurring').hide();
    $('#weekly-input').prop('required', false);
    //$('#monthly-input').prop('required', false);
    $('.singlePrice').show();
    //$('#sprice-input').prop('required', true);
}


$("input[name='isRecuring']").change(function() {
        if(this.checked) {
            $('.singlePrice').hide();
            //$('#sprice-input').prop('required', false);
            $('.recurring').show();
            $('#weekly-input').prop('required', true);
            //$('#monthly-input').prop('required', true);
        }else{
            $('.singlePrice').show();
            //$('#sprice-input').prop('required', true);
            $('.recurring').hide();
            $('#weekly-input').prop('required', false);
            //$('#monthly-input').prop('required', false);
        }
     });

     $('#tag-input').tokenfield({
       
    });
    $('#resource_image').change( function(event) {
    base_url = window.location.origin;
    
    formatinput = $("#format-input").val();
    if(formatinput == 'Image'){
        var tmppath = URL.createObjectURL(event.target.files[0]);
        $(".img-fluid").fadeIn("fast").attr('src',URL.createObjectURL(event.target.files[0]));
        $("#resource_url_perview").attr("href", "#");
    } else if(formatinput == 'Article'){
        base_url_path = $('#resource_image_path').val();
        
        var tmppath = URL.createObjectURL(event.target.files[0]);
        if(base_url_path != ''){
            $(".img-fluid").fadeIn("fast").attr('src',base_url_path);
        }
        $("#resource_url_perview").attr("href", URL.createObjectURL(event.target.files[0])).attr('target','_blank');
    } else if(formatinput == 'EBook'){
        base_url_path = base_url  + '/publicfront/images/document.jpg';
        var tmppath = URL.createObjectURL(event.target.files[0]);
        $(".img-fluid").fadeIn("fast").attr('src',base_url_path);
        $("#resource_url_perview").attr("href", URL.createObjectURL(event.target.files[0])).attr('target','_blank');
    }
    
    
});

/*$('#image_upload').change( function(event) {
    base_url = window.location.origin;
    
    formatinput = $("#format-input").val();
    if(formatinput == 'Image'){
        var tmppath = URL.createObjectURL(event.target.files[0]);
        $(".img-fluid").fadeIn("fast").attr('src',URL.createObjectURL(event.target.files[0]));
        $("#resource_url_perview").attr("href", "#");
    } else if(formatinput == 'Article'){
        base_url_path = $('#resource_image_path').val();
        
        var tmppath = URL.createObjectURL(event.target.files[0]);
        //$(".img-fluid").fadeIn("fast").attr('src',base_url_path);

        $("#resource_url_perview").attr("href", URL.createObjectURL(event.target.files[0])).attr('target','_blank');
    } else if(formatinput == 'EBook'){
        base_url_path = base_url  + '/public/front/images/document.jpg';
        var tmppath = URL.createObjectURL(event.target.files[0]);
        $(".img-fluid").fadeIn("fast").attr('src',base_url_path);
        $("#resource_url_perview").attr("href", URL.createObjectURL(event.target.files[0])).attr('target','_blank');
    } else if(formatinput == 'Video'){
        var tmppath = URL.createObjectURL(event.target.files[0]);
        urlinput = $("#url-input").val();
        $(".img-fluid").fadeIn("fast").attr('src',URL.createObjectURL(event.target.files[0]));
        $("#resource_url_perview").attr("href", urlinput).attr('target','_blank');
    }
    
    
});*/

    $('button[data-toggle=modal]').click(function () {
    var editorData= CKEDITOR.instances['massage-input'].getData();
   base_url = window.location.origin;
   nameinput = $('#name-input').val();
   subtitle = $('#subtitle-input').val();
   massageinput = editorData;
   formatinput = $("#format-input").val();
   base_url_path = $('#resource_image_path').val();
   
   $('.blog_title').html(nameinput);
   $('.blog_subtitle').html(subtitle);
   $('.blog_desc').html(massageinput);
   //alert(base_url_path);
   if(base_url_path != ''){
        if(formatinput == 'Video'){
            urlinput = $("#url-input").val();
            var videoTypes = $('input[name="videoType"]:checked').val();
            $('#resource_url_perview').css("display", "block");
            $(".add-img-fluid").css("display", "none");
            $(".img-fluid").fadeIn("fast").attr('src',base_url_path);
            //alert($('input[name="videoType"]:checked').val());
            if(videoTypes == 1){
            
                 var urlinput = $("#url-input").val();
                 var str = urlinput;
                  /*if(urlinput == ''){
                    $(".add-img-fluid").css("display", "block");
                    $(".img-fluid").css("display", "none");
                  } else {*/
                    var res = str.split("=");
                    $('video_Geeks3').hide();
                    $('youtube_Geeks3').show();
                    $("#video_Geeks3").css("display", "none");
                    $("#youtube_Geeks3").css("display", "block");
                    var embeddedUrl = "https://www.youtube.com/embed/"+res[1];
                    document.getElementById('youtube_Geeks3').src = embeddedUrl;
                  //}
                 //alert(str);
                
                } else {
                    /*if ($('#videofile_input').get(0).files.length === 0) {
                        $(".add-img-fluid").css("display", "block");
                        $(".img-fluid").css("display", "none");
                    } else {*/
                        $('video_Geeks3').show();
                        $('youtube_Geeks3').hide();
                        $("#video_Geeks3").css("display", "block");
                        $("#youtube_Geeks3").css("display", "none");
                        var str = $('#resource_video_path').val();
                       //alert(str);
                        document.getElementById('video_Geeks3').src = str;
                    //}
                    
                }
        } else {
        //alert('sss');
            $('#resource_url_perview').css("display", "none");
            $(".add-img-fluid").css("display", "block");
            $(".add-img-fluid").fadeIn("fast").attr('src',base_url_path);
            //$('#perview_resource_details').modal('hide');
            $('#perview_resource_details').unbind('click');
        }
        
        //alert($('input[name="videoType"]:checked').val());
    } else {
        var videoTypes = $('input[name="videoType"]:checked').val();
        //alert(videoTypes);
        if(videoTypes == 1){
            urlinput = $("#url-input").val();
           
            if(urlinput == ''){
                $(".add-img-fluid").css("display", "block");
                $(".img-fluid").css("display", "none");
            } else {
                $(".add-img-fluid").css("display", "none");
                $(".img-fluid").css("display", "block");
            }
            
        } else if(videoTypes == 2){
            if ($('#videofile_input').get(0).files.length === 0) {
                $(".add-img-fluid").css("display", "block");
                $(".img-fluid").css("display", "none");
            } else {
                $(".add-img-fluid").css("display", "none");
                $(".img-fluid").css("display", "block");
            }
            
        } else {
        //alert('sss');
            $(".add-img-fluid").css("display", "block");
            $(".img-fluid").css("display", "none");
        }
      }  
  });
    

var getImgResult =function(event,fileType){
        return new Promise(function(resolve, reject) {
            const $snapshot_second=5;
            var $result =event.target.result;
            $('.profile_type').data('video-src', '');
            if(['video/mp4', 'video/mov', 'video/quicktime'].includes(fileType)){
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


  var imagesPreview = function(input, placeToInsertImagePreview) {
        dataid = $(input).data('id');
        
        datakey = $(input).data('key');
        placeToInsertImagePreview = 'image_upload';
        if (input.files) {
            var filesAmount = input.files.length;

            for (i = 0; i < filesAmount; i++) {
                var reader = new FileReader(); 
                var file_size = input.files[i].size;
                const  fileType = input.files[i]['type']; 
                if($('#format-input').val() == 'Video'){
                    var validImageTypes = ['video/mp4', 'video/quicktime'];
                } else {
                    var validImageTypes = ['image/jpg', 'image/jpeg', 'image/png'];
                }
                //var validImageTypes = ['image/jpg', 'image/jpeg', 'image/png', 'video/mp4', 'video/mov', 'video/quicktime'];
                if (!validImageTypes.includes(fileType)) {
                    //$("."+placeToInsertImagePreview).append("<p style='color:#FF0000'>File type should be jpeg/png/jpg.</p>");
                    if($('#format-input').val() == 'Video'){
                        
                        $(input).after("<p style='color:#FF0000' class='hide_msg'>File type should be mp4.</p>");
                    } else {
                        
                        $(input).after("<p style='color:#FF0000' class='hide_msg'>File type should be jpeg/png/jpg.</p>");
                    }
                    
                    setTimeout(function() {
                            $('.hide_msg').hide();
                        }, 2000);
                    $(input).val('').clone(true);
                    return false;
                }
                else if(file_size>10485760) {
                        $(input).after("<p style='color:#FF0000' class='hide_msg'>File size should not be greater than 10MB</p>");
                        setTimeout(function() {
                            $('.hide_msg').hide();
                        }, 2000);
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
});
</script>
<script>
    $('.resModal').on('hidden.bs.modal', function (e) {
  // do something...
  $('.resModal iframe').attr("src", jQuery(".resModal iframe").attr("src"));
  $('.resModal video').attr("src", jQuery(".resModal video").attr("src"));
});
    $('.crop_close').on('click', function (e) {
  // do something...
 $('#image_upload').val('');
 $('#url-input').val('');
 $('#videofile_input').val('');
});
</script>
@endsection