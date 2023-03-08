@extends('front.trainer.layout.trainer')
@section('title', 'Add Resource')
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
                        <h1 class="h3 text-muted font-weight-normal mb-0 mt-lg-0 mt-3 order-lg-0 order-3"><a href="{{ route('resource.list') }}" class="mr-3"><img src="{{ asset('front/trainer/images/back-arrow.png') }}" alt="Back to Resource Management" /></a> {{ $resource[0]->name }}</h1>
                        @include('front.trainer.layout.includes.header')
                    </div>

                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body p-md-5">
                                    
                                    <h3 class="font-weight-normal mb-4">{{$resource[0]->name}} <span class="mx-3 text-lighter">|</span> 
                                    </h3>
                                    <p class="mb-4 h5 font-weight-normal">{{strip_tags($resource[0]->description)}}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    @include('front.trainer.layout.includes.footer')

                </div>
            </div>
        </div>
@endsection

