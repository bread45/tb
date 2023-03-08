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

<section class="order-history-table" style="padding:15px 0 !important;">
        <div class="container">
            
                <h1>Order History</h1>
        </div>
</section>
<section class="order-history-table">
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
                            <th>Sr No</th>
                            <th>Time</th>
                            <th >Service Name</th>
                            <th >Service Category</th>
                            <th >Service Type</th>
                            <th >Business Information</th>
                            <th>Amount($)</th>
                            <th>Payment Date</th>
                            <th>Status</th>  
                        </tr>
                    </thead>
                    
                    <tbody>
                        @foreach($orders as $key => $order)
                        <?php 
                            $servicecategorytails = DB::table('trainer_services')->where(["id" => $order->service_id])->first();

                            $servicecategoryname = DB::table('services')->where(["id" => $servicecategorytails->service_id])->first();
                            $startDate = date('j F Y', strtotime($order->start_date));            
                            $endDate = date('j F Y',strtotime($order->end_date) );
                        ?>
                        <tr>
                            <td>{{$key + 1}}</td>
                            <td>
                                
                                
                                 {{$order->service_time}} 
                            </td>
                            <td> 
                                   {{$servicecategorytails->name}}         
                                                         
                            </td>
                            <td> 
                                       {{$servicecategoryname->name}}      
                                                       
                            </td>
                            <td> 
                                            
                                            {{$order->plan_type}}            
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="profile-img mr-2">
                                        @if($order->trainer->photo)
                                        <img src="{{asset('front/profile/'.$order->trainer->photo)}}" alt="{{$order->trainer->first_name}} {{$order->trainer->last_name}}" />
                                        @else
                                        <img src="{{asset('front/profile/default.png')}}" alt="{{$order->trainer->first_name}} {{$order->trainer->last_name}}" />
                                        @endif
                                        
                                    </div>
                                    <div class="profile-content">
                                        <h5>
                                        @if(!empty($order->trainer->business_name))
                                        {{$order->trainer->business_name}}
                                        @else
                                        {{$order->trainer->first_name}}
                                        {{$order->trainer->last_name}}
                                        @endif
                                        </h5>
                                        <p>
                                          {{$order->trainer->address}}
                                          {{$order->trainer->city}}
                                          {{$order->trainer->state}}<br/>
                                          {{$order->trainer->country}}
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
                                {{$order->amount}}
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
                                    <a href="{{route('customer.cancel.subscription',['order'=> base64_encode($order->id), 'subscriptionid' => base64_encode($order->stripe_subscription_id) ])}}" onclick="return confirm('Are you sure?')" class="cancelOrder">Cancel Subscription</a>
                                    @endif
                                @else
                                    @if(!empty($order->stripe_refund_id) )
                                    <span class="badge badge-danger">Cancelled</span>
                                        @if(!empty($order->refund_amount))
                                        <br/><span style="margin-top: 8px;" class="badge badge-light">Refunded: ${{$order->refund_amount}} USD</span>
                                        @endif
                                    @elseif(date('Y-m-d H:i:s',strtotime('+24 hours',strtotime($order->created_at))) > date('Y-m-d H:i:s'))
                                    <span class="badge badge-success" style="margin-bottom: 4px;">Confirmed</span>
                                   
                                    <a href="{{route('customer.cancel.order',['order'=> base64_encode($order->id), 'paymentid' => base64_encode($order->stripe_payment_id) ])}}" onclick="return confirm('Are you sure?')" class="cancelOrder">Cancel Order</a>
                                    @else
                                    <span class="badge badge-success">Confirmed</span>
                                    @endif
                                @endif
                            </td>
                           
                            <!--<td>
                                <div class="d-flex align-items-center justify-content-between">
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
                                    @if(isset($order->Ratting->rating))
                                    <a href="{{route('customer.review',base64_encode($order->id))}}" class="edit-rating-link">Edit</a>
                                    @else
                                    
                                        @if(Carbon\Carbon::createFromFormat('Y-m-d', $order->start_date) < Carbon\Carbon::today())
                                            @if(($order->stripe_subscription_id) && ($order->subscription_status != "canceled"))
                                            <a href="{{route('customer.review',base64_encode($order->id))}}" class="edit-rating-link">Add</a>
                                            @endif
                                            @if(($order->stripe_payment_id) && ($order->stripe_refund_id == null))
                                            <a href="{{route('customer.review',base64_encode($order->id))}}" class="edit-rating-link">Add</a>
                                            @endif
                                        @endif
                                    @endif
                                </div>
                            </td>-->
                            
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@stop
@section('pagescript')
<link rel="stylesheet" type="text/css" href="http://trainingblock.sgssys.info/public//front/trainer/css/responsive.css" />
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
    background: #17a2b8;
    padding: 3px 8px;
    border-radius: 4px;
    color: #FFF;
    font-size: 12px;
    border: solid 1px #CCC;
    margin-top: 8px;
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

