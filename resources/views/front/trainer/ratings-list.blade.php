@extends('front.trainer.layout.trainer')
@section('title', 'Ratings & Reviews List')
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
                <h1 class="h3 text-muted font-weight-normal mb-0 mt-lg-0 mt-3 order-lg-0 order-2">Ratings & Reviews List</h1>
                
                @include('front.trainer.layout.includes.header')
            </div>
            @if($providerStatus[0]->is_subscription)
                    @if($trailingProviderOrders < 1)
                    @if($providerOrdersCount < 1)
                    <div class="popup popup-danger text-center" role="alert">
                    Your subscription is cancelled and your page will not be visible to the public until you activate your account again, You can reactivate your subscription in the account information tab.
                    </div>
                    @endif
                    @endif
                    @endif
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body px-0 pb-2">
                            <div class="common-data-table">
                                @if (session('message'))
                                <div class="alert alert-success">
                                    {{ session('message') }}
                                </div>
                                @endif
                                @if (session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                                @endif
                                <div class="container-fluid">
                                <table id="myTable" class="display nowrap" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th width="2%">SI.No</th>
                                            <th width="15%">Date</th>
                                            <th width="18%">Client Information</th>
                                            <th width="12%">Title</th>
                                            <th width="25%">Rating</th>
                                            <th width="26%">Review</th>
                                            <th width="10%">Action</th>
                                        </tr>
                                    </thead>
                                    
                                    <tbody>
                                    @if($ratings)
                                    @foreach($ratings as $key => $rating)
                                    
                                    
                                        <tr>
                                            <td>{{$key + 1}}</td>
                                            <td>{{ date('m-d-Y', strtotime($rating->created_at)) }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="profile-img mr-2">
                                                    @if($rating->user->photo)
                                                    <img src="{{asset('front/profile/'.$rating->user->photo)}}" alt="{{$rating->user->first_name}} {{$rating->user->last_name}}" />
                                                    @else
                                                    <img src="{{asset('front/profile/default.png')}}" alt="{{$rating->user->first_name}} {{$rating->user->last_name}}" />
                                                    @endif
                                                   
                                                    </div>
                                                    <div class="profile-content">
                                                        <h5>
                                                            {{$rating->user->first_name}} 
                                                            {{$rating->user->last_name}}
                                                        </h5>
                                                        <p>
                                                        {{$rating->user->address_1}}
                                                        {{$rating->user->city}}
                                                        {{$rating->user->state}}
                                                        {{$rating->user->country}}
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
                                                    <a href="{{ route('trainer.ratings.delete',base64_encode($rating->id)) }}" class="delete-link" onclick="return confirmation();" >Delete</a>
                                                </div>
                                            </td>
                                            
                                        </tr>
                                        @endforeach
                                        @else 
                                            <tr><td colspan="7">No ratings & reviews yet</td></tr>
                                            @endif
                                       
                                    </tbody>
                                </table>
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
@endsection

@section('pagescript')
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
            "bLengthChange": true,
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
                "paginate": {
                   "previous": "Prev"
                },
                "sEmptyTable":     "No any ratings & reviews yet"
            },
            "pageLength": 10,
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

