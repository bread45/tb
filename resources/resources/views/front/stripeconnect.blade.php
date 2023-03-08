@extends('front.layout.app')
@section('title', 'Explore Local Providers')
@section('content')

<section class="inner-banner-section bg-primary">
    <div class="container">
        <div class="banner-content">
            <h1>Stripe Connect</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Stripe Connect</li>
                </ol>
            </nav>
        </div>
    </div>
</section>
<section class="training-services-section">
    <div class="container">
        
        @include('admin.theme.includes.message')
        <div class="sr-root">
      <div class="sr-main"> 
          <a id="submit" href="javascript:void(0)"><img src="{{asset('images/blue-on-dark.png')}}" alt="blue on dark"></a>
      </div> 
            <!-- Mount the instance within a <label> -->
 
  <form id="payment-form" class="sr-payment-form">
            <div class="sr-form-row">
              <label for="card-element">Enter your card details</label>
              <div class="sr-input sr-card-element" id="card-element"></div>
            </div>
            <div class="sr-form-row">
              <label for="enabled-accounts-select">Pay to</label>
              <select id="enabled-accounts-select" class="sr-select"></select>
            </div>
            <div class="sr-form-row">
              <div class="sr-field-error" id="card-errors" role="alert"></div>
              <button id="submit">
                <div class="spinner hidden" id="spinner"></div>
                <span id="button-text">Pay</span><span id="order-amount"></span>
              </button>
            </div>
          </form>
  
    </div>
    </section>

@stop
@section('pagescript')
<script src="https://js.stripe.com/v3/"></script>
<script>
    var stripe = Stripe('pk_test_XVPQLdn9pB1HTXThmDqHds4g', {
  stripeAccount: 'acct_1DW7ohKLxHOSb45P'
}); 
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
var cardElement = elements.create('card',{ style: style });
 cardElement = elements.getElement('card');
 cardElement.mount('#card-element');
var paymentForm = document.getElementById("payment-form");
paymentForm.addEventListener("submit", function(event) {
    event.preventDefault();
    // Initiate payment when the submit button is clicked
    var paymentIntentClientSecret = 'sk_test_p94u4c8YUZ8Mydw7r3GT1Cjg'; 
    pay(stripe, cardElement, paymentIntentClientSecret);
  });

var pay = function(stripe, card, clientSecret) {
   $.ajax({
            url: '{{ route('createpaymentintent') }}',
            type: 'GET',
            success: function (result) {
                  stripe
                    .confirmCardPayment(result, {
                      payment_method: {
                        card: card
                      }
                    })
                    .then(function(result) {
                      if (result.error) {
                        // Show error to your customer
                        console.log(result.error.message);
                      } else {
                        // The payment has been processed!
                        console.log(clientSecret);
                      }
                    });
                 
            }, error: function () {
                toastr.error("Permission Denied!");
            }
        });
  // Initiate the payment.
  // If authentication is required, confirmCardPayment will automatically display a modal
  
};
var orderComplete = function(clientSecret) {
  // Just for the purpose of the sample, show the PaymentIntent response object
  stripe.retrievePaymentIntent(clientSecret).then(function(result) {
    var paymentIntent = result.paymentIntent;
    var paymentIntentJson = JSON.stringify(paymentIntent, null, 2);

    document.querySelector(".sr-payment-form").classList.add("hidden");
    document.querySelector("pre").textContent = paymentIntentJson;

    document.querySelector(".sr-result").classList.remove("hidden");
    setTimeout(function() {
      document.querySelector(".sr-result").classList.add("expand");
    }, 200);
 
  });
};
let elmButton = document.querySelector("#submit");

if (elmButton) {
  elmButton.addEventListener(
    "click",
    e => {
      elmButton.setAttribute("disabled", "disabled");
$.ajax({
            url: '{{ route('stripecreate.geturl') }}',
            type: 'GET',
            success: function (result) {
                  
                 window.location = result;
            }, error: function () {
                toastr.error("Permission Denied!");
            }
        });
//      fetch("{{ route('stripecreate.geturl') }}", {
//        method: "GET",
//        headers: {
//          "Content-Type": "application/json"
//        }
//      })
//         .then(data => {
//          if (data) {
//              alert(data);
//            window.location = data;
//          } else {
//            elmButton.removeAttribute("disabled");
//            elmButton.textContent = "<Something went wrong>";
//            console.log("data", data);
//          }
//        });
    },
    false
  );
}

</script>
@endsection