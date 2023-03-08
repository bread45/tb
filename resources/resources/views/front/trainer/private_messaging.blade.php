@extends('front.trainer.layout.trainer')
@section('title', 'Private Messaging')
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
                        <h1 class="h3 text-muted font-weight-normal mb-0 mt-lg-0 mt-3 order-lg-0 order-3">Private Messaging</h1>
                        @include('front.trainer.layout.includes.header')
                    </div>

                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body px-0 pb-2">
                                    <div class="common-data-table messaging-data-table">
                                        <table id="private-messaging" class="display nowrap" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>Sr No</th>
                                                    <th>Date/Time</th>
                                                    <th>From(Customer)</th>
                                                    <th>Message</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                    <div class="row mt-4 mb-3">
                                        <div class="col-12 text-center">
                                            <a href="{{ route('trainer.private_messaging.send') }}" class="btn btn-danger btn-lg">Send Message</a>
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

@stop
@section('pagescript')
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
                ajax: "{{ route('trainer.private-messaging.getall') }}",
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
    