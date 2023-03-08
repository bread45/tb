@extends('admin.layouts.default')
@section('title', 'CMS Management')
@section('content')
<link href="{{ asset('/theme/vendors/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
<!-- begin:: Content Head -->
<div class="kt-subheader  kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">CMS Management</h3>
            <!--<span class="kt-subheader__separator kt-subheader__separator--v"></span>-->
        </div>
        <div class="kt-subheader__toolbar">
            <a href="{{route('cms_pages.create')}}" class="btn btn-primary btn-bold"><i class="fa fa-plus"></i> Add New</a>
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
            <table class="table data-table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
                <thead>
                    <tr>
                        <th width="20px">No</th>
                        <th>Title</th>
                        <th>Slug</th>
                        <th width="60px">Status</th>
                        <th width="110px">Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <!--end: Datatable -->
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
        ajax: "{{ route('cms_pages.getall') }}",
        language: {
 oPaginate: {
   sNext: '<i class="flaticon2-next"></i>',
   sPrevious: '<i class="flaticon2-back"></i>',
   sFirst: '<i class="fa fa-step-backward"></i>',
   sLast: '<i class="fa fa-step-forward"></i>'
   }
   } ,
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex',orderable: false, searchable: false},
            {data: 'title', name: 'title'},
            {data: 'slug', name: 'slug'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
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
    $(document).on('change', '.order_by', function () {
        var id = $(this).attr('data-bind');
        var order = $(this).val();
        $.ajax({
            url: "{{route('cms_pages.order')}}",
            type: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                "id": id,
                "order": order,
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
    });
});
</script>
@endsection