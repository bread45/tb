@extends('front.layout.app')
@section('title', 'Forgot password')

<!-- Main Content -->
@section('content')
<section class="inner-banner-section bg-primary">
        <div class="container">
            <div class="banner-content">
                <h1>Forgot password</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Forgot password</li>
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
                                    <h2>Forgot password</h2>
                                    <p class="mb-4">Enter your email address below and we'll send you an email with a link to reset your password.</p>
                                </div>
                                @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                                <form class="form form-horizontal" role="form" method="POST" action="{{ route('front.password.request') }}">
                                    {{ csrf_field() }}
                                    <div class="form-group">
                                        <label for="mail-input">Email</label>
                                        <input id="email" type="email" class="form-control" name="email" placeholder="Enter Your Email" value="{{ old('email') }}">
                                        <!-- <input type="email" class="form-control" name="" id="mail-input" placeholder="Enter Your Email" /> -->
                                        @if ($errors->has('email'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <!-- <a href="login.html" class="btn btn-danger btn-lg">Send</a> -->
                                    <button type="submit" class="btn btn-danger btn-lg">
                                        Send
                                    </button>
                                </form>
                               
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>

@endsection
