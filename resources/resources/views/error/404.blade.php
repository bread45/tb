@extends('front.layout.app')
@section('content')
<section class="inner-banner-section bg-primary">
        <div class="container">
            <div class="banner-content">
                <h1>404</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
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
                <img src="{{ asset('images/404.png') }}" alt="Page not found" />
            </div>
            <a href="{{url('/')}}" class="btn btn-link">Back to Home</a>
        </div>
    </section>
<script type="text/javascript">
        $('.featured-business-slider').slick({
            infinite: true,
            slidesToShow: 3,
            slidesToScroll: 1,
            responsive: [
                {
                    breakpoint: 992,
                    settings: {
                        slidesToShow: 2,
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 1,
                    }
                },
            ]
        });

        var $slider = $('.expert-guidance-slider');

        if ($slider.length) {
            var currentSlide;
            var slidesCount;
            var sliderCounter = document.createElement('div');
            sliderCounter.classList.add('slider-counter');
            
            var updateSliderCounter = function(slick, currentIndex) {
                currentSlide = slick.slickCurrentSlide() + 1;
                slidesCount = slick.slideCount;
                $(sliderCounter).text('0' + currentSlide + ' / 0' +slidesCount)
            };

            $slider.on('init', function(event, slick) {
                $slider.append(sliderCounter);
                updateSliderCounter(slick);
            });

            $slider.on('afterChange', function(event, slick, currentSlide) {
                updateSliderCounter(slick, currentSlide);
            });

            $slider.slick({
                infinite: true,
                slidesToShow: 5,
                slidesToScroll: 1,
                centerMode: true,
                responsive: [
                    {
                        breakpoint: 992,
                        settings: {
                            slidesToShow: 3,
                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 2,
                        }
                    },
                    {
                        breakpoint: 576,
                        settings: {
                            slidesToShow: 1,
                        }
                    },
                ]
            });
        }

        $(function(){
            $('.pl-input').on("focus", function(){
                $(this).siblings('.myinput-placeholder').eq(0).hide();
            });
            $('.pl-input').on("blur", function(){
                if($(this).val() == "") {
                    $(this).siblings('.myinput-placeholder').eq(0).show();
                }
            });
        });
    </script>
@stop