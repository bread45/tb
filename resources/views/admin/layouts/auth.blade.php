<!DOCTYPE html>
<html lang="en">
    <head>
        @include('admin.theme.includes.head')
    </head>
    <body class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--fixed kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-page--loading">
        @yield('content')
        @include('admin.theme.includes.scriptlink')
        @yield('pagescript')
    </body>
</html>