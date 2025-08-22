<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>{{ $title ?? 'GoFlyHabibi - Travel' }}</title>

  <!-- Favicon -->
  <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}"/>
    <link rel="stylesheet" href="{{ asset('css/personal.css') }}">
<!-- Latest Font Awesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- Flatpickr CSS & JS -->
<link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet" />
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Nice Select CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/css/nice-select.css" />
<meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Your CSS -->
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
 <link rel="stylesheet" href="{{ asset('css/flight-list.css') }}">

    <!--Custom CSS-->
    <link href="{{asset('css_gofly/style.css')}}" rel="stylesheet" type="text/css" />
  
    <link href="{{asset('fonts_gofly/flaticon.css')}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/longbill/jquery-date-range-picker@latest/dist/daterangepicker.min.css" />
    <script src="https://cdn.jsdelivr.net/gh/longbill/jquery-date-range-picker@latest/dist/jquery.daterangepicker.min.js"></script>

  @stack('styles')
</head>
<body>

  {{-- Include header --}}
  @include('layout_new.header')

  {{-- Page content --}}
  <main class="">
    @yield('content')
  </main>

  {{-- Include footer --}}
  @include('layout_new.footer')

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Nice Select JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/js/jquery.nice-select.min.js"></script>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
 
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
@stack('scripts')
</body>
</html>
