@extends('front.trainer.layout.trainer')
@section('title', 'Private Messaging Send')
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
                        <h1 class="h3 text-muted font-weight-normal mb-0 mt-lg-0 mt-3 order-lg-0 order-3">Private Messaging</h1>
                        @include('front.trainer.layout.includes.header')
                    </div>

                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body p-0 ">
                                <div class="register-block">
                                <!-- <div class="col-lg-8 offset-lg-2 col-xl-6 offset-xl-3">
                                </div> -->
                                <div class="register-block-inner">
                        <div class="row">
                            <div class=" mb-5  mt-5 col-lg-8 offset-lg-2 col-xl-6 offset-xl-3">
                                <div class="block-inner pr-lg-3">
                                    <div class="title">
                                        <h3>PRIVATE MESSAGING</h3>
                                    </div>
                                    <form method="POST">
                                        @csrf
                                        <div class="form-group service-group">
                                            <label for="name-input">Customer</label>
                                            <select name="to_id" id="to_id" class="form-control service-control" required="">
                                                <option value="">Select Customer</option>
                                                <!-- <option value="0">Admin</option> -->
                                                    @foreach($ordersData as $user)
                                                    <option value="{{$user->Users->id}}">{{$user->Users->first_name}} {{$user->Users->last_name}}</option>
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
                    </div>

                    @include('front.trainer.layout.includes.footer')

                </div>
            </div>
</div>

@stop
@section('pagescript')

@endsection
