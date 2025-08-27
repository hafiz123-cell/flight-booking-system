<!DOCTYPE html>
<html lang="zxx">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>{{ 'GoFlyHabibi - Tour & Travel Multipurpose Template' }}</title>

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('images/favicon.png') }}" />

    <!-- Default CSS -->
    <link href="{{ asset('css/default.css') }}" rel="stylesheet" type="text/css" />

    <!-- Custom CSS -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet" type="text/css" />

    <!-- Color Switcher CSS -->
    <link rel="stylesheet" href="{{ asset('css/color/color-default.css') }}" />

    <!-- Plugin CSS -->
    <link href="{{ asset('css/plugin.css') }}" rel="stylesheet" type="text/css" />

    <!-- Flaticons CSS -->
    <link href="{{ asset('fonts/flaticon.css') }}" rel="stylesheet" type="text/css" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css" />

  </head>

  <body>
<!-- Success Alert -->
@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3" style="z-index: 1050; width: 320px;">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

<!-- Error Alert -->
@if($errors->any())
  <div class="alert alert-danger alert-dismissible fade show position-fixed top-0 end-0 mt-5 m-3" style="z-index: 1050; width: 320px;">
    <ul class="mb-0 ps-3">
      @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

<!-- On Load Modal -->
<!-- Modal -->
<div class="modal fade" id="welcomeModal" tabindex="-1" aria-labelledby="welcomeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow-lg border-0 rounded-4">
      <!-- Red Header -->
      <div class="modal-header text-white rounded-top-4" style="background-color: #ec7b34;">
        <h5 class="modal-title text-white" id="welcomeModalLabel">Welcome to GoFlyHabibi</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- White Body -->
      <div class="modal-body text-center py-4 px-4">
        <p class="fs-5 fw-semibold mb-3" style="color: #ec7b34;">
          Discover exclusive travel deals and unforgettable journeys.
        </p>
        <p class="text-muted mb-0">
          Create your account or log in to get started.
        </p>
      </div>

      <!-- Clean Footer -->
      <div class="modal-footer justify-content-center border-0 pb-4">
        <a href="{{ route('login.show') }}" class="btn px-4 me-2 text-white" style="background-color:#ec7b34;">Login</a>
        <a href="{{ route('register.show') }}" class="btn px-4" style="border-color:#ec7b34; color:#ec7b34;">Register</a>
      </div>
    </div>
  </div>
</div>


    <!-- Preloader -->
    <div id="preloader">
      <div id="status"></div>
    </div>
    <!-- Preloader Ends -->

    <!-- header starts -->
    <header class="main_header_area">
      <!-- Navigation Bar -->
      <div class="">
        <div class="container">
            <div class="header-flex">
                <!-- Left: Logo / Heading -->
                <div class="header-logo"> 
                  <a href="{{ route('home') }}">
                    <h1>GoFlyHabibi</h1>
                  </a>
                </div>

                <!-- Right: Navigation -->
                <nav class="header-nav">
                    <ul>
                       <li>
    <a href="{{ route('home') }}" class="{{ url()->current() == route('home') ? 'active' : '' }}">
        <i class="fa fa-home"></i> Home
    </a>
</li>

                        <li><a href="/flight"><i class="fa fa-plane"></i> Flights</a></li>
                        <li><a href="/search-hotels"><i class="fa fa-hotel"></i> Hotel</a></li>
                       @auth
    @if(Auth::user()->role === 'user')
        <li>
            <a href="/user/profile"><i class="fa fa-user"></i> {{ Auth::user()->name }}</a>
        </li>
    @endif
@else
    <li>
        <a href="{{ route('register.show') }}"><i class="fa fa-user"></i> Login or Signup</a>
    </li>
@endauth

                    </ul>
                </nav>
            </div>
        </div>
    </div>
  </header>
