@extends('front.trainer.layout.trainer')
@section('title', 'Account Information')
@section('content')

<div id="content-wrapper" class="d-flex flex-column">
            <div id="content" class="account_page_wrapper">
                <div class="container-fluid">
                    <div class="page-title d-flex align-items-center justify-content-between mb-lg-4 mb-3 pb-lg-3 flex-wrap">
                        <a href="javascript:void(0);" class="menu-trigger d-lg-none d-flex order-0">
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>
                        </a>
                         <h2 class="h3 text-muted font-weight-normal mb-0 mt-lg-0 mt-3 order-lg-0 order-2">Account Information</h2>
                        
                        @include('front.trainer.layout.includes.header')
                    </div>
                    @if($providerStatus[0]->is_subscription)
                    @if($trailingProviderOrders < 1)
                    @if($providerOrdersCount < 1)
                    <div class="popup popup-danger text-center" role="alert">
                    Your subscription is cancelled and your page will not be visible to the public until you activate your account again. You can reactivate your subscription at any time in the account information tab.  
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
				                 @if (session('fail-message'))
				                    <div class="alert alert-danger">
				                    {{ session('fail-message') }}
				                    </div>
				                 @endif
				                 @if (session('error'))
				                    <div class="alert alert-danger">
				                    {{ session('error') }}
				                    </div>
				                 @endif
                                
                                <div id="loader-wrapper" style="display: none;">
                           
			                            <div id="loader">
			                                <img src="{{ asset('images/loader.png') }}" alt="Loader">
			                            </div>
			                            <div class="loader-section section-left"></div>
			                            <div class="loader-section section-right"></div>
			                             <div class="text_loaders" align="center"><h1>Your payment is processing... Do not refresh the page till the payment is completed.</h1></div>
			                        </div>
                                    <?php if(Auth::guard('front_auth')->user()->is_subscription == 1){ ?>
			                        <?php if($checkproviderOrders == 0){?>
			                        <form method="POST" action="{{route('trainer.providerpaymentsave')}}" class="require-validation mb-lg-5"
			                                                     data-cc-on-file="false"
			                                                    data-stripe-publishable-key="{{ env('STRIPE_PUB_KEY') }}"
			                                                    id="payment-form" >
			                            @csrf
			                            <!-- <input type="hidden" name="product_id" id="product_id" value="">
			                            <input type="hidden" name="plan_id" id="plan_id" value=""> -->
			                            <input type="hidden" name="start_date" id="start_date" value="<?php echo date('Y-m-d');?>">
			                            <div class="form-group mb-4 col-lg-4">
                                        
	                                        <!-- <h2>Your Current Subscription</h2> -->
                                            <h2>Select A Subscription</h2>
	                                        <select class="form-control" name="subscription_plan" id="subscription_plan" required>
		                                        	<option value="">Select Your Subscription Plan</option>
		                                        	@foreach($accountData as $acnt)
		                                        	<option value="{{$acnt->subcription_plan}}">{{$acnt->subcription_plan}} - ${{number_format($acnt->price, 2, '.', ',')}}</option>
		                                        	<!-- <option value="yearly">Yearly</option> -->
		                                        	@endforeach
	                                        </select>
	                                       
	                                    </div>
	                                    <!-- <div class="form-group mb-4 col-lg-6">
                                        
	                                        <label for="service-input">Price $</label>
	                                        <input type="text" class="form-control" name="price" id="price" value="" readonly>
	                                        
	                                       
	                                    </div>
	                                    <div class="form-group mb-4 col-lg-6">
                                        
	                                        <label for="service-input">Free Trial Period</label>
	                                        <input type="text" class="form-control" name="free_trial" id="free_trial" value="" readonly>
	                                        
	                                       
	                                    </div> -->
                                        <?php 
                                        //echo '<pre>';print_r($list_all_card_details);exit();
                                        $container = array();
                                            foreach($list_all_card_details as $data) {
                                                if(!empty($data->data)){
                                                    if(!isset($container[$data->data[0]->last4])) {
                                                        $container[$data->data[0]->last4] = $data;
                                                    }
                                                }
                                            }
                                            $container = array_values($container);
                                            
                                        ?>
	                                    
			                            <div class="payment-form col-md-6">
			                                <div class="section-title mb-3">
			                                    <!-- <h5 class="text-danger mb-3">Payment</h5> -->
			                                    <h3 class="mb-2">Payment Information</h3>
			                                </div>
			                                <?php $i=1; foreach ($container as $value) {
	                                    	?>
	                                    
			                            <label>
									        <span><input type="radio" name="payment-source" value="<?php echo $value->data[0]->customer;?>" <?php if($i==1){ echo 'checked'; }?>></span>
									        <div id="saved-card"><?php echo $value->data[0]->brand;?> **** **** **** <?php echo $value->data[0]->last4;?></div>
									    </label>
									    <?php $i = $i+1;}?>
									    <label>
									        <span><input type="radio" name="payment-source" value="New_card" id="new-card-radio"></span>
									        <div id="card-element" class="field sr-input sr-card-element"></div> 
									        
									    </label>
									    <div class='form-row row'>
			                                    <div class='col-md-12 error form-group hide'>
			                                    <div class='alert-danger alert sr-field-error'>Please correct the errors and try
			                                    again.</div>
			                                    </div>
			                                </div><div class='form-row row invaild-card' style="display:none;">
			                                    <div class='col-md-12 error form-group'>
			                                    <div class='alert-danger alert'>Your credit card information appears to be incorrect. Please try again.</div>
			                                    </div>
			                                </div><br>
                                        <div class="row">
                                                <div class="form-group mb-0 col-lg-12 book-now-btn">
                                                    <button class="btn btn-danger" type="submit">SAVE</button>
                                                </div>
                                            </div>
									</div>
                                    <!-- <div class="outcome">
                                      <div class="error"></div>
                                      <div class="success-saved-card">
                                        Success! Your are using saved card <span class="saved-card"></span>
                                      </div>
                                      <div class="success-new-card">
                                        Success! The Stripe token for your new card is <span class="token"></span>
                                      </div>
                                    </div> -->
			                            <!-- <div class="payment-form col-md-6" id="New_card_detailssss" >
			                                <div class="section-title mb-3">
			                                    <h5 class="text-danger mb-3">Payment</h5>
			                                    <h2 class="mb-2">CARD DETAILS</h2>
			                                    <h6 class="text-danger mb-0" style="text-decoration:lowercase">Powered by Stripe</h6>
			                                </div>
											<div class="sr-input sr-card-element" id="card-element"></div>
			                                <div class='form-row row'>
			                                    <div class='col-md-12 error form-group hide'>
			                                    <div class='alert-danger alert sr-field-error'>Please correct the errors and try
			                                    again.</div>
			                                    </div>
			                                </div><div class='form-row row invaild-card' style="display:none;">
			                                    <div class='col-md-12 error form-group'>
			                                    <div class='alert-danger alert'>Your credit card information appears to be incorrect. Please try again.</div>
			                                    </div>
			                                </div><br>
			                                
			                                <div class="row">
                                                <div class="form-group mb-0 col-lg-12 book-now-btn">
                                                    <button class="btn btn-danger" type="submit">BOOK</button>
                                                </div>
                                            </div>



			                             </div> -->
                                         
			                        </form>
			                    <?php }?>
			                    <div class="common-data-table">
			                        <div class="container-fluid">
                                <table id="account-table" class="display nowrap" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th width="2%">SI.No</th>
                                            <th width="15%">Date</th>
                                            <th width="18%">Plan Type</th>
                                            <th width="12%">Amount</th>
                                            <th width="12%">Cancel Date</th>
                                            <th width="25%">Status</th>
                                        </tr>
                                    </thead>
                                    
                                    <tbody>
                                    @if($providerOrders)
                                    @foreach($providerOrders as $key => $orders)
                                    
                                    
                                        <tr>
                                            <td>{{$key + 1}}</td>
                                            <td>{{ date('m-d-Y', strtotime($orders->start_date)) }}</td>
                                            
                                            <td>{{$orders->plan_type}}</td>
                                            
                                            <td>@if($orders->plan_type == "monthly")
				                                ${!! number_format((float)($orders->amount), 2) !!} USD Monthly
				                                @endif
				                                 @if($orders->plan_type == "yearly")
				                                 ${!! number_format((float)($orders->amount), 2) !!} USD Yearly
				                                @endif 
				                            </td>
				                            <td>@if($orders->cancel_date != '0000-00-00'){{ date('m-d-Y', strtotime($orders->cancel_date)) }}@endif</td>
                                            <td>
                                            	<?php
                                            	Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY')); 
			                                     if($orders->subscription_status !== "cancelled"){
			                                        $subscription = \Stripe\Subscription::retrieve(
			                                            //$order->stripe_subscription_id, ['stripe_account' => $TrainerServicesdata->stripe_user_id]
			                                            $orders->stripe_subscription_id
			                                         );
			                                     }

			                                     
			                                     // dd($subscription);
			                                ?>
			                                    @if($orders->subscription_status == "cancelled")
			                                    <span class="badge badge-success">Cancelled</span>
			                                    @else
			                                    <span class="badge badge-success">{{ucfirst($subscription->status)}}</span><br>
			                                    <a href="{{route('trainer.provider.cancel.subscription',['order'=> base64_encode($orders->id), 'subscriptionid' => base64_encode($orders->stripe_subscription_id) ])}}" onclick="return confirm('Are you sure?')" class="cancelOrder">Cancel Subscription</a>
                                                <!-- <a href="{{route('trainer.provider.edit.plan.subscription',['planid'=> base64_encode($orders->id) ])}}" onclick="return confirm('Are you sure?')" class="cancelOrder">Edit Plan</a> -->
                                                <a href="{{route('trainer.provider.edit.plan.subscription',['planid'=> base64_encode($orders->id) ])}}" class="cancelOrder">Edit Plan</a>
			                                    @endif
                                               
                                            </td>
                                            
                                        </tr>
                                        @endforeach
                                        @else 
                                            <!-- <tr><td colspan="7">No ratings & reviews yet</td></tr> -->
                                            @endif
                                       
                                    </tbody>
                                </table>
                                </div>
                            </div>
                            
                            <!-- change password section -->
                            <hr />
                            <?php } ?>
                            <section id="change_password" class="page-content login-register-page" style="padding-top: 0px;padding-bottom:30px">
                            <div class="p-lg-5">
                                <div class="register-block">
                                    <div class="register-block-inner">
                                        <div class="row">
                                            <div class="col-lg-12 pb-3">
                                                <div class="title">
                                                    <h2>Change Password</h2>
                                                </div>
                                            </div>
                                        </div>
                                        @if (session('warning'))
                                        <div class="alert alert-danger">
                                            {{ session('warning') }}
                                        </div>
                                        @endif
                                        @if (session('success'))
                                        <div class="alert alert-success">
                                            {{ session('success') }}
                                        </div>
                                        @endif
                                        <form class="form-row" method="POST" action="{{route('trainer.change_password')}}#change_password" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="role_type" value="customer" />
                                            <div class="form-group col-lg-6">
                                                <label for="password-input">Current Password</label>
                                                <input type="password" class="form-control" name="old_password" id="old_password-input" placeholder="current password" value="" required="" >
                                                @if ($errors->has('old_password'))
                                                <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('old_password') }}</div>
                                                @endif
                                            </div>
                                            <div class="form-group col-lg-6">
                                                <label for="password-input">New Password</label><span style="float: right;font-size: 12px;" class="password_limit">Minimum 8 characters</span>
                                                <input type="password" class="form-control" name="password" id="password-input" placeholder="new password" value="" required="" minlength="8" onChange="validatePassword()">
                                                @if ($errors->has('password'))
                                                <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('password') }}</div>
                                                @endif
                                                <div style="display: block;" class="error invalid-feedback check_special_char"></div>
                                            </div>
                                            <div class="form-group col-lg-6">
                                                <label for="confirm-password-input">Confirm Password</label>
                                                <input type="password" class="form-control" name="confirm_password" id="confirm-password-input" placeholder="confirm password" value="" required="">
                                                @if ($errors->has('confirm_password'))
                                                <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('confirm_password') }}</div>
                                                @endif
                                            </div>
                                            <div class="col-lg-12 d-flex justify-content-center">
                                                <input type="submit" value="Save Details" class="btn btn-danger btn-lg user_change_password" />
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </section>
                          <!-- end change password section -->
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

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://js.stripe.com/v3/"></script>

