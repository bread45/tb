    @extends('front.layout.app')
@section('title', 'Explore Local Providers')
@section('content')
<section class="inner-banner-section bg-primary">
    <div class="container">
        <div class="banner-content">
            <h1>Explore Local Providers</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Explore Local Providers</li>
                </ol>
            </nav>
        </div>
    </div>
</section> 
<section class="training-services-section">
    <div class="container-fluid">
        <form class="searchform" method="post" autocomplete="off">
            <div class="row service-filters"> 
               <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12"> 
                 <div class="row justify-content-center">
                @csrf
                <div class="col-lg-6 col-sm-6">
                    <div class="form-group search-group">
                        <input type="text" name="keyword" id="keyword" class="form-control search-control" placeholder="Search by Keyword" value="@if($request->keyword){{$request->keyword}}@endif" />
                    </div>
                    <div style="display: none;" id="filter-error" class="error invalid-feedback">Please select any filter</div>

                </div>
                <div class="col-lg-6 col-sm-6">
                    <div class="form-group location-group">
                        <input type="text" name='location' id="location" autocomplete="off" class="form-control location-control" placeholder="Search by Location" value="@if($request->location){{$request->location}} @endif" />
                        <div id="locationlist"></div>  
                    </div>
                </div>
                <div class="col-lg-6 col-sm-6">
                    <div class="form-group service-group">
                        <select name="services" id="services" class="form-control service-control">
                            <option value="">Category</option>
                            @foreach($services as $Trainer)
                            <option @if($Trainer->id == $request->services)selected @endif value="{{$Trainer->id}}">{{$Trainer->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="col-lg-6 col-sm-6">
                    <div class="form-group ">
                        
                        
                        <div class="select_button"> <input type="radio" name="keyword_search" value="1"><span class="select_text">At least one of the word</span>
                        </div>
                        <div class="select_button"> <input type="radio" name="keyword_search" value="2"><span class="select_text">All of the word</span>
                        </div>

                    </div>
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
        <div class="searchdata">
            <div class="row">
                @if(isset($TrainerServicesdata) && !empty($TrainerServicesdata))
                @foreach($TrainerServicesdata as $trainerservices)
             
                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                        <a href="{{url('trainer/'.$trainerservices->trainer->spot_description)}}" class="card-outer-link">
                        <div class="card">
                            <div class="card-image">
                                @if(!empty($trainerservices->image))
                                <img src="{{asset('front/images/services/'.$trainerservices->image)}}" alt="{{$trainerservices->name}}" />
                                @else
                                <img src="{{asset('images/Expert_01.jpg')}}" alt="{{$trainerservices->name}}" />
                                @endif
                                
                            </div>
                            <div class="card-body pb-4">
                                <h4 class="text-uppercase mb-3">
                                @if(!empty($trainerservices->trainer->business_name))
                                {{$trainerservices->trainer->business_name}}
                                @else
                                {{$trainerservices->trainer->first_name}}
                                {{$trainerservices->trainer->last_name}}
                                @endif
                                
                                </h4>
                                <h5 class="location mb-1">{{$trainerservices->trainer->address_1}}, {{$trainerservices->trainer->city}}</h5>
                                <div class="trainerservices">
                                    <h5 class="trainerservices-name">
                                        @if (file_exists(public_path('front/images/').$trainerservices->service->icon))
                                        <img width="30px" class="mr-3 pull-left" src="{{asset('front/images/'.$trainerservices->service->icon)}}" alt="{{$trainerservices->name}}" />
                                        @else
                                        <img width="30px" class="mr-3 pull-left" width="30px" src="{{ asset('front/trainer/images/default-service.png') }}" alt="default service">
                                        @endif
                                        {{$trainerservices->name}} 


                                    </h5>

                                    <h5 class="status mb-1 trainerservices-status">{{$trainerservices->format}}</h5>

                                </div>

                                <p class="mb-3 trainerBio">@if(!empty($trainerservices->trainer->bio)){!! \Illuminate\Support\Str::limit($trainerservices->trainer->bio, 100)  !!} @endif</p>
                                @if(!empty($trainerservices->trainer->bio))<a href="javascript:void(0)" data-toggle="modal" data-target="#trainerBio{{$trainerservices->trainer->id}}"><i>view more</i></a>@endif
                                <!-- model start -->
                    <div class="modal fade" id="trainerBio{{$trainerservices->trainer->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitleSC" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                                        <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLongTitleSC">{{$trainerservices->name}}</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                        {!! $trainerservices->trainer->bio  !!}
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        </div>
                                        </div>
                            </div>
                    </div>
                    <!-- model end -->

                                <div class="rating">
                                    <ul class="nav">
                                        @for($i=1;$i<=$trainerservices->ratting;$i++) 
                                        <li><img src="{{asset('images/star.png')}}" alt="Rating" /></li>
                                        @endfor
                                        @for($i=5;$i>=$trainerservices->ratting+1;$i--) 
                                        <li><img src="{{asset('images/star-blank.png')}}" alt="Rating" /></li>
                                        @endfor
                                    </ul>
                                </div>
                            </div>
                        </div>
                        </a>
                    </div>
                
                @endforeach
                @endif 
            </div>
            {!! $TrainerServicesdata->links('pagination') !!} 
        </div>

    </div>
</section>
@stop
@section('pagescript')
<style>
.trainerBio{
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
}
.card .card-body {
   /* min-height: 420px;*/
    /*max-height: 420px;*/
}
div#locationlist {
        position: absolute;
    top: 69px;
    width: 100%;
    z-index:99;
}
.list-group-item{
    cursor: pointer;
    color: #000;
    background-color: #ccc;
}
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.js"></script>
<script>
function changeNumberFormat(x){
        $value  = x.replace(/[^0-9\s]/gi, '');
        if($value.length > 10){
            x = $value.substr(0,9);
        }
        if($value.length > 3 && $value.length < 6){
            x = "("+$value.substr(0,3)+") "+$value.substr(3,3);
        }
        if($value.length > 5){
            x = "";
            x = "("+$value.substr(0,3)+") "+$value.substr(3,3)+"-"+$value.substr(6,4);
        }
        return x;
    }
    if(!!window.performance && window.performance.navigation.type === 2)
{
    console.log('Reloading');
    window.location.reload();
}
    $(document).ready(function () {
         
         $('#location').keyup(function(){  
           var query = $(this).val();  
           if(query != '')  
           {  
                $.ajax({  
                     url:"{{route('get.locationdata')}}",  
                     method:"POST",  
                     data:{search:query,"_token": "{{ csrf_token() }}"},  
                     success:function(data)  
                     {  
                          $('#locationlist').fadeIn();  
                          $('#locationlist').html(data);  
                     }  
                });  
           }  
      });  
      $(document).on('click', 'body', function(){  
           $('#locationlist').fadeOut();  
      });
      $(document).on('click', 'li.list-group-item', function(){  
           $('#location').val($(this).text());  
           $('#locationlist').fadeOut();  
      });
      $(document).on('click', '.pagination a', function(e){  
    e.preventDefault();
    $('body').removeClass('loaded');
    var url = $(this).attr('href');
    $.ajax({
                type: 'POST',
                url: url,
                data: $('.searchform').serialize(),
                success: function (result) {
                    $('.searchdata').html(result);
                    $('body').addClass('loaded');
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    //alert(xhr.status);
                    //alert(thrownError);
                }
            });
});
        $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            $.ajax({
                type: 'POST',
                url: '{{route('exploreservices')}}?page={{session()->get("page")}}',
                data: $('.searchform').serialize(),
                success: function (result) {
                    $('.searchdata').html(result);
                    $('body').addClass('loaded');
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    //alert(xhr.status);
                    //alert(thrownError);
                }
            });
        $('.clearbnt').click(function (e) {
            e.preventDefault(); //**** to prevent normal form submission and page reload
              var keyword = $('#keyword').val('');
            var location = $('#location').val('');
            var services = $('#services').val('');
            var format = $('#format').val('');
            $('body').removeClass('loaded');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            $.ajax({
                type: 'POST',
                url: '{{route('exploreservices')}}?search=true',
                data: $('.searchform').serialize(),
                success: function (result) {
                    $('.searchdata').html(result);
                    $('body').addClass('loaded');
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    //alert(xhr.status);
                    //alert(thrownError);
                }
            });
        });
        $('.searchform').submit(function (e) {
            e.preventDefault(); //**** to prevent normal form submission and page reload
            
            var keyword = $('#keyword').val();
            var location = $('#location').val();
            var services = $('#services').val();
            var format = $('#format').val();
            if(keyword == '' && location == '' && services == '' && format == ''){
                new PNotify({
                    title: 'Please select any one filter!.',
                            text: '',
                            type: 'Notice'
                    });
                return false;
            }
            $('#filter-error').hide();
            $('body').removeClass('loaded');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            $.ajax({
                type: 'POST',
                url: '{{route('exploreservices')}}?search=true',
                data: $('.searchform').serialize(),
                success: function (result) {
                    $('.searchdata').html(result);
                    $('body').addClass('loaded');
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    //alert(xhr.status);
                    //alert(thrownError);
                }
            });
        });

    });

</script>
@endsection