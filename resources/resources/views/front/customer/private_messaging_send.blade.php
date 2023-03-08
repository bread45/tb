@extends('front.layout.app')
@section('title', 'Private Messaging Send')
@section('content')
<section class="inner-banner-section bg-primary">
    <div class="container">
        <div class="banner-content">
            <h1>Private Messaging</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">My Account</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Private Messaging</li>
                </ol>
            </nav>
        </div>
    </div>
</section>
<section class="page-content login-register-page">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2 col-xl-6 offset-xl-3">
                <div class="register-block">
                    <div class="register-block-inner">
                        <div class="row">
                            <div class="col-md-12 pr-lg-4 mb-lg-0 mb-5">
                                <div class="block-inner pr-lg-3">
                                    <div class="title">
                                        <h2>PRIVATE MESSAGING</h2>
                                    </div>
                                    <form method="POST">
                                        @csrf
                                        <div class="form-group service-group">
                                            <label for="name-input">Trainer</label>
                                            <select name="to_id" id="to_id" class="form-control service-control" required="">
                                                <option value="">Select Trainer</option>
                                                <option value="0">Admin</option>
                                                    @foreach($ordersData as $user)
                                                    <option value="{{$user->trainer->id}}">
                                                    @if(!empty($user->trainer->business_name))
                                                    {{$user->trainer->business_name}}
                                                    @else
                                                    {{$user->trainer->first_name}} {{$user->trainer->last_name}}
                                                    @endif
                                                    
                                                    </option>
                                                    @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="lname-input">Message</label>
                                            <textarea class="form-control" name="message" id="" cols="30" rows="10" required=""></textarea>
                                        </div>
                                        <input type="submit" class="btn btn-danger btn-lg" value="Send message"> 
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


@stop
@section('pagescript')

@endsection
