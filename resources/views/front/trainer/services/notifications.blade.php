@extends('front.trainer.layout.trainer')
@section('title', 'Notifications')
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
                        
                        <h1 class="h3 text-muted font-weight-normal mb-0 mt-lg-0 mt-3 order-lg-0 order-3">Notifications</h1>
                        
                       
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
                                <div class="card-body px-0 pb-2">
                                    <div class="common-data-table">
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
                                    <div class="col-12">
                                      <div class="">
                                        <div class="p-lg-2">

                                          <form class="form-row add-service-form" method="POST" action="{{ route('trainer.search.notifications')}}">
                                          @csrf
                                            <input type="hidden" name="search" value="1">
                                            <div class="form-group col-lg-4 col-md-6">
                                              <label for="sprice-input">SERVICE NAME</label>
                                              <input type="text" class="form-control" name="service_name" id="name-input" maxlength="30" placeholder="enter service name" value="{{ $service_name }}">
                                            </div>
                                            <div class="form-group col-lg-4 col-md-6">
                                              <label for="sprice-input">SERVICE Category</label>
                                              <select name="service_category" class="form-control" id="service-input">
                                             <option value="">select service category</option>
                                             @foreach($services as $service)
                                              <option @if($service_category == $service->id) selected @endif value="{{ $service->id }}" >{{ $service->name }}</option> 
                                             @endforeach
                                         </select>
                                            </div>
                                            <div class="form-group col-lg-4 col-md-6">
                                              <label for="sprice-input">Status</label>
                                              <select name="status" class="form-control" id="service-input">
                                              <option value="">select status</option>
                                              <option value="1" @if($status == 1) selected @endif>New</option>
                                              <option value="2" @if($status == 2) selected @endif>Accepted</option>
                                              <option value="3" @if($status == 3) selected @endif>Declined</option>
                                              </select>
                                            </div>
                                            <!--<div class="col-lg-12 d-flex justify-content-center">
                                              <input type="submit" name="submit" value="Search" class="btn btn-success btn-lg" />
                                              <a href="{{ route('trainer.notifications') }}"><input type="button" value="Reset" class="btn btn-danger btn-lg" /></a>
                                            </div>-->
                                            <div class="col-lg-12 d-flex justify-content-center mb-1">
                                              <input type="submit" name="submit" value="Search" class="btn btn-success btn-lg" style="background-color: #07ad94;" />
                                            </div>
                                              <div class="col-lg-12 d-flex justify-content-center mb-1">
                                              <a href="{{ route('trainer.notifications') }}"><input type="button" value="Reset" class="btn bg-transparent" style="color: #44859b;text-decoration: underline;" /></a>
                                            </div>
                                          </form>

                                        </div>
                                      </div>
                                    </div>
                                    
                                    <div class="container-fluid">
                                    <table id="notification-service-table" class="display" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>SI.No</th>
                                                    <th>Service Name</th>
                                                    <th>Service Category</th>
                                                    <th>First Name</th>
                                                    <th>Last Name</th>
                                                    <th>Email ID</th>
                                                    <th>Phone Number</th>
                                                    <th>Requested Date and Time</th>
                                                    <th>Status</th>
                                                    <th width="12%">Comments</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            
                                                @foreach($serviceRequestData as $k => $service)

                                                <?php 
                                                    $order_details = DB::table('orders')->where(["id" => $service->order_id])->first();
                                                    $service_details = DB::table('trainer_services')->where(["id" => $service->service_id])->first();
                                                ?>
                                                <tr>
                                                    <td>{{$k+1}}</td>
                                                    <td>
                                                        {{$service->service_name}}
                                                    </td>
                                                    <td>
                                                        {{$service->category_name}}
                                                    </td>
                                                    <td>
                                                        {{$service->first_name}}
                                                    </td>
                                                    <td>
                                                        {{$service->last_name}}
                                                    </td>
                                                    <td>
                                                        {{$service->email_id}}
                                                    </td>
                                                    <td>
                                                        {{$service->phone}}
                                                    </td>
                                                    <td>
                                                       
                                                        @if($service_details->format == 'In person - Single Appointment' || $service_details->format == 'In person - Group Appointment' || $service_details->format == 'Virtual - Single Appointment' || $service_details->format == 'Virtual - Group Appointment')
                                                            {{ date('m-d-Y', strtotime($order_details->appointment_date)) }} {{$order_details->service_time}} 
                                                        @elseif($service_details->format == 'Monthly Membership' || $service_details->format == 'Yearly Membership')
                                                            {{ date('m-d-Y', strtotime($order_details->start_date)) }}
                                                        @else
                                                            {{ date('m-d-Y H:i:s', strtotime($service->reuest_date_time))}}
                                                        @endif
                                                    </td>
                                                     @if($service->status == 1)
                                                         <td>
                                                         <a href="#" data-toggle="modal" data-target="#comments{{$service->id}}"> <span class="badge badge-primary" style="background-color: #1e2732;">New</span></a></td>
                                                    @elseif($service->status == 2)
                                                        <td><span class="badge badge-primary" style="    background-color: #07ad94;">Accepted</span></td>
                                                    @else
                                                        <td><span class="badge badge-primary" style="    background-color: #cf5260;">Declined</span></td>
                                                    @endif
                                                    <td>
                                                        {{$service->comments}}
                                                    </td>
                                                   
                                                   
                                                    
                                                </tr>

                                                <div class="modal fade" id="comments{{$service->id}}" tabindex="-1" role="dialog" aria-labelledby="comments" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                  <div class="modal-content">
                                                    <div class="modal-header">
                                                      <h5 class="modal-title" id="exampleModalLabel">Status</h5>
                                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                      </button>
                                                    </div>
                                                    <div class="modal-body">
                                                    
                                                      <div class="p-2">
                                                      <form method="POST" action="{{route('providerpaymentintent')}}" class="require-validation" >
                                                      
                                                        <input type="hidden" name="request_id" id="request_id" value="{{$service->id}}">
                                                        <input type="hidden" name="user_id" id="user_id" value="{{$service->user_id}}">
                                                        <input type="hidden" name="trainer_id" id="trainer_id" value="{{$service->trainer_id}}">
                                                        <input type="hidden" name="service_id" id="service_id" value="{{$service->service_id}}">
                                                        <input type="hidden" name="stripeToken" id="stripeToken" value="{{$service->stripeToken}}">
                                                        <input type="hidden" name="email_id" id="email_id" value="{{$service->email_id}}">
                                                        <input type="hidden" name="order_id" id="order_id" value="{{$service->order_id}}">
                                                        <div class="form-group col-lg-12 col-md-12"><label>Status : </label><select name="status" id="status" class="form-control mb-3" required="">
                                                        <option value="">Select</option>
                                                        <!-- <option value="1" @if($service->status == 1) selected @endif>New</option> -->
                                                        <option value="2" @if($service->status == 2) selected @endif>Accept</option>
                                                        <option value="3" @if($service->status == 3) selected @endif>Decline</option></select>
                                                        </div>
                                                        <div class="form-group col-lg-12 col-md-12"><label>Comments : </label><textarea
                                                            class="form-control shadow-none textarea_comments" name="comments"></textarea></div>
                                                            
                                                        <div class="mt-2 text-right">
                                                        
                                                            <input type="submit" id="status_update" class="btn btn-primary btn-sm shadow-none post_commet" value="Update" style="background-color: #008080;color: #fff;border: 1px solid #008080;">
                                                            @csrf
                                                            </div>
                                                            </form>
                                                      </div>
                                                      
                                                      
                                                      
                                                    </div>
                                                  </div>
                                                </div>
                                              </div>

                                                @endforeach
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @include('front.trainer.layout.includes.footer')
                    
                </div>
            </div>
        </div>
