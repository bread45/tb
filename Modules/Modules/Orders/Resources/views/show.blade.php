@extends('admin.layouts.default')
@section('title', 'Order History')
@section('content')
<link href="{{ asset('/theme/css/demo1/pages/invoices/invoice-2.css') }}" rel="stylesheet" type="text/css" />
<div class="kt-subheader  kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">Order Details</h3>
        </div>
    </div>
</div>
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
		<div class="kt-portlet">
	<div class="kt-portlet__body kt-portlet__body--fit">
		<div class="kt-invoice-2">
                    <div class="kt-invoice__head" style="padding-top: 0px;">
                <div class="kt-invoice__container">
                     
                    <div class="kt-invoice__items">
                        <div class="kt-invoice__item">
                            <span class="kt-invoice__subtitle">Date</span>
                            <span class="kt-invoice__text">{{\Carbon\Carbon::parse($order_details->created_at)->format('d M Y, H:i')}}</span>
                        </div>
                         
                        <div class="kt-invoice__item">
                            <span class="kt-invoice__subtitle">Trainer Details</span>
                            <span class="kt-invoice__text">
                                {{$order_details->trainer->address_1}}</br>
                                {{$order_details->trainer->city}}, 
                                {{$order_details->trainer->state}}</br>
                                {{$order_details->trainer->country}}, {{$order_details->trainer->zip_code}}</br>
                                <label>phone : </label> {{$order_details->trainer->phone}}
                            </span>
                        </div>
                        <div class="kt-invoice__item">
                            <span class="kt-invoice__subtitle">Customer Details</span>
                            <span class="kt-invoice__text">
                                {{$order_details->address}}</br>
                                {{$order_details->city}}, 
                                {{$order_details->state}}</br>
                                {{$order_details->country}}, {{$order_details->zip_code}}</br>
                                <label>phone : </label> {{$order_details->phone}}
                            </span>
                        </div>
                    </div>
                </div>
			</div>	
			<div class="kt-invoice__body">
                <div class="kt-invoice__container">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Service Name</th>
                                    <th>RATE</th>
                                    <th>AMOUNT</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td> {{$order_details->service->name}}</td>
                                    <td>
                                    @if($order_details->stripe_subscription_id)
                                        @if($order_details->plan_type == "monthly")
                                        ${{$order_details->service->price_monthly}} USD Monthly
                                        @endif
                                        @if($order_details->plan_type == "weekly")
                                        ${{$order_details->service->price_weekly}} USD Weekly
                                        @endif
                                    @else
                                        ${{$order_details->amount}} USD
                                    @endif
                                    <!-- ${{$order_details->amount}} -->
                                    </td>
                                    <td class="kt-font-danger kt-font-lg">
                                    <!-- ${{$order_details->amount}} -->
                                    @if($order_details->stripe_subscription_id)
                                        @if($order_details->plan_type == "monthly")
                                        ${{$order_details->service->price_monthly}} USD 
                                        @endif
                                        @if($order_details->plan_type == "weekly")
                                        ${{$order_details->service->price_weekly}} USD
                                        @endif
                                    @else
                                        ${{$order_details->amount}} USD
                                    @endif
                                    </td>
                                </tr>
                                 
                            </tbody>
                        </table>
                    </div>
                </div>
			</div>
<!--			<div class="kt-invoice__actions">
                <div class="kt-invoice__container">
                    <button type="button" class="btn btn-brand btn-bold" onclick="window.print();">Print Invoice</button>
                </div>
            </div>-->
		</div>
	</div>
</div>	</div>
@stop