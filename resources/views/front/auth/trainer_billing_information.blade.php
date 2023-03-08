@extends('front.layout.app')
@section('title', 'Register')
@section('content')
<section class="inner-banner-section bg-primary">
        <div class="container">
            <div class="banner-content">
                <h1>Register/Log In</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Register/Log In</li>
                    </ol>
                </nav>
            </div>
        </div>
    </section>

    <section class="page-content login-register-page">
        <div class="container">
            <div class="register-block">
                <div class="">
                    <div class="row justify-content-md-center">
                        <div class="col-lg-6 pr-lg-6 col-md-auto mb-lg-0 mb-5 bg_first">
                            <div class="block-inner">
                                
                                <h2 class="text-center login_text">Choose Your Subscription Plan</h2>
                                <!-- <h4 class="text-center text-danger">Try us free for <span id="free_trial_month">3</span> months when you sign up today! Cancel at any time.</h4> -->
                                <hr class="hre_padding">
                                @if (session('message'))
                                    <div class="alert alert-success">
                                        {{ session('message') }}
                                    </div>
                                @endif
                                

                                
                                            </div>
                             
                             <div id="loader-wrapper" style="display: none;">
                           
                                <div id="loader">
                                    <img src="{{ asset('images/loader.png') }}" alt="Loader">
                                </div>
                                <div class="loader-section section-left"></div>
                                <div class="loader-section section-right"></div>
                                 <div class="text_loaders" align="center"><h1>Your payment is processing... Do not refresh the page till the payment is completed.</h1></div>
                            </div>
                      <form method="POST" action="{{route('front.register')}}" class="require-validation mb-lg-5"
                                                                 data-cc-on-file="false"
                                                                data-stripe-publishable-key="pk_live_ygqzqoE0bKgVWtbk7MLQW5eW00hFXQQGMw"
                                                                id="payment-form" >
                                        @csrf
                                        @if (app('request')->input('ref'))
                                            <input type="hidden" name="ref" value="{{app('request')->input('ref')}}" />
                                        @endif
                                        <input type="hidden" name="product_id" id="product_id" value="">
                                        <input type="hidden" name="plan_id" id="plan_id" value="">
                                        <input type="hidden" name="user_role" id="user_role" value="<?php echo $requestData['user_role'];?>">
                                        <input type="hidden" name="first_name" id="first_name" value="<?php echo $requestData['first_name'];?>">
                                        <input type="hidden" name="last_name" id="last_name" value="<?php echo $requestData['last_name'];?>">
                                        <input type="hidden" name="business_name" id="business_name" value="<?php echo $requestData['business_name'];?>">
                                        <input type="hidden" name="email" id="email" value="<?php echo $requestData['email'];?>">
                                        <input type="hidden" name="password" id="password" value="<?php echo $requestData['password'];?>">
                                        <input type="hidden" name="password_confirmation" id="password_confirmation" value="<?php echo $requestData['password_confirmation'];?>">
                                        <input type="hidden" name="start_date" id="start_date" value="<?php echo date('Y-m-d');?>">
                                        <div class="form-group">

                                        <div class="section-title mb-5">
                                        <h2 class="mb-2">SUBSCRIPTION PLAN</h2>
                                            <select class="form-control" name="subscription_plan" id="subscription_plan" required>
                                                    <!-- <option value="">Select Your Subscription Plan</option> -->
                                                    @foreach($accountData as $key => $acnt)

		                                        	<option value="{{$acnt->subcription_plan}}" >{{$acnt->subcription_plan}} - ${{number_format($acnt->price, 2, '.', ',')}}</option>
		                                        	<!-- <option value="yearly">Yearly</option> -->
		                                        	@endforeach
                                            </select>
                                            </div> 
                                        </div>

                                        <input type="hidden" class="form-control" name="price" id="price" value="" readonly>
                                        <input type="hidden" class="form-control" name="free_trial" id="free_trial" value="" readonly>
                                      <!--    <div class="form-group">
                                        
                                            <label for="service-input">Price $</label>
                                           
                                            
                                        </div> 

                                       <div class="form-group">
                                        
                                            <label for="service-input">Free Trial <span id="free_trial_month">0</span> Month Period</label>
                                            
                                            
                                           
                                        </div>-->

                                        

                                        <div class="payment-form form-group">
                                            <div class="section-title mb-3">
                                            <h2 class="text-center login_text">Payment Information</h2>
                                                <h2 class="mt-4" style="margin-bottom: 0;">CARD DETAILS</h2>
                                                <h6 class="text-danger mb-0" style="text-transform: lowercase;">Securely Powered by Stripe</h6>
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
                                                <div class="form-group mb-0 col-lg-12 book-now-btn text-center">
                                                    
                                                    <button type="submit" class="btn btn-info btn-lg mb-3 width_40" >Register</button>
                                                </div>
                                            </div>



                                         </div>
                                    </form>



                      
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('pagescript')
<!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" /> -->
<!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<!--<script type="text/javascript" src="https://js.stripe.com/v2/"></script>-->
<script src="https://js.stripe.com/v3/"></script>

