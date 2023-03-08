@if(Session::has('error'))

<div class="alert alert-danger alert-dismissible fade show" role="alert">

    <button class="close" data-dismiss="alert"><i class="fa fa-close"></i></button>

    Error !:&nbsp;{{ Session::get('error') }}

</div>

@endif

@if(Session::has('success'))

<div class="alert alert-success alert-dismissible fade show" role="alert">

    <button class="close" data-dismiss="alert"><i class="fa fa-close"></i></button>

    {{ Session::get('success') }}

</div>

@endif