<!DOCTYPE html>
<html lang="en">
    <head>
        @include('admin.theme.includes.head')
    </head>
    <body class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--fixed kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-page--loading">        
        @include('admin.theme.includes.headerlogo')
        <div class="kt-grid kt-grid--hor kt-grid--root">
            <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-page">
                @include('admin.theme.includes.sidebar')
                <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper">
                    @include('admin.theme.includes.header')
                    <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
                        @yield('content')

                        <!-- begin::Scrolltop -->
                        <div id="kt_scrolltop" class="kt-scrolltop">
                            <i class="fa fa-arrow-up"></i>
                        </div>
                    </div>
                    @include('admin.theme.includes.footer')
                </div>
            </div>
        </div>    
        @include('admin.theme.includes.scriptlink')
        @yield('pagescript')
    </body>
</html>