<style>
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
.StripeElement:before{
    width: 0px !important;
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
        line-height: 40px !important;
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
</style>
<script type="text/javascript">
    
    $(function(){
        $('#subscription_plan').val('monthly');
        updateTrail();
    });
$('#subscription_plan').on('change', function() {
    updateTrail();
});
    function updateTrail(){

    if($('#subscription_plan').val()){
       var dropdownValue = $('#subscription_plan').val();
    //    console.log(dropdownValue);
        $.ajax({
              url: "{{url('provideraccountinformationdetails')}}/"+dropdownValue,
              contentType: "application/json",
              dataType: "json",
              type: "GET",
              async: false,
              success: function(data) {
                
                $('#price').val(data.price);
                $('#free_trial').val(data.free_trial_months);
                $('#free_trial_month').html(data.free_trial_months);
                $('#product_id').val(data.product_id);
                $('#plan_id').val(data.plan_id);
              }
          });
    } else {
        $('#price').val('');
        $('#free_trial').val('');
        $('#free_trial_month').html(data.free_trial_months);
        $('#product_id').val('');
        $('#plan_id').val('');
    }
    
};

@if(isset($StripeAccountsData->stripe_user_id))
    var stripe = Stripe('{{ env('STRIPE_PUB_KEY') }}'); 
@else
    var stripe = Stripe('{{ env('STRIPE_PUB_KEY') }}'); 
@endif

var stripe = Stripe('pk_live_ygqzqoE0bKgVWtbk7MLQW5eW00hFXQQGMw');
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
  //$('.home-header').attr('style','display: block !important');
  //$('.dark-header').attr('style','display: block !important');
  $('.dark-header').removeAttr('style');
  //console.log(errorMsgText);
  errorMsg.textContent = errorMsgText; 
};
  var orderComplete = function(clientSecret) {
      console.log(orderComplete);
  // Just for the purpose of the sample, show the PaymentIntent response object
  stripe.retrievePaymentIntent(clientSecret).then(function(result) {
    var paymentIntent = result.paymentIntent;
    var paymentIntentJson = JSON.stringify(paymentIntent, null, 2);
 $.ajax({
            url: '{{ url('register') }}',
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
                 window.location = '{{route('front.dashboard')}}';
            }, error: function () {
                $('body').addClass('loaded');
            }
        });
  });
};
$('body').removeClass('loaded');
    if (!$form.data('cc-on-file')) {
      e.preventDefault();
    
       
            stripe.createToken(card).then(function(result) {
            if (result.error) {
              showError(result.error.message); 
            } else { 
                
                $('#loader-wrapper').attr('style','display: block !important');
                $('.dark-header').attr('style','display: none !important');
                $('.home-header').attr('style','display: none !important');
                
                //alert(result);
              stripeResponseHandler(result.token.id);
            }
          });
          var plan_price = $('#price').val();
            /*$.ajax({
                    url: '{{ url('createnewproviderpaymentintent') }}',
                    'type': 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: {
                        'form_data':$('#payment-form').serialize(),
                        'planPrice' : plan_price,
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
                                //orderComplete(clientcreactkey);
                              }
                            });
                        }else{
                            showError(result.Message);
                            $('body').addClass('loaded');
                        }
                       
                         
                    }, error: function () {
                        $('body').addClass('loaded');
                    }
                }); */

    }
  
  });
  
  function stripeResponseHandler(token) {
        //console.log(token);
            $form.find('input[type=text]').empty();
            $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
            $form.get(0).submit();
       
    }
  
});
</script>



@endsection
