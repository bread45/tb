@extends('admin.layouts.default')
@section('title', 'Event Me Now')
@section('content')

<link href="{{ asset('/theme/vendors/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />

<!-- begin:: Content Head -->
<div class="kt-subheader  kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">Contact US</h3>
            <!--<span class="kt-subheader__separator kt-subheader__separator--v"></span>-->
        </div>
    </div>
</div>
<!-- end:: Content Head -->

<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__body">
            <!--begin: Datatable -->
            <table class="table data-table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
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
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('contactus') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'name', name: 'name'},
            {data: 'email', name: 'email'},
            {data: 'phone_number', name: 'phone_number'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });

    $(document).on('click', '.view_contactus', function () {
        var id = $(this).attr('data-id');
        $.ajax({
            url: id,
            type: 'GET',
            success: function (result) {
                if (result.status == true) {
                    $('.ajax_content').html(result.data);
                    $('#contactus').modal('show');
                    table.draw(true);
                } else {
                    toastr.error(result.Message);
                }
            }, error: function () {
                toastr.error("Permission Denied!");
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
                    url: "{{route('contactus.destroy')}}",
                    type: 'DELETE',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "id": id
                    },
                    success: function (result) {
                        if (result.status == true) {
                            toastr.success(result.Message);
                            table.draw(true);
                        } else {
                            toastr.error(result.Message);
                        }
                    }, error: function () {
                        toastr.error("Permission Denied!");
                    }
                });
            }
        });
    });
});
</script>
@endsection