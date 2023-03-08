@if(isset($pagination))
  {{ $blogs->links() }}
@endif


@if($blogs && !isset($pagination))
  @foreach($blogs as $blog)
  <div class="col-12 col-md-6 mb-4">

    <!-- Card -->
    <div class="card border-0">

      <div class="badge card-badge">
        <time class="text-uppercase" datetime="{{ $blog->created_time }}">{{ date("M y",strtotime($blog->created_time)) }}</time>
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
@endif

@if(count($blogs) == 0 && !isset($pagination))
  <div class="col-12 col-md-12 mb-4 text-center">
    <h3>There is no blog to show.</h3>
  </div>
@endif