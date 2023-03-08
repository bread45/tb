@extends('front.layout.app')
@section('title', 'Event Calendar')
@section('content')
<section class="inner-banner-section bg-primary">
    <div class="container">
        <div class="banner-content">
            <h1>Event Calendar</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Event Calendar</li>
                </ol>
            </nav>
        </div>
    </div>
</section> 

<section class="page-content event_calendar">
     <div class="container">

            <div class="row justify-content-center">
            
                <div class="col-12 mb-5">
                    <form action="{{ route('search-event')}}" method="post" id="eventListForm">
                        @csrf
                       <input type="hidden" name="search" value="1">
                       <div class="row">
                           <div class="col-md-3 searchBar">
                           <div class="form-group search-group">
                             <input type="text" name="keyword" id="keyword" class="form-control search-control" placeholder="Search by Keyword" value ="{{request('keyword')}}">
                           </div>
                           </div>
                           <div class="col-md-3 searchBar">
                           <div class="form-group">
                              <input type="text" id="location" name="location" class="form-control" placeholder="Location" autocomplete="off" value ="{{request('location')}}"/>
                            </div>
                           </div>
                           <div class="col-md-3 searchBar">
                           <div class="form-group">
                              <input type="text" id="start_date" name="start_date" class="form-control" placeholder="Event Date" autocomplete="off" value="{{request('start_date')}}" />
                            </div>
                           </div>
                           <div class="col-md-3 searchBar">
                           <div class="form-group search-group">
                           <select name="category" id="category" class="form-control service-control">
                               <option value="">Category</option>
                               <option @if($category == "Running event") selected @endif value="Running event">Running event</option>
                               <option @if($category == "Cycling event") selected @endif value="Cycling event">Cycling event</option>
                               <option @if($category == "Triathlon event") selected @endif value="Triathlon event">Triathlon event</option>
                               <option @if($category == "Other group sport event") selected @endif value="Other group sport event">Other group sport event</option>
                               <option @if($category == "Workshop") selected @endif value="Workshop">Workshop</option>
                               <option @if($category == "Webinar") selected @endif value="Webinar">Webinar</option>
                               <option @if($category == "Training Camp") selected @endif value="Training Camp">Training Camp</option>
                           </select>
                           </div>
                           </div>
                           <div class="col-md-12 d-flex justify-content-center">
                            <input type="submit" name="event_filter" id="event_filter" class="text-center btn btn-info" value="SEARCH" />
                            <!--<input type="submit" name="event_filter"  id="event_filter" value="Search" class="text-center btn btn-info" style="margin-right:5px;">-->
                           </div>
                           <div class="col-md-12 d-flex justify-content-center">
                           <a href="{{ route('event-calendar') }}" class="reset_link">Reset</a>
                           </div>
                       </div>
                    </div>
                    </form>
                </div>
                                   
                <table id="eventCalendarTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                                <tr class="headTable">
                                <th class="EventHead"></th>
                                <!-- <th class="dateHead">Date</th>
                                <th class="dateLocation">Location</th> -->
                                </tr>
                        </thead>
                        <tbody>

                        @foreach($Listevents as $Listevent)
                        <?php 
                            $attendeesCount = DB::table('event_registration')
                            ->join('front_users', 'event_registration.attender_id', '=', 'front_users.id' )
                            ->where('event_registration.event_id', '=', $Listevent->id)->get();
								        ?>
                                <tr>
                                  <td class="event_cell">
                                  <a href="{{ url('/event-details/'.base64_encode($Listevent->id)) }}">
                                  <div class="row event_section">
                                    <div class="col-md-2">
                                    @if($Listevent->photo != NULL)
                                    <img class="event_img" src="{{ asset('front/profile/'.$Listevent->photo) }}">
                                    @else 
                                    <img class="event_img" src="{{ asset('front/images/events_default.png') }}">
                                    @endif
                                    </div>
                                    <div class="col-md-10">
                                    <p class="event_datetime"><?php echo date('l, M d, Y', strtotime($Listevent->start_date)) ?> - {{ $Listevent->start_time }}</p>
                                    <h4>{{ $Listevent->title }}</h4>
                                    <p>{{ $Listevent->business_name }} - @if($Listevent->venue){{ $Listevent->venue }}@else Virtual @endif</p>
                                    @if( count($attendeesCount) != 0 )
                                      @if(count($attendeesCount) == 1)
                                        <p class="event_attendees_count"><?php echo count($attendeesCount); ?> Attendee</p>
                                      @else
                                      <p class="event_attendees_count"><?php echo count($attendeesCount); ?> Attendees</p>
                                    @endif
                                    @endif
                                    </div>
                                  </div>
                                  </a>
                                </td>
                                </tr>  
                        @endforeach           
                        </tbody>
                </table>

	</div>	
</div>
            </div>
            
          </div>
