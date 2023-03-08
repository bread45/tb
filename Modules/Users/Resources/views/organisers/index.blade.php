@extends('admin.layouts.default')
@section('title', 'Users List')
@section('content')
<style>.btn-group, .btn-group-vertical{float: left;}</style>
<link href="{{ asset('/theme/vendors/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
<!-- begin:: Content Head -->
<div class="kt-subheader  kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">Users Management</h3>
        </div>
        <div class="kt-subheader__toolbar">
            <a href="{{route('users.create')}}" class="btn btn-primary btn-bold"><i class="fa fa-plus"></i> Add New</a>
        </div>
    </div>
</div>
<!-- end:: Content Head -->

<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__body">
            @include('admin.theme.includes.message')
            <!--begin: Datatable -->
            <!--<p id="selectTriggerFilter"><label><b>Filter:</b></label><br></p>-->
            <div class="row">
                        <div class="col-lg-12" id="selectTriggerFilter"> 
                             
                        </div> 
                    </div>
            <table class="table data-table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
                <thead>
                     <tr>
                        <th>No</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>City</th>
                        <th>State</th>
                        <th>User Role</th>
                        <th>Status</th>
                        <th width="100px">Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <!--end: Datatable -->
        </div>
    </div>

    <div class="modal fade" id="contactus" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content ajax_content"></div>
        </div>
    </div>
</div>
<!-- end:: Content -->
@stop

@section('pagescript')
<!--begin::Page Vendors(used by this page) -->
<script src="{{ asset('/theme/vendors/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
<!--end::Page Vendors -->
<script>
$(function () {
    function newexportaction(e, dt, button, config) {
    var self = this;
    var oldStart = dt.settings()[0]._iDisplayStart;
    dt.one('preXhr', function (e, s, data) {
        // Just this once, load all data from the server...
        data.start = 0;
        data.length = 2147483647;
        dt.one('preDraw', function (e, settings) {
            // Call the original action function
            if (button[0].className.indexOf('buttons-copy') >= 0) {
                $.fn.dataTable.ext.buttons.copyHtml5.action.call(self, e, dt, button, config);
            } else if (button[0].className.indexOf('buttons-excel') >= 0) {
                $.fn.dataTable.ext.buttons.excelHtml5.available(dt, config) ?
                    $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config) :
                    $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
            } else if (button[0].className.indexOf('buttons-csv') >= 0) {
                $.fn.dataTable.ext.buttons.csvHtml5.available(dt, config) ?
                    $.fn.dataTable.ext.buttons.csvHtml5.action.call(self, e, dt, button, config) :
                    $.fn.dataTable.ext.buttons.csvFlash.action.call(self, e, dt, button, config);
            } else if (button[0].className.indexOf('buttons-pdf') >= 0) {
                $.fn.dataTable.ext.buttons.pdfHtml5.available(dt, config) ?
                    $.fn.dataTable.ext.buttons.pdfHtml5.action.call(self, e, dt, button, config) :
                    $.fn.dataTable.ext.buttons.pdfFlash.action.call(self, e, dt, button, config);
            } else if (button[0].className.indexOf('buttons-print') >= 0) {
                $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
            }
            dt.one('preXhr', function (e, s, data) {
                // DataTables thinks the first item displayed is index 0, but we're not drawing that.
                // Set the property to what it was before exporting.
                settings._iDisplayStart = oldStart;
                data.start = oldStart;
            });
            // Reload the grid with the original page. Otherwise, API functions like table.cell(this) don't work properly.
            setTimeout(dt.ajax.reload, 0);
            // Prevent rendering of the full data to the DOM
            return false;
        });
    });
    // Requery the server with the new one-time export settings
    dt.ajax.reload();
};
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        dom:'lBfrtip',
//          buttons: ['excel', 'csv'],
            buttons : [ {
            extend : 'csv', 
            "action": newexportaction
        },{
            extend : 'excel', 
            "action": newexportaction
        } ],
         "lengthMenu": [10,25,50,100], 
//         "sScrollX": "100%",
//         "scrollCollapse": true,
        ajax: {
            'url': "{{ route('users.getall') }}",
            'type': 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            data: function (d) {
                d.user_role = "supplier";
            }
        },
        language: {
 oPaginate: {
   sNext: '<i class="flaticon2-next"></i>',
   sPrevious: '<i class="flaticon2-back"></i>',
   sFirst: '<i class="fa fa-step-backward"></i>',
   sLast: '<i class="fa fa-step-forward"></i>'
   }
   } ,
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'first_name', name: 'first_name'},
            {data: 'last_name', name: 'last_name'},
            {data: 'email', name: 'email'},
            {data: 'city', name: 'city'},
            {data: 'state_code', name: 'state_code'},
            {data: 'user_role', name: 'user_role'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        initComplete: function() {
            var column = this.api().column(5); 
            var select = $('<select style="width: 20%;float: right;"  class="filter form-control"><option value="">All</option></select>')
              .appendTo('#selectTriggerFilter')
              .on('change', function() {
                var val = $(this).val();
                column.search(val ? '^' + $(this).val() + '$' : val, true, false).draw();
              });

            column.data().unique().sort().each(function(d, j) {
              select.append('<option value="' + d + '">' + d + '</option>');
            });
          }
    });

    $(document).on('click', '.status_change', function () {
        var id = $(this).attr('data-id');
        $.ajax({
            url: id,
            type: 'GET',
            success: function (result) {
                if (result.status == true) {
                    toastr.success(result.Message);
                    table.draw(true);
                } else {
                    toastr.error(result.Message);
                }
            }
        });
    });

    $(document).on('click', '.delete_contactus', function () {
        var id = $(this).attr('data-id');
        swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            animation: false,
            customClass: 'animated tada',
            confirmButtonText: 'Yes, delete it!'
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: id,
                    type: 'DELETE',
                    data: {
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function (result) {
                        if (result.status == true) {
                            toastr.success(result.Message);
                            table.draw(true);
                        } else {
                            toastr.error(result.Message);
                        }
                    }
                });
            }
        });
    });
});
</script>
@endsection