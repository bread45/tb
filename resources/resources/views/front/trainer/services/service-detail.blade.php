@extends('front.trainer.layout.trainer')
@section('title', 'Add Service')
@section('content')
<div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
            <div class="container-fluid">
                    <div class="page-title d-flex align-items-center justify-content-between mb-lg-4 mb-3 pb-lg-3 flex-wrap">
                        <a href="javascript:void(0);" class="menu-trigger d-lg-none d-flex order-0">
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>
                        </a>
                        <h1 class="h3 text-muted font-weight-normal mb-0 mt-lg-0 mt-3 order-lg-0 order-3"><a href="{{ route('services.list') }}" class="mr-3"><img src="{{ asset('front/trainer/images/back-arrow.png') }}" alt="Back to Service Management" /></a> {{ $service->name }}</h1>
                        @include('front.trainer.layout.includes.header')
                    </div>

                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body p-md-5">
                                    <div class="bg-danger detail-icon mb-4">
                                    <!-- @if (file_exists(public_path('front/trainer/images/').strtolower($service->name).'-pink.png'))
                                        <img src="{{asset('front/trainer/images/'.strtolower($service->service->name).'-icon.png')}}" alt="{{$service->name}}" />
                                    @else
                                        <img src="{{ asset('front/trainer/images/fitness-icon.png') }}">
                                    @endif -->

                                        <img src="{{asset('front/images/'.$service->service->white_icon)}}" alt="{{$service->service->name}}" />
                                    </div>
                                    <h3 class="font-weight-normal mb-4">{{$service->name}} <span class="mx-3 text-lighter">|</span> 
                                    <span class="h4 font-weight-normal">
                                    @if($service->is_recurring == "yes")
                                    ${{$service->price_monthly}} USD Monthly    
                                    @else
                                    ${{$service->price}} USD 
                                    @endif </span></h3>
                                    <p class="mb-4 h5 font-weight-normal">{{$service->message}}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    @include('front.trainer.layout.includes.footer')

                </div>
            </div>
        </div>
@endsection

