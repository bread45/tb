@extends('front.layout.app')
@section('title', 'Profile')
@section('content')
<!--<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>

    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">-->

<style>
    .card-body { 
        padding: 1.25rem;
    }
    section {
        padding: 50px 0;
    }
    .badge-notify{
    background: #cf5260;
    color: #fff;
    position: absolute;
    top: 6px;
    left: 124px;
    border-radius: 50%;
  }

</style>
<section class="inner-banner-section bg-primary">
    <div class="container">
        <div class="banner-content">
            @if(Auth::guard('front_auth')->user()->id == $user->id)
            <h1>My Profile</h1>
            @else
            <h1>{{$user->first_name}} {{$user->last_name}}</h1>
            @endif
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    @if(Auth::guard('front_auth')->user()->id == $user->id)
                    <li class="breadcrumb-item"><a href="#">My Profile</a></li>
                    @else 
                    <li class="breadcrumb-item"><a href="#">{{$user->first_name}} {{$user->last_name}}</a></li>
                    @endif
                </ol>
            </nav>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="row">
            <div class="col-md-12 mb-5"> 
                <div class="card">
                    <div class="card-body profile-details d-md-flex justify-content-between">


                        <div class="profile_main d-sm-flex">
                            <div class="profile-img">
                                <img src="@if(isset($user->photo) && !empty($user->photo)) {{ asset('front/profile/'.$user->photo)}} @else {{ asset('front/images/details_default.png')}}  @endif " alt="profile pic"/>
                            </div> 
                            <div class="profile-head">
                                <h5>
                                    {{$user->first_name}} {{$user->last_name}}
                                </h5>
                                <h6><i class="fas fa-map-marker-alt"></i>
                                    {{$user->city}}@if($user->state != '' && $user->city != ''), @endif{{$user->state}}
                                </h6> 
                                <h6><i class="fas fa-user-friends"></i>
                                    {{$friendlist->count()}} Friends
                                </h6> 
                                <h6 style="color: #333333;"> @if($user->spot_description != '')
