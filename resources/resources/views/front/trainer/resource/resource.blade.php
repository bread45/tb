@extends('front.trainer.layout.trainer')
@section('title', 'Resource')
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
                        
                        <h1 class="h3 text-muted font-weight-normal mb-0 mt-lg-0 mt-3 order-lg-0 order-3">My Resources</h1>
                       
                        <a href="{{ route('resource.add')}}" class="btn btn-info ml-lg-auto mr-3 order-lg-1 order-4 mt-lg-0 mt-2">Add Resource</a>
                       
                        @include('front.trainer.layout.includes.header')
                    </div>

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

                                    <table id="resource-table" class="display nowrap" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th width="6%">Sr No</th>
                                                    <th width="28%">Categories</th>
                                                    <th width="12%">Title</th>
                                                    <th width="15%">Subtitle</th>
                                                    <th width="12%">Body Content</th>
                                                    <th width="12%">Tags</th>
                                                    <th width="15%">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            
                                                @foreach($resource as $k => $resource)
                                                <tr>
                                                    <td>{{$k+1}}</td>
                                                  
                                                    <td>
                                                    {{$resource->category}} 
                                                       
                                                    </td>
                                                    <td>
                                                    {{$resource->title}} 
                                                       
                                                    </td>
                                                    <td>
                                                    {{$resource->subtitle}} 
                                                       
                                                    </td>

                                                    <td>
                                                    {{strip_tags(str_replace('&nbsp;', ' ', $resource->description))}} 
                                                       
                                                    </td>

                                                    <td>
                                                    {{$resource->tags}} 
                                                       
                                                    </td>
                                        
                                                    <td>
                                                        <div class="d-flex actions-links">
                                                            <a href="{{ route('resource.edit',['resource'=> base64_encode($resource->id)]) }}" class="edit-link mr-2">Edit</a>
                                                            <a href="" class="view-link mr-2" data-toggle="modal"                     data-target=".bd-example-modal-lg{{ $resource->id }}">View</a>
                                                            <a href="{{ route('resource.delete',['resource'=> base64_encode($resource->id)]) }}" class="delete-link" onclick="return confirmation();" >Delete</a>
                                                            
                                                        </div>
                                                    </td>
                                                </tr>

                                                <div class="modal fade bd-example-modal-lg{{ $resource->id}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                  <div class="modal-content">
                                                    <div class="modal-header">
                                                      <h5 class="modal-title" id="exampleModalLabel">Resource Details</h5>
                                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                      </button>
                                                    </div>
                                                    <section class="pt-4 no_padding">
                                                      <div class="container">
                                                        <div class="row justify-content-center">
                                                          <div class="col-12 col-md-10">
                                                            @if($resource->format == 'Image')
                                                            <img class="img-fluid position_relative" src="@if(isset($resource->format_name) && !empty($resource->format_name)) {{ asset('front/images/resource/'.$resource->format_name)}} @else {{ asset('front/images/service_placeholder.png')}}  @endif" alt="...">
                                                        @endif
                                                        @if($resource->format == 'Video')
                                                            <a href="{{ $resource->format_name }}" target="_blank" id="resource_url_perview"><img class="img-fluid position_relative" src="@if(isset($resource->format_name) && !empty($resource->format_name)) {{ asset('front/images/resource/'.$resource->image_name)}} @else {{ asset('front/images/service_placeholder.png')}}  @endif" alt="..."></a>
                                                        @endif  
                                                        @if($resource->format == 'Article')
                                                            <a href="{{ asset('front/images/resource/'.$resource->format_name)}}" target="_blank" id="resource_url_perview"><img class="img-fluid position_relative" src="@if(isset($resource->format_name) && !empty($resource->format_name)) {{ asset('front/images/resource/'.$resource->image_name)}} @else {{ asset('front/images/service_placeholder.png')}}  @endif" alt="..."></a>
                                                        @endif 
                                                        @if($resource->format == 'EBook')
                                                            <a href="{{ asset('front/images/resource/'.$resource->format_name)}}" target="_blank" id="resource_url_perview"><img class="img-fluid position_relative" src="@if(isset($resource->format_name) && !empty($resource->format_name)) {{ asset('front/images/document.jpg')}} @else {{ asset('front/images/service_placeholder.png')}}  @endif" alt="..."></a>
                                                        @endif
                                                            <!--<img class="img-fluid position_relative" src="{{ asset('front/images/resource/'.$resource->format_name)}} " alt="...">
                                                            <div class="date_and_time_details"><span class="light_font"><?php echo date('M', strtotime($resource->created_at)) ?></span><br><?php echo date('d', strtotime($resource->created_at)) ?></div>-->
                                                            
                                                            

                                                            <!-- Image -->


                                                          </div>
                                                        </div>
                                                      </div>
                                                    </section>
                                                    <section class="py-4">
                                                      <div class="container">
                                                        <div class="row justify-content-center">
                                                          <div class="col-12 col-md-10">
                                                            <span class="short_text">{{ \Carbon\Carbon::parse($resource->created_at)->diffForHumans() }}</span>
                                                            <!-- Heading -->
                                                            <h3 class="mb-1 blog_title">{{ $resource->title }}</h3>
                                                            <h4 class="blog_subtitle">{{ $resource->name }}</h4>


                                                            <p class="mb-3">
                                                            <p style="text-align:justify"><span style="font-size:11pt"><span style="font-family:Arial"><span
                                                                    style="color:#000000">{{strip_tags(htmlspecialchars_decode($resource->description))}}</span></span></span></p>

                                                            

                                                          </div>
                                                        </div>
                                                      </div>
                                                    </section>

                                                  </div>
                                                </div>
                                              </div>


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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<style>
    label.radio-inline{
    margin: 0 10px 0 0;
    }
