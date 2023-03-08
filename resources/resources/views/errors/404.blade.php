@extends('front.layout.app')
@section('title', '404')
@section('content')
<section class="inner-banner-section bg-primary">
        <div class="container">
            <div class="banner-content">
                <h1>404</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">404</li>
                    </ol>
                </nav>
            </div>
        </div>
</section>
<section class="pagenotfound-section">
        <div class="container text-center">
            <h2>Page not found</h2>
            <p>Oops.. the page you were looking for is no longer available.</p>
            <div class="pagenotfound-img">
                <img src="{{asset('images/404.png')}}" alt="Page not found" />
            </div>
            <a href="{{url('/')}}" class="btn btn-link">Back to Home</a>
        </div>
    </section>

@stop
