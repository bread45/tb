@extends('front.trainer.layout.trainer')
@section('title', 'Order History')
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
                        <h1 class="h3 text-muted font-weight-normal mb-0 mt-lg-0 mt-3 order-lg-0 order-2">Order History</h1>
                        
                        @include('front.trainer.layout.includes.header')
                    </div>

                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body px-0 pb-2">
                                    <div class="common-data-table">
                                        <table id="order-history-table" class="display nowrap" style="width:100%">
                                            <thead>
                                                <tr>
                                                <th width="2%">SN</th>
                                                <th width="15%">Date_Time</th>
                                                <th width="12%">Service</th>
                                                <th width="20%">Client Information</th>
                                                <th width="12%">Amount</th>
                                                <th width="13%">Payment Date</th>
                                                <th width="6%">Status</th>
                                                <th width="16%">Review/Rating</th>
                                                </tr>
                                            </thead>
                                            <tfoot style="display: table-header-group;">
                                                <tr>
                                                    <th width="2%" ></th>
                                                    <th width="15%" id="Date_Time">Date/Time</th>
                                                    <th width="12%" id="service">Service</th>
                                                    <th width="20%" id="client">Client Information</th>
                                                    <th width="12%"></th>
                                                    <th width="13%"></th>
                                                    <th width="6%" id="status">Status</th>
                                                    <th width="16%"></th>
                                                </tr>
                                            </tfoot>
                                            <tbody>
                                            @foreach($orders as $key => $order)
                                                <?php 
                                               $startDate = date('j F Y', strtotime($order->start_date));            
                                                $endDate = date('j F Y',strtotime($order->end_date) );
                                                ?>
                                            
                                                <tr>
                                                    <td>{{$key + 1}}</td>
                                                    <td>
                                                    @if(($startDate == $endDate) || $order->stripe_subscription_id)
                                                    {{$startDate}}
                                                    @else
                                                    {{$startDate}} - {{$endDate}} 
                                                    @endif 
                                                    <br/> {{$order->service_time}}</td>
                                                    <td>{{$order->service->name}} <br/> 
                                                    <!-- <span class="badge badge-light">${{$order->service->price}} USD</span><br/> -->
                                                    <!-- ${{$order->service->price}} USD <br/> -->
                                                    {{$order->service->format}}
                                                       
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="profile-img mr-2">
                                                            @if($order->users->photo)
                                                            <img src="{{asset('front/profile/'.$order->users->photo)}}" alt="{{$order->users->first_name}} {{$order->users->last_name}}" />
                                                            @else
                                                            <img src="{{asset('front/profile/default.png')}}" alt="{{$order->users->first_name}} {{$order->users->last_name}}" />
                                                            @endif
                                                           
                                                            </div>
                                                            <div class="profile-content">
                                                                <h5>
                                                                    {{$order->users->first_name}} 
                                                                    {{$order->users->last_name}}
                                                                </h5>
                                                                <p>
                                                                {{$order->users->address_1}}
                                                                {{$order->users->city}}
                                                                {{$order->users->state}}
                                                                {{$order->users->country}}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                    @if($order->stripe_subscription_id)
                                                        @if($order->plan_type == "monthly")
                                                        ${{$order->service->price_monthly}} USD Monthly
                                                        @endif
                                                        <!-- @if($order->plan_type == "weekly")
                                                        ${{$order->service->price_weekly}} USD Weekly
                                                        @endif -->
                                                    @else
                                                        ${{$order->amount}} USD
                                                    @endif
                                                    </td>
                                                    <td>
                                                    @if($order->stripe_subscription_id)
                                                    {{ date('j F Y',strtotime($order->start_date)) }}
                                                    @else
                                                    {{ date('j F Y',strtotime($order->created_at)) }}
                                                    @endif
                                                    </td>
                                                    <td>
                                                        @if($order->stripe_subscription_id)
                                                        <?php 
                                                            $TrainerServicesdata = \App\StripeAccounts::where('user_id',$order->trainer_id)->first();
                                                                Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY')); 
                                                                if($order->subscription_status !== "canceled"){
                                                                    $subscription = \Stripe\Subscription::retrieve(
                                                                        $order->stripe_subscription_id, ['stripe_account' => $TrainerServicesdata->stripe_user_id]
                                                                    );
                                                                }

                                                                
                                                                // dd($subscription);
                                                            ?>
                                                            @if($order->subscription_status == "canceled")
                                                            <span class="badge badge-danger">Cancelled</span>
                                                            @else
                                                            <span class="badge badge-success">{{ucfirst($subscription->status)}}</span>
                                                            @endif
                                                        @else
                                                            @if(!empty($order->stripe_refund_id) )
                                                                <span class="badge badge-danger">Cancelled</span><br/>
                                                                @if(!empty($order->refund_amount))
                                                                <span class="badge badge-light">Refunded: ${{$order->refund_amount}} USD</span>
                                                                @endif
                                                            @else
                                                                <span class="badge badge-success">Confirmed</span>
                                                            @endif
                                                        @endif

                                                        
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center justify-content-start">
                                                            @if(!empty($order->Ratting))
                                                            <div class="tooltip-wrap">
                                                            <div id="triangle-down"></div>
                                                                <div class="rating">
                                                                    <ul class="nav">
                                                                    @if(isset($order->Ratting->rating))
                                                                        @for($i=1;$i<=$order->Ratting->rating;$i++) 
                                                                        <li><img src="{{asset('/front/images/star.png')}}" alt="Rating" /></li>
                                                                        @endfor
                                                                        @for($i=5;$i>$order->Ratting->rating;$i--) 
                                                                        <li><img src="{{asset('/front/images/star-blank.png')}}" alt="Rating" /></li>
                                                                        @endfor    
                                                                    @endif
                                                                    </ul>   
                                                                </div>
                                                          
                                                                    <div class="tooltip-content">
                                                                        <h5>{{$order->Ratting->title}}</h5>
                                                                        <p>{{$order->Ratting->description}}</p>
                                                                        
                                                                    </div> 
                                                            </div>
                                                            
                                                            @endif
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
                    
                    @include('front.trainer.layout.includes.footer')

                </div>
            </div>
        </div>
