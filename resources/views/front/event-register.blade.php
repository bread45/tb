@extends('front.layout.app')
@section('title', 'Event Registration')
@section('content')
 
<section class="inner-banner-section bg-primary">
   <div class="container">
      <div class="banner-content">
         <h1>Event Registration</h1>
         <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
               <li class="breadcrumb-item"><a href="index.html">Home</a></li>
               <li class="breadcrumb-item active" aria-current="page">Event Registration</li>
            </ol>
         </nav>
      </div>
   </div>
</section>
<section class="event-register-section">
   <div class="container">
      <div class="row">
      <div class="col-lg-12 order-1 order-lg-0"> 
            <!--<a style="float:right;background-color: #00ab91;" class="btn btn-primary btn-lg" href="{{ url('/event-details/'.base64_encode($event[0]->id)) }}">Back</a>-->
        </div>
      </div>
      <div class="section-title mb-3">
                                    <!-- <h2 class="mb-2">EVENT REGISTRATION</h2> -->
                                    <h2 class="text-danger mb-3">{{ $event[0]->title }}</h2>
                                    <h5 style="text-transform: Capitalize;">Subtotal: ${{$event[0]->cost}}</h5>
                                </div>
           <div class="row">
           
              <div class="col-md-7">
              <div id="loader-wrapper" style="display: none;">
                           
                           <div id="loader">
                               <img src="{{ asset('images/loader.png') }}" alt="Loader">
                           </div>
                           <div class="loader-section section-left"></div>
                           <div class="loader-section section-right"></div>
                            <div class="text_loaders" align="center"><h1>Your booking is processing... Do not refresh the page till the booking is completed.</h1></div>
                       </div>

                                <form method="POST" action="{{route('eventregisterpayment')}}" class="require-validation" data-cc-on-file="false" data-stripe-publishable-key="{{ env('STRIPE_PUB_KEY') }}" id="payment-form" >
                              @csrf   
                            @if($event[0]->type != 'Free')
                            @if($event[0]->accept_promo == 1)
                            <div class="coupon-form">
                                <div class="section-title mb-3">
                                    <!-- <h5 class="text-danger mb-3">Promo Code Details</h5> -->
                                    
                                </div>
                                <?php
                                $coupondetails = DB::table('coupons')->where(["trainer_id" => $event[0]->trainer_id])->first();
                                if(isset($coupondetails)){
                                    if($coupondetails->unit == 1){
                                        
                                        $total_discounts = ($coupondetails->percentage/100)*$event[0]->cost;
                                        $amountToPay = $event[0]->cost - $total_discounts;
                                    } else {
                                        $total_discounts = $coupondetails->percentage;
                                        $amountToPay = $event[0]->cost - $total_discounts;
                                    }
                                    
                                } else {
                                    $amountToPay = '';
                                }
                                ?>
                                  <input type="hidden" name="coupon_code" id="coupon_code" value="@if(isset($coupondetails)){{ $coupondetails->coupon_code }}@endif">
                                  <input type="hidden" name="coupon_percent" id="coupon_percent" value="@if(isset($coupondetails)){{ $coupondetails->percentage }}@endif">
                                  <input type="hidden" name="from_coupon_date" id="from_coupon_date" value="@if(isset($coupondetails)){{ $coupondetails->fromdate }}@endif">
                                  <input type="hidden" name="coupon_date" id="coupon_date" value="@if(isset($coupondetails)){{ $coupondetails->todate }}@endif">
                                  <input type="hidden" name="amountToPay" id="amountToPay" value="{{ $amountToPay }}">
                                 <div class="row">
                                <div class=" mb-4 col-lg-12 form-group">
                                        <label for="cnumber-input">Promo Code</label>
                                         <input type="text" class="form-control" name="service_coupon" id="service-coupon" placeholder="Enter promo code"/> 
                                    </div>
                                  <div class=" mb-4 col-lg-12 form-group">
                                        <label for="cnumber-input">Discounted Price $</label>
                                         <input type="text" class="form-control" name="discounted_price" id="discounted-Price" readonly/> 
                                    </div>
                               
                                    <div class="form-group mb-0 col-lg-12">
                                        <button class="btn btn-info btn-lg" id="coupon_apply" type="button">APPLY</button>
                                    </div>
                                </div>


                             </div><br>
                             @endif
                             @endif

                           <?php 
                             $stripeID = DB::table('stripe_accounts')->where(["user_id" => $event[0]->trainer_id])->first();
                             ?>
                            <input type="hidden" name="eventPrice" id="eventPrice" value="{{ $event[0]->cost }}">
                            <input type="hidden" name="stripe_user_id" id="stripe_user_id" value="{{ $stripeID->stripe_user_id }}">
                            <input type="hidden" name="event_id" id="event_id" value="{{ $event[0]->id }}">
                            <input type="hidden" name="trainer_id" id="trainer_id" value="{{ $event[0]->trainer_id }}">

                            <div class="payment-form">
                                <div class="section-title mb-3">
                                <hr />
                                    <h4 style="text-transform: Capitalize;">Total: $<span id="totalAmount">{{$event[0]->cost}}</span></h4>
                                    <hr />
                                    <h5 class="text-danger mb-3">Payment</h5>
                                    <h2 class="mb-2">CARD DETAILS</h2>
                                    <h6 class="text-danger mb-0" style="text-decoration:lowercase">Powered by Stripe</h6>
                                </div>
                                <div class="sr-input sr-card-element" id="card-element"></div>
                            </div>
                            <div class='form-row row'>
                                    <div class='col-md-12 error form-group hide'>
                                    <div class='alert-danger alert sr-field-error'>Please correct the errors and try again.</div>
                                    </div>
                                </div><div class='form-row row invaild-card' style="display:none;">
                                    <div class='col-md-12 error form-group'>
                                    <div class='alert-danger alert'>Your credit card information appears to be incorrect. Please try again.</div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                
                                <div class="form-group mb-0 col-lg-12">
                               <div class="g-recaptcha" data-sitekey="{{ env('CAPTCHA_SITE_KEY') }}" data-callback="recaptchaCallback"></div>
                               <input type="hidden" class="hiddenRecaptcha" name="hiddenRecaptcha" id="hiddenRecaptcha">
                            </div>
                         
                                    <div class="form-group mb-0 col-lg-12 book-now-btn">
                                    @if($selfRegistered > 0)
                                    <button class="btn btn-danger" type="submit" disabled >REGISTER</button>
                                    @else
                                        @if($event[0]->members_allowed != NULL && $attendeesCount > $event[0]->members_allowed)
                                        <button class="btn btn-danger" type="submit" disabled >REGISTER</button>
                                        @else
                                        <button class="btn btn-danger" type="submit">REGISTER</button>
                                        @endif
                                    @endif
                                    </div>
                                </div>
                                </form>
                             </div>
    
