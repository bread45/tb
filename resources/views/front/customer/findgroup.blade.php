@extends('front.layout.app')
@section('title', 'Profile')
@section('content')
<section class="inner-banner-section bg-primary">
    <div class="container">
        <div class="banner-content">
            <h1>Find Groups</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{route('customer.newprofile',base64_encode(Auth::guard('front_auth')->user()->id))}}">My Profile</a></li>
                    <li class="breadcrumb-item"><a >Find Groups</a></li>
                </ol>
            </nav>
        </div>
    </div>
</section>
<section class="find-friend-section">
    <div class="container">
        <form class="searchform" method="post" autocomplete="off">
            <div class="row service-filters"> 
                <div class="col-lg-10 col-md-12 col-sm-12 col-xs-12"> 
                    <div class="row justify-content-center">
                        @csrf
                        <div class="col-lg-8 col-sm-6">
                            <div class="form-group search-group">
                                <input type="text" name="keyword" id="keyword" class="form-control search-control" placeholder="Search by Keyword" value="" />
                            </div>
                            <div style="display: none;" id="filter-error" class="error invalid-feedback">Please select any filter</div>

                        </div> 
                        <div class="col-lg-auto col-sm-6">
                            <button type="submit" class="btn btn-danger btn-block" title="Search"><img src="{{asset('images/search-icon-large.png')}}" alt="Search"></button>
                        </div>
                        <div class="col-lg-auto col-sm-6">
                            <button type="reset" class="btn btn-danger btn-block clearbnt" title="Clear"><img src="{{asset('images/clear.png')}}" alt="Search"></button>
                        </div>
                    </div>
                </div>
            </div>
        </form> 
    </div>
</section>
<div class="container">
    <div class="row">
        <div class="col-md-10">
            <div class="people-nearby searchdata">
                
            </div>
        </div>
    </div>
</div>
@stop
@section('pagescript')
<script>
    getfrienddata();
     $('.searchform').submit(function (e) {
            e.preventDefault();
            getfrienddata();
     }); 
      $('.clearbnt').click(function (e) {
           var keyword = $('#keyword').val('');
           getfrienddata();
      });
      $(document).on('click', '.join-group', function(e){
          var dataid = $(this).data('id');
          $.ajax({
            type: 'POST',
            url: '{{route('customer.JoinGroup')}}',
            data:{"id": dataid,'type':'add'},        
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function (result) {
                new PNotify({
                    title: 'Group Joined.',
                            text: '',
                            type: 'success'
                });
               getfrienddata();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                //alert(xhr.status);
                //alert(thrownError);
            }
        });
      });  
      $(document).on('click', '.pagination a', function(e){  
        e.preventDefault();
        var url = $(this).attr('href');
        $.ajax({
            type: 'POST',
            url: url,
            data: $('.searchform').serialize(),
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function (result) {
                $('.searchdata').html(result);
                //$('body').addClass('loaded');
                $('html, body').animate({
                    scrollTop: $(".searchform").offset().top - 200
                }, 2000); 
            },
            error: function (xhr, ajaxOptions, thrownError) {
                //alert(xhr.status);
                //alert(thrownError);
            }
        });
        });
    function getfrienddata() {
        $.ajax({
            type: 'POST',
            url: '{{route('customer.findgroupsdata')}}?page={{$page}}',
            data: $('.searchform').serialize(),
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function (result) {
                $('.searchdata').html(result);
                //$('body').addClass('loaded');
            },
            error: function (xhr, ajaxOptions, thrownError) {
                //alert(xhr.status);
                //alert(thrownError);
            }
        });
    }
</script>
@endsection