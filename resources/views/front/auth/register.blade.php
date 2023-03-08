@extends('front.layout.app')
@section('title', 'Register')
@section('content')
<section class="inner-banner-section bg-primary">
        <div class="container">
            <div class="banner-content">
                <h1>Register/Log In</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Register/Log In</li>
                    </ol>
                </nav>
            </div>
        </div>
    </section>

    <section class="page-content login-register-page">
        <div class="container">
            <div class="register-block">
                <div class="">
                    <div class="row justify-content-md-center">
                        <div class="col-lg-6 pr-lg-6 col-md-auto mb-lg-0 mb-5 bg_first">
                            <div class="block-inner">
                               
                                    <h2 class="text-center login_text">Join Training Block</h2>
                                
                                @if (session('message'))
                                    <div class="alert alert-success">
                                        {{ session('message') }}
                                    </div>
                                @endif
                                
                                <div class="row">
                         <div class="col-md-12 mb-3">
                            <p class="text-center heig_nor">By clicking Sign up with Facebook, Google you agree to the<br> <a
                                href="{{route('terms.conditions')}}" class="link_a">Terms
                                of Use.</a> 
                            </p>
                            <div>
                                <a href="{{ url('/atauth/fbredirect/facebook') }}">

                                <button class="facebook"> <img src="{{ asset('front/images/face-book1.png') }}" width="22"> &nbsp Sign up with
                                    facebook</button>
                                </a>

                            </div>
                            </div>
                            <div class="col-md-12 mb-3">
                            <div>

                                <a href="{{ url('/atauth/google') }}" id="atGmail">

                                <button class="mail"><img src="{{ asset('front/images/gmail.png') }}" width="22"> &nbsp Sign up with Google </button>
                                </a>

                            </div>
                            </div>


                            </div>
                            
                        </div> 



                        <!--<div class="col-lg-6 pl-lg-4">
                            <div class="block-inner pl-lg-3">
                                <div class="title">
                                    <h2>Why Sign Up?</h2>
                                </div>
                                <div class="why-sign-up-points">
                                    <ul>
                                        <li>FREE to use - never pay a membership fee</li>
                                        <li>Effortlessly book appointments with local specialists and export appointment times directly to your personal calendar</li>
                                        <li>Direct messaging with local specialists after booking</li>
                                        <li>Thorough ratings & reviews from local athletes</li>
                                    </ul>
                                </div>
                                <div class="already-member mt-4 pt-lg-1">
                                    <h5 class="text-uppercase mb-4 pb-lg-2">Already a member?</h5>
                                    <a href="{{route('front.login')}}" class="btn btn-danger btn-lg">Login</a>
                                </div>
                            </div>
                        </div>-->
                        <p class="text-center sub_title_1">Already have an account? <br><a href="{{route('front.login')}}"> Login</a> 

              </p> <br />

                                 <div class="position-relative">
                        <hr class="hre_padding"><span class="btn-round">or</span>
                      </div> 
                      <form class="form" method="POST" action="{{route('front.register')}}" name="register" onsubmit="return validateform()">
                                    @csrf
                                    @if (app('request')->input('ref'))
                                        <input type="hidden" name="ref" value="{{app('request')->input('ref')}}" />
                                    @endif
                                    <input type="hidden" name="user_role" value="customer" />
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                            <label for="name-input">First Name</label>
                                            <input type="text" class="form-control" name="first_name" id="name-input" placeholder="enter your first name" required="" value="{{old('first_name')}}"/>
                                            @if ($errors->has('first_name'))
                                                <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('first_name') }}</div>
                                            @endif
                                    </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="lname-input">Last Name</label>
                                                <input type="text" class="form-control" name="last_name" id="lname-input" placeholder="enter your last name" required="" value="{{old('last_name')}}" />
                                                @if ($errors->has('last_name'))
                                                    <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('last_name') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    
                                    <!--<div class="form-group">
                                        <label for="lname-input">Last Name</label>
                                        <input type="text" class="form-control" name="last_name" id="lname-input" placeholder="enter your last name" required="" value="{{old('last_name')}}" />
                                        @if ($errors->has('last_name'))
                                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('last_name') }}</div>
                                        @endif
                                    </div>-->
                                    <div class="form-group">
                                        <label for="mail-input">Email</label>
                                        <input type="email" class="form-control" name="email" id="mail-input" placeholder="enter your email" pattern="[^@\s]+@[^@\s]+\.[^@\s]+" required="" value="{{old('email')}}" />
                                        @if ($errors->has('email'))
                                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('email') }}</div>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label for="password-input">Password</label><span style="float: right;font-size: 12px;">Minimum 8 characters</span>
                                        <input type="password" class="form-control" name="password" id="password-input" placeholder="enter your password" required="" minlength="8"  value="{{old('password')}}" onChange="validatePassword()"/>
                                        @if ($errors->has('password'))
                                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('password') }}</div>
                                        @endif
                                        <div style="display: block;" class="error invalid-feedback check_special_char"></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="cpassword-input">Confirm Password</label>
                                        <input type="password" class="form-control" name="password_confirmation" id="cpassword-input" placeholder="confirm password" required="" value="{{old('password_confirmation')}}" />
                                        @if ($errors->has('password_confirmation'))
                                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('password_confirmation') }}</div>
                                        @endif
                                    </div>
                                    <!-- <script src='https://www.google.com/recaptcha/api.js'></script>
                            <div class="g-recaptcha" style="margin-bottom: 20px;"
        data-sitekey="6LdnSykaAAAAALJrvGhc1sJODDfc4eHRFEJgm-Es">
    </div>
                            @if ($errors->has('g-recaptcha-response'))
                                    <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('g-recaptcha-response') }}</div>
                                    @endif  -->
                                    <button type="submit" class="btn btn-info btn-lg mb-3 btn-center">Register</button>
                                </form>

                      
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('pagescript')
<script type="text/javascript">
 //$(".alert").delay(8000).slideUp(300);
 $(function(){
    $("input[name=email]")[0].oninvalid = function () {
        this.setCustomValidity("Please enter a valid email");
    };
    $("input[name=email]")[0].oninput= function () {
        this.setCustomValidity("");
    };
});

