@extends('front.layout.app')
@section('title', $details->title)
@section('content')
    
    <link rel="stylesheet" href="{{ url('public/front/css/simple-list-without-sidebar.min.css') }}">

    <section class="inner-banner-section bg-primary">
        <div class="container">
            <div class="banner-content">
                <h1>{{ $details->title }}</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{url('blogs')}}">Blogs</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $details->title }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </section>
    {{-- <section class="page-content">
        <div class="container">
            <div class="section-title mb-3">
                <h2 class="mb-0 text-center">{{ $details->title }}</h2>
            </div>
        </div>
    </section> --}}

    <!-- Image -->
    <section class="pt-4">
        <div class="container">
            <div class="row">
                <div class="col-12">

                    <!-- Image -->
                    <img class="img-fluid" src="{{ url('/public/sitebucket/blog') }}/{{ $details->image }}" alt="...">

                </div>
            </div>
        </div>
    </section>

    <!-- Content -->
    <section class="py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-10">
                    <!-- Subheading -->
                    <h6 class="mb-3 text-muted font-weight-bold">
                        By {{ $details->created_by }} / {{ date('F d, Y',strtotime($details->created_time)) }}
                    </h6>
                    <!-- Heading -->
                    <h3 class="mb-3">{{ $details->sub_title }}</h3>

                    <p class="mb-3">
                       <?php echo $details->description ?>
                    </p>

                </div>
            </div>
        </div>
    </section>

    <!-- Related Blog section-->
    @if(count($blogs) > 0)
    <section class="related-blog py-4">
        <div class="container">
            <div class="row align-items-center mb-5">
                <div class="col-12">

                    <!-- Heading -->
                    <h2 class="text-center">Related in Blog</h2>

                </div>
            </div>
            <div class="row">
                
                    @foreach($blogs as $blog)
                    <div class="col-12 col-md-6 mb-4">

                    <!-- Card -->
                    <div class="card border-0">

                      <div class="badge card-badge">
                        <time class="text-uppercase" datetime="{{ $blog->created_time }}">{{ date("M j",strtotime($blog->created_time)) }}</time>
                      </div>

                      <a href="{{ route('blogs-details',$blog->slug) }}">
                        <img class="card-img-top" src="{{ url('/public/sitebucket/blog') }}/{{ $blog->image }}" alt="...">
                      </a>

                      <!-- Body -->
                      <div class="card-body pb-5">
                        
                        <h5 class="mb-2"><a class="text-inherit" href="{{ route('blogs-details',$blog->slug) }}">{{ $blog->title }}</a>
                        </h5>

                        <p>
                          {{ $blog->sub_title }}
                        </p>

                        <a class="btn-link text-info font-weight-bold" href="{{ route('blogs-details',$blog->slug) }}">
                          Read More <i class="fa fa-arrow-right ml-2"></i>
                        </a>

                      </div>


                    </div>

                    </div>
                    @endforeach
            </div>
        </div>
    </section>
    @endif


@stop
