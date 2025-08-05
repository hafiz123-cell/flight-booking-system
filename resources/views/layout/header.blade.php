<!DOCTYPE html>
<html lang="zxx">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>{{ $title ?? 'Nepayatri - Tour & Travel Multipurpose Template' }}</title>

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
      <div class="modal-header bg-danger text-white rounded-top-4">
        <h5 class="modal-title text-white" id="welcomeModalLabel">Welcome to Nepayatri</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- White Body -->
      <div class="modal-body text-center py-4 px-4">
        <p class="fs-5 fw-semibold text-danger mb-3">
          Discover exclusive travel deals and unforgettable journeys.
        </p>
        <p class="text-muted mb-0">
          Create your account or log in to get started.
        </p>
      </div>

      <!-- Clean Footer -->
      <div class="modal-footer justify-content-center border-0 pb-4">
        <a href="{{ route('login.show') }}" class="btn btn-danger px-4 me-2">Login</a>
        <a href="{{ route('register.show') }}" class="btn btn-outline-danger px-4">Register</a>
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
      <div class="header-content">
        <div class="container">
          <div class="links links-left">
            <ul>
              <li>
                <a href="#"><i class="fa fa-phone-alt"></i> (000)999-898-888</a>
              </li>
              <li>
                <a href="#"><i class="fa fa-envelope-open"></i> info@Nepayatri.com</a>
              </li>
              <li>
                <select>
                  <option>EUR</option>
                  <option>FRA</option>
                  <option>ESP</option>
                </select>
              </li>
            </ul>
          </div>
          <div class="links links-right pull-right">
            <ul>
              <li>
                <ul class="social-links">
                  <li>
                    <a href="#"><i class="fab fa-facebook" aria-hidden="true"></i></a>
                  </li>
                  <li>
                    <a href="#"><i class="fab fa-twitter" aria-hidden="true"></i></a>
                  </li>
                  <li>
                    <a href="#"><i class="fab fa-instagram" aria-hidden="true"></i></a>
                  </li>
                  <li>
                    <a href="#"><i class="fab fa-linkedin" aria-hidden="true"></i></a>
                  </li>
                </ul>
              </li>
              <li>
                <a href="{{route('login.show')}}" data-toggle="modal" data-target="#login"><i class="fa fa-sign-in-alt"></i> Login</a>
              </li>
              <li>
                <a href="{{route('register.show')}}"><i class="fa fa-sign-out-alt"></i> Register</a>
              </li>
              <li>
                <div class="header_sidemenu">
                  <div class="menu">
                    <div class="close-menu">
                      <i class="fa fa-times white"></i>
                    </div>
                    <div class="m-contentmain">
                      <div class="m-logo mar-bottom-30">
                        <img src="{{asset('images/logo-white.png')}}" alt="m-logo" />
                      </div>

                      <div class="content-box mar-bottom-30">
                        <h3 class="white">Get In Touch</h3>
                        <p class="white">
                          We must explain to you how all seds this mistakens idea off denouncing pleasures and praising pain was born and I will give you a
                          completed accounts..
                        </p>
                        <a href="#" class="biz-btn biz-btn1">Consultation</a>
                      </div>

                      <div class="contact-info">
                        <h4 class="white">Contact Info</h4>
                        <ul>
                          <li><i class="fa fa-map-marker-alt"></i> Travel 26, Old Brozon Mall, Newyrok NY 10005</li>
                          <li><i class="fa fa-phone-alt"></i>555 626-0234</li>
                          <li><i class="fa fa-envelope-open"></i>support@travel.com</li>
                          <li><i class="fa fa-clock"></i> Week Days: 09.00 to 18.00 Sunday: Closed</li>
                        </ul>
                      </div>
                    </div>
                  </div>

                  <div class="mhead">
                    <span class="menu-ham"><i class="fa fa-bars white"></i></span>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <!-- Navigation Bar -->
      <div class="header_menu affix-top">
        <nav class="navbar navbar-default">
          <div class="container">
            <div class="navbar-flex">
              <!-- Brand and toggle get grouped for better mobile display -->
              <div class="navbar-header">
                <a class="navbar-brand" href="{{route('home')}}">
                  <h1 class="header-logo" style="font-size: 32px; font-weight: 600; letter-spacing: 0px; color: #ec7b34; ">GoFlyHabibi</h1>
                </a>
              </div>
              <!-- Collect the nav links, forms, and other content for toggling -->
              <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav" id="responsive-menu">
                  <li class="dropdown submenu">
                    <a href="{{route('home')}}" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"
                      >Home</a>
                    
                  </li>
                  <li class="submenu dropdown active">
                    <a href="index-flights.html" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"
                      >Flights <i class="fa fa-angle-down" aria-hidden="true"></i
                    ></a>
                    <ul class="dropdown-menu">
                      <li><a href="index-flights.html">Flight Home</a></li>
                      <li><a href="flightlist.html">Flight Grid</a></li>
                      <li><a href="flightlist-1.html">Flight List</a></li>
                      <li><a href="flight-single.html">Flight Detail</a></li>
                      <li><a href="flight-booking.html">Booking</a></li>
                      <li><a href="flight-confirm.html">Thank You</a></li>
                    </ul>
                  </li>
                  <li class="submenu dropdown">
                    <a href="index-hotel.html" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"
                      >Hotel <i class="fa fa-angle-down" aria-hidden="true"></i
                    ></a>
                    <ul class="dropdown-menu">
                      <li><a href="index-hotel.html">Hotel Home</a></li>
                      <li class="submenu dropdown">
                        <a href="hotellist-1.html" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"
                          >Hotel Lists<i class="fa fa-angle-right" aria-hidden="true"></i
                        ></a>
                        <ul class="dropdown-menu">
                          <li><a href="hotellist-1.html">Hotel List 1</a></li>
                          <li><a href="hotellist-2.html">Hotel List 2</a></li>
                        </ul>
                      </li>
                      <li class="submenu dropdown">
                        <a href="hotelsingle-1.html" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"
                          >Hotel Detail<i class="fa fa-angle-right" aria-hidden="true"></i
                        ></a>
                        <ul class="dropdown-menu">
                          <li><a href="hotelsingle-1.html">Hotel Single 1</a></li>
                          <li><a href="hotelsingle-2.html">Hotel Single 2</a></li>
                        </ul>
                      </li>
                      <li><a href="hotel-booking.html">Hotel Booking</a></li>
                    </ul>
                  </li>
                 
                  <li class="submenu dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"
                      >Pages <i class="fa fa-angle-down" aria-hidden="true"></i
                    ></a>
                    <ul class="dropdown-menu">
                      
                      <li class="submenu dropdown">
                        <a href="about.html" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"
                          >About Us <i class="fa fa-angle-right" aria-hidden="true"></i
                        ></a>
                        <ul class="dropdown-menu">
                           <li><a href="about.html">About Us</a></li>
                          <li><a href="about1.html">About Us 1</a></li>
                        </ul>
                      </li>
                      
                      <li class="submenu dropdown">
                        <a href="contact.html" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"
                          >Contact Us <i class="fa fa-angle-right" aria-hidden="true"></i
                        ></a>
                        <ul class="dropdown-menu">
                          <li><a href="contact.html">Contact Us</a></li>
                          <li><a href="contact1.html">Contact Us 1</a></li>
                        </ul>
                      </li>
                      <li class="submenu dropdown">
                        <a href="404.html" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"
                          >Error<i class="fa fa-angle-right" aria-hidden="true"></i
                        ></a>
                        <ul class="dropdown-menu">
                          <li><a href="404.html">Error Page 1</a></li>
                          <li><a href="404-1.html">Error Page 2</a></li>
                        </ul>
                      </li>
                     
                      <li><a href="hotel-booking.html">Booking</a></li>
                      <li><a href="confirmation.html">Confirmation</a></li>
                      <li><a href="testimonial.html">Testimonials</a></li>
                      <li><a href="terms.html">Terms</a></li>
                      <li><a href="faq.html">FAQ</a></li>
                    </ul>
                  </li>
                 
                  <li class="submenu dropdown">
                    <a href="dashboard.html" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"
                      >Dashboard <i class="fa fa-angle-down" aria-hidden="true"></i
                    ></a>
                    <ul class="dropdown-menu">
                      <li><a href="dashboard.html">Dashboard</a></li>
                      <li><a href="dashboard-my-profile.html">User Profile</a></li>
                      <li><a href="dashboard-list.html">User Wishlist</a></li>
                      <li><a href="dashboard-messages.html">User Messages</a></li>
                      <li><a href="dashboard-history.html">Booking History</a></li>
                      <li><a href="dashboard-add-new.html">Add New</a></li>
                      <li><a href="dashboard-hotel-list.html">Hotel List</a></li>
                      <li><a href="dashboard-reviews.html">Dashboard Reviews</a></li>
                    </ul>
                  </li>
                  
                  <li class="dropdown submenu">
                    <a href="#search1" class="mt_search"><i class="fa fa-search"></i></a>
                  </li>
                  
                </ul>
              </div>
              <!-- /.navbar-collapse -->
              <div id="slicknav-mobile"></div>
            </div>
          </div>
          <!-- /.container-fluid -->
        </nav>
        
      </div>
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
            border: 1px solid #dc3545;
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
      <!-- Navigation Bar Ends -->
    </header>
    <!-- header ends -->