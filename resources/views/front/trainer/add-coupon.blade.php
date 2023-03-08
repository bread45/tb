@extends('front.trainer.layout.trainer')
@section('title', 'Add Promo')
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
                         <h1 class="h3 text-muted font-weight-normal mb-0 mt-lg-0 mt-3 order-lg-0 order-2">Add / Edit Promo Code</h1>
                        
                        @include('front.trainer.layout.includes.header')
                    </div>
                    @if($providerStatus[0]->is_subscription)
                    @if($trailingProviderOrders < 1)
                    @if($providerOrdersCount < 1)
                    <div class="popup popup-danger text-center" role="alert">
                    Your subscription is cancelled and your page will not be visible to the public until you activate your account again, You can reactivate your subscription in the account information tab.
                    </div>
                    @endif
                    @endif
                    @endif

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
                                            <input type="text" class="form-control startenddate" readonly='true' name="start_date" id="start_date" placeholder="enter start date" autocomplete="off" value="@if(isset($grtCoupon->fromdate)){{ date('m-d-Y', strtotime($grtCoupon->fromdate)) }}@else{{old('start_date')}}@endif" required>
                                            @if ($errors->has('start_date'))
                                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('start_date') }}</div>
                                            @endif 
                                        </div>
                                        <div class="form-group mb-4 col-lg-4">
                                            <label for="date-input">To Date</label>
                                            <input type="text" class="form-control startenddate" name="end_date" id="end_date" placeholder="enter end date" readonly='true' autocomplete="off" value="@if(isset($grtCoupon->todate)){{ date('m-d-Y', strtotime($grtCoupon->todate)) }}@else{{old('end_date')}}@endif">
                                            @if ($errors->has('end_date'))
                                            <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('end_date') }}</div>
                                            @endif 
                                        </div>

                                        <div class="form-group col-lg-4">
                                            <label for="coupon_code-input">Promo Code</label> 
                                            <input type="text" class="form-control" name="coupon_code" required id="coupon_code-input" placeholder="enter promo code" value="@if(isset($grtCoupon->coupon_code)){{ $grtCoupon->coupon_code }}@else{{old('coupon_code')}}@endif">
                                            @if ($errors->has('coupon_code'))
                                                <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('coupon_code') }}</div>
                                            @endif
                                        </div>
                                        <div class="form-group col-lg-7">
                                        <label for="percentage">Promo Unit</label> <br>
                                        <input type="hidden" name="unitType" value="@if(isset($grtCoupon->unit)){{ $grtCoupon->unit}} @endif">
                                        <div class="row">
                                        <div class="col-md-6"> <input type="radio" id="percentage" name="unit" value="1" @if(isset($grtCoupon->unit))<?php if($grtCoupon->unit == '1'){ echo"checked"; } ?> @else checked @endif ><label for="percentage" style="line-height:3">&nbsp;Percentage Discount</label></div>
                                        <div class="col-md-6"><div class="input-group percent-input">
                                            <input type="number" class="form-control decimal_validate" name="percentage" required id="percentage-input" placeholder="enter value" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength = "2" value="@if(isset($grtCoupon->percentage) && $grtCoupon->unit == '1'){{ $grtCoupon->percentage }}@else{{old('percentage1')}}@endif">
                                            <div class="input-group-append" style="display:none;">
                                                    <span class="input-group-text" id="percentage_addon" >Discount (%)</span>
                                                    <!-- <span class="input-group-text" id="dollar_addon" >Discount ($)</span> -->
                                             </div>
                                             </div>   </div>
                                        <div class="col-md-6"> <input type="radio" id="dollar" name="unit" value="2"  @if(isset($grtCoupon->unit)) <?php if($grtCoupon->unit == '2'){ echo"checked"; }  ?> @endif ><label for="dollar" style="line-height:3">&nbsp;Dollar Discount</label></div>
                                        <div class="col-md-6"><div class="input-group dollar-input">
                                            <input type="number" class="form-control decimal_validate" name="percentage" required id="percentage-input" placeholder="enter value" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength = "2" value="@if(isset($grtCoupon->percentage) && $grtCoupon->unit == '2'){{ $grtCoupon->percentage }}@else{{old('percentage1')}}@endif">
                                            <div class="input-group-append" style="display:none;">
                                                    <!-- <span class="input-group-text" id="percentage_addon" >Discount (%)</span> -->
                                                    <span class="input-group-text" id="dollar_addon" >Discount ($)</span>
                                             </div>
                                             </div>   </div>  

                                        @if ($errors->has('unit'))
                                                <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('unit') }}</div>
                                            @endif
                                            <!-- <div class="input-group">
                                            <input type="number" class="form-control" name="percentage" required id="percentage-input" placeholder="enter value" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength = "2" value="@if(isset($grtCoupon->percentage)){{ $grtCoupon->percentage }}@else{{old('percentage')}}@endif">
                                            <div class="input-group-append">
                                                    <span class="input-group-text" id="percentage_addon" >Discount (%)</span>
                                                    <span class="input-group-text" id="dollar_addon" >Discount ($)</span>
                                             </div>
                                             </div>                                                                          -->
                                            @if ($errors->has('percentage'))
                                                <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('percentage') }}</div>
                                            @endif
                                            </div>
                                        </div>
                                    
                                        <!-- <div class="form-group col-lg-6">
                                        <label for="percentage-input">Value</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" name="percentage" required id="percentage-input" placeholder="enter value" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength = "2" value="@if(isset($grtCoupon->percentage)){{ $grtCoupon->percentage }}@else{{old('percentage')}}@endif">
                                            <div class="input-group-append">
                                                    <span class="input-group-text" id="percentage_addon">Discount (%)</span>
                                                    <span class="input-group-text" id="dollar_addon" style="display: none">Discount ($)</span>
                                             </div>
                                             </div>                                                                         
                                            @if ($errors->has('percentage'))
                                                <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('percentage') }}</div>
                                            @endif
                                        </div> -->

                                        
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
#percentage-input:disabled, #percentage-input[readonly] {
    background-color: #efefef !important;
    opacity: 1;
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
            dateFormat: "mm-dd-yy",
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
            dateFormat: "mm-dd-yy",
            minDate: 0
        });
});
  $( ".decimal_validate" ).keypress(function() {
  return event.charCode >= 48 && event.charCode <= 57
});
  </script>
  <script>
      $('.dollar-input #percentage-input').attr('disabled', '');
      $('input[name="unit"]').on('change', function(){
          var unit = $(this).val();
        if(unit == 1){
            $('.percent-input #percentage-input').removeAttr('disabled');
            $('.dollar-input #percentage-input').attr('disabled', 'true');
            // $('.dollar-input #percentage-input').attr('type', 'hidden');
            $('.percent-input #percentage-input').attr('type', 'number');
            $('.percent-input #percentage-input').attr('name', 'percentage');
            $('.percent-input #percentage-input').attr('required', '');
            $('.dollar-input #percentage-input').removeAttr('required');
            $('.dollar-input #percentage-input').val('');
        }
        else {
            $('.dollar-input #percentage-input').removeAttr('disabled');
            // $('.percent-input #percentage-input').attr('type', 'hidden');
            $('.percent-input #percentage-input').removeAttr('name');
            $('.percent-input #percentage-input').removeAttr('required');
            $('.dollar-input #percentage-input').attr('type', 'number');
            $('.dollar-input #percentage-input').attr('required', '');
            $('.percent-input #percentage-input').attr('disabled', 'true');
            $('.percent-input #percentage-input').val('');
        }
      })
      $(document).ready(function(){
        var unitValue = $('input[name="unitType"]').val(); 
        
        if(unitValue){
            if(unitValue == 1){
                $('.percent-input #percentage-input').removeAttr('disabled');
            $('.dollar-input #percentage-input').attr('disabled', 'true');
            // $('.dollar-input #percentage-input').attr('type', 'hidden');
            $('.percent-input #percentage-input').attr('type', 'number');
            $('.percent-input #percentage-input').attr('name', 'percentage');
            $('.percent-input #percentage-input').attr('required', '');
            $('.dollar-input #percentage-input').removeAttr('required');
            
        }
        else {
            $('.dollar-input #percentage-input').removeAttr('disabled');
            // $('.percent-input #percentage-input').attr('type', 'hidden');
            $('.percent-input #percentage-input').removeAttr('name');
            $('.percent-input #percentage-input').removeAttr('required');
            $('.dollar-input #percentage-input').attr('type', 'number');
            $('.dollar-input #percentage-input').attr('required', '');
            $('.percent-input #percentage-input').attr('disabled', 'true');
            
        }
        }
      });
  </script>

@endsection