<style> 

 .badge-purple {
    background-color: #6f42c1;
    color: white;
}

  .highlight {
            background-color: yellow;
            font-weight: bold;
            display: inline;
            /* Ensures it stays on the same line */
        }

        .custom-location-select .optionsList {
            display: none;
            position: absolute;
            background: #fff;
            border: 1px solid #ccc;
            width: 100%;
            z-index: 1000;
        }

        .custom-location-select {
            position: relative;
        }

        .custom-radio {
            width: 14px;
            height: 14px;
            border-radius: 50%;
            border: 1px solid #999;
            background-color: #fff;
            appearance: none;
            -webkit-appearance: none;
            outline: none;
            cursor: pointer;
            position: relative;
        }

        .custom-radio:checked {
            background-color:  #ec7b34;
            /* Red when selected */
            border: 1px solid #ec7b34;
        }

        .form-check-label {
            margin-left: 8px;
            font-size: 14px;
            cursor: pointer;
        }

        .filter-box .form-check-input[type=radio] {
            padding-left: 0px;
        }
    </style>

    <!-- CSS to make radio buttons circle -->
    <style>
        .traveler-dropdown {
            display: none;
            overflow: hidden;
            max-height: 0;
            transition: max-height 0.4s ease, opacity 0.4s ease;
            opacity: 0;
        }

        .traveler-dropdown.show {
            display: block;
            max-height: 500px;
            /* enough height to show content */
            opacity: 1;
        }

        .traveler-dropdown.hide {
            max-height: 0;
            opacity: 0;
            display: block;
        }

        .traveler-dropdown {
            display: none;
            overflow: hidden;
            max-height: 0;
            /* Start with a max height of 0 */
            opacity: 0;
            /* Start with zero opacity */
            transition: max-height 0.4s ease, opacity 0.4s ease;
        }

        .traveler-dropdown.show {
            max-height: 500px;
            /* Adjust this based on your content */
            opacity: 1;
            display: block;
            /* Make sure it's displayed before transitioning */
        }

        .traveler-dropdown.hide {
            max-height: 0;
            opacity: 0;
        }
    </style>


    <!-- This styling is for input search group -->
    <style>
        .niceSelectWrapper {
            position: relative;
            width: 100%;
        }

        .searchInput {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            cursor: pointer;
        }

        .optionsList {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            border: 1px solid #ccc;
            background: white;
            max-height: 200px;
            overflow-y: auto;
            z-index: 99;
            border-radius: 0 0 5px 5px;
        }

        .option {
            padding: 10px;
            cursor: pointer;
        }

        .option:hover {
            background: #f5f5f5;
        }
     
  .flatpickr-day.disabled {
    color: #ccc !important;
    background: #f8f8f8;
    text-decoration: line-through;
    cursor: not-allowed;
  }

  .dropdown-menu {
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
  }
  .traveler-btn {
    width: 30px;
    height: 30px;
    text-align: center;
    padding: 0;
    font-size: 18px;
  }


  /* Dropdown card styling */
  .dropdown-menu {
    width: 300px !important;
    background-color: white;
    border: 1px solid #ec7b34;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    padding: 15px;
  }

  /* Red plus/minus buttons */
  .traveler-btn {
    color:  #ec7b34;
    border: 1px solid  #ec7b34;
    background-color: white;
    transition: all 0.3s ease;
  }

  /* Hover effect on buttons */
  .traveler-btn:hover {
    background-color:  #ec7b34;
    color: white;
  }

  /* Optional spacing and styling inside card */
  .dropdown-menu .d-flex {
    margin-bottom: 10px;
  }

  /* Class dropdown styling */
  #travelClass {
    border: 1px solid #ec7b34;
    color:  #ec7b34;
  }

  #travelClass:focus {
    border-color:  #ec7b34;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
  }

  /* Dropdown label color (optional) */
  .dropdown-menu strong {
    color:  #ec7b34;
  }

