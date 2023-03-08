<footer class="bg-primary">
        <div class="footer-container">
        <div class="row">
        <div class="col-md-12 mb-md-0 mb-4">
          <div id='subscribe-css'>
            <p class='subscribe-note'>SUBSCRIBE TO OUR NEWSLETTER</p>
            <div class='subscribe-wrapper'>
              <div class='subscribe-form'>
                <form action='#' class='subscribe-form' method='GET'>
                  <input autocomplete='off' required pattern="[^@\s]+@[^@\s]+\.[^@\s]+" class='subscribe-css-email-field' name='subscribe_email' id='subscribe_email' placeholder='Enter your Email' />
                  <input class='subscribe-css-email-button' id='subscribe_submit' title='' type='button' value='Subscribe' />
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
            <div class="row">
                <div class="col-md-5 mb-md-0 mb-4 text-md-left text-center">
                <a href="{{ url('/')}}" class="mb-3 d-inline-block"><img src="{{ asset('images/logo.png') }}" alt="Training Block"></a>
                    <p class="text-white">helping athletes achieve their goals by connecting the athletic community</p>
                </div>
                <div class="col-md-3 mb-md-0 mb-4 text-md-left text-center">
                    <h3>Pages</h3>
                    <ul class="nav footer-nav">
                        <li class="nav-item">
                            <a class="nav-link {{ (request()->segment(1) == 'aboutus') ? 'active' : '' }}" href="{{route('aboutus')}}">About</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ (request()->segment(1) == 'exploreservices') ? 'active' : '' }}" href="{{route('exploreservices')}}">Explore</a>
                        </li>
                        <li class="nav-item {{ (request()->segment(1) == 'blogs') ? 'active' : '' }}">
                    <a class="nav-link" href="{{route('blogs')}}">Blog</a>
                </li>
                        <li class="nav-item">
                            <a class="nav-link {{ (request()->segment(1) == 'contactus') ? 'active' : '' }}" href="{{route('contactuspage')}}">Contact us</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ (request()->segment(1) == 'login' || request()->segment(1) == 'register') ? 'active' : '' }}" href="{{route('front.login')}}">Login</a>
                        </li>
			<li class="nav-item">
                            <a class="nav-link {{ (request()->segment(1) == 'terms-conditions') ? 'active' : '' }}" href="{{route('terms.conditions')}}">Terms & Conditions</a>
                        </li>
                    </ul>
                </div>
                <div class="col-md-4 text-md-left text-center">
                    <h3>Get in Touch</h3>
                    <ul class="nav social-nav mb-3 justify-content-md-start justify-content-center">
                        <li class="nav-item">
                            <a href="{{getSetting('facebook-link')}}" class="nav-link facebook" title="Facebook">facebook</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{getSetting('linkdin-link')}}" class="nav-link linkedin" title="Linkedin">linked in</a>
                        </li>
<!--                        <li class="nav-item">
                            <a href="{{getSetting('twitter-link')}}" class="nav-link twitter" title="Twitter">twitter</a>
                        </li>-->
                        <li class="nav-item">
                            <a href="{{getSetting('insta-link')}}" class="nav-link insta" title="Instagram">insta</a>
                        </li>
                    </ul>

                    <div class="contact-info">
                        <p class="text-white">Ask a question, give some feedback, or just say hello:</p>
                        
                        <!-- <p><a class="mail-link" href="mailto:{{getSetting('email')}}">{{getSetting('email')}}</a></p> -->
                        <p><a class="mail-link" href="mailto:support@trainingblockusa.com">support@trainingblockusa.com</a></p>
                        <a class="call-link text-white" href="callto:{{getSetting('phone-number')}}">{{getSetting('phone-number')}}</a>
                        <p>
                            <!-- @if(Auth::guard('front_auth')->user()) 
                                @if(Auth::guard('front_auth')->user()->user_role == "customer")
                                <a class="refer-link" data-toggle="modal" data-target="#referFriendModal" href="">Refer a friend</a>
                                @endif    
                            @else
                                <a class="refer-link" href="{{route('front.login')}}">Refer a friend</a>
                            @endif -->
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-copyright">
            <div class="container">
                <p>Copyright © {{date('Y')}} Training Block. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Modal -->
<div class="modal fade" id="referFriendModal" tabindex="-1" role="dialog"  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Refer a Friend</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Refer a friend and get $5 discount on order!</p>
        <div class="input-group">
            @if(Auth::guard('front_auth')->user())
            <input type="text" id="referLinkInput" class="form-control"  readonly="readonly" value="{{ url('/register') . '/?ref=' . Auth::guard('front_auth')->user()->affiliate_id }}">
            @endif
            <div class="tooltip">
                <button class="btn-dark btn-sm" onclick="referFriend()" onmouseout="outFunc()">
                <span class="tooltiptext" id="referTooltip">Copy & Share</span>
                Copy link
                </button>
            </div>
        </div>
        
        <script>
            function referFriend() {
            var copyText = document.getElementById("referLinkInput");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            document.execCommand("copy");
            
            var tooltip = document.getElementById("referTooltip");
            tooltip.innerHTML = "Copied!";
            }

            function outFunc() {
            var tooltip = document.getElementById("referTooltip");
            tooltip.innerHTML = "Copy & Share";
            }
            function linkcount(link, id) {
        if (link != '') {
            $.ajax({
                url: '{{ url("advertisement/changecounter/") }}' + '/' + id,
                type: 'get',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function (data) {
                    var win = window.open(link, '_blank');
//                        win.focus();
                },
                error: function () {
                    new PNotify({
                        title: 'Oh No!',
                        text: 'Something went wrong!',
                        type: 'error'
                    });
                }
            });
        } 
    }

    
        </script>
      </div>
      <div class="modal-footer">
      @if(Auth::guard('front_auth')->user())
        <?php 
        echo Share::page(url('/register') . '/?ref=' . Auth::guard('front_auth')->user()->affiliate_id)
         ->facebook()
         ->twitter()
         ->linkedin()
        ?>
      @endif
      </div>
    </div>
  </div>
</div>

<style>
#referFriendModal #social-links ul li{
    display:inline-block;
    margin: 0px 10px;
}
#referFriendModal #social-links ul li a .fa::before{
    font-size:25px;
}
.fa-facebook-official{
    color: #3b5998;
}
/*.fa-linkedin{
 color:#0976b4;
} 
.fa-twitter{
 color:#55acee;
}*/
.fa-whatsapp{

}
#referFriendModal .form-control{
    height: auto;
    font-size: 12px;
}
.tooltip {
  position: relative;
  display: inline-block;
  opacity: 1;
}

.tooltip .tooltiptext {
  visibility: hidden;
  width: 140px;
  background-color: #555;
  color: #fff;
  text-align: center;
  border-radius: 6px;
  padding: 5px;
  position: absolute;
  z-index: 1;
  bottom: 150%;
  left: 50%;
  margin-left: -75px;
  opacity: 0;
  transition: opacity 0.3s;
}

.tooltip .tooltiptext::after {
  content: "";
  position: absolute;
  top: 100%;
  left: 50%;
  margin-left: -5px;
  border-width: 5px;
  border-style: solid;
  border-color: #555 transparent transparent transparent;
}

.tooltip:hover .tooltiptext {
  visibility: visible;
  opacity: 1;
}
</style>