@extends('front.layout.app')
@section('title', 'Blog')
@section('content')
    
    <link rel="stylesheet" href="{{ url('public/front/css/simple-list-without-sidebar.min.css') }}">

    <section class="inner-banner-section bg-primary">
        <div class="container">
            <div class="banner-content">
                <h1>Blog</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Blog</li>
                    </ol>
                </nav>
            </div>
        </div>
    </section>
    <section class="page-content">
        <div class="container">
            {{-- <div class="section-title mb-3">
                <h2 class="mb-0 text-center">Blog</h2>
            </div> --}}
            <div class="row">
                <div class="col-12 category">
                <!-- Heading-->

                <ul class="nav nav-pills mb-5" id="pills-tab" role="tablist">
                    
                    @if(count($category) != 0)
                        <li class="nav-item">
                          <a class="nav-link active category_id" data-id = "all" data-toggle="pill" href="javascript:" role="tab" aria-controls="all"
                            aria-selected="true">All</a>
                        </li>

                        @foreach($category as $cate)
                        <li class="nav-item">
                          <a class="nav-link category_id" data-id = "{{ $cate->id }}" data-toggle="pill" href="javascript:" role="tab"
                            aria-controls="{{ $cate->title }}" aria-selected="false">{{ $cate->title }}</a>
                        </li>
                        @endforeach
                    @else
                        {{-- <h3>There is no blog to show.</h3> --}}
                    @endif
                    

                </ul>

                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" role="tabpanel" aria-labelledby="all-tab">
                      <div class="row blogs_list">
                        
                      </div>
                    </div>
                  </div>
                </div>
            </div>

            <!-- Progress -->
            <div class="row">
              <div class="col-12 text-center">

                <!-- pagination -->
                <nav class="pagination justify-content-center align-items-center" id="pagination" style="margin-top: 40px !important;">
                  
                </nav>

              </div>
            </div>
              
        </div>
    </section>
@stop

@section('pagescript')
<script>
    function getBlogs($url= "{{ route('blogs-list') }}",$id = 'all'){
        $.ajax({
            url: $url,
            data: {id:$id},
        })
        .done(function($data) {
            $('.blogs_list').html($data.blogs);
            $('#pagination').html($data.pagination);
            //console.log($data.pagination);
            console.log("success");
        })
        .fail(function() {
            console.log("error");
        });
    }

    getBlogs();

    $(document).on('click', '.category_id', function(event) {
        event.preventDefault();
        
        $id = $(this).attr('data-id');

        getBlogs("{{ route('blogs-list') }}",$id);
    });

    $(document).on('click', '.page-link', function(event) {
        
        getBlogs($(this).attr('href'));
        event.preventDefault();
    });
</script>
@endsection
