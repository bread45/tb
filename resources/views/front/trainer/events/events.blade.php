@extends('front.trainer.layout.trainer')
@section('title', 'Events')
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
                        
                        <h1 class="h3 text-muted font-weight-normal mb-0 mt-lg-0 mt-3 order-lg-0 order-3">My Events</h1>

                        @if(empty($StripeAccountsdata))
                        <a id="submit-stripe" class="btn btn-info ml-lg-auto mr-3 order-lg-1 order-4 mt-lg-0 mt-2" href="javascript:void(0)">Connect Your Bank Account</a>
                        @else 
                        <div class="stripe_style">
                            <!--<img src="{{asset('images/stripe_connected.png')}}" style="width: 30px;padding-right: 5px;margin-right: 2px;margin-top: -6px;">-->Your stripe account is connected
                          </div>
                        @endif 
                        
                         <!-- @if(empty($StripeAccountsdata))
                        <a id="submit-stripe" class="btn btn-info ml-lg-auto mr-3 order-lg-1 order-4 mt-lg-0 mt-2" href="javascript:void(0)">Connect with Stripe</a>
                        @else 
                        <div class="stripe_style">
                           Your stripe account is connected
                          </div>
                        @endif  -->
                        <a href="{{ route('trainer.events.add')}}" class="btn btn-info ml-lg-auto mr-3 order-lg-1 order-4 mt-lg-0 mt-2">Add Events</a>
                       
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


                    <!-- Alert for Service -->
                    <div class="row mb-4">
                        <div class="col-12">
                    <div class="alert alert-warning alert-dismissible fade show">
                    List your upcoming events here. Please note that in order for athletes to register for events that cost money, you must first connect your bank account. Training Block takes a 5% transaction fee for all paid event registrations.
                       <!-- <button type="button" class="close" data-dismiss="alert">&times;</button>-->
                    </div>
                    </div>
                    </div>

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
                                    
                                    <div class="container-fluid">
                                    <table id="service-table" class="display nowrap" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th width="6%">SI.No</th>
                                                    <th width="23%">Event Title</th>
                                                    <th width="10%">Start Date</th>
                                                    <th width="12%" class="text-center">Event Category</th>
                                                    <th width="10%" class="text-center">Event Format</th>
                                                    <th width="15%">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            
                                                @foreach($events as $k => $event)
                                                <tr>
                                                    <td>{{$k+1}}</td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <p class="mb-0">{{$event->title}}</p>
                                                        </div>
                                                    </td>
                                                    <td>
                                                            <p class="mb-0">{{$event->start_date}}</p>
                                                    </td>
                                                    <td>
                                                            <p class="mb-0">{{$event->category}}</p>
                                                    </td>
                                                     <td class="text-center">
                                                         {{$event->format}}
                                                     </td>
                                                    <td>
                                                        <div class="d-flex actions-links">
                                                        <a href="{{ route('trainer.events.edit',['event'=> base64_encode($event->id)]) }}" class="edit-link mr-2">Edit</a>
                                                        <a href="{{ route('trainer.events.delete',['event'=> base64_encode($event->id)]) }}" class="delete-link" onclick="return confirmation('{{base64_encode($event->id)}}'@if(isset($event->recurrence_id)), '{{base64_encode($event->recurrence_id)}}'@endif);" >Delete</a>
                                                        </div>
                                                    </td>
                                                </tr>
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
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    label.radio-inline{
    margin: 0 10px 0 0;
    }
    .stripe_style {
    background: #00ab91;
    color: #fff;
    font-size: 14px;
    text-transform: uppercase;
    font-weight: 600;
    border-radius: 5px;
    border: 2px solid transparent;
    height: 40px;
    line-height: 38px;
    padding: 0 25px;
    /* padding: 5px 20px; */
    margin-left: 44px;
}
button.swal2-confirm.swal2-styled, button.swal2-deny.swal2-styled, button.swal2-cancel.swal2-styled {
    font-size: 14px;
    padding: 5px 19px;
}
.swal2-styled.swal2-confirm:focus, .swal2-deny.swal2-styled:focus, .swal2-cancel.swal2-styled:focus {
    box-shadow: none;
}
h2#swal2-title {
    font-size: 22px;
    padding: 30px 20px 10px 20px;
}
.swal2-popup.swal2-modal.swal2-show {
    padding-bottom: 30px;
}
.swal2-styled.swal2-confirm {
    background-color : #00ab91;
}
.swal2-styled.swal2-deny {
    background-color : #cf5260;
}
</style>
<script type="text/javascript">
function confirmation(eventID, recurrenceID)
{   
    event.preventDefault();
    // return confirm("Are you sure you want to delete this event?");
    Swal.fire({
      title: 'Are you sure you want to delete this event?',
      showDenyButton: true,
      showCancelButton: true,
      confirmButtonText: 'Delete single event',
      denyButtonText: `Delete the series`,
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax(
                    {
                        type: "get",
                        url: "/trainer/delete-event/"+eventID,
                        success: function(data){
                           if(data.status == 'Success'){
                            location.reload();
                           }
                           else {
                            Swal.fire('Something Went Wrong..!', '', 'info');
                           }
                        }
                    }
            )
      } else if (result.isDenied) {
        
        if(typeof recurrenceID !== "undefined"){
        $.ajax(
                    {
                        type: "get",
                        url: "/trainer/delete-recurring-event/"+recurrenceID,
                        success: function(data){
                           if(data.status == 'Success'){
                            location.reload();
                           }
                           else {
                            Swal.fire('Something Went Wrong..!', '', 'info');
                           }
                        }
                    }
            )
      }
    else {
        $.ajax(
                    {
                        type: "get",
                        url: "/trainer/delete-recurring-event/"+eventID,
                        success: function(data){
                           if(data.status == 'Success'){
                            location.reload();
                           }
                           else {
                            Swal.fire('Something Went Wrong..!', '', 'info');
                           }
                        }
                    }
            )
        }
    }
    })
}

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

let elmButton = document.querySelector("#submit-stripe");
if (elmButton) {
  elmButton.addEventListener(
    "click",
    e => {
      elmButton.setAttribute("disabled", "disabled");
$.ajax({
            url: '{{ route('stripecreate.geturl') }}',
            type: 'GET',
            success: function (result) {
              
                 window.open(result, '_self');
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




    var table = $('#service-table').DataTable({
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
                    "sEmptyTable":     "You have not yet added any events."
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