<!--                                    <i class="fas fa-tag"></i>-->
                                    {{$user->spot_description}}@endif
                                </h6> 

                            </div> 
                        </div>


                        @if(Auth::guard('front_auth')->user()->id == $user->id)
                        <div class="profile_right">
                            <ul class="profile_customer">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{route('customer.profile')}}"><span class="icon_abs_profile"><i class="fas fa-user-edit"></i></span><span class="icon_app_text">Update Profile</span></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link " href="{{route('customer.findfriends')}}"><span class="icon_abs_profile"><i class="fas fa-hand-point-right"></i></span><span class="icon_app_text">Find Friends</span></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link active" href="javascript:void(0)" data-toggle="modal" data-target="#InviteFriends"><span class="icon_abs_profile"><i class="fas fa-user-friends"></i></span><span class="icon_app_text">Invite Friends</span></a>
                                </li>
                                <!--                                <li class="nav-item">
                                                                    <a class="nav-link " href="{{route('customer.findgroup')}}"><i class="fas fa-search-plus"></i> Find Group</a>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <a class="nav-link " href="javascript:void(0)" data-toggle="modal" data-target="#myModalgroup"><i class="fas fa-plus-circle"></i> Create Group</a>
                                                                </li>-->
                            </ul>
                        </div>
                        @else
                        <div class="profile_right">
					@if(Auth::guard('front_auth')->user()->user_role == 'customer')
                            <ul class="nav profile-nav">
                                @php $frienddata = \App\Friend::whereRaw('user_id = '.$user->id.' and friend_id = '.Auth::guard('front_auth')->user()->id)->orwhereRaw('user_id = '.Auth::guard('front_auth')->user()->id.' and friend_id = '.$user->id)->first();
                                @endphp


                                @if(empty($frienddata))
                                <li class="nav-item">
                                    <a class="nav-link add-friend" href="javascript:void(0)" data-id='{{$user->id}}'><i class="fas fa-plus-circle"></i>Add Friend</a>
                                </li>
                                @elseif($frienddata->accept == 0)

                                <!--<a class="nav-link" href="javascript:void(0)" data-id='{{$user->id}}'>Requested</a>-->
                                @else

                                <!--<a class="nav-link" href="javascript:void(0)" data-id='{{$user->id}}'>Friend</a>-->
                                @endif 

                                @if(!empty($frienddata) && $frienddata->accept == 1)
                                <li class="nav-item">
                                    <a class="nav-link " href="{{route('customer.private_messaging.view',$user->id)}}"><i class="fas fa-paper-plane"></i>Send Message</a>
                                </li>
                                @endif
                                 @if(!empty($frienddata))
                                <button class="btn btn-outline-danger" onclick="acceptrejectrequest(<?php echo $frienddata->id;?>, 'remove')">Unfriend</button>
                                 @endif
                               
                            </ul> 
							@endif
                        </div>
                        @endif
                        <!--end::Info-->
                    </div>

                </div>
            </div>
        </div>
              <!-- Alert Message -->
              @if (session('message'))
                    <div class="alert alert-success">
                    {{ session('message') }}
                    </div>
        @endif
        <div class="row">
            <div class="col-lg-3 col-xl-2">
                <div class="card">
                    <div class="card-header"><h5 class="card-title mb-0">Activities</h5></div>
                    <div class="card-body p-0">
                        <div class="profile-sidebar"> 
                            <!-- SIDEBAR MENU -->   
                            <div class="profile-usermenu tabbable tabs-left">
                                <ul class="nav nav-tabs">
                                    <li><a href="#Profile" data-toggle="tab"  class="active">Profile</a></li>
                                    @if(Auth::guard('front_auth')->user()->id == $user->id)
                                    <li><a href="#notification" data-toggle="tab" >Notifications
                                    <button class="btn btn-xs btn-link">
                                        <i class="fa fa-bell" style="color:#6f6f6f;font-size: 18px;line-height: 2;"></i>
                                    </button>
                                    <span class="badge badge-notify" style="font-size:10px;"><?php if($requestlist->count() !=0){ echo $requestlist->count();}?></span>
                                    </a> 
                                    
                                </li>
                                    @endif
                                    <li id="eventsTab"><a href="#rsvp" data-toggle="tab">Events</a></li>                                    
                                    <li><a href="#Friend" data-toggle="tab" onclick="getfrienddata()">Friends <!-- (<span id="friends_count">{{$friendlist->count()}}</span>) --></a></li>
                                    @if(Auth::guard('front_auth')->user()->id == $user->id)
                                    <!--<li><a href="#Order" data-toggle="tab" onclick="getorderdata()">Order History</a></li>-->
                                    @endif
                                    <li><a href="#Recommended" data-toggle="tab" onclick="getrecommendeddata()">Favorites</a></li>
                                    <li><a href="#Savedresource" data-toggle="tab" onclick="getsaveddata()">Saved Resources</a></li>
                                    <li><a href="#reviews" data-toggle="tab" onclick="getreviewsdata()">Reviews</a></li>
                                </ul> 
                            </div>
                            <!-- END MENU -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 col-xl-6 my-4 my-lg-0">
                <div class="tab-content">
                    <div class="tab-pane active" id="Profile">                
                        <div class="profile-tab-content">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-header"><h5 class="card-title mb-0">Profile</h5></div>
                                        <div class="card-body">
                                                                                        <!--<h3 class="title">About</h3>-->
                                                                                        <div class="about-content ln_height"><?php echo $user->bio;  ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                        </div>
                    </div> 
                    @if(Auth::guard('front_auth')->user()->id == $user->id)
                    <div class="tab-pane" id="notification"> 
                        <div class="card">
                            <div class="card-header"><h5 class="card-title mb-0">Notifications</h5></div>
                            <div class="card-body notificationdata">

                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="tab-pane " id="Friend" > 
                        <div class="card">
                            <div class="card-header"><h5 class="card-title mb-0">Friends</h5></div>
                            <div class="card-body frienddata pb-4">

                            </div>
                        </div>
                    </div>
                    @if(Auth::guard('front_auth')->user()->id == $user->id)
                    <!--                    <div class="tab-pane" id="Order"> 
                                            <div class="card">
                                                <div class="card-header"><h5 class="card-title mb-0">Order History</h5></div>
                                                <div class="card-body orderhistory">
                    
                                                </div>
                                            </div>
                                        </div>-->
                    @endif
                    <div class="tab-pane" id="Recommended"> 
                        <div class="card">
                            <div class="card-header"><h5 class="card-title mb-0">Favorites</h5></div>
                            <div class="card-body recommendeddata">

                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="Savedresource"> 
                        <div class="card">
                            <div class="card-header"><h5 class="card-title mb-0">Saved Resources</h5></div>
                            <div class="card-body savedresourcedata">

                            </div>
                        </div>
                    </div>
                    
                    <div class="tab-pane" id="reviews"> 
                        <div class="card">
                            <div class="card-header"><h5 class="card-title mb-0">Reviews</h5></div>
                            <div class="card-body reviewsdata">

                            </div>
                        </div>
                    </div>

                    <div class="tab-pane" id="rsvp">
                        <div class="card">
                            <div class="card-header"><h5 class="card-title mb-0">Events</h5></div>
                            <div class="card-body">
                            @foreach($events as $event)
                            <div class="row event_list">
                                <div class="col-md-7">
                                <a href="{{ url('/event-details/'.base64_encode($event->event_id)) }}"><h5 class="mt-0 font-18 mb-1">{{ $event->title }}</h5></a>
                                    <p><?php echo date('F d, Y', strtotime($event->start_date))  ?> from <?php echo $event->start_time ?> - <?php echo $event->end_time ?></p>
                                </div>
                                <div class="col-md-5">
                                <h5>RSVP Status</h5>
                                    <div class="fullpoint">
                                        <div class="form-check">
                                        <form action="/profileEventChange" method="post" id="rsvpForm">
                                        @csrf
                                        <input type="hidden" name="id" value="<?php echo $event->id?>">
                                        <select name="rsvp_status" onchange="submit();" id="rsvp_status" class="rsvp_status form-select">
                                            <option value="Attending" {{$event->rsvp == "Attending" ? 'selected' : ''}} >Attending</option>
                                            <option value="Maybe" {{$event->rsvp == "Maybe" ? 'selected' : ''}}>Maybe</option>
                                            <option value="Not Attending" {{$event->rsvp == "Not Attending" ? 'selected' : ''}}>Not Attending</option>
                                        </select>
                                        </form>
                                        </div>
                                
                                    </div>
                                </div>
                            </div>
                        @endforeach

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="recommended-content">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <!--                                <div class="card-header"><h5 class="card-title mb-0">Groups</h5></div>
                                                                <div class="card-body">
                                                                    @foreach($Groupdata as $groups)
                                                                    <div class="media" id="groupdata_id_{{$groups->group->id}}">
                                                                        <img class="d-flex mr-3 rounded-circle img-thumbnail thumb-lg" src="@if(isset($groups->group->image) && !empty($groups->group->image) && file_exists(public_path('front/profile/'.$groups->group->image))) {{ asset('front/profile/'.$groups->group->image)}} @else {{ asset('front/images/details_default.png')}}  @endif" />
                                                                        <div class="media-body">
                                                                            <h5 class="mt-0 font-18 mb-3"> 
                                                                                {{$groups->group->name}} 
                                                                            </h5>
                                                                            @if(Auth::guard('front_auth')->user()->id == $user->id)
                                                                            <button class="btn btn-outline-danger profile-btn remove-group" data-id="{{$groups->group->id}}">Leave Group</button>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    @endforeach
                                                                </div>-->

                                <div class="card-header"><h5 class="card-title mb-0">Favorites</h5></div>
                                <div class="card-body">
                                    @foreach($Trainerdata as $Trainer)
                                    <div class="media" id="addrecommended_id_{{$Trainer->users->id}}">
                                        <img class="d-flex mr-3 rounded-circle img-thumbnail thumb-lg" style="object-fit: cover;" src="@if(isset($Trainer->users->photo) && !empty($Trainer->users->photo) && file_exists(public_path('front/profile/'.$Trainer->users->photo))) {{ asset('front/profile/'.$Trainer->users->photo)}} @else {{ asset('front/images/details_default.png')}}  @endif" alt="default"/>
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
                                            @if(Auth::guard('front_auth')->user()->id == $user->id)
                                            <img onclick="removerecommended({{$Trainer->users->id}})" class="float-right" style="cursor: pointer" src="{{asset('images/delete-icon.png')}}" alt="Rating" />
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>