function validateform(){  
    var first_name=document.register.first_name.value;  
    var last_name=document.register.last_name.value;  
    if((first_name.length+last_name.length < 3) || (first_name.length+last_name.length > 50)){  
    new PNotify({
                        title: 'Warning',
                                text: 'first name & last name length should be between 3-50 characters',
                                type: 'Notice'
                    });
    return false;  
    }  
}

function validatePassword() {
    var p = $('#password-input').val();
   
    const errors = [];
    if (p.length < 8) {
        errors.push("Your password must be at least 8 characters.");
    }
    if (p.length > 32) {
        errors.push("Your password must be at max 32 characters.");
    }
    if (p.search(/[a-z]/) < 0) {
        errors.push("Your password must contain at least one lower case letter."); 
    }
    if (p.search(/[A-Z]/) < 0) {
        errors.push("Your password must contain at least one upper case letter."); 
    }

    if (p.search(/[0-9]/) < 0) {
        errors.push("Your password must contain at least one digit.");
    }
   if (p.search(/[!@#\$%\^&\*_]/) < 0) {
        errors.push("Your password must contain at least special char from -[ ! @ # $ % ^ & * _ ]"); 
    }
    if (errors.length > 0) {
        //console.log(errors.join("\n"));
        if(p.length == 0){
            $('.check_special_char').hide();
            //$('.btn-info').show();
            $('.btn-info').removeAttr('disabled');
        } else {
            $('.check_special_char').show();
            $('.check_special_char').html(errors.join("\n"));
            //$('.btn-info').hide();
            $('.btn-info').attr('disabled', 'disabled');
            return false;
        }
    }
    $('.check_special_char').hide();
    //$('.btn-info').show();
    $('.btn-info').removeAttr('disabled');
    return true;
}
</script>
@endsection