</style>
<script type="text/javascript">
function confirmation()
{ // event.preventDefault();
    return confirm("Are you sure you want to delete this resource?");
    // Swal.fire({
    // title: 'Are you sure?',
    // text: "You won't be able to revert this!",
    // icon: 'warning',
    // showCancelButton: true,
    // confirmButtonColor: '#3085d6',
    // cancelButtonColor: '#d33',
    // confirmButtonText: 'Yes, delete it!'
    // }).then((result) => {
    // if (result.value) {
    //     Swal.fire(
    //     'Deleted!',
    //     'Your file has been deleted.',
    //     'success'
    //     )
    // }
    // })

}

$(function () {
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

let elmButton = document.querySelector("#submit-stripe");
if (elmButton) {
  elmButton.addEventListener(
    "click",
    e => {
      elmButton.setAttribute("disabled", "disabled");
$.ajax({
            url: '{{ route('stripecreate.geturl') }}',
            type: 'GET',
            success: function (result) {
              
                 window.open(result, '_blank');
            }, error: function () {
                toastr.error("Permission Denied!");
            }
        });
//      fetch("{{ route('stripecreate.geturl') }}", {
//        method: "GET",
//        headers: {
//          "Content-Type": "application/json"
//        }
//      })
//         .then(data => {
//          if (data) {
//              alert(data);
//            window.location = data;
//          } else {
//            elmButton.removeAttribute("disabled");
//            elmButton.textContent = "<Something went wrong>";
//            console.log("data", data);
//          }
//        });
    },
    false
  );
}




    var table = $('#resource-table').DataTable({
                rowReorder: false,
                responsive: true,
                "bPaginate": true,
                "bLengthChange": true,
                "bFilter": true,
                "bInfo": true,
                "bSortable": true,
                "bAutoWidth": false,
                "columnDefs": [
                    { "orderable": false, "targets": 0 },
                    { "orderable": false, "targets": 6 }
                ],
                language: {
                    searchPlaceholder: "Search..",
                    "paginate": {
                       "previous": "Prev"
                    }
                },
                "order": [[ 0, "asc" ]],
                "aaSorting": [[ 1, 'asc' ], [ 2, 'asc' ], [ 3, 'asc' ]] ,
                "pageLength": 10
            });

            $(".alert-danger").delay(4000).slideUp(300);
            $(".alert-success").delay(4000).slideUp(300);

    $(document).on('click', '.featured_change', function () {
        var id = $(this).attr('data-id');
        $.ajax({
            url: id,
            type: 'GET',
            success: function (result) {
                if (result.status == true) {
                   // toastr.success(result.Message);
                    Swal.fire(
                     'Updated!',
                      result.Message,
                     'success'
                     );
                    table.draw(true);
                    setTimeout(function(){
                        window.location.reload(); 
                    },1000);
                } else {
                   // toastr.error(result.Message);
                    Swal.fire(
                     'Sorry!',
                      result.Message,
                     'error'
                     );
                }
            }
        });
    });

    $(document).on('click', '.status_change', function () {
        var id = $(this).attr('data-id');
        $.ajax({
            url: id,
            type: 'GET',
            success: function (result) {
                if (result.status == true) {
                   // toastr.success(result.Message);
                    Swal.fire(
                     'Updated!',
                      result.Message,
                     'success'
                     );
                    table.draw(true);
                    setTimeout(function(){
                        window.location.reload(); 
                    },1000);
                } else {
                   // toastr.error(result.Message);
                    Swal.fire(
                     'Sorry!',
                      result.Message,
                     'error'
                     );
                }
            }
        });
    });

   
});
</script>
@endsection