<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button> 
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <form name="add-note-frm" class="add-note-frm"  method="post" >
                        @csrf
                        <div class="form-group">
                            <label for="mail-input">Add Note</label>
                            <input type="hidden" id="retting_id" name="order_id" value=""/>
                            <textarea class="form-control order_comment" name="order_comment" id="order_comment" required=""></textarea>
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-danger btn-lg">Submit</button>
                        </div> 
                    </form> 
                </div> 
            </div>

        </div>
    </div>
</div>
<div class="modal fade" id="InviteFriends" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                  <h5 class="modal-title">Invite Friends</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button> 
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <form name="invite-friend-frm" class="invite-friend-frm"  method="post" >
                        @csrf
                        <div class="form-group">
                            <label for="mail-input">Send invites to these email addresses:</label> 
                            
                            <textarea class="form-control" name="email" id="email" required=""></textarea>
                            <p>(separate email addresses with a comma)</p>
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-danger btn-lg">Invite</button>
                        </div> 
                    </form> 
                </div> 
            </div>

        </div>
    </div>
</div>
<div class="modal fade" id="myModalgroup" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>

            </div>
            <div class="modal-body">
                <div class="form-group">
                    <form name="group-add-frm" class="group-add-frm"  method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="mail-input">Group Name</label> 
                            <input type="text" class="form-control" name="group_name" id="group_name" required="">
                        </div>
                        <div class="form-group">
                            <label for="mail-input">Group Image</label> 
                            <input type="file" class="form-control" name="group_image" id="group_image" required=""> 
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-danger btn-lg">Submit</button>
                        </div> 
                    </form>

                </div> 
            </div>

        </div>
    </div>
