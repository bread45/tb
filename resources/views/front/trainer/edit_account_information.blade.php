@extends('front.trainer.layout.trainer')
@section('title', 'Edit Plan')
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
                         <h1 class="h3 text-muted font-weight-normal mb-0 mt-lg-0 mt-3 order-lg-0 order-2">Account Information</h1>
                        
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
                                <div class="row">
						            <div class="col-lg-12 order-1 order-lg-0"> 
						            <a style="float:right;background-color: #00ab91;" class="btn btn-primary btn-sm" href="{{ url('/trainer/account-information')}}">Back</a>
						            </div>
						        </div>
                                <div id="loader-wrapper" style="display: none;">
                           
			                            <div id="loader">
			                                <img src="{{ asset('images/loader.png') }}" alt="Loader">
			                            </div>
			                            <div class="loader-section section-left"></div>
			                            <div class="loader-section section-right"></div>
			                             <div class="text_loaders" align="center"><h1>Your payment is processing... Do not refresh the page till the payment is completed.</h1></div>
			                        </div>
			                        <?php //echo '<pre>';print_r($providerOrders);exit();?>
			                        <form method="POST" action="{{route('trainer.providerpaymenteditsave')}}" class="require-validation mb-lg-5 edit_payment_form"
			                                                     data-cc-on-file="false"
			                                                    data-stripe-publishable-key="{{ env('STRIPE_PUB_KEY') }}"
			                                                    id="payment-form" >
			                            @csrf
			                            <input type="hidden" name="order_id" id="order_id" value="{{$providerOrders->id}}">
			                            <input type="hidden" name="subscription_id" id="subscription_id" value="{{$providerOrders->stripe_subscription_id}}">
			                            <input type="hidden" name="start_date" id="start_date" value="<?php echo date('Y-m-d');?>">
			                            <div class="row">
			                            <div class="form-group mb-4 col-lg-5">
                                        
	                                        <!-- <label for="service-input">Edit Subscription Plan</label> -->
                                          <h2>Your Current Subscription</h2> 
	                                        <select class="form-control" name="subscription_plan" id="subscription_plan" required disabled >
		                                        	<!-- <option value="">Select Your Subscription Plan</option> -->
		                                        	@foreach($accountData as $acnt)
		                                        	<option value="{{$acnt->subcription_plan}}" <?php if($providerOrders->plan_type == $acnt->subcription_plan){
                                                echo"selected";
                                              } ?> 
                                              >
                                              {{$acnt->subcription_plan}} - ${{number_format($acnt->price, 2, '.', ',')}}
                                              </option>
		                                        	<!-- <option value="yearly">Yearly</option> -->
		                                        	@endforeach
	                                        </select>
	                                    </div>
                                      <div class="form-group mb-4 col-lg-3">
                                      <button type="button" class="activate_plan btn btn-danger mt-2" id="activate_plan" style="margin-top: 10px;">Change</button>
                                      </div>
                                      </div>
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
                                    
			                            
                                         
			                        </form>
			                   
			                    
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
    
	

@if(isset($StripeAccountsData->stripe_user_id))
    var stripe = Stripe('{{ env('STRIPE_PUB_KEY') }}'); 
@else
    var stripe = Stripe('{{ env('STRIPE_PUB_KEY') }}'); 
@endif

// var stripe = Stripe('{{ env('STRIPE_PUB_KEY') }}'); 
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
            url: '{{ route('trainer.providerpaymenteditsave') }}',
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
              
            }
          });
         /* $.ajax({
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
            $('#subscription_plan').removeAttr('disabled');
            $form.get(0).submit();
       
    }
  
// Subscription plan enabled script

$('#activate_plan').on('click', function(){ 
  $('#subscription_plan').removeAttr('disabled'); 
});

});
    
    
</script>



@endsection