</section>
@endsection
<style>
#event_filter{
    font-size: 14px !important;
    height: auto !important;
    line-height: 3 !important;
    padding: 0px 14px !important;
}
.event_cell a:hover {
  text-decoration : none;
}
.datepicker-days .table-condensed {
  width: 100%;
}
.searchBar{
  max-width: 100% !important;
}
.event_attendees_count {
    font-weight: 700;
    color: #00ab91;
    margin-bottom: 5px;
}
.event_datetime {
    text-transform: uppercase;
    font-weight: 700;
    color: #00ab91;
    margin-bottom: 5px;
}
.event_img{
  width: 100%;
  height: auto;
  border-radius: 5px;
}
.event_section {
  padding : 0 10px;
}
.event_cell {
  border-bottom : 1px solid #d7d7d7 !important;
}
#eventCalendarTable td {
    padding: 20px 5px !important;
}
.headTable th {
  text-align : left !important;
  display: none;
}

.headTable {
    background: #00a990!important;
}


.EventHead, .dateHead, .dateLocation{
    width: 237px;
    color: #fff;
    font-weight: 400;
    font-size: 15px;
}
#eventCalendarTable {
  font-size : 15px;
}

tr:hover {
  transform: scale(1);
  -webkit-transform: scale(1);
  -moz-transform: scale(1);
  box-shadow: 0px 0px 5px rgba(0,0,0,0.3);
  -webkit-box-shadow: 0px 0px 5px rgba(0,0,0,0.3);
  -moz-box-shadow: 0px 0px 5px rgba(0,0,0,0.3);
}

#eventCalendarTable_previous, #eventCalendarTable_next {
  width: 100px;
}
.pagination{
  display: flex;
  justify-content: center;
}
.paginate_button {
  width: 50px;  
  line-height: 40px;
  text-align: center;
  border: 1px solid #DDDDDD;
  display: inline-block;
  text-decoration: none;
  color: black;
}
.paginate_button :not(:first-child){
  margin-left: 5px;
}
.paginate_button :first-child{
  border-radius: 10px 0 0 10px;
}
.paginate_button :last-child{
  border-radius: 0 10px 10px 0;
}
.paginate_button :hover{
  background-color: #DDDDDD;
}
.pagination  .active{
  background-color: #00a990	 !important;
}

.pagination .active a {
  color : #fff;
}
.input-sm{
    margin-left: 5px;
}
.dateLocation{
    width: 20%;   
}
.dateHead{
    width: 30%;    
}
.timeStyle{
    color: #007463;
}
table, tr, td{
 border:none!important;
}
.dataTables_filter{
     display: none !important;
    margin-bottom: 15px;
 } 
 .dataTables_length, .dataTables_info, .dt-buttons > .btn-default{
     display: none;
 }  
 th::after {
  display: none !important;
}
.headTable{
        background: #FFC0CB;
}
.headTable > th {
        text-align: center;
}
tbody > tr{
 background: #fff !important;
}
 
</style>
@section('pagescript')



<!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css" />


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.colVis.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
<script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>


<script src='https://unpkg.com/@fullcalendar/core@4.4.0/main.min.js'></script>
<script src='https://unpkg.com/@fullcalendar/interaction@4.4.0/main.min.js'></script>
<script src='https://unpkg.com/@fullcalendar/daygrid@4.4.0/main.min.js'></script>
<script src='https://unpkg.com/@fullcalendar/timegrid@4.4.0/main.min.js'></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/plug-ins/1.11.1/sorting/currency.js"></script>

<script type="text/javascript"
        src="https://maps.google.com/maps/api/js?key={{ env('GOOGLE_MAP_KEY') }}&libraries=places" ></script>
    <script>
        $(document).ready(function () {
            $("#latitudeArea").addClass("d-none");
            $("#longtitudeArea").addClass("d-none");
        });
    </script>
    <script>
        google.maps.event.addDomListener(window, 'load', initialize);
  
        function initialize() {
            var input = document.getElementById('location');
            var autocomplete = new google.maps.places.Autocomplete(input);
  
            autocomplete.addListener('place_changed', function () {
                var place = autocomplete.getPlace();
                $('#latitude').val(place.geometry['location'].lat());
                $('#longitude').val(place.geometry['location'].lng());
  
                $("#latitudeArea").removeClass("d-none");
                $("#longtitudeArea").removeClass("d-none");
            });
        }
    </script>

<script>
$(document).ready(function() {
	//Only needed for the filename of export files.
	//Normally set in the title tag of your page.
	document.title='Event Calendar';
	// DataTable initialisation
	$('#eventCalendarTable').DataTable(
		{
			"dom": '<"dt-buttons"Bf><"clear">lirtp',
			"paging": true,
      "sorting" : false,
			"autoWidth": true,
			"buttons": [
				'colvis',
				'copyHtml5',
        'csvHtml5',
				'excelHtml5',
        'pdfHtml5',
				'print'
			]
		}
	);
});

</script>

<script>
   $('#start_date').datepicker({
        weekStart: 1,
        todayHighlight: true,
        autoclose: true,
        format: 'mm/dd/yyyy',
    });
    

</script>
<script>
$(document).ready(function(){
$( ".dataTables_empty" ).text("No Events Found!");
});

</script>
@endsection