</div>
@stop
@section('pagescript')

<style>
.fullpoint{
    /* display: flex; */
}
.form-check-input{
        margin-top: 8px;
}
.event_list {
    background: #ebebeb;
    padding: 10px;
    margin-bottom: 5px;
  }
  .form-check {
    padding-left: 0;
  }
  select#rsvp_status {
    border: 0;
    padding: 5px;
}
</style>
<script>
    
$("#InviteFriends").on("hidden.bs.modal",function(){
 
        $("#email").val('');
});
            getfrienddata();
//                    getorderdata();
            getrecommendeddata();
            getreviewsdata();
            getsaveddata();
            notificationdata();
            $('.invite-friend-frm').submit(function (e) {
    e.preventDefault();
    $('.btn-danger').hide();
            $.ajax({
            type: 'POST',
                    url: '{{route("customer.friend.invite")}}',
                    data: new FormData(this),
//                            data: {user_id: '{{$user->id}}',group_name : $('#group_name').val()},
                    headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    contentType: false,
                    processData: false,
                    success: function (result) {
                    var data = $.parseJSON(result);
                            new PNotify({
                            title: data.message,
                                    text: '',
                                    type: 'success'
                            });
                            getorderdata();
                            $('#InviteFriends').modal('hide');
                            $('.btn-danger').show();
//                            $('#group_name').val('');
//                            $('#email').val('');
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                    //alert(xhr.status);
                    //alert(thrownError);
                    $('.btn-danger').show();
                    alert('Your session has been timed out. Please Login to continue.');
                    window.location = '{{ url("/login") }}';
                    }
            });
    });
            $('.group-add-frm').submit(function (e) {
    e.preventDefault();
            $.ajax({
            type: 'POST',
                    url: '{{route("customer.group.create")}}',
                    data: new FormData(this),
//                            data: {user_id: '{{$user->id}}',group_name : $('#group_name').val()},
                    headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    contentType: false,
                    processData: false,
                    success: function (result) {
                    var data = $.parseJSON(result);
                            new PNotify({
                            title: data.message,
                                    text: '',
                                    type: 'success'
                            });
                            getorderdata();
                            $('#myModalgroup').modal('hide');
                            $('#group_name').val('');
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                    //alert(xhr.status);
                    //alert(thrownError);
                    }
            });
    });
            $('.add-note-frm').submit(function (e) {
    e.preventDefault();
            $.ajax({
            type: 'POST',
                    url: '{{route("customer.order.addnote")}}',
                    data: $('.add-note-frm').serialize(),
                    headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function (result) {
                    var data = $.parseJSON(result);
                            new PNotify({
                            title: data.message,
                                    text: '',
                                    type: 'success'
                            });
                            getorderdata();
                            $('#myModal').modal('hide');
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                    //alert(xhr.status);
                    //alert(thrownError);
                    }
            });
    });
            function notificationdata() {
            $.ajax({
            type: 'POST',
                    url: '{{route("customer.notificationdata")}}',
                    data: {user_id: '{{$user->id}}'},
                    headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function (result) {
                    $('.notificationdata').html(result);
                    },
                    error: function (xhr, ajaxOptions, thrownError) {

                    }
            });
            }
    function getorderdata() {
    $.ajax({
    type: 'POST',
            url: '{{route("customer.orderhistory")}}',
            data: {user_id: '{{$user->id}}'},
            headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function (result) {
            $('.orderhistory').html(result);
            },
            error: function (xhr, ajaxOptions, thrownError) {

            }
    });
    }
    function getreviewsdata() {
    $.ajax({
    type: 'POST',
            url: '{{route("customer.getreviewsdata")}}',
            data: {user_id: '{{$user->id}}'},
            headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function (result) {
            $('.reviewsdata').html(result);
            },
            error: function (xhr, ajaxOptions, thrownError) {

            }
    });
    }
    function getrecommendeddata() {
    $.ajax({
    type: 'POST',
            url: '{{route("customer.recommendeddata")}}',
            data: {user_id: '{{$user->id}}'},
            headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function (result) {
            $('.recommendeddata').html(result);
            },
            error: function (xhr, ajaxOptions, thrownError) {

            }
    });
    }
    function getsaveddata() {
    $.ajax({
    type: 'POST',
            url: '{{route("customer.savedresourcedata")}}',
            data: {user_id: '{{$user->id}}'},
            headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function (result) {
            $('.savedresourcedata').html(result);
            },
            error: function (xhr, ajaxOptions, thrownError) {

            }
    });
    }
    function addrecommended(id) {
    $.ajax({
    type: 'POST',
            url: '{{route("customer.addrecommended")}}',
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
                    getrecommendeddata();
            } else{
            new PNotify({
            title: data.message,
                    text: '',
                    type: 'error'
            });
            }


            },
            error: function (xhr, ajaxOptions, thrownError) {

            }
    });
    }
    function removerecommended(id) {
    if (confirm("Are you sure?")){
    $.ajax({
    type: 'POST',
            url: '{{route("customer.addrecommended")}}',
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
                    getrecommendeddata();
            } else{
            new PNotify({
            title: data.message,
                    text: '',
                    type: 'error'
            });
            }


            },
            error: function (xhr, ajaxOptions, thrownError) {

            }
    });
    }
    }
    function getfrienddata() {
    $.ajax({
    type: 'POST',
            url: '{{route("customer.getfrienddata")}}',
            data: {user_id: '{{$user->id}}'},
            headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function (result) {
            $('.frienddata').html(result);
            },
            error: function (xhr, ajaxOptions, thrownError) {
            }
    });
    }
    function acceptrejectrequest(id, type) {
    
        if(confirm("Are you sure?")){
            $.ajax({
            type: 'POST',
                    url: '{{route("customer.acceptrejectrequest")}}',
                    data: {id: id, "type": type},
                    headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function (result) {
                    var data = $.parseJSON(result);
                            new PNotify({
                            title: data.message,
                                    text: '',
                                    type: 'success'
                            });
                            getfrienddata();
                            window.location.reload();
                    },
                    error: function (xhr, ajaxOptions, thrownError) {

                    }
            });
    }
    }

    function acceptrejectrequestindvidual(id) {
    
        if(confirm("Are you sure?")){
            $.ajax({
            type: 'POST',
                    url: '{{route("customer.acceptrejectrequestindvidual")}}',
                    data: {id: id, user_id: '{{$user->id}}'},
                    headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function (result) {
                    var data = $.parseJSON(result);
                            getfrienddata();
                            $('#friends_count').html(data.friendlistcount);
                            //window.location.reload();
                            //$(this).parent().addClass('active');
                            //$('a[href^="Friend"]').addClass('active');
                            
                    },
                    error: function (xhr, ajaxOptions, thrownError) {

                    }
            });
    }
    }
    function openmodel(id) {
    $('#retting_id').val(id);
            var review_comment = $('#order_comment_data_' + id).val();
            $('.order_comment').val(review_comment);
            $('#myModal').modal('toggle');
    }
    $(document).on('click', '.remove-group', function(e){
    var dataid = $(this).data('id');
    if(confirm("Are you sure?")){
            $.ajax({
            type: 'POST',
                    url: '{{route('customer.JoinGroup')}}',
                    data:{"id": dataid, 'type':'remove'},
                    headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function (result) {
                    var data = $.parseJSON(result);
                            new PNotify({
                            title: data.message,
                                    text: '',
                                    type: 'success'
                            });
                            $('#groupdata_id_' + dataid).fadeOut(300, function(){ $(this).remove(); });
                            getrecommendeddata();
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                    //alert(xhr.status);
                    //alert(thrownError);
                    }
            });
        }
    });
            $(document).on('click', '.add-friend', function(e){
    var dataid = $(this).data('id');
            $.ajax({
            type: 'POST',
                    url: '{{route('customer.addfriend')}}',
                    data:{"id": dataid},
                    headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function (result) {
                    $(this).text('Requested');
                            new PNotify({
                            title: 'Friend request sent.',
                                    text: '',
                                    type: 'success'
                            });
                            getfrienddata();
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                    //alert(xhr.status);
                    //alert(thrownError);
                    }
            });
    });

    $( document ).ready(function() {
        hrefurl=$(location).attr("href");
        last_part=hrefurl.substr(hrefurl.lastIndexOf('#') + 1);
        
        /*if(last_part !=''){
            $('.nav-tabs a[href="#' + last_part + '"]').tab('show');
        }*/

        if(last_part == 'notification'){
            $('.nav-tabs a[href="#notification"]').tab('show');
        }
        $( ".bg-primary" ).scrollTop( 300 );
         
        if(last_part == 'rsvp'){
            $('.nav-tabs a[href="#rsvp"]').tab('show');
        }
        $( ".bg-primary" ).scrollTop( 300 );

        /*$("#bell_icon a").click(function(){
          alert("The paragraph was clicked.");
        }); */
    });


</script>
@endsection