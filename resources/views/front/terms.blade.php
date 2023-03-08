@extends('front.layout.app')
@section('title', 'Terms & Conditions')
@section('content')
<section class="inner-banner-section bg-primary">
        <div class="container">
            <div class="banner-content">
                <h1>{{$cmsdata->title}}</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{$cmsdata->title}}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </section>
<section class="page-content">
        <div class="container">
            <div class="section-title mb-3">
                <h5 class="text-danger">{{$cmsdata->title}}</h5>
                <h2 class="mb-0">{{$cmsdata->sub_title_text}}</h2>
            </div>
            <div class="row">
                <div class="col-12">
                     <?php echo $cmsdata->description; ?>
                </div>
            </div>
        </div>
</section>

@stop
