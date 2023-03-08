@extends('front.trainer.layout.trainer')
@section('title', 'Add Coupon')
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
                         <h1 class="h3 text-muted font-weight-normal mb-0 mt-lg-0 mt-3 order-lg-0 order-2">Add /Edit Promo Code</h1>
                        
                        @include('front.trainer.layout.includes.header')
                    </div>

                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body p-lg-5">
                               @if (session('message'))
                                    <div class="alert alert-success">
                                        {{ session('message') }}
                                    </div>
                                @endif
                                @if (session('error'))
                                    <div class="alert alert-danger">
                                        {{ session('error') }}
                                    </div>
                                @endif
                                
                                <form class="form-row add-coupon-form" method="POST" action="{{ route('front.update.coupon') }}" enctype='multipart/form-data'>
                                        @csrf
                                        <input type="hidden" name="couponId" value="@if(isset($grtCoupon->id)) {{ $grtCoupon->id }} @endif" />

                                        
                                        <div class="form-group mb-4 col-lg-4">
                                            <label for="date-input">From Date</label>
                                            <input type="text" class="form-control startenddate" readonly='true' name="start_date" id="start_date" placeholder="enter start date" autocomplete="off" value="@if(isset($grtCoupon->fromdate)){{ $grtCoupon->fromdate }}@else{{old('start_date')}}@endif" required>
                                            @if ($errors->has('start_date'))
                                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('start_date') }}</div>
                                            @endif 
                                        </div>
                                        <div class="form-group mb-4 col-lg-4">
                                            <label for="date-input">To Date</label>
                                            <input type="text" class="form-control startenddate" name="end_date" id="end_date" placeholder="enter end date" readonly='true' autocomplete="off" value="@if(isset($grtCoupon->todate)){{ $grtCoupon->todate }}@else{{old('end_date')}}@endif">
                                            @if ($errors->has('end_date'))
                                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('end_date') }}</div>
                                            @endif 
                                        </div>

                                        <div class="form-group col-lg-4">
                                            <label for="coupon_code-input">Coupon Code</label> 
                                            <input type="text" class="form-control" name="coupon_code" required id="coupon_code-input" placeholder="enter coupon code" value="@if(isset($grtCoupon->coupon_code)){{ $grtCoupon->coupon_code }}@else{{old('coupon_code')}}@endif">
                                            @if ($errors->has('coupon_code'))
                                                <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('coupon_code') }}</div>
                                            @endif
                                        </div>
                                        <div class="form-group col-lg-4">
                                        <label for="percentage">Coupon Unit</label> <br>
                                        <input type="radio" id="percentage" name="unit" value="1" @if(isset($grtCoupon->unit))<?php if($grtCoupon->unit == '1'){ echo"checked"; } ?> @endif ><label for="percentage">&nbsp;Percentage (%)</label><br>
                                        <input type="radio" id="dollar" name="unit" value="2" @if(isset($grtCoupon->unit)) <?php if($grtCoupon->unit == '2'){ echo"checked"; }  ?> @endif ><label for="dollar">&nbsp;Dollars ($)</label><br>
                                            @if ($errors->has('unit'))
                                                <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('unit') }}</div>
                                            @endif
                                        </div>

                                        <div class="form-group col-lg-4">
                                            <label for="percentage-input">Value ($)</label> 
                                            <input type="number" class="form-control" name="percentage" required id="percentage-input" placeholder="enter percentage ($)" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength = "2" value="@if(isset($grtCoupon->percentage)){{ $grtCoupon->percentage }}@else{{old('percentage')}}@endif">
                                            @if ($errors->has('percentage'))
                                                <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('percentage') }}</div>
                                            @endif
                                        </div>

                                        
                                        <div class="col-lg-12 d-flex justify-content-center">
                                            <input type="submit" value="Update" class="btn btn-danger btn-lg" />
                                        </div>
                                    </form>
                                  <div id='calendart'></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @include('front.trainer.layout.includes.footer')

                </div>
            </div>
        </div>
@endsection
<style>
#start_date, #end_date{
    background: #fff;
}
</style>
@section('pagescript')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script>
 

  $(document).ready(function(){
    

    $("#start_date").datepicker({
            dateFormat: "yy-mm-dd",
            minDate: 0,
            onSelect: function (date) {
                var dt2 = $('#end_date');
                var startDate = $(this).datepicker('getDate');
                var minDate = $(this).datepicker('getDate');

                startDate.setDate(startDate.getDate() + 30);
                //sets dt2 maxDate to the last day of 30 days window
                dt2.datepicker('option', 'maxDate', startDate);
                dt2.datepicker('option', 'minDate', minDate);
                //$(this).datepicker('option', 'minDate', minDate);

            }
        });
        $('#end_date').datepicker({
            dateFormat: "yy-mm-dd",
            minDate: 0
        });
});
  </script>

@endsection