<style>
    
    #change_password label {
    height: 35px;
}
.password_limit {
    position: absolute;
    top: 0;
    right: 25px;
}
@media only screen and (max-width: 430px) {
    .password_limit {
    position: relative;
}
}
/* #is_recurring, #recurring_options{
  display:none;
} */
#slot-input option:disabled{
    color: darkgray; 
}
#show_data{
 position:sticky;
 height:100%;
 top:0;
}
.ajax-loader {
   visibility: hidden;
   position: absolute;
   bottom: 3%;
}

.ajax-loader img {
  position: relative;
  top:50%;
  left:50%;
}
    /* Payment form  */
    #payment-form .card{
        border: 0;
        display: block;
        border-radius: 0;
        box-shadow: none;
        overflow: auto;
    }
    #payment-form .hide{
        display: none !important;
    }
    .btn{
        font-size: 15px;
        height: 42px;
        line-height: 38px;
        padding: 0 10px;
    }
    .text_loaders h1{
       
        display: block;
        position: absolute;
        top: 25%;
        width: 100%;
        z-index: 1001;
        text-align: center;
        display: flex;
        color: #00ab91;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }
   table{
  margin: 0 auto;
  width: 100%;
  clear: both;
  border-collapse: collapse;
  /* table-layout: fixed;  */
  word-wrap:break-word; 
}
.common-data-table table.dataTable tbody td{
    white-space: inherit; 
}
.cancelOrder{
    background: #00ab91;
    padding: 5px 10px;
    border-radius: 4px;
    color: #FFF;
    font-size: 12px;
    border: solid 1px #CCC;
    margin-top: 8px;
    line-height: 38px;
    /* display:block; */
}
.cancelOrder:hover{
    color: #FFF; 
}
td.details_control{
    background: url('../public/images/select-arrow.png') no-repeat center center;
    cursor: pointer;
}

#triangle-down{
    width: 0;
    height: 0;
    border-left: 10px solid transparent;
    border-right: 10px solid transparent;
    border-top: 15px solid #fff;
    right: 0;
    right: 5px;
    bottom: 0;
}
    .tooltip-wrap {
  position: relative;
}
#triangle-down{
    display:none;
}
.dataTables_filter{
    float:right;
}
.tooltip-wrap .tooltip-content {
  display: none;
  position: absolute;
    bottom: 100%;
    /* left: 0; */
    right: 5%;
    background-color: #fff;
    padding: 1em;
    width: 350px;
    border-radius: 5px;
    border: solid 1px #e7e6e6;
}
.tooltip-wrap:hover .tooltip-content, .tooltip-wrap:hover #triangle-down {
  display: block;
}
.badge {
    padding: 8px 12px;
    font-size: 11px;
    
}

