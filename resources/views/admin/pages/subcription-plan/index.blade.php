@extends('admin.layouts.default')
@section('title', 'Subscription Plans')
@section('content')

<link href="{{ asset('/theme/vendors/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
<!-- begin:: Content Head -->
<div class="kt-subheader  kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">Subscription Plans</h3>
        </div>
        <!-- <div class="kt-subheader__toolbar">
            <a href="{{route('subcriptionplan.create')}}" class="btn btn-primary btn-bold"><i class="fa fa-plus"></i> Add New</a>
        </div> -->
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
                        <th>No</th>
                        <th>Subscription Plan</th>
                        <th>Price $</th>
                        <th>Free Trial Period Month</th>
                        <th width="100px">Action</th>
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
<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
<script>
    $(function() {
        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('subcriptionplan.getall') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'subcription_plan',
                    name: 'subcription_plan'
                },
                {
                    data: 'price',
                    name: 'price'
                },
                {
                    data: 'free_trial_months',
                    name: 'free_trial_months'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });

        $(document).on('click', '.status_change', function() {
            var id = $(this).attr('data-id');
            $.ajax({
                url: id,
                type: 'GET',
                success: function(result) {
                    if (result.status == true) {
                        toastr.success(result.Message);
                        table.draw(true);
                    } else {
                        toastr.error(result.Message);
                    }
                }
            });
        });

        $(document).on('click', '.delete_contactus', function() {
            var id = $(this).attr('data-id');
            swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                animation: false,
                customClass: 'animated tada',
                confirmButtonText: 'Yes, delete it!'
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        url: id,
                        type: 'DELETE',
                        data: {
                            "_token": "{{ csrf_token() }}",
                        },
                        success: function(result) {
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