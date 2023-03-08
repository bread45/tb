@extends('front.trainer.layout.trainer')
@section('title', 'Services')
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
                        
                        <h1 class="h3 text-muted font-weight-normal mb-0 mt-lg-0 mt-3 order-lg-0 order-3">My Services</h1>
                         @if(empty($StripeAccountsdata))
                        <a id="submit-stripe" class="ml-lg-auto mr-3 order-lg-1 order-4 mt-lg-0 mt-2" href="javascript:void(0)"><img src="{{asset('images/blue-on-dark.png')}}"></a>
                        @else 
                        <div class="stripe_style">
                            <img src="{{asset('images/stripe_connected.png')}}" style="width: 30px;padding-right: 5px;margin-right: 2px;margin-top: -6px;">Your stripe account is connected.
                          </div>
                        @endif 
                        <a href="{{ route('service.add')}}" class="btn btn-info ml-lg-auto mr-3 order-lg-1 order-4 mt-lg-0 mt-2">Add Service</a>
                       
                        @include('front.trainer.layout.includes.header')
                    </div>
                    <!-- Alert for Service -->
                    <div class="row mb-4">
                        <div class="col-12">
                    <div class="alert alert-warning alert-dismissible fade show">
                    List the services you offer to athletes, and add appointment booking. In order to add appointment booking, you must first connect your Training Block account with your Stripe payment account. Note that Training Block takes a 15% transaction fee for all appointments booked by an athlete through our platform.
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
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
                                                    <th width="6%">Sr No</th>
                                                    <th width="28%">Service</th>
                                                    <th width="15%">Price $</th>
                                                    
                                                    <th width="12%" class="text-center">Service Type</th>
                                                    <th width="15%">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            
                                                @foreach($services as $k => $service)
                                                <tr>
                                                    <td>{{$k+1}}</td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                        <img class="mr-3" width="30px" src="{{asset('front/images/'.$service->service->icon)}}" alt="{{$service->service->name}}" />
                                                            <p class="mb-0">{{$service->name}}</p>
                                                        </div>
                                                    </td>
                                                    <td>
                                                    @if($service->is_recurring == "yes")
                                                    <!-- ${{$service->price_weekly}} USD Weekly <br/> -->
                                                    ${{$service->price_monthly}} USD Monthly    
                                                    @else
                                                    {{$service->price}} USD 
                                                    @endif 
                                                       
                                                    </td>

                                        
                                        <td class="text-center">
                                            {{$service->format}}
                                        </td>
                                        <!-- <td>{{$service->message}}</td> -->
                                                    <td>
                                                        <div class="d-flex actions-links">
                                                            <a href="{{ route('service.edit',['service'=> base64_encode($service->id)]) }}" class="edit-link mr-2">Edit</a>
                                                            <a href="{{ route('service.detail',['service'=> base64_encode($service->id)]) }}" class="view-link mr-2">View</a>
                                                            <a href="{{ route('service.delete',['service'=> base64_encode($service->id)]) }}" class="delete-link" onclick="return confirmation();" >Delete</a>
                                                            
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<style>
    label.radio-inline{
    margin: 0 10px 0 0;
    }
    .stripe_style {
    background: #6772e5;
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
</style>
<script type="text/javascript">
function confirmation()
{ // event.preventDefault();
    return confirm("Are you sure you want to delete this service?");
    // Swal.fire({
    // title: 'Are you sure?',
    // text: "You won't be able to revert this!",
    // icon: 'warning',
    // showCancelButton: true,
    // confirmButtonColor: '#3085d6',
    // cancelButtonColor: '#d33',
    // confirmButtonText: 'Yes, delete it!'
    // }).then((result) => {
    // if (result.value) {
    //     Swal.fire(
    //     'Deleted!',
    //     'Your file has been deleted.',
    //     'success'
    //     )
    // }
    // })

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
              
                 window.open(result, '_blank');
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
                    }
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

