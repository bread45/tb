@extends('front.layout.app')
@section('title', 'Profile')
@section('content')

<section class="inner-banner-section bg-primary">
    <div class="container">
        <div class="banner-content">
            <h1>Edit Profile</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">My Account</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Profile</li>
                </ol>
            </nav>
        </div>
    </div>
</section>

<section class="page-content login-register-page">
    <div class="container">
        <div class="register-block">
            <div class="register-block-inner">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="title">
                            <h2>Edit Profile</h2>
                        </div>
                    </div>
                </div>
                @if (session('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
                @endif
                <form class="form-row" method="POST" action="{{route('customer.update.profile')}}" enctype="multipart/form-data" name="edit_profile" onsubmit="return validateform()">
                    @csrf
                    <input type="hidden" name="role_type" value="customer" />
                    <div class="col-lg-6">
                        <div class="form-group">
                        <label for="name-input">First Name</label>
                        <input type="text" class="form-control" name="first_name" id="name-input" placeholder="enter your first name" required value="{{ $user->first_name}}">
                        @if ($errors->has('first_name'))
                        <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('first_name') }}</div>
                        @endif
                    </div>
                        <div class="form-group">
                        <label for="last-name-input">Last Name</label>
                        <input type="text" class="form-control" name="last_name" id="last-name-input" placeholder="enter your last name" required value="{{ $user->last_name}}">
                        @if ($errors->has('last_name'))
                        <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('last_name') }}</div>
                        @endif
                    </div>
                        <div class="form-group">
                        <label for="mail-input">Email</label>
                        <input type="email" class="form-control" name="email" id="mail-input" placeholder="enter your email" readonly required value="{{ $user->email}}">
                    </div>
                    <div class="form-group">
                        <label for="city-input">City</label>
                        <input type="text" class="form-control" name="city" id="city-input" placeholder="enter your city" value="{{ $user->city }}">
                        @if ($errors->has('city'))
                        <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('city') }}</div>
                        @endif
                    </div>
                        <div class="form-group">
                        <label for="state-input">State</label>
                        <select name="state" class="form-control" id="state-input">
                            <option value="">select your state</option>
                            @foreach ($states as $state)
                            <option @if($user->state == $state->name) selected @endif value="{{$state->name}}">{{$state->name}}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('state'))
                        <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('state') }}</div>
                        @endif
                    </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                        <label for="tag_line-input">Tag Line</label>
                        <input type="text" class="form-control" name="tag_line" id="zipcode-input" placeholder="enter your tag line" value="{{ $user->spot_description }}">
                        @if ($errors->has('tag_line'))
                        <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('tag_line') }}</div>
                        @endif
                    </div>
                        <div class="form-group">
                        <label for="bio-input">About</label>
                        <textarea class="form-control" rows="5" name="bio" id="bio-input" required>{{ $user->bio  }}</textarea>
                        @if ($errors->has('bio'))
                        <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('bio') }}</div>
                        @endif
                    </div>
                        <div class="form-group">
                        <label for="number-input">Upload Image</label>
                        <div class="profile upload-input" style="margin: 0px;position:relative;">
                            @if(!empty($user->photo))
                            <div class="edit-img">
                                <img class="show_image" id='show_image' style="border-radius: 50%" src="@if(isset($user->photo) && !empty($user->photo)) {{ asset('front/profile/'.$user->photo)}} @else {{ asset('front/images/yoga-checkout.jpg')}}  @endif " alt="yoga checkout">
                                <div class="edit-link">Edit Image</div> 
                            </div>
                            <input type="file" name="photo" id="photo-edit" class="form-control">

                            @else
                            <div class="upload-input-img" style="background-image: url(../front/images/upload-file-img.png);">Upload file</div>
                            <div class="edit-img" style="display:none;">
                                <img id="show_image" src="" style="border-radius: 50%" alt="image">
                                <div class="edit-link">Edit Image</div> 
                            </div>
                            <input type="file" name="photo" id="photo-input" class="form-control">
                            @endif

                        </div>

                        <span id="file_error"></span>
                        @if ($errors->has('photo'))
                        <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('photo') }}</div>
                        @endif
                    </div>
                    </div>
                     
                    
                    
                    <div class="col-lg-12 d-flex justify-content-center">
                        <input type="submit" value="Save Details" class="btn btn-danger btn-lg" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<section id="change_password" class="page-content login-register-page" style="padding-top: 0px;">
    <div class="container">
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
                <form class="form-row"  method="POST" action="{{route('customer.change_password')}}#change_password" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="role_type" value="customer" />
                    <div class="form-group col-lg-6">
                        <label for="password-input">Current Password</label>
                        <input type="password" class="form-control" name="old_password" id="old_password-input" placeholder="enter your current password" value="" required="">
                        @if ($errors->has('old_password'))
                        <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('old_password') }}</div>
                        @endif
                    </div>
                    <div class="form-group col-lg-6">
                        <label for="password-input">New Password</label><span style="float: right;font-size: 12px;">Minimum 8 characters</span>
                        <input type="password" class="form-control" name="password" id="password-input" placeholder="enter your new password" value="" required="" minlength="8">
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
@stop
@section('pagescript')
<script src="//cdn.ckeditor.com/4.13.0/full/ckeditor.js"></script>
<script>
    var editor = CKEDITOR.replace('bio-input');
       CKEDITOR.config.height = '300px';   // CSS unit (percent).
  
    CKEDITOR.config.enterMode = CKEDITOR.ENTER_BR;
    CKEDITOR.config.shiftEnterMode = CKEDITOR.ENTER_BR;

 
