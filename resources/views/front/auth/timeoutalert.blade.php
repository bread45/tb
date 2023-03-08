


<div class="container">
  <!-- Trigger the modal with a button -->
  <!-- <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Open Modal</button> -->

  <!-- Modal -->
  <div class="modal fade bd-example-modal-sm" id="myModal" role="dialog" aria-labelledby="mySmallModalLabel" data-keyboard="false">
    <div class="modal-dialog modal-sm">
    
      <!-- Modal content-->
      <div class="modal-content">
        <!-- <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"></h4>
        </div> -->
       <!--  <div class="modal-body">
          <p>Your Session has timed out due to inactivity.</p>
        </div>
        <div class="modal-footer">
          <a href="{{ url('/logout') }}"><button type="button" class="btn btn-default closed" data-dismiss="modal">OK</button></a>
        </div> -->
      </div>
      
    </div>
  </div>
  
</div>

<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

<script type="text/javascript">
    $(window).load(function()
    {
      console.log('asasaas');
	  var url      = window.location.href;
        //alert('Your session has been timed out. Please Login to continue.');
        window.location = url;
        //location.reload(); 
        //$('#myModal').modal('show');
    });

     $(".closed").click(function() {
       //alert('saass'); 
       location.reload();           
    });
</script>