</style>

 <style>
  
        .main_header_area {
          
            background: rgba(0, 0, 0, 0.6); /* transparent black */
            padding: 20px 0;
            height: 126px;
            position: absolute;
        }

        .header-flex {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top:10px;
        }

        .header-logo h1 {
           font-size: 32px;
    font-weight: 600;
    letter-spacing: 0px;
    color: #ec7b34;
    padding-top:10px;
        }
 .header-nav {
  position: relative;
 }
        .header-nav ul {

            list-style: none;
            margin: 10px; !important;
           
            display: flex;
            gap: 20px;
        }

        .header-nav ul li {
            display: inline-block;
           
        }

        .header-nav ul li a {
            color: #fff;
            text-decoration: none;
            font-size: 18px;
            transition: color 0.3s;
            font-weight: 300;
            padding:10px;
        }

      .header-nav ul li a.active {
    color: #ec7b34;
    font-weight: 600;
}
       
    </style>

    <!-- header ends -->





      <style>
        .highlight {
            background-color: yellow;
            font-weight: bold;
            display: inline;
            /* Ensures it stays on the same line */
        }

        .custom-location-select .optionsList {
            display: none;
            position: absolute;
            background: #fff;
            border: 1px solid #ccc;
            width: 100%;
            z-index: 1000;
        }

        .custom-location-select {
            position: relative;
        }

        .custom-radio {
            width: 14px;
            height: 14px;
            border-radius: 50%;
            border: 1px solid #999;
            background-color: #fff;
            appearance: none;
            -webkit-appearance: none;
            outline: none;
            cursor: pointer;
            position: relative;
        }

        .custom-radio:checked {
            background-color: #ec7b34;
            /* Red when selected */
            border: 1px solid #ec7b34;
        }

        .form-check-label {
            margin-left: 8px;
            font-size: 14px;
            cursor: pointer;
        }

        .filter-box .form-check-input[type=radio] {
            padding-left: 0px;
        }
    </style>

    <!-- CSS to make radio buttons circle -->
    <style>
        .traveler-dropdown {
            display: none;
            overflow: hidden;
            max-height: 0;
            transition: max-height 0.4s ease, opacity 0.4s ease;
            opacity: 0;
        }

        .traveler-dropdown.show {
            display: block;
            max-height: 500px;
            /* enough height to show content */
            opacity: 1;
        }

        .traveler-dropdown.hide {
            max-height: 0;
            opacity: 0;
            display: block;
        }

        .traveler-dropdown {
            display: none;
            overflow: hidden;
            max-height: 0;
            /* Start with a max height of 0 */
            opacity: 0;
            /* Start with zero opacity */
            transition: max-height 0.4s ease, opacity 0.4s ease;
        }

        .traveler-dropdown.show {
            max-height: 500px;
            /* Adjust this based on your content */
            opacity: 1;
            display: block;
            /* Make sure it's displayed before transitioning */
        }

        .traveler-dropdown.hide {
            max-height: 0;
            opacity: 0;
        }
    </style>


    <!-- This styling is for input search group -->
    <style>
        .niceSelectWrapper {
            position: relative;
            width: 100%;
        }

        .searchInput {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            cursor: pointer;
        }

        .optionsList {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            border: 1px solid #ccc;
            background: white;
            max-height: 200px;
            overflow-y: auto;
            z-index: 99;
            border-radius: 0 0 5px 5px;
        }

        .option {
            padding: 10px;
            cursor: pointer;
        }

        .option:hover {
            background: #f5f5f5;
        }
    </style>

    <style>
        .nav-tabs .nav-link.active {
            background-color: #ec7b34;
            color: #fff;
            padding: 18px 36px;
            border-radius: 12px 12px 0 0;
            transition: all 0.3s ease;
        }

        .nav-tabs .nav-link {
            background-color: #fff;
            color: #7b7b7b;
            cursor: pointer;
            border: none;
            padding: 10px 15px 8px;
            border-radius: 8px 8px 0 0;
            transition: all 0.3s ease;
        }
    </style>


