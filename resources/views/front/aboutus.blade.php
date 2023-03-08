@extends('front.layout.app')
@section('title', 'About us')
@section('content')
<section class="inner-banner-section bg-primary">
        <div class="container">
            <div class="banner-content">
                <h1>{{$cmsdata->title}}</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{$cmsdata->title}}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </section>
<section class="page-content">
        <div class="container">
            <div class="section-title mb-3">
                <h5 class="text-danger">WHAT WE DO</h5>
                <h2 class="mb-0">ABOUT TRAINING BLOCK</h2>
            </div>
            <div class="row">
                <div class="col-12">
                     <?php echo $cmsdata->short_description; ?>
                </div>
            </div>
        </div>
    </section>
<section class="aboutus-inner-section">
        <div class="aboutus-outer how-work-about bg-muted">
            <div class="about-left">
                <div class="about-img h-100">
                    <img src="{{$howitwork->banner_image}}" alt="{{$howitwork->title}}" />
                </div>
            </div>
<div class="container">
            <div class="about-right">
                <div class="about-content">
                    <div class="section-title text-left">
                        <h5 class="text-danger mb-3">HOW WE DO IT</h5>
                        <h2 class="mb-4">{{$howitwork->title}}</h2>
                         
                    </div>
                    <div class="row">
                         <div class="col-md-12">
                         <?php echo $howitwork->short_description; ?>
                         </div>
                    </div>
                </div>
            </div>
            </div>

        </div>
        <div class="aboutus-outer mission-about bg-muted">
            <div class="about-left">
                <div class="about-img h-100">
                    <img src="{{$ourmission->banner_image}}" alt="{{$ourmission->title}}" />
                </div>
            </div>
            <div class="about-right">
                <div class="about-content">
                    <div class="section-title text-left">
                        <h5 class="text-danger mb-3">WHY WE DO IT</h5>
                        <h2 class="mb-4">{{$ourmission->title}}</h2>
                        <?php echo $ourmission->short_description; ?>
                    </div>
                </div>
            </div>
        </div>
    </section> 
@stop
