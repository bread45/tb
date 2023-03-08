@extends('front.layout.app')
@section('title', 'Ratings & Reviews List')
@section('content')

<section class="inner-banner-section bg-primary">
        <div class="container">
            <div class="banner-content">
                <h1>Ratings & Reviews List</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">My Account</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Your Reviews</li>
                    </ol>
                </nav>
            </div>
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
                
                <table id="myTable" class="display nowrap " style="width:100%">
                    <thead>
                        <tr>
                            <th>SN</th>
                            <th>Date Time</th>
                            <th>Business Information</th>
                            <th>Title</th>
                            <th>Ratings</th>
                            <th>Reviews</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot style="display: table-header-group;">
                        <tr>
                            <th></th>
                            <th id="Date_Time">Date Time</th>
                            <th id="client">Business Information</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </tfoot>
                    <tbody>
                    @foreach($ratings as $key => $rating)
                    
                        <tr>
                            <td>{{$key + 1}}</td>
                            <td>{{ date('j F Y', strtotime($rating->created_at)) }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="profile-img mr-2">
                                        @if($rating->trainer->photo)
                                        <img src="{{asset('front/profile/'.$rating->trainer->photo)}}" alt="{{$rating->trainer->first_name}} {{$rating->trainer->last_name}}" />
                                        @else
                                        <img src="{{asset('front/profile/default.png')}}" alt="{{$rating->trainer->first_name}} {{$rating->trainer->last_name}}" />
                                        @endif
                                        
                                    </div>
                                    <div class="profile-content">
                                        <h5>
                                        @if(!empty($rating->trainer->business_name))
                                        {{$rating->trainer->business_name}}
                                        @else
                                        {{$rating->trainer->first_name}}
                                        {{$rating->trainer->last_name}}
                                        @endif
                                        </h5>
                                        <p>
                                          {{$rating->trainer->address}}
                                          {{$rating->trainer->city}}
                                          {{$rating->trainer->state}}<br/>
                                          {{$rating->trainer->country}}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td>{{$rating->title}}</td>
                            <td>
                                <div class="d-flex align-items-center justify-content-start">
                                    @if(!empty($rating->rating))
                                    {{-- <div class="tooltip-wrap"> --}}
                                    <div id="triangle-down"></div>
                                        <div class="rating">
                                            <ul class="nav">
                                            @if(isset($rating->rating))
                                                @for($i=1;$i<=$rating->rating;$i++) 
                                                <li><img src="{{asset('/front/images/star.png')}}" alt="Rating" /></li>
                                                @endfor
                                                @for($i=5;$i>$rating->rating;$i--) 
                                                <li><img src="{{asset('/front/images/star-blank.png')}}" alt="Rating" /></li>
                                                @endfor    
                                            @endif
                                            </ul>   
                                        </div>
                                    {{-- </div> --}}
                                    
                                    @endif
                                </div>
                            </td>
                            <td>{{$rating->description}}</td>
                            <td>
                                <div class="d-flex actions-links">
                                <a href="{{route('customer.review',base64_encode($rating->trainer->id))}}" class="edit-rating-link">Edit</a>
                                    <a href="{{ route('customer.ratings.delete',base64_encode($rating->id)) }}" class="delete-link" style="margin-left: 5px;" onclick="return confirmation();" >Delete</a>     
                                </div>
                                
                            </td>
                            
                        </tr>
                        @endforeach
                       
                    </tbody>
                </table>

            </div>
        </div>
    </section>
@stop
@section('pagescript')
<link rel="stylesheet" type="text/css" href="http://trainingblock.sgssys.info/public/front/trainer/css/responsive.css" />
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
    display:block;
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

<script type="text/javascript">
    $(".alert-danger").delay(4000).slideUp(300);
    $(".alert-success").delay(4000).slideUp(300);

    function confirmation(){
        return confirm("Are you sure you want to delete this ratings & reviews?");
    }
    $(document).ready(function() { 
            // Setup - add a text input to each footer cell 
            $('#myTable tfoot th').each( function () 
            { //console.log($(this).index());
                if($(this).index() == 1 || $(this).index() == 2){
                    var title = $('#myTable tfoot th').eq( $(this).index() ).text(); 
                 $(this).html( '<input type="text" style="width: 100%" placeholder=" '+title+'" />' ); 
                }
                
            }); 
           // DataTable 
           var table = $('#myTable').DataTable({
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
                { "width": 180, "targets": 2 },
                { "width": 80, "targets": 4 },
                { "orderable": false, "targets": 0 },
            ],
            fixedColumns: true,
            language: {
                searchPlaceholder: "Search..",
                "sEmptyTable":     "No any ratings & reviews yet"
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

