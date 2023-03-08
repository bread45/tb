@extends('front.trainer.layout.trainer')
@section('title', 'Add Resource Category')
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
                        <h1 class="h3 text-muted font-weight-normal mb-0 mt-lg-0 mt-3 order-lg-0 order-2">Add /Edit Category</h1>
                        
                        @include('front.trainer.layout.includes.header')
                    </div>

                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body p-lg-5">
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
                                
                                <form class="form-row add-resource-category-form" method="POST" action="{{route('resource_category.store')}}" enctype='multipart/form-data'>
                                        @csrf
                                        <input type="hidden" name="categoryId" value="@if(isset($resource_category->id)) {{ $resource_category->id }} @endif" />

                                        
                                        <div class="form-group col-lg-6">
                                            <label for="name-input">CATEGORY NAME</label> 
                                            <input type="text" class="form-control" name="name" required id="name-input" placeholder="enter category name" value="@if(isset($resource_category->name)){{ $resource_category->name }}@else{{old('name')}}@endif">
                                            @if ($errors->has('name'))
                                                <div style="display: block;" id="email-error" class="error invalid-feedback">{{ $errors->first('name') }}</div>
                                            @endif
                                        </div>
                                        
                                        <div class="col-lg-12 d-flex justify-content-center">
                                            <input type="submit" value="Save Details" class="btn btn-danger btn-lg" />
                                        </div>
                                    </form>
                                 
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
label.radio-inline{
  margin: 0 10px 0 0;
}
.checkRecurring{
 margin-right: 8px;  
 float: right;
 margin: 2px 10px;
}

</style>
<script>
function readURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    
    reader.onload = function(e) {
      $('#show_image').attr('src', e.target.result);
    }
    
    reader.readAsDataURL(input.files[0]); // convert to base64 string
  }
}

$("#service_image").change(function() {
  readURL(this);
});

$( document ).ready(function() {
if ($('input.checkRecurring').is(':checked')) { //console.log("checked");
    $('.recurring').show();
    $('#weekly-input').prop('required', true);
    //$('#monthly-input').prop('required', true);
    $('.singlePrice').hide();
    //$('#sprice-input').prop('required', false);
}else{
    $('.recurring').hide();
    $('#weekly-input').prop('required', false);
    //$('#monthly-input').prop('required', false);
    $('.singlePrice').show();
    //$('#sprice-input').prop('required', true);
}


$("input[name='isRecuring']").change(function() {
        if(this.checked) {
            $('.singlePrice').hide();
            //$('#sprice-input').prop('required', false);
            $('.recurring').show();
            $('#weekly-input').prop('required', true);
            //$('#monthly-input').prop('required', true);
        }else{
            $('.singlePrice').show();
            //$('#sprice-input').prop('required', true);
            $('.recurring').hide();
            $('#weekly-input').prop('required', false);
            //$('#monthly-input').prop('required', false);
        }
     });
});
</script>
@endsection