@endsection
@section('pagescript')

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css" />
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
<script src="https://js.stripe.com/v3/"></script>

<style>
    label.radio-inline{
    margin: 0 10px 0 0;
    }
    .buttons-excel{
        margin: 0 5px;
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
</style>
<script type="text/javascript">


$(function () {
    jQuery.extend( jQuery.fn.dataTableExt.oSort, {
    "currency-pre": function ( a ) {
        a = (a==="-") ? 0 : a.replace( /[^\d\-\.]/g, "" );
        return parseFloat( a );
    },
 
    "currency-asc": function ( a, b ) {
        return a - b;
    },
 
    "currency-desc": function ( a, b ) {
        return b - a;
    }
} );
<?php if($order_request_count == 0){?>
 var ddd = 'No notifications at this time';
<?php } else {?>
    var ddd = 'No results matched your search criteria';
<?php }?>
    var table = $('#notification-service-table').DataTable({
                rowReorder: false,
                responsive: true,
                "bPaginate": true,
                "bLengthChange": true,
                "bFilter": true,
                "bInfo": true,
                "bSortable": true,
                "bAutoWidth": false,
                "columnDefs": [
                    { "orderable": false, "targets": 0 }
                ],
                language: {
                    searchPlaceholder: "Search..",
                    "paginate": {
                       "previous": "Prev"
                    },
                    "emptyTable": ddd
                },
                dom: 'lBfrtip',
                 buttons: [
                    {
                        extend: 'excel',
                        title: 'Notifications'
                    }
                ],
                drawCallback: function() {
                    var hasRows = this.api().rows({ filter: 'applied' }).data().length > 0;
                    $('.buttons-excel')[0].style.visibility = hasRows ? 'visible' : 'hidden'
                  },
                "order": [[ 0, "asc" ]],
                "aaSorting": [[ 2, 'asc' ]] ,
                "pageLength": 10
                
            });

            $(".alert-danger").delay(4000).slideUp(300);
            $(".alert-success").delay(4000).slideUp(300);

    $(document).on('click', '.featured_change', function () {
        var id = $(this).attr('data-id');
        $.ajax({
            url: id,
            type: 'GET',
            success: function (result) {
                if (result.status == true) {
                   // toastr.success(result.Message);
                    Swal.fire(
                     'Updated!',
                      result.Message,
                     'success'
                     );
                    table.draw(true);
                    setTimeout(function(){
                        window.location.reload(); 
                    },1000);
                } else {
                   // toastr.error(result.Message);
                    Swal.fire(
                     'Sorry!',
                      result.Message,
                     'error'
                     );
                }
            }
        });
    });

    $(document).on('click', '.status_change', function () {
        var id = $(this).attr('data-id');
        $.ajax({
            url: id,
            type: 'GET',
            success: function (result) {
                if (result.status == true) {
                   // toastr.success(result.Message);
                    Swal.fire(
                     'Updated!',
                      result.Message,
                     'success'
                     );
                    table.draw(true);
                    setTimeout(function(){
                        window.location.reload(); 
                    },1000);
                } else {
                   // toastr.error(result.Message);
                    Swal.fire(
                     'Sorry!',
                      result.Message,
                     'error'
                     );
                }
            }
        });
    });

   
    

   
});

</script>
@endsection

