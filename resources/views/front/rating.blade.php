@extends('front.layout.app')
@section('title', 'Leave a Review')
@section('content')

<section class="inner-banner-section bg-primary">
        <div class="container">
            <div class="banner-content">
                <h1>Leave a Review</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Reviews & Ratings</li>
                    </ol>
                </nav>
            </div>
        </div>
</section>

<!--<section class="contact-map-section py-0">
        <img src="{{asset('images/banner.jpg')}}" alt="Add Rating">
</section>-->

    <section class="section">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-lg-5">
                   
                    <div class="section-title mb-3">
                         @php
                $TrainerPhoto = \App\TrainerPhoto::where('is_featured',1)->where('trainer_id',$trainer->id)->first();
                @endphp
                @if(!empty($TrainerPhoto))
                    <img src="{{asset('front/profile/'.$TrainerPhoto->image)}}" alt="{{$trainer->business_name}}" />
             
                @elseif(!empty($trainer->photo))
                    <img src="{{asset('front/profile/'.$trainer->photo)}}" alt="{{$trainer->business_name}}" />
  
                @endif 
                        <h5 class="text-danger">Business name</h5>
                        <h2 class="mb-0">@if(!empty($trainer->business_name)){{$trainer->business_name}} @else {{$trainer->first_name}} {{$trainer->last_name}} @endif</h2>
                    </div>
                    <div class="contact-detail">
                        <h5 class="location">
                        {{$trainer->address_1}}
                        {{$trainer->city}}
                        {{$trainer->state}}
                        {{$trainer->country}}
                        </h5>
                        {{-- <h5 class="phone">{{$trainer->phone_number}}</h5> --}}
                        <p class="mb-3">{!! $trainer->bio !!}</p>
                        {{-- <h5 class="date-time">{{date('j F Y', strtotime($order->start_date))}} - {{$endDate = date('j F Y',strtotime($order->end_date) )}},  {{$order->service_time}}</h5> --}}
                    </div>
                </div>
                <div class="col-lg-6">
                
                    <form class="contact-form" action="{{route('customer.review.submit')}}" method="POST" id="rating_submit">
                        @if(session('message'))
                            <div class="alert alert-success seo-rating-success">
                                {{ session('message') }}
                            </div>
                        @endif
                        @csrf
<!--                        <div class="section-title mb-3">
                            <h5 class="text-danger">Your</h5>
                            <h2 class="mb-0">Reviews & Ratings</h2>
                        </div>-->
                        {{-- <input type="hidden" name="order" value="{{$order->id}}" /> --}}
                        <input type="hidden" name="trainer" value="{{$trainer->id}}" />
                        <input type="hidden" name="ratingId" value="@if(isset($rating) && !empty($rating)){{$rating->id}} @endif" />
                         <div class="form-group m-0">
                        
                        <div class="rating">
                            {{-- <label>Select your rating</label> --}}
                        <input id="rating-input" name="ratingInput" type="text" title="" value="@if(isset($rating) && !empty($rating)) {{$rating->rating}} @endif" required/>
                        
                        @if ($errors->has('ratingInput'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('ratingInput') }}</div>
                        @endif
                            {{-- <!-- <ul class="nav">
                                 <li><img src="{{asset('images/star.png')}}" alt="Rating"></li>
                                <li><img src="{{asset('images/star.png')}}" alt="Rating"></li>
                                <li><img src="{{asset('images/star.png')}}" alt="Rating"></li>
                                <li><img src="{{asset('images/star-blank.png')}}" alt="Rating"></li>  
                            </ul> --> --}}
                        </div>
                        </div>
                        <div class="form-group">
                            <label for="title-input">Title</label>
                            <input type="text" class="form-control" name="title" id="title-input" value="@if(isset($rating) && !empty($rating)) {{$rating->title}} @endif" required />
                            @if ($errors->has('title'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('title') }}</div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="mail-input">Give us the 411</label>
                            <textarea class="form-control" style="min-height:200px" name="review_sumary" id="" required>@if(isset($rating) && !empty($rating)) {{$rating->description}} @endif</textarea>
                            @if ($errors->has('review_sumary'))
                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('review_sumary') }}</div>
                            @endif
                        </div>
                        <!-- <div class="form-group">
                            <label for="number-input">upload image</label>
                            <div class="upload-input">
                                <div class="upload-input-img">Upload file</div>
                                <input type="file" name="" id="" class="form-control">
                            </div>
                        </div> -->
                        <div class="text-center">
                            <button type="submit" class="btn btn-danger btn-lg">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@stop
@section('pagescript')
 
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<link href="{{asset('/front/css/star-rating.css')}}" media="all" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="{{ asset('../front/css/custom.css') }}">
<script src="{{asset('/front/js/star-rating.js')}}" type="text/javascript"></script>
<style>
    .navbar-brand {
    float: left;
    height: auto !important;
    padding: 0 !important;
}
    .navbar-collapse.collapse{
        display: flex !important;  
    }
    .glyphicon {
    position: relative;
    top: 1px;
    display: inline-block;
    font-family: "Glyphicons Halflings";
    font-style: normal;
    font-weight: 400;
    line-height: 1;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}
.form-control {
    border-radius: 5px;
    outline: none!important;
    box-shadow: none;
    height: 56px;
    padding: 0 15px;
    color: #898989;
}
/*.form-group {
    margin-bottom: 40px;
}*/
.form-group label {
    font-size: 16px;
    text-transform: uppercase;
    font-weight: 500;
    color: #1e2732;
    margin-bottom: 0;
    line-height: 1;
    letter-spacing: .45px;
}
</style>
<script>
       var $inp = $('#rating-input');

$inp.rating({
    min: 0,
    max: 5,
    step: 1,
    size: 'lg',
    showClear: false,
    clearCaption: 'Select your rating'
});

$inp.on('rating.change', function () {
                alert($('#rating-input').val());
            });
    $("#rating_submit").on("submit", function(){
        
       $('.btn-danger').hide();
    });
</script>

@endsection