<!-- Registered Athletes Section -->
                     
<!-- end of Registered Athletes Section -->
                  </div>
                  </div>
              </div>
                             
              </div>
              
           </div>                                                                                                                                                                                                                                     



      </div>
   </div>    
</section>
@stop
@section('pagescript')
<!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" /> -->
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
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
</style>
<script type="text/javascript">
$( document ).ready(function() {

$('#number-input, #zipcode-input').keypress(function (e) {    
    
    var charCode = (e.which) ? e.which : event.keyCode    

    if (String.fromCharCode(charCode).match(/[^0-9]/g))    

        return false;                        

}); 
 $("#zero-prize-submit").on("submit", function(){
    $('body').removeClass('loaded');
     $('#loader-wrapper').attr('style','display: block !important');
  });
    //$('#loader').hide();
    $('#start_date').datepicker({
        dateFormat: 'yy-mm-dd',
        minDate: new Date(),
        onSelect: function(dateText, inst) {
        var start_date = $(this).val();
        $("#end_date").datepicker("option", "minDate", new Date(start_date));
        var service_id = $('#service-input option:selected').attr('value');
        //getTimeSlots(service_id, start_date, null);
        }
    }).datepicker("setDate", "0");
    $('#end_date').datepicker({
        dateFormat: 'yy-mm-dd',
        minDate: new Date(),
        onSelect: function(dateText, inst) {
        var end_date = $(this).val();
        var start_date = $('#start_date').val();
        var service_id = $('#service-input option:selected').attr('value');
        getTimeSlots(service_id, start_date, end_date);
        }
    });

    function getTimeSlots(service_id, start_date, end_date){ console.log("ajax function");
        if(service_id && start_date){
            $.ajax({
            url: "{{ route('service.timeslots') }}",
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            data: {
                "service_id": service_id,
                'start_date': start_date,
                'end_date':end_date,
            },
            beforeSend: function(){
                $('.ajax-loader').css("visibility", "visible");
            },
            success: function (result) {
                var result = JSON.parse(result);
                if (result.status == true) {
                   // console.log(result.data);
                    $('#slot-input').html(result.data);
                } else {
                    
                }
            },
            complete: function(){
                $('.ajax-loader').css("visibility", "hidden");
            }
            });
            
        }else{

        }
    }

     $("#service-input").change(function(){
        var amount = $('option:selected', this).attr('serviceAmount');
        //alert(amount); 
        $('#amount-input').val(amount); 
        $('#showAmount').show();
        //$('#is_recurring').show();

        var id = $('option:selected', this).attr('data-id');
        var service_id = $('option:selected', this).attr('value');
        var start_date = $('#start_date').val(); 
        //getTimeSlots(service_id, start_date, null);

      
        //$('#loader').show();
        $.ajax({
            url: id,
            type: 'GET',
            dataType: "json",
            beforeSend: function(){
                $('.ajax-loader').css("visibility", "visible");
            },
            success: function (result) {
                if (result.status == true) {
                    //console.log(result.data)
                    var html = result.data;
                    var recurringData = result.recurringData;
                    //$('#isRecurring').val(result.isRecurring);
                    //console.log(result.recurringData);
                    $('#recurring_service_options').html(recurringData);
                    if(recurringData !== null){
                            
                            $('#is_recurring').show();
                    }
                    
                    $('#show_data').html(html);
                    //$('#show_data').html($(result.data).find('#content').html());
                } else {
                    
                }
            },
            complete: function(){
                $('.ajax-loader').css("visibility", "hidden");
            }
        });
     });

     $("input[name='recurring']").change(function() {
        if(this.checked) {
            $('#recurring_options').show();
        }
     });

    //  $(document).on('change', '#recurring_options', function(){  
    //     var plan = $('option:selected', this).attr('data-id');
    //     //alert(plan); 
    //     $('#plan_type').val(plan); 
    //  });


});
@if(isset($StripeAccountsData->stripe_user_id))
    var stripe = Stripe('{{ env('STRIPE_PUB_KEY') }}', {
  stripeAccount: '{{$StripeAccountsData->stripe_user_id}}'
}); 
@else
    var stripe = Stripe('{{ env('STRIPE_PUB_KEY') }}'); 