@endsection

@section('pagescript')
<style>
table{
  margin: 0 auto;
  width: 100%;
  clear: both;
  border-collapse: collapse;
  table-layout: fixed; 
  word-wrap:break-word; 
}
.common-data-table table.dataTable tbody td{
    white-space: inherit; 
}
    #triangle-down{
    width: 0;
    height: 0;
    border-left: 10px solid transparent;
    border-right: 10px solid transparent;
    border-top: 15px solid #fff;
    right: 0;
    right: 5px;
    bottom: 0;
}
    .tooltip-wrap {
  position: relative;
}
#triangle-down{
    display:none;
}
.tooltip-wrap .tooltip-content {
  display: none;
  position: absolute;
    bottom: 100%;
    /* left: 0; */
    right: 5%;
    background-color: #fff;
    padding: 1em;
    width: 350px;
    border-radius: 5px;
    border: solid 1px #e7e6e6;
}
.tooltip-wrap:hover .tooltip-content, .tooltip-wrap:hover #triangle-down {
  display: block;
}


</style>
<script type="text/javascript">
// Define the select input variables globally
var date_Time = '';
var service = '';
var client = '';
var status = '';
        // $(document).ready( function () {
        //     var table = $('#order-history-table').DataTable({
        //         rowReorder: false,
        //         responsive: true,
        //         "bPaginate": true,
        //         "bLengthChange": false,
        //         "bFilter": true,
        //         "bInfo": true,
        //         "bAutoWidth": false,
        //         "columnDefs": [
        //             { "orderable": false, "targets": 0 }
        //         ],
        //         language: {
        //             searchPlaceholder: "Search..",
        //             "paginate": {
        //                "previous": "Prev"
        //             }
        //         },
        //         "pageLength": 9,    
        //     });
            
        // } );



        $(document).ready(function() { 
                // Setup - add a text input to each footer cell 
                $('#order-history-table tfoot th').each( function () 
                { //console.log($(this).index());
                    if($(this).index() == 1 || $(this).index() == 2 || $(this).index() == 3 || $(this).index() == 6){
                        var title = $('#order-history-table tfoot th').eq( $(this).index() ).text(); 
                     $(this).html( '<input type="text" style="width: 100%" placeholder=" '+title+'" />' ); 
                    }
                    
                }); 
               // DataTable 
               var table = $('#order-history-table').DataTable({
                rowReorder: false,
                responsive: {
                    details: {
                        type: 'inline'
                    }
                },
                "bPaginate": true,
                "bLengthChange": false,
                "bFilter": true,
                "bInfo": true,
                "bAutoWidth": false,
                "columnDefs": [
                    { "orderable": false, "targets": 0 },
                    { "width": 18, "targets": 0 },
                    { "width": 100, "targets": 1 },
                    { "width": 150, "targets": 3 },
                    { "width": 100, "targets": 2 },
                    { "width": 80, "targets": 4 },
                    { "width": 110, "targets": 5 },
                    { "width": 120, "targets": 6 },
                    { "width": 120, "targets": 7},
                ],
                fixedColumns: true,
                language: {
                    searchPlaceholder: "Search..",
                    "paginate": {
                       "previous": "Prev"
                    },
                    "sEmptyTable":     "No any orders yet"
                },
                "pageLength": 9,
                "fnDrawCallback": function(oSettings) {
                    if (oSettings._iDisplayLength > oSettings.fnRecordsDisplay()) {
                        $(oSettings.nTableWrapper).find('.dataTables_paginate').hide();
                    }
                }   
                }); 

               // Apply the search 
               table.columns().eq( 0 ).each( function ( colIdx ) 
                { 
                    $( 'input', table.column( colIdx ).footer() ).on( 'keyup change', function () 
                    {
                    table .column( colIdx ) .search( this.value ) .draw(); 
                    }); 
                }); 
        });
    </script>

@endsection

