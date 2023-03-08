@extends('front.layout.app')
@section('title', 'Private Messaging')
@section('content')
<section class="inner-banner-section bg-primary">
        <div class="container">
            <div class="banner-content">
                <h1>Private Messaging</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">My Account</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Private Messaging</li>
                    </ol>
                </nav>
            </div>
        </div>
    </section>
 <section class="private-messaging">
        <div class="container">
            <div class="common-data-table messaging-data-table">
                <table id="private-messaging" class="display nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>Sr No</th>
                            <th>Date/Time</th>
                            <th>From(Trainer)</th>
                            <th>Message</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="row mt-5">
                <div class="col-12 text-center">
                    <a href="{{ route('customer.private_messaging.send') }}" class="btn btn-danger btn-lg">Send Message</a>
                </div>
            </div>
        </div>
    </section>


@stop
@section('pagescript')
<style>
#private-messaging_processing{
    margin-top:0px;
    background-color: #a19c9c;
    padding-top:7px;
}
</style>
    <script type="text/javascript">
        $(document).ready( function () {
         
            var table = $('#private-messaging').DataTable({
                rowReorder: {
                    selector: 'td:nth-child(2)'
                },
                responsive: true,
                "bPaginate": true,
                "bLengthChange": false,
                "bFilter": true,
                "bInfo": true,
                "bAutoWidth": false,
                processing: true,
                serverSide: true,
                ajax: "{{ route('private-messaging.getall') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex',orderable: false, searchable: false},
                    {data: 'date', name: 'date'},
                    {data: 'user_id', name: 'user_id'},
                    {data: 'message', name: 'message',orderable: false},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                "columnDefs": [
                    { "orderable": false, "targets": 0 }
                ],
                language: {
                    searchPlaceholder: "Search..",
                    "sEmptyTable":     "No any messages yet"
                },
                "order": [[ 1, "asc" ]],
                "pageLength": 5,
                "fnDrawCallback": function(oSettings) {
                    if (oSettings._iDisplayLength > oSettings.fnRecordsDisplay()) {
                        $(oSettings.nTableWrapper).find('.dataTables_paginate').hide();
                    }
                }
            });
        } );
    </script>
    @endsection
    