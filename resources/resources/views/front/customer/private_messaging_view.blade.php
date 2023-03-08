@extends('front.layout.app')
@section('title', 'Private Messaging View')
@section('content')
<section class="inner-banner-section bg-primary">
    <div class="container">
        <div class="banner-content">
            <h1>Private Messaging</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">My Account</a></li>
                    <li class="breadcrumb-item"><a href="#">Private Messaging</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Full Message</li>
                </ol>
            </nav>
        </div>
    </div>
</section>
<section class="page-content login-register-page private-message-board">
    <div class="container">
        <div class="register-block">
            <div class="row">
                <div class="col-md-12">
                    <div class="block-inner">
                        <div class="card-header">
                            @include('admin.theme.includes.message')
                            <h3 class="mb-0">{{$trainer_data->first_name}} {{$trainer_data->last_name}}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="register-block-inner">
                <div class="row">
                    <div class="col-md-12 wrap-chat">
                        <div class="wrap-chatbox">
                            <div class="messages-wrap">
                                <ul class="pl-0">
                                    @foreach($messagedata as $message)
                                    @if($message->to_id == $to_id)
                                    <li class="replies">
                                        <div class="d-flex align-items-end justify-content-start">
                                            @if($message->FromUsers->photo != '')
                                            
                                            <img src="{{asset('/front/profile/'.$message->FromUsers->photo)}}" alt="user">
                                            @else
                                            <span class="default-char">{{substr($message->FromUsers->first_name, 0, 1) }}</span>
                                            @endif
                                            <p>
                                                {{$message->message}}
                                            </p>
                                        </div>
                                    </li> 
                                    @else
                                    <li class="sent">
                                        <div class="d-flex align-items-end justify-content-end">
                                            @if($message->FromUsers->photo != '')
                                            <img src="{{asset('/front/profile/'.$message->FromUsers->photo)}}" alt="user">
                                            @else
                                            <span class="default-char">{{substr($message->FromUsers->first_name, 0, 1) }}</span>
                                            @endif
                                            <p>
                                                {{$message->message}}
                                            </p>
                                        </div>
                                    </li>

                                    @endif
                                    @endforeach                                         
                                </ul>
                                <div id="latest"></div>
                            </div>
                            <form method="POST" action="{{route('private-messaging-send')}}" autocomplete="off">
                                <div class="message-input">
                                    <div class="wrap">

                                        @csrf
                                        <input name="to_id" value="{{$trainer_data->id}}" type="hidden" >
                                        <input type="hidden" name="type" value="view" >
                                        <input type="text" name="message" placeholder="type anything here" required="" />
                                        <button type="submit" class="submit">
                                            <img src="{{ asset('/images/send.png')}}"  alt="send">
                                        </button>

                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-12 text-center">
                <a href="{{route('customer.private_messaging')}}" class="btn btn-danger btn-lg btn-icon">Back to Messages</a>
            </div>
        </div>
    </div>
</section>

@stop
@section('pagescript')
<style>
#latest{
    padding-top: 300px;
    margin-top: -300px;
}
</style>
@endsection