CKEDITOR.config.toolbar = 'Basic';

CKEDITOR.config.toolbar_Basic =
[
    ['Bold', 'Italic','Underline', '-', 'Link', 'Unlink']
];
    $(function () {
        $("input[name=phone_number]")[0].oninvalid = function () {
            this.setCustomValidity("Please enter a valid phone number");
        };
        $("input[name=phone_number]")[0].oninput = function () {
            this.setCustomValidity("");
        };
        $("input[name=zip_code]")[0].oninvalid = function () {
            this.setCustomValidity("Please enter a valid zip code");
        };
        $("input[name=zip_code]")[0].oninput = function () {
            this.setCustomValidity("");
        };
    });

    function validateform() {
        var first_name = document.edit_profile.first_name.value;
        var last_name = document.edit_profile.last_name.value;
        if ((first_name.length + last_name.length < 3) || (first_name.length + last_name.length > 50)) {
            new PNotify({
                title: 'Warning',
                text: 'first name & last name lenght should be between 3-50 characters',
                type: 'Notice'
            });
            return false;
        }
    }

    function readURL(input) {
        if (input.files && input.files[0]) {
            // var file_size = input.files[0].size;
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#show_image').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]); // convert to base64 string
        }
    }

    $("#photo-edit").change(function () {
        var file_size = $('#photo-edit')[0].files[0].size;
                const  fileType = $('#photo-edit')[0].files[0]['type'];
                //alert(fileType);
                const validImageTypes = ['image/jpg', 'image/jpeg', 'image/png'];
                if (!validImageTypes.includes(fileType)) {
            $("#file_error").html("<p style='color:#FF0000'>File type should be jpeg/png/jpg.</p>");
            return false;
        }
        else if (file_size > 10485760) {
            $("#file_error").html("<p style='color:#FF0000'>File size should not be greater than 10MB</p>");
            $(".file_upload1").css("border-color", "#FF0000");
            return false;
        } else {
            $("#file_error").html("");
            readURL(this);
        }

    });

    $("#photo-input").change(function () {
        var file_size = $('#photo-input')[0].files[0].size;
                const  fileType = $('#photo-input')[0].files[0]['type'];
                //alert(file_size);
                const validImageTypes = ['image/jpg', 'image/jpeg', 'image/png'];
                if (!validImageTypes.includes(fileType)) {
            $("#file_error").html("<p style='color:#FF0000'>File type should be jpeg/png/jpg.</p>");
            //alert(file_size);
            return false;
        }
        else if (file_size > 5000000) {
            $("#file_error").html("<p style='color:#FF0000'>File size should not be greater than 5MB</p>");
            $(".file_upload1").css("border-color", "#FF0000");
            return false;
        } else {
            $("#file_error").html("");
            readURL(this);
            $('.edit-img').show();
            $('.upload-input-img').hide();
            //$('#photo-input').hide();
        }

    });
    $('html, body').animate({
        scrollTop: $('div.alert').offset().top - 200
    }, 5000);
    setTimeout(function () {
        $('.alert-success').fadeOut('fast');
    }, 5000);
</script>
@endsection
