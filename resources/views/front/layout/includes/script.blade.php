<script src="{{ asset('../front/js/jquery-3.4.1.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('../front/js/popper.min.js') }}"></script>
<script src="{{ asset('../front/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('../front/js/slick.min.js') }}"></script>
<script src="{{ asset('../front/js/lazysizes.min.js') }}"></script>
<!-- <script src="{{ asset('../front/js/jquery-3.2.1.slim.min.js') }}"></script> -->
<!--<script src="{{ asset('../front/js/jquery.dataTables.min.js') }}"></script>-->
<script src="{{ asset('../front/js/pnotify.custom.min.js') }}"></script>
<script src="{{ asset('js/share.js') }}"></script>

<script src="{{ asset('../front/js/developer.js') }}"></script>
<script src="{{ asset('../front/js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('../front/js/java/menu-style.js') }}"></script>
 @if(session()->has('jsAlert'))
    <script>
        alert('Congratulations! You’ve successfully subscribed to the Training Block newsletter.');
    </script>
@endif 
<script type="text/javascript">

$('a').each(function() {
   var a = new RegExp('/' + window.location.host + '/');
   if (!a.test(this.href)) {
      $(this).attr("rel","nofollow");
   }
});

$( "#subscribe_submit" ).click(function() {
      subscribe_email = $("#subscribe_email").val();
      mailformat = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
      
      if(subscribe_email ==''){
       alert("Please enter email address");
        $("#subscribe_email").focus();
        return false;
      }
      if(subscribe_email.match(mailformat)){
            $.ajax({
              url: "{{url('subcribes')}}/"+subscribe_email,
              type: 'GET',
              success: function(res) {
             
                alert("Congratulations! You’ve successfully subscribed to the Training Block newsletter.");
                $("#subscribe_email").val('');
              }
          });
      } else {
      alert("You have entered an invalid email address!");
        $("#subscribe_email").focus();
        return false;
      }
});


$('#subscribe_email').keypress(function (e) {
  if (e.which == 13) {
        subscribe_email = $("#subscribe_email").val();
      mailformat = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
      
      if(subscribe_email ==''){
       alert("Please enter email address");
        $("#subscribe_email").focus();
        return false;
      }
      if(subscribe_email.match(mailformat)){
            return true;
      } else {
      alert("You have entered an invalid email address!");
        $("#subscribe_email").focus();
        return false;
      }
    }
});

// Join Newsletter popup ajax

$( "#newsletterSubmit" ).click(function() {
    subscribe_email = $("#newsletterEmail").val();
      mailformat = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
      
      if(subscribe_email ==''){
       alert("Please enter email address");
        $("#newsletterEmail").focus();
        return false;
      }
      if(subscribe_email.match(mailformat)){
            $.ajax({
              url: "{{url('subcribes')}}/"+subscribe_email,
              type: 'GET',
              success: function(res) {
             
                alert("Congratulations! You’ve successfully subscribed to the Training Block newsletter.");
                $("#newsletterEmail").val('');
                $('#newsletterModal').modal('toggle');
                var expDate = new Date();
                expDate.setTime(expDate.getTime() + (1440 * 60 * 1000));
                $.cookie("newsletter", "1", { path: '/', expires: expDate } );
              }
          });
      } else {
      alert("You have entered an invalid email address!");
        $("#newsletterEmail").focus();
        return false;
      }
});

$('#newsletterEmail').keypress(function (e) {
  if (e.which == 13) {
        newsletterEmail = $("#newsletterEmail").val();
      mailformat = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
      
      if(newsletterEmail ==''){
       alert("Please enter email address");
        $("#newsletterEmail").focus();
        return false;
      }
      if(newsletterEmail.match(mailformat)){
            return true;
      } else {
      alert("You have entered an invalid email address!");
        $("#newsletterEmail").focus();
        return false;
      }
    }
});



//  $("#arrow").addClass("arrow-right");
//       $(function () {

//         if ($(window).width() <= 480){
//         $('#slideout').animate({right:'-280px'}, {queue: false, duration: 500}).removeClass("popped");
//         document.getElementById("clickme").style.background = "#00ab91";
//         $("#arrow").removeClass("arrow-right");
//         $("#arrow").addClass("arrow-left");
//         }

//     $("#clickme").click(function () {
//         if($(this).parent().hasClass("popped")){
//             if ($(window).width() <= 480){
//             $(this).parent().animate({right:'-280px'}, {queue: false, duration: 500}).removeClass("popped");
//             document.getElementById("clickme").style.background = "#00ab91";
//             $("#arrow").removeClass("arrow-right");
//             $("#arrow").addClass("arrow-left"); 
//             }
//             else {