#loader-wrapper {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1000;
}
#loader {
    display: block;
    position: relative;
    left: 50%;
    top: 50%;
    width: 150px;
    height: 150px;
    margin: -75px 0 0 -75px;
    border-radius: 50%;
    border: 7px solid transparent;
    border-top-color: #fff;
    -webkit-animation: spin 2s linear infinite;
    animation: spin 2s linear infinite;
    z-index: 1001;
    display: flex;
    align-items: center;
    justify-content: center;
}
#loader img {
    -webkit-animation: spin-invert 2s linear infinite;
    animation: spin-invert 2s linear infinite;
}

#loader-wrapper .loader-section {
    position: fixed;
    top: 0;
    width: 51%;
    height: 100%;
    background: #1e2732;
    z-index: 1000;
    -webkit-transform: translateX(0);
    -ms-transform: translateX(0);
    transform: translateX(0);
}
#loader-wrapper .loader-section.section-left {
    left: 0;
}
#loader-wrapper .loader-section.section-right {
    right: 0;
}
.loaded #loader-wrapper .loader-section.section-left {
    -webkit-transform: translateX(-100%);
    -ms-transform: translateX(-100%);
    transform: translateX(-100%);
    -webkit-transition: all .7s .3s cubic-bezier(0.645, 0.045, 0.355, 1.000);
    transition: all .7s .3s cubic-bezier(0.645, 0.045, 0.355, 1.000);
}
.loaded #loader-wrapper .loader-section.section-right {
    -webkit-transform: translateX(100%);
    -ms-transform: translateX(100%);
    transform: translateX(100%);
    -webkit-transition: all .7s .3s cubic-bezier(0.645, 0.045, 0.355, 1.000);
    transition: all .7s .3s cubic-bezier(0.645, 0.045, 0.355, 1.000);
}
.loaded #loader {
    opacity: 0;
    -webkit-transition: all .3s ease-out;
    transition: all .3s ease-out;
}
.loaded #loader-wrapper {
    visibility: hidden;
    -webkit-transform: translateY(-100%);
    -ms-transform: translateY(-100%);
    transform: translateY(-100%);
    -webkit-transition: all .3s 1s ease-out;
    transition: all .3s 1s ease-out;
}
.no-js #loader-wrapper {
    display: none;
}
.no-js h1 {
    color: #222;
}
label, #pouet {
  position: relative;
  color: #8898AA;
  font-weight: 300;
  height: 40px;
  line-height: 40px;
  margin-left: 0px;
  display: flex;
  flex-direction: row;
}
label > span, #pouet > span {
  width: 18px;
  text-align: right;
  margin-right: 10px;
}
.outcome {
  float: left;
  width: 100%;
  padding-top: 8px;
  min-height: 24px;
  text-align: center;
}
button:focus {
  background: #555ABF;
}

