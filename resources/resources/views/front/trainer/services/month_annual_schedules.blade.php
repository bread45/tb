@extends('front.trainer.layout.trainer')
@section('title', 'Monthly/ Annual Schedules')
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
                        
                        <h1 class="h3 text-muted font-weight-normal mb-0 mt-lg-0 mt-3 order-lg-0 order-3">Monthly/ Annual Schedules</h1>
                        
                       
                        @include('front.trainer.layout.includes.header')
                    </div>

                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body px-0 pb-2">
                                    <div class="common-data-table">
                                    
                                    <div class="col-12">
                                      <div class="">
                                        <div class="p-lg-5">

                                          <form class="form-row add-service-form" method="POST" action="{{ route('trainer.search.month.annual.schedules')}}">
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
                                              <label for="sprice-input">SERVICE Type</label>
                                              <select name="package_type" class="form-control" id="service-input">
                                              <option value="">select service Type</option>
                                              
                                              <option value="Monthly Membership" @if($package_type == 'Monthly Membership') selected @endif>Monthly Membership</option>
                                              <option value="Yearly Membership" @if($package_type == 'Yearly Membership') selected @endif>Yearly Membership</option>
                                              </select>
                                            </div>
                                            <div class="form-group col-lg-4 col-md-6">
                                              <label for="sprice-input">Effective From</label>
                                              <input type="text" class="form-control" name="paid_date" id="paid_date" maxlength="30" placeholder="enter effective from" value="{{ $paid_date }}">
                                            </div>
                                            <div class="form-group col-lg-4 col-md-6">
                                              <label for="sprice-input">Status</label>
                                              <select name="status" class="form-control" id="service-input">
                                              <option value="">select status</option>
                                              
                                              <option value="1" @if($status == 1) selected @endif>Active</option>
                                              <option value="2" @if($status == 2) selected @endif>Cancelled</option>
                                              </select>
                                            </div>
                                            
                                            <div class="col-lg-12 d-flex justify-content-center">
                                              <input type="submit" name="submit" value="Search" class="btn btn-success btn-lg" />
                                              <a href="{{ route('trainer.month.annual.schedules') }}"><input type="button" value="Reset" class="btn btn-danger btn-lg" /></a>
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
                                                    <th>Service Name</th>
                                                    <th>Service Category</th>
                                                    <th>First Name</th>
                                                    <th>Last Name</th>
                                                    <th>Email ID</th>
                                                    <th>Phone Number</th>
                                                    <th>Service Type</th>
                                                    <th>Paid</th>
                                                    <th>Paid Date and Time</th>
                                                    <th>Effective From</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            
                                                @foreach($serviceEventsData as $k => $service)
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
                                                    <?php $athleetmail = DB::table('front_users')->where(["id" => $service->user_id])->first();?>
                                                        {{$athleetmail->email}}
                                                    </td>
                                                    <td>
                                                        {{$service->phone}}
                                                    </td>
                                                    <td>
                                                        {{$service->plan_type}}
                                                    </td>
                                                    <td>
                                                        ${{$service->amount}} USD
                                                    </td>
                                                    <td>
                                                        {{$service->created_at}}
                                                    </td>
                                                    <td>
                                                        {{$service->start_date}}
                                                    </td>
                                                   
                                                    @if($service->status == 1)
                                                         <td>Active</td>
                                                        @else
                                                        <td>Cancelled</td>
                                                    @endif
                                                    
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
<style>
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
                    { "orderable": false, "targets": 0 },
                    { type: 'currency', targets: 2 }
                ],
                language: {
                    searchPlaceholder: "Search..",
                    "paginate": {
                       "previous": "Prev"
                    }
                },
                dom: 'lBfrtip',
                 buttons: [
                    'excelHtml5'
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
$('#paid_date').datepicker({
        format: 'yyyy-mm-dd',
        minDate: new Date(),
        autoclose: true,
        onSelect: function(dateText, inst) {
        var paid_date = $(this).val();
        $("#end_date").datepicker("option", "minDate", new Date(paid_date));
        
        
        }
    });
});
</script>
@endsection

