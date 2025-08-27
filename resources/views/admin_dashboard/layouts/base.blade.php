<!DOCTYPE html>
<html @yield('html-attribute')>

<head>
    @include('admin_dashboard.layouts.partials/title-meta')

    @include('admin_dashboard.layouts.partials/head-css')
</head>

<body @yield('body-attribuet')>

    @yield('content')

    @include('admin_dashboard.layouts.partials/vendor-scripts')

</body>

</html>