@endif

var stripe = Stripe('pk_test_fqBedhauV53FOkek7Wu43eMS00qeYeph7j', {
  stripeAccount: 'acct_1GyLbJJRYVtlIUWP'
});
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

 card.on('change', function(event) {
  if (event.complete) {
    // $('#loader-wrapper').attr('style','display: block !important');
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
    var data = $('#payment-form').serialize();
 $.ajax({
            url: '{{ route('createeventpaymentsave') }}',
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
                window.location = '{{url('/profile/'.$athleteDetails->first_name.'-'.$athleteDetails->last_name.'-'.$athleteDetails->id.'#rsvp')}}';
            }, error: function () {
                $('body').addClass('loaded');
            }
        });
  });
};
$('body').removeClass('loaded');
    if (!$form.data('cc-on-file')) {
      e.preventDefault();
      var recurring_options = $('#recurring_options').val();
      //var recurring_options = $('#recurring_options option:selected').attr('value');
      //alert(recurring_options);
      if(recurring_options != '' && recurring_options !== undefined){
       
            stripe.createToken(card).then(function(result) {
            if (result.error) {
              showError(result.error.message); 
            } else { 
              stripeResponseHandler(result.token.id);
            }
          });
      }else{
          
       
     $.ajax({
            url: '{{ route('createeventpaymentintent') }}',
            'type': 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            data: {
                "stripe_user_id": $('#stripe_user_id').val(),
                "service_amount": $('#eventPrice').val(),
                "discounted_Price": $('#discounted-Price').val()
            },
            
            success: function (result) {
                if (result.status == true) {
                     clientcreactkey = result.client_secret; 
                  stripe
                    .confirmCardPayment(clientcreactkey, {
                      payment_method: {
                        card: card
                      }
                    })
                    .then(function(result) {
                      if (result.error) {
                        // Show error to your customer
                        $('body').addClass('loaded');
                       showError(result.error.message);
                      } else {
                        if(grecaptcha.getResponse() != ''){
                         $("div").remove(".captcha_error");
                         orderComplete(clientcreactkey);
                         $('#loader-wrapper').attr('style','display: block !important');
                        }
                        else {
                         $("div").remove(".captcha_error");
                         $('.g-recaptcha').append('<div class="alert alert-danger mt-2 captcha_error">Please solve captcha.</div>');
                        }
                        //  orderComplete(clientcreactkey);
                      }
                    });
                }else{
                    showError(result.Message);
                    $('body').addClass('loaded');
                }
               
                 
            }, error: function () {
                $('body').addClass('loaded');
            }
        });
  
          
         
      }
      
      
      
      

    }
  
  });
  
  function stripeResponseHandler(token) {
        //console.log(token);
            $form.find('input[type=text]').empty();
            $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
            $form.get(0).submit();
       
    }



    $( "#coupon_apply" ).click(function() {
        var servicecoupon = $('#service-coupon').val();
        var coupon_code = $('#coupon_code').val();
        var amountToPay = $('#amountToPay').val()
        
        var TodayDate = new Date();
        var endDate= new Date(Date.parse($("#coupon_date").val()));
        var fromDate= new Date(Date.parse($("#from_coupon_date").val()));
        var current_date = (new Date()).toISOString().split('T')[0];
        
        if (servicecoupon == '') {
            alert('Please enter promo code');
            $('#service-coupon').css('border-color', 'red');
            $('#service-coupon').focus();
        } else if(coupon_code != servicecoupon){
            
            alert('promo code does not match');
            $('#service-coupon').css('border-color', 'red');
            $('#service-coupon').focus();
        } else if(fromDate> TodayDate){
            alert('promo code has expired');
            $('#service-coupon').css('border-color', 'red');
            $('#service-coupon').val('');
            $('#discounted-Price').val('');
            $('#totalAmount').html('{{$event[0]->cost}}');
            $('#service-coupon').focus();
        } else if (endDate> TodayDate) {
            alert('promo code applied successfully');
            $('#discounted-Price').val(amountToPay);
            $('#totalAmount').html(amountToPay);
            
            $('#service-coupon').css('border-color', '');
        } else if(current_date == $("#coupon_date").val()){
            alert('promo code applied successfully');
            $('#discounted-Price').val(amountToPay);
            $('#totalAmount').html(amountToPay);
            $('#service-coupon').css('border-color', '');
        } else {
            alert('promo code has expired');
            $('#service-coupon').css('border-color', 'red');
            $('#service-coupon').val('');
            $('#discounted-Price').val('');
            $('#totalAmount').html('{{$event[0]->cost}}');
            $('#service-coupon').focus();
        }
    });
  
});
    
function recaptchaCallback() {
  		$('#hiddenRecaptcha').valid();
	};
    
</script>



@endsection