//         $(this).parent().animate({right:'-320px'}, {queue: false, duration: 500}).removeClass("popped");
//         document.getElementById("clickme").style.background = "#00ab91";
//         $("#arrow").removeClass("arrow-right");
//         $("#arrow").addClass("arrow-left");
//     }
        
//     }else {
//         $(this).parent().animate({right: "0px" }, {queue: false, duration: 500}).addClass("popped");
//           document.getElementById("clickme").style.background = "#1f2732";
//            $("#arrow").removeClass("arrow-left");
//            $("#arrow").addClass("arrow-right");
//           }
//     });
// });



$(function() {
          $('.owl-carousel.testimonial-carousel').owlCarousel({
            nav: true,
            navText: ['<i class="fa fa-chevron-left"></i>', '<i class="fa fa-chevron-right"></i>'],
            dots: false,
            responsive: {
              0: {
                items: 1,
              },
              750: {
                items: 2,
              }
            }
          });

          
});
    

$('.featured-business-slider').slick({
infinite: true,
slidesToShow: 3,
slidesToScroll: 1,
responsive: [
    {
        breakpoint: 992,
        settings: {
            slidesToShow: 2,
        }
    },
    {
        breakpoint: 768,
        settings: {
            slidesToShow: 1,
        }
    },
]
});

var $slider = $('.expert-guidance-slider');
// var $slider2 = $('.reviews-retting-slider');

if ($slider.length) {
var currentSlide;
var slidesCount;
var sliderCounter = document.createElement('div');
sliderCounter.classList.add('slider-counter');

var updateSliderCounter = function (slick, currentIndex) {
    currentSlide = slick.slickCurrentSlide() + 1;
    slidesCount = slick.slideCount;
    $(sliderCounter).text('0' + currentSlide + ' / 0' + slidesCount)
};

$slider.on('init', function (event, slick) {
    $slider.append(sliderCounter);
    updateSliderCounter(slick);
});

$slider.on('afterChange', function (event, slick, currentSlide) {
    updateSliderCounter(slick, currentSlide);
});

$slider.slick({
    infinite: true,
    slidesToShow: 5,
    slidesToScroll: 1,
    centerMode: true,
    responsive: [
        {
            breakpoint: 1899,
            settings: {
                slidesToShow: 4,
            }
        },
        {
            breakpoint: 1599,
            settings: {
                slidesToShow: 3,
            }
        },
        {
            breakpoint: 1199,
            settings: {
                slidesToShow: 2,
            }
        },
        {
            breakpoint: 767,
            settings: {
                slidesToShow: 1,
            }
        }
    ]
});
}
// if ($slider2.length) {
// var currentSlide;
// var slidesCount;
// var sliderCounter = document.createElement('div');
// sliderCounter.classList.add('slider-counter');


// $slider2.slick({
//     infinite: true,
//     slidesToShow: 2,
//     slidesToScroll: 1,
//     centerMode: true,
//     dots: true, 
//      arrows: true,
//     prevArrow:"<button type='button' class='slick-prev pull-left'><i class='fa fa-angle-left' aria-hidden='true'></i></button>",
//             nextArrow:"<button type='button' class='slick-next pull-right'><i class='fa fa-angle-right' aria-hidden='true'></i></button>"
     
// });
// }




$(function () {
$('.pl-input').on("focus", function () {
    $(this).siblings('.myinput-placeholder').eq(0).hide();
});
$('.pl-input').on("blur", function () {
    if ($(this).val() == "") {
        $(this).siblings('.myinput-placeholder').eq(0).show();
    }
});
var down = false;
 
 
$('.bell-icn').click(function(e){
e.stopPropagation();
var color = $(this).text();
if(down){

$('#box').css('height','0px');
$('#box').css('opacity','0');
$('#box').hide();
down = false;
}else{

$('#box').css('height','auto');
$('#box').css('opacity','1');
$('#box').show();
down = true;

}
});
 $(document).click(function(){  
  $('#box').hide(); //hide the button

  });
window.addEventListener( "pageshow", function ( event ) {
  var historyTraversal = event.persisted || 
                         ( typeof window.performance != "undefined" && 
                              window.performance.navigation.type === 2 );
  if ( historyTraversal ) {
    var body = $("html, body");

    // body.stop().animate({scrollTop:0}, 500, 'swing', function() { 
    //    alert('sss');
    // });
  }
});
 

});
</script>
