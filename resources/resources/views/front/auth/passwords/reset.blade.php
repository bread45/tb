@extends('front.layout.app')
@section('title', 'Reset password')
@section('content')
<section class="inner-banner-section bg-primary">
        <div class="container">
            <div class="banner-content">
                <h1>Reset password</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Reset password</li>
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
                        <div class="col-lg-6 pr-lg-4 mb-lg-0 mb-5">
                            <div class="block-inner pr-lg-3">
                                <div class="title">
                                    <h2>Reset password</h2>
                                    <!-- <p class="mb-4">Enter your email address below and we'll send you an email with a link to reset your password.</p> -->
                                </div>
                                @if (session('status'))
                                    <div class="alert alert-success">
                                        {{ session('status') }}
                                    </div>
                                @endif
                        
                        
                        
                        <form class="form form-horizontal" role="form" method="POST" action="{{ route('front.password.email') }}">
                        {{ csrf_field() }}

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="control-label">E-Mail Address</label>
                                <input id="email" type="email" class="form-control" name="email" placeholder="enter your email" value="{{$email}}" required >
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="control-label">Password</label><span style="float: right;font-size: 12px;">Minimum 8 characters</span>
                                <input id="password" type="password" class="form-control" name="password" placeholder="enter new password" required minlength="8" autofocus>
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                        </div>

                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <label for="password-confirm" class="control-label">Confirm Password</label>
                         
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="confirm password" required>

                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif 
                        </div>

                        <div class="form-group"> 
                                <button type="submit" class="btn btn-danger btn-lg">
                                    Reset Password
                                </button>   
                        </div>
                    </form>
                            
                               
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>

@endsection
