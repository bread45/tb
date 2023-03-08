@extends('front.trainer.layout.trainer')
@section('title', 'Private Messaging View')
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
                                <div class="card-body p-0">
                                    <div class="register-block">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="block-inner">
                                                    <div class="card-header bg-white p-4">
                                                        <h3 class="mb-0">{{$trainer_data->first_name}} {{$trainer_data->last_name}}</h3>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="register-block-inner p-4">
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
                                                                        @if($userdata->photo != '')
                                                                        <img src="{{asset('/front/profile/'.$userdata->photo)}}" alt="user">
                                                                        @else
                                                                        <span class="default-char">{{substr($userdata->first_name, 0, 1) }}</span>
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
                                                        <form method="POST" action="{{route('trainer.private-messaging-send')}}" autocomplete="off">
                                                            <div class="message-input">
                                                                <div class="wrap"> 
                                                                    @csrf
                                                                    <input name="to_id" value="{{$trainer_data->id}}" type="hidden" >
                                                                    <input type="text" name="message" placeholder="type something here.." required="" />
                                                                    <input type="hidden" name="type" value="view" >
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
                                    <div class="row mt-5 mb-5">
                                        <div class="col-12 text-center">
                                            <a href="{{route('trainer.private_messaging')}}" class="btn btn-danger btn-lg btn-icon">Back to Messages</a>
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
<style>
#latest{
    padding-top: 300px;
    margin-top: -300px;
}
</style>
@endsection
