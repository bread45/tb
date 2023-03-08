@extends('admin.layouts.default')
@section('title', 'Messages')
@section('content')
<div class="kt-subheader  kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">{{$trainer_data->first_name}} {{$trainer_data->last_name}}</h3>
        </div> 
        <div class="kt-subheader__toolbar">
            <a href="{{route('messages.index')}}" class="btn btn-default btn-bold"><i class="fa fa-arrow-left"></i> Back</a>
        </div>
    </div>
</div>
<style>
    
    .private-message-board .card-header{background: transparent;padding: 45px;}

/* Chat CSS */
.wrap-chatbox {position: relative;}
.messages-wrap {max-height: 90vh;overflow-y: scroll;overflow-x: hidden;}
.messages-wrap::-webkit-scrollbar {width: 5px;background: rgba(0, 0, 0, 0);}
.messages-wrap::-webkit-scrollbar-thumb {background-color: rgba(0, 0, 0, 0.3);}
.messages-wrap ul li {display: inline-block;margin: 15px 15px 20px 15px;width: calc(100% - 25px);}
.messages-wrap ul li:last-child{margin-bottom: 90px;}
.messages-wrap ul li img {width: 65px;border-radius: 50%;}
.messages-wrap ul li.replies p:before, .messages-wrap ul li.sent p:after {content: ' ';position: absolute;width: 0;height: 0;border: 10px solid;border-color: #f3f4f4 transparent transparent #f3f4f4;transform: rotate(45deg);}
.messages-wrap ul li.replies p:before {left: -9px;right: auto;top: auto;bottom: -12px;}
.messages-wrap ul li.sent p:after {left: auto;right: -9px;top: auto;bottom: -12px;}
.messages-wrap ul li.replies img {margin-right: 25px;}
.messages-wrap ul li.sent img {order: 1;margin-left: 25px;}
.messages-wrap ul li p {display: inline-block;padding: 8px 16px;border-radius: 5px;max-width: 510px;box-shadow: 0px 4px 4px 0px rgba(161, 161, 161, 0.15);font-size: 14px;position: relative;line-height: 24px;background-color: #f3f4f4;color: #000000;margin-bottom: 10px;}
.message-input {position: absolute;bottom: 0;width: 100%;z-index: 99;background: #fff;border: 1px solid rgba(221, 221, 221, 0.6);padding: 5px;}
.message-input .wrap {position: relative;display: flex;justify-content: space-between;height: 100%;}
.message-input .wrap input {border: none;width: 100%;color: #32465a;padding: 5px 20px;font-size: 18px;font-weight: 300;outline: none;}
.message-input .wrap button {float: right;border: none;cursor: pointer;background: #cf5260;color: #f5f5f5;font-weight: bold;font-size: 18px;width: 55px;height: 55px;outline: none;}
.messages-wrap .default-char {width: 65px;height: 65px;display: flex;background: #eaf9f7;border-radius: 50%;margin-right: 25px;font-size: 28px;text-align: center;justify-content: center;align-items: center;font-weight: 600;color: #02a48d;}

</style>
@include('admin.theme.includes.message')
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid"> 
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__body">
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
                            </div>
                            <form method="POST" action="{{route('messages.store')}}">
                                <div class="message-input">
                                    <div class="wrap"> 
                                        @csrf
                                        <input name="to_id" value="{{$from_id}}" type="hidden" >
                                        <input name="type" value="page" type="hidden" >
                                        <input type="text" name="message" placeholder="So wo can here" required="" />
                                        <button type="submit" class="submit">
                                            <img src="{{ asset('/images/send.png')}}" alt="send">
                                        </button>

                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop