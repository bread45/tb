@extends('front.trainer.layout.trainer')
@section('title', 'Attendees List')
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
                        
                        <h1 class="h3 text-muted font-weight-normal mb-0 mt-lg-0 mt-3 order-lg-0 order-3">Attendees List</h1>
                        
                       
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
                                    
                                    <div class="col-12 soliMudunchu">
                                      <div class="">
                                        <div class="p-lg-5">
                                          <form class="form-row add-service-form" method="POST" action="{{ route('trainer.attendees.find')}}">
                                          @csrf
                                            <input type="hidden" name="search" value="1">
                                            <div class="form-group col-lg-6 col-md-6">
                                              <label for="sprice-input">Event Name</label>
                                              <select name="event_name" class="form-control" id="service-input">
                                             <option value="">select event name</option>
                                             
                                                    @foreach($attendeesFormDetails as $attendeesFormDetail)  
                                                                   @if($attendeesFormDetail->id != null)                                                                                
                                                        <option @if($attendeesFormDetail->id == $event_name) selected @endif value="{{ $attendeesFormDetail->event_id }}" >{{ $attendeesFormDetail->title }} ({{ $attendeesFormDetail->start_date }})</option> 
                                                                    @endif
                                                    @endforeach
                                             
                                         </select>
                                            </div>
                                            <div class="form-group col-lg-6 col-md-6">
                                              <label for="sprice-input">Event Category</label>
                                              <select name="event_category" class="form-control" id="service-input">
                                             <option value="">select event category</option>
                                              <option {{$event_category == "Running event" ? 'selected' : ''}} value="Running event" >Running event</option> 
                                              <option {{$event_category == "Cycling event" ? 'selected' : ''}} value="Cycling event" >Cycling event</option> 
                                              <option {{$event_category == "Triathlon event" ? 'selected' : ''}} value="Triathlon event" >Triathlon event</option> 
                                              <option {{$event_category == "Other group sport event" ? 'selected' : ''}} value="Other group sport event" >Other group sport event</option> 
                                              <option {{$event_category == "Workshop" ? 'selected' : ''}} value="Workshop" >Workshop</option> 
                                              <option {{$event_category == "Webinar" ? 'selected' : ''}} value="Webinar" >Webinar</option> 
                                              <option {{$event_category == "Training Camp" ? 'selected' : ''}} value="Training Camp" >Training Camp</option> 
                                                
                                         </select>
                                            </div>
                                            
                                            <div class="form-group col-lg-6 col-md-6">
                                              <label for="sprice-input">Event Date</label>
                                              <input type="text" class="form-control" name="event_date" id="event_date" maxlength="30" placeholder="select date" value="{{$event_date != '' ? $event_date : ''}}" autocomplete="off">
                                            </div>
                                            <div class="form-group col-lg-6 col-md-6">
                                              <label for="sprice-input">Status</label>
                                              <select name="status" class="form-control" id="service-input">
                                              <option value="">select status</option>
                                              
                                            <option {{$status == "Attending" ? 'selected' : ''}} value="Attending" >Attending</option>
                                            <option {{$status == "Maybe" ? 'selected' : ''}} value="Maybe" >Maybe</option>
                                            <option {{$status == "Not Attending" ? 'selected' : ''}} value="Not Attending" >Not Attending</option>
                                              </select>
                                            </div>
                                            
                                            <!--<div class="col-lg-12 d-flex justify-content-center">
                                              <input type="submit" name="submit" value="Search" class="btn btn-success btn-lg" />
                                              <a href="{{ route('trainer.month.annual.schedules') }}"><input type="button" value="Reset" class="btn btn-danger btn-lg" /></a>
                                            </div>-->
                                            <div class="col-lg-12 d-flex justify-content-center mb-1">
                                              <input type="submit" name="submit" value="Search" class="btn btn-success btn-lg" style="background-color: #07ad94;"/>
                                            </div>

                                              <div class="col-lg-12 d-flex justify-content-center mb-1">
                                              <a href="{{ route('trainer.attendees.list') }}"><input type="button" value="Reset" class="btn bg-transparent" style="color: #44859b;text-decoration: underline;"/></a>
                                            </div>
                                          </form>

                                        </div>
                                      </div>
                                    </div>
                                    <div class="container-fluid">
                                    <table id="month-annual-table" class="display" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>SI.No</th>       
                                                    <th>Event Name</th>                                                                                                
                                                    <th>First Name</th>
                                                    <th>Last Name</th>
                                                    <th>Email ID</th>
                                                    <th>Event Date</th>
                                                    <th>Category</th>
                                                    <th>Location</th>                                                   
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $s = 1; ?>
                                            
                                                @foreach($attendeesDetails as $k => $attendeesDetail)
                                                
                                                    @if($attendeesDetail->id != null) 
                                                <tr>
                                                    <td>{{$s++}}</td>
                                                    <td>
                                                        {{$attendeesDetail->title}}
                                                    </td>
                                                    <td>
                                                        {{$attendeesDetail->attender_first}}
                                                    </td>
                                                    <td>
                                                        {{$attendeesDetail->attender_last}}
                                                    </td>
                                                    <td>
                                                        {{$attendeesDetail->attender_email}}
                                                    </td>                                                                                                        
                                                    <td>
                                                        {{$attendeesDetail->start_date}}
                                                    </td>
                                                    <td>
                                                        {{$attendeesDetail->category}}
                                                    </td>
                                                    <td>
                                                        {{$attendeesDetail->venue}}
                                                    </td>
                                                    <td>
                                                        {{$attendeesDetail->rsvp}}
                                                    </td>   
                                                </tr>

                                                    @endif
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
<link href="{{ asset('/theme/vendors/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css') }}" rel="stylesheet" type="text/css" />
<script src="{{ asset('/theme/vendors/general/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('/theme/vendors/custom/js/vendors/bootstrap-datepicker.init.js') }}" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css" />
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/plug-ins/1.11.1/sorting/currency.js"></script>
<style>
/*.soliMudunchu{
    display: none;
}*/
    label.radio-inline{
    margin: 0 10px 0 0;
    }
    .buttons-excel{
        margin: 0 5px;
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




    var table = $('#month-annual-table').DataTable({
                rowReorder: false,
                
                responsive: true,
                "bPaginate": true,
                "bLengthChange": true,
                "bFilter": true,
                "bInfo": true,
                "bSortable": true,
                dom: 'Bfrtip',
                "bAutoWidth": false,
                "columnDefs": [
                    { "orderable": false, "targets": 0 }
                ],
                language: {
                    searchPlaceholder: "Search..",
                    "paginate": {
                       "previous": "Prev"
                    }
                },
                dom: 'lBfrtip',
                 buttons: [
                    {
                        extend: 'excel',
                        title: 'Attendees List'
                    }
                ],
                columnDefs: [
                   { type: 'currency', targets: 8 }
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
$('#event_date').datepicker({
        format: 'mm/dd/yyyy',
        //minDate: new Date(),
        autoclose: true
    });
});
</script>
@endsection

