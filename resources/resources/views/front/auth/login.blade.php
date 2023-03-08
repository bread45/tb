@extends('front.layout.app')
@section('title', 'Login')
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

      <div class="row justify-content-md-center">


        <div class="col-lg-6 pr-lg-6 col-md-auto mb-lg-0 mb-5 bg_first">


          <div class="block-inner ">

            <div>
              <h2 class="text-center login_text">LOGIN</h2>
              <p class="text-center sub_title_1">New to Training Block? <br><a href="{{route('register')}}"> Sign Up</a> 

              </p>
              <p class="text-center sub_title_1"><a href="{{route('trainer.register')}}"> Join our Provider Network </a>

              </p>

            </div>
            @if ($errors->any() && (Session::get('tabName') == "customer"))
                                        <div class="alert alert-danger">
                                            {{$errors->first()}}
                                        </div>
                                        @endif
            @if ($errors->any() && (Session::get('tabName') == "trainer"))
                                        <div class="alert alert-danger">
                                            {{$errors->first()}}
                                        </div>
                                        @endif
            <form class="form" method="POST" action="{{ route('front.loggedIn') }}">

                @csrf
                <input type="hidden" name="user_role" value="customer" />
                <input type="hidden" name="user_role" value="trainer" />
              <div class="form-group">
                <label for="mail-input">Email</label>
                <input type="email" class="form-control" name="email" id="mail-input-customer" value="@if($tabName == "customer"){{ old("email") }}@endif" placeholder="enter your email" pattern="[^@\s]+@[^@\s]+\.[^@\s]+" required="" />
            </div>
            <div class="form-group">
                <label for="password-input">Password</label>
                <input type="password" class="form-control" name="password" id="password-input" placeholder="enter your password" required="" />
            </div>

            <button type="submit" class="btn btn-info btn-lg mb-3 width_100">Login</button>

              <div class="row">
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                  <div class="form-group mb-2">
                    <div class="d-flex">
                      <!-- <a href="#" class="btn btn-danger btn-lg order-sm-1 order-2">Login</a> -->
                      <div class="remember-password custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="remember" id="customCheck1" value="true">
                        <label class="custom-control-label" for="customCheck1">Remember Me</label>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                  <a href="{{route('front.password.reset')}}"
                    class="btn btn-link pull-right lostposs">Lost Your Password?</a>
                </div>
              </div>


            </form>

              <div class="position-relative">
                <hr class="hre_padding"><span class="btn-round">or</span>
              </div>

              <div class="row">
                <div class="col-md-12 mb-3">
                  <p class="text-center heig_nor">By clicking Continue with Facebook, Google you agree to the<br> <a
                      href="{{route('terms.conditions')}}" class="link_a">Terms
                      of Use</a>
                  </p>
                  <div>
                    <a href="{{ url('/atauth/fbredirect/facebook') }}">

                      <button class="facebook"> <img src="{{ asset('/front/images/face-book1.png') }}" width="22"> &nbsp Continue with
                        facebook</button>
                    </a>

                  </div>
                </div>
                <div class="col-md-12 mb-3">
                  <div>

                    <a href="{{ url('/atauth/google') }}" id="atGmail">

                      <button class="mail"><img src="{{ asset('/front/images/gmail.png') }}" width="22"> &nbsp Continue with Google </button>
                    </a>

                  </div>
                </div>
              </div>



              






          </div>
        </div>


      </div>

      <div class="row">
        <div class="col-lg-6 pr-lg-4 mb-lg-0 mb-5">

        </div>
        <div class="col-lg-6 pl-lg-4">

        </div>
      </div>











    </div>
  </section>
@endsection
@section('pagescript')
@if (session('message'))
<script>
    new PNotify({
                    title: '{{ session('message') }}',
                            text: '',
                            type: 'success'
                });
</script>
@endif
<script type="text/javascript">
 //$(".alert").delay(8000).slideUp(300);
 $(function(){
    $("#mail-input-trainer")[0].oninvalid = function () {
        this.setCustomValidity("Please enter a valid email");
    };
    $("#mail-input-trainer")[0].oninput= function () {
        this.setCustomValidity("");
    };
    $("#mail-input-customer")[0].oninvalid = function () {
        this.setCustomValidity("Please enter a valid email");
    };
    $("#mail-input-customer")[0].oninput= function () {
        this.setCustomValidity("");
    };
});
$('html, body').animate({
            scrollTop: $('div.alert').offset().top-200
}, 5000);
</script>

@endsection