@extends('front.layout.app')
@section('title', 'Testimonials')
@section('content')
<section class="inner-banner-section bg-primary">
        <div class="container">
            <div class="banner-content">
                <h1>Title</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Testimonials</li>
                    </ol>
                </nav>
            </div>
        </div>
    </section>
<section class="page-content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                <div class="testimonial-grid text-center">
                    <div class="thumbnail">
                        <img src="{{ $testimonials->user_image != '' ? asset('images/testimonials/'.$testimonials->user_image) : asset('front/images/events_default.png') }}" alt="Testimonial" class="profile-icon">
                        <span class="qoute-icon"><img src="{{ asset('../front/images/new-template/quotes.svg') }}" width="16"></span>

                    </div>
                    <div class="content">
                        <h5 class="title">{{ $testimonials->user_name }}</h5>
                        <span class="subtitle">{{ $testimonials->position }}</span>
                        <div class="rating-icon">
                            @php
                            $count = 1;
                            $result = "";
                            $stars = $testimonials->rating
                            @endphp
                            <?php for($i = 1; $i <= 5; $i++){
                                if($stars >= $count){
                                    $result .= "<i class='fa-solid fa-star'></i>";
                                } else {
                                    $result .= "<i class='fa-regular fa-star'></i>";
                                }
                                $count++;
                            } ?>
                            @php
                            echo $result;
                            @endphp
                        </div>
                        <h4>{{ $testimonials->title }}</h4>
                        <p>{{ $testimonials->description }}</p>
                        
                    </div>
                    </div>
                </div>
            </div>
        </div>
</section>

@stop
