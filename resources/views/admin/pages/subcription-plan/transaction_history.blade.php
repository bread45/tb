@extends('admin.layouts.default')
@section('title', 'Providers Transaction History')
@section('content')

<link href="{{ asset('/theme/vendors/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
<!-- begin:: Content Head -->
<div class="kt-subheader  kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">Providers Transaction History</h3>
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
                        <th>Frist Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Subscription Plan</th>
                        <th>Price $</th>
                        <th>Start Date</th>
                        <th>Status</th>
                        <th>Cancel Date</th>
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
            ajax: "{{ route('subcriptionplan.gettransactionall') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'first_name',
                    name: 'first_name'
                },
                {
                    data: 'last_name',
                    name: 'last_name'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'plan_type',
                    name: 'plan_type'
                },
                {
                    data: 'amount',
                    name: 'amount'
                },
                {
                    data: 'start_date',
                    name: 'start_date'
                },
                {
                    data: 'subscription_status',
                    name: 'subscription_status'
                },
                {
                    data: 'cancel_date',
                    name: 'cancel_date'
                },
            ]
        });

       

        
    });
</script>
@endsection