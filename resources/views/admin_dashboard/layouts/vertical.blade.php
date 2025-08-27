<!DOCTYPE html>
<html lang="en" @yield('html-attribute')>

<head>
    @include('admin_dashboard.layouts.partials/title-meta')

    @include('admin_dashboard.layouts.partials/head-css')
</head>

<body>

    <div class="app-wrapper">

        @include('admin_dashboard.layouts.partials/sidebar')

        @include('admin_dashboard.layouts.partials/topbar')

        <div class="page-content">

            <div class="container-fluid">

                @yield('content')

            </div>

            @include('admin_dashboard.layouts.partials/footer')
        </div>

    </div>

    @include('admin_dashboard.layouts.partials/vendor-scripts')


</body>

</html>