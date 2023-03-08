@extends('front.layout.app')
@section('title', 'Order History')
@section('content')

<section class="inner-banner-section bg-primary">
        <div class="container">
            <div class="banner-content">
                <h1>Order History</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">My Account</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Order History</li>
                    </ol>
                </nav>
            </div>
        </div>
</section>

<section class="order-history-table no_padding" style="padding:15px 0 !important;">
        <div class="container">
            
                <h1>Order History</h1>
        </div>
</section>
<section class="order-history-table no_padding">
        <div class="container">

            <div class="common-data-table table-responsive">
                @if (session('message'))
                    <div class="alert alert-success">
                    {{ session('message') }}
                    </div>
                @endif

                <table id="order-history-table" class="display nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>SI.No</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th >Service Name</th>
                            <th >Service Category</th>
                            <th >Service Type</th>
                            <th >Business Information</th>
                            <th>Amount($)</th>
                            <th>Status</th>  
                        </tr>
                    </thead>
                    
                    <tbody>
                        @foreach($orders as $key => $order)
                        <?php 
                            $servicecategorytails = DB::table('trainer_services')->where(["id" => $order->service_id])->first();

                            if(!empty($servicecategorytails->service_id)){
                                $servicecategoryname = DB::table('services')->where(["id" => $servicecategorytails->service_id])->first();
                            }
                            $startDate = date('j F Y', strtotime($order->start_date));            
                            $endDate = date('j F Y',strtotime($order->end_date) );
                            $total_amnt = $order->amount+$order->admin_fees;
                        ?>
                        <tr>
                            <td>{{$key + 1}}</td>
                            <td style="width: 10%">
                            @if($order->start_date != '0000-00-00')
                            {{ date('m-d-Y',strtotime($order->start_date)) }}
                            @elseif($order->appointment_date != '0000-00-00')
                            {{ date('m-d-Y',strtotime($order->appointment_date)) }}
                            @else
                            {{ date('m-d-Y',strtotime($order->created_at)) }}
                            @endif
                            </td>
                            <td>
                                
                                
                                 {{$order->service_time}} 
                            </td>
                            <td> @if(!empty($servicecategorytails->name))
                                   {{$servicecategorytails->name}}         
                                            @endif             
                            </td>
                            <td> @if(!empty($servicecategorytails->service_id))
                                       {{$servicecategoryname->name}}      
                                                       @endif
                            </td>
                            <td> 
                                            
                                            {{$order->plan_type}}            
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="profile-img mr-2">
                                        @if(!empty($order->trainer->photo))
                                        @if($order->trainer->photo)
                                        <img src="{{asset('front/profile/'.$order->trainer->photo)}}" alt="{{$order->trainer->first_name}} {{$order->trainer->last_name}}" />
                                        @else
                                        <img src="{{asset('front/profile/default.png')}}" alt="{{$order->trainer->first_name}} {{$order->trainer->last_name}}" />
                                        @endif
                                        @else
                                        <img src="{{asset('front/images/details_default.png')}}" alt="" />
                                        @endif
                                        
                                    </div>
                                    <div class="profile-content">
                                        <h5>
                                        @if(!empty($order->trainer->business_name))
                                        {{$order->trainer->business_name}}
                                       
                                        @endif
                                        </h5>
                                        <p>
                                            @if(!empty($order->trainer->business_name))
                                          {{$order->trainer->address}}
                                          {{$order->trainer->city}}
                                          {{$order->trainer->state}}<br/>
                                          {{$order->trainer->country}}
                                          @endif
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td>
                            @if($order->stripe_subscription_id)
                                @if($order->plan_type == "In person - Monthly Membership" || $order->plan_type == "Virtual - Monthly Membership")
                                ${!! number_format((float)($total_amnt), 2) !!} USD Monthly
                                @endif
                                 @if($order->plan_type == "In person - Yearly Membership" || $order->plan_type == "Virtual - Yearly Membership")
                                 ${!! number_format((float)($total_amnt), 2) !!} USD Yearly
                                @endif 
                            @else
                            
                                @if($order->amount != '0.00')
                                ${!! number_format((float)($total_amnt), 2) !!} USD
                                @else
                                ${{$order->amount}} USD
                                @endif
                            
                            @endif
                            </td>
                            <?php 
                                if($order->appointment_date == '0000-00-00'){
                                    $appointment_date = date('Y-m-d H:i:s');
                                } else {
                                    $appointment_date = $order->appointment_date.' '.$order->service_time.':00';
                                }
                            ?>
                            @if($order->amount != '0.00')
                            <td style="width: 15%;">
                                
                                
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
                                    <span class="badge badge-success">{{ucfirst($subscription->status)}}</span><br>
                                    <a href="{{route('customer.cancel.subscription',['order'=> base64_encode($order->id), 'subscriptionid' => base64_encode($order->stripe_subscription_id) ])}}" onclick="return confirm('Are you sure?')" class="cancelOrder">Cancel Subscription</a>
                                    @endif
                                @else
                                    @if(!empty($order->stripe_refund_id) )
                                    <span class="badge badge-danger">Cancelled</span>
                                        @if(!empty($order->refund_amount))
                                        <br/><span style="margin-top: 8px;" class="badge badge-light">Refunded: ${{$order->refund_amount}} USD</span>
                                        @endif
                                    @elseif(date('Y-m-d H:i:s',strtotime('-24 hours',strtotime($appointment_date))) > date('Y-m-d H:i:s'))
                                    @if($order->status == 4 )
                                    <span class="badge badge-dark" style="margin-bottom: 10px;">Waiting For Provider Approval</span>
                                    @elseif($order->status == 5)
                                    <span class="badge badge-danger" style="margin-bottom: 10px;">Rejected by Provider</span>
                                    @else
                                    <span class="badge badge-success" style="margin-bottom: 10px;">Confirmed</span><br>
                                    @endif
                                   @if($order->type == 'order' && $order->plan_type != 'Package Deal')
                                    <span><a href="{{route('customer.cancel.order',['order'=> base64_encode($order->id), 'paymentid' => base64_encode($order->stripe_payment_id) ])}}" onclick="return confirm('Are you sure?')" class="cancelOrder">Cancel Order</a></span>
                                    @endif
                                    
                                    @else
                                    @if($order->status == 4 )
                                    <span class="badge badge-dark" style="margin-bottom: 10px;">Waiting For Provider Approval</span>
                                    @elseif($order->status == 5)
                                    <span class="badge badge-danger" style="margin-bottom: 10px;">Rejected by Provider</span>
                                    @else
                                    <span class="badge badge-success">Confirmed</span>
                                    @endif
                                    @endif
                                @endif
                            </td>
                           @else
                            <td>
                               @if($order->status == 4 )
                                    <span class="badge badge-dark" style="margin-bottom: 10px;">Waiting For Provider Approval</span>
                                @elseif($order->status == 5)
                                    <span class="badge badge-danger" style="margin-bottom: 10px;">Rejected by Provider</span>
                                @else
                                    <span class="badge badge-success">Confirmed</span>
                                @endif
                            </td>
                          @endif
                            
                            
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@stop
@section('pagescript')
<link rel="stylesheet" type="text/css" href="https://trainingblockusa.com/public//front/trainer/css/responsive.css" />
<style>
table{
  margin: 0 auto;
  width: 100%;
  clear: both;
  border-collapse: collapse;
  /* table-layout: fixed;  */
  word-wrap:break-word; 
}
.common-data-table table.dataTable tbody td{
    white-space: inherit; 
}
.cancelOrder{
    background: #00ab91;
    padding: 5px 10px;
    border-radius: 4px;
    color: #FFF;
    font-size: 12px;
    border: solid 1px #CCC;
    margin-top: 8px;
    line-height: 38px;
    /* display:block; */
}
.cancelOrder:hover{
    color: #FFF; 
}
td.details_control{
    background: url('../public/images/select-arrow.png') no-repeat center center;
    cursor: pointer;
}
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
.dataTables_filter{
    float:right;
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
.badge {
    padding: 8px 12px;
    font-size: 11px;
    
}
</style>

<script src="{{asset('../front/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('../front/js/dataTables.rowReorder.min.js')}}"></script>
<script src="{{asset('../front/js/dataTables.responsive.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css" />
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>

<script type="text/javascript">



        $(document).ready( function () {
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

            var table = $('#order-history-table').DataTable({
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
                 buttons: [
                    {
                        extend: 'excel',
                        title: 'Order History',
                        exportOptions: {
                            columns: [0,1,2,3,4,5,6,7]
                        }
                    }
                ],
                drawCallback: function() {
                    var hasRows = this.api().rows({ filter: 'applied' }).data().length > 0;
                    $('.buttons-excel')[0].style.visibility = hasRows ? 'visible' : 'hidden'
                  },
                "order": [[ 0, "asc" ]],
                "aaSorting": [[ 2, 'asc' ]] ,
                "pageLength": 10
                
            });

            

            
        } );

        function confirmation(){
                Swal.fire({
                title: 'Are you sure?',
                text: "You want to cancel this order!",
                icon: 'warning',
                width: 500,
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes',
                cancelButtonText: 'No'
                }).then((result) => {
                if (!result.value) {
                    console.log("No");
                    event.preventDefault();
                   // $(this).parents('form').submit();
                   // document.getElementById('cancel-order-form').submit();
                    // Swal.fire(
                    // 'Cancelled!',
                    // 'Your order has been cancelled.',
                    // 'success'
                    // )
                }
                })

            // if(confirm('Are you sure?')){
            //     document.getElementById('delete-form').submit();
            // }else{
            //     return false;
            // }   
        }
    </script>



@endsection

