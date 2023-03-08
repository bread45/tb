@extends('front.trainer.layout.trainer')
@section('title', 'Availability Management')
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
              <h1 class="h3 text-muted font-weight-normal mb-0 mt-lg-0 mt-3 order-lg-0 order-2">Availability</h1>
              
              @include('front.trainer.layout.includes.header')
          </div>

          <div class="row mb-4">
              <div class="col-12">
                  <div class="card">
                      <div class="card-body p-lg-3 pb-lg-5 p-2 pb-3">
                          <section class="pagenotfound-section">
                            <div class="container text-center">
                                <h2>Coming soon...</h2>
                                <a href="{{url('/')}}" class="btn btn-link">Back to Home</a>
                            </div>
                        </section>
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
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@endsection