button:active {
  background: #43458B;
}
.field {
  background: transparent;
  font-weight: 300;
  border: 0;
  color: #31325F;
  outline: none;
  flex: 1;
  padding-right: 10px;
  padding-left: 10px;
  cursor: text;
}
.field::-webkit-input-placeholder { color: #CFD7E0; }
.field::-moz-placeholder { color: #CFD7E0; }

/*.success-new-card, .success-saved-card, .error {
  display: none;
  font-size: 13px;
}

.success-new-card.visible, .success-saved-card.visible, .error.visible {
  display: inline;
}

.error {
  color: #E4584C;
}

.success-new-card, .success-saved-card {
  color: #666EE8;
}

.success-new-card .token, .success-saved-card .saved-card, {
  font-weight: 500;
  font-size: 13px;
}*/
</style>
<script type="text/javascript">
    
	<?php if($checkproviderOrders == 0){?>
/*$('select').on('change', function() {

	if(this.value){
  		$.ajax({
              url: "{{url("accountinformationdetails")}}/"+this.value,
              contentType: "application/json",
              dataType: "json",
              type: "GET",
              async: false,
              success: function(data) {
              	
              	$('#price').val(data.price);
              	$('#free_trial').val(data.free_trial_months);
              	$('#product_id').val(data.product_id);
              	$('#plan_id').val(data.plan_id);
              }
          });
	} else {
		$('#price').val('');
		$('#free_trial').val('');
		$('#product_id').val('');
        $('#plan_id').val('');
	}
});*/
@if(isset($StripeAccountsData->stripe_user_id))
    var stripe = Stripe('{{ env('STRIPE_PUB_KEY') }}'); 
@else
    var stripe = Stripe('{{ env('STRIPE_PUB_KEY') }}'); 
@endif

//var stripe = Stripe('{{ env('STRIPE_PUB_KEY') }}'); 
var stripe = Stripe('pk_live_ygqzqoE0bKgVWtbk7MLQW5eW00hFXQQGMw');
    //console.log('{{ env('STRIPE_PUB_KEY') }}');
var elements = stripe.elements();

var style = {
    base: {
      color: "#32325d",
      fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
      fontSmoothing: "antialiased",
      fontSize: "16px",
      "::placeholder": {
        color: "#aab7c4"
      }
    },
    invalid: {
      color: "#fa755a",
      iconColor: "#fa755a"
    }
  };
  var paymentIntentClientSecret = null;
var card = elements.create('card',{ style: style });
 card = elements.getElement('card');
 card.mount('#card-element');

card.on('focus', function(event) {
  document.querySelector('#new-card-radio').checked = true;
});
 card.on('change', function(event) {
  if (event.complete) {
    $('.invaild-card').attr('style','display: none !important');
  } else if (event.error) {
  $('.sr-field-error').attr('style','display: none !important');
    $('.invaild-card').attr('style','display: block !important');
  }
});
// StripePayment
$(function() {
    
    var $form         = $(".require-validation");
  $('form.require-validation').bind('submit', function(e) {
    
    var $form         = $(".require-validation"),
        inputSelector = ['input[type=email]', 'input[type=password]',
                         'input[type=text]', 'input[type=file]',
                         'textarea'].join(', '),
        $inputs       = $form.find('.required').find(inputSelector),
        $errorMessage = $form.find('div.error'),
        valid         = true;
        $errorMessage.addClass('hide');
 
        $('.has-error').removeClass('has-error');
    $inputs.each(function(i, el) {
      var $input = $(el);
      if ($input.val() === '') {
        $input.parent().addClass('has-error');
        $errorMessage.removeClass('hide');
        e.preventDefault();
      }
    });
    var showError = function(errorMsgText) {
        $('body').addClass('loaded');
  $errorMessage = $form.find('div.error'), 
        $errorMessage.removeClass('hide');
  var errorMsg = document.querySelector(".sr-field-error");
  $('.invaild-card').attr('style','display: none !important');
  $('.sr-field-error').attr('style','display: block !important');
  errorMsg.textContent = errorMsgText; 
};
var orderComplete = function(clientSecret) {
  // Just for the purpose of the sample, show the PaymentIntent response object
  stripe.retrievePaymentIntent(clientSecret).then(function(result) {
    var paymentIntent = result.paymentIntent;
    var paymentIntentJson = JSON.stringify(paymentIntent, null, 2);
 $.ajax({
            url: '{{ route('trainer.providerpaymentsave') }}',
            'type': 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            data: {
                "paymentIntent": paymentIntent,
                'paymentIntentJson': paymentIntentJson,
                'form_data':$('#payment-form').serialize(),
            },
            
            success: function (result) { 
                 //window.location = '{{route('trainer.account.information')}}';
            }, error: function () {
                $('body').addClass('loaded');
            }
        });
  });
};   
$('body').removeClass('loaded');
    if (!$form.data('cc-on-file')) {
      e.preventDefault();
    var radioButton = document.querySelector('input[name="payment-source"]:checked');
    
  if (radioButton.value == 'New_card') {
    stripe.createToken(card).then(function(result) {
          	
            if (result.error) {
              showError(result.error.message); 
            } else { 
            	$('#loader-wrapper').attr('style','display: block !important');
            	stripeResponseHandler(result.token.id);
            	
                
            }
          });
           /*$.ajax({
		            url: '{{ route('trainer.createproviderpaymentintent') }}',
		            'type': 'POST',
		            headers: {
		                'X-CSRF-TOKEN': '{{ csrf_token() }}'
		            },
		            data: {
		                'form_data':$('#payment-form').serialize(),
		            },
		            
		            success: function (result) {
		                if (result.status == true) {
		                     clientcreactkey = result.client_secret; 
		                 //alert(clientcreactkey);
		                  stripe.confirmCardPayment(clientcreactkey, {
		                      payment_method: {
		                        card: card
		                      }
		                    })
		                    .then(function(result) {
		                    	//alert(result.error);
		                      if (result.error) {
		                        // Show error to your customer
		                        $('body').addClass('loaded');
		                       showError(result.error.message);
		                      } else {
		                      	
		                         stripe.createToken(card).then(function(result) {
          	
						            if (result.error) {
						              showError(result.error.message); 
						            } else { 
						            	//$('#loader-wrapper').attr('style','display: block !important');
						            	stripeResponseHandler(result.token.id);
						            	
						                
						            }
						          });             					
              					orderComplete(clientcreactkey);
		                      }
		                    });
		                }else{
		                    showError(result.Message);
		                    $('body').addClass('loaded');
		                }
		               
		                 
		            }, error: function () {
		                $('body').addClass('loaded');
		            }
		        });  */
          


  } else {
    setOutcome({saved_card: radioButton.value});
    $('#loader-wrapper').attr('style','display: block !important');
  }
       
         

    }
  
  });
  function setOutcome(result) {
 
  
  if (result.token) {
    
  } else if (result.saved_card) {
   stripeResponseHandler(result.saved_card);
   
  } else if (result.error) {
    
    showError(result.error.message); 
  }
}
  
  function stripeResponseHandler(token) {
        //console.log(token);
            $form.find('input[type=text]').empty();
            $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
            $form.get(0).submit();
       
    }
  
});
<?php }?>
    $(document).ready(function() { 

        /*$("input[name=payment-source]").click(function (e) {

                if ($(this).val() == "New_card") {
                    // Disable your roomnumber element here
                    $('#New_card_detailssss').show();
                    //e.preventDefault();
                } else {
                    // Re-enable here I guess
                    $('#New_card_detailssss').hide();
                   // e.preventDefault();
                }
                
            });*/
            // Setup - add a text input to each footer cell 
            $('#myTable tfoot th').each( function () 
            { //console.log($(this).index());
                if($(this).index() == 1 || $(this).index() == 2){
                    var title = $('#myTable tfoot th').eq( $(this).index() ).text(); 
                 $(this).html( '<input type="text" style="width: 100%" placeholder=" '+title+'" />' ); 
                }
                
            }); 
           // DataTable 
           var table = $('#account-table').DataTable({
                rowReorder: false,
                responsive: true,
                "bPaginate": true,
                "bLengthChange": true,
                "bFilter": true,
                "bInfo": true,
                "bSortable": true,
                "bAutoWidth": false,
                "columnDefs": [
                    { "orderable": false, "targets": 0 },
                    { type: 'currency', targets: 2 }
                ],
                language: {
                    searchPlaceholder: "Search..",
                    "paginate": {
                       "previous": "Prev"
                    },
                    "sEmptyTable":     "You have added no services to your account"
                },
                "order": [[ 0, "asc" ]],
                "aaSorting": [[ 2, 'asc' ]] ,
                "pageLength": 10
            });

           // Apply the search 
           /*table.columns().eq( 0 ).each( function ( colIdx ) 
            { 
                $( 'input', table.column( colIdx ).footer() ).on( 'keyup change', function () 
                {
                table .column( colIdx ) .search( this.value ) .draw(); 
                }); 
            }); */

           
    });


// Change Password Validation

function validatePassword() {
    var p = $('#password-input').val();
  
   
    const errors = [];

    if (p.length < 8) {
        errors.push("Your password must be at least 8 characters.");
    }
    if (p.length > 32) {
        errors.push("Your password must be at max 32 characters.");
    }
    if (p.search(/[a-z]/) < 0) {
        errors.push("Your password must contain at least one lower case letter."); 
    }
    if (p.search(/[A-Z]/) < 0) {
        errors.push("Your password must contain at least one upper case letter."); 
    }

    if (p.search(/[0-9]/) < 0) {
        errors.push("Your password must contain at least one digit.");
    }
   if (p.search(/[!@#\$%\^&\*_]/) < 0) {
        errors.push("Your password must contain at least special char from -[ ! @ # $ % ^ & * _ ]"); 
    }
    if (errors.length > 0) {
        
        //console.log(errors.join("\n"));
        if(p.length == 0){
            $('.check_special_char').hide();
            //$('.user_change_password').show();
            $('.user_change_password').removeAttr('disabled');
        } else {
            $('.check_special_char').show();
            $('.check_special_char').html(errors.join("\n"));
            //$('.user_change_password').hide();
             $('.user_change_password').attr('disabled', 'disabled');
            return false;
        }
    }

    $('.check_special_char').hide();
    //$('.user_change_password').show();
    $('.user_change_password').removeAttr('disabled');
    return true;
}


</script>



@endsection