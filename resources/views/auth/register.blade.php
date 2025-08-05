<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="zxx">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
<meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Nepayatri - Tour & Travel Multipurpose Template</title>
    <!-- Favicon -->
   <!-- Favicon -->
<link rel="shortcut icon" type="image/x-icon" href="{{ asset('images/favicon.png') }}" />
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

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
    <!--Font Awesome-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css" />
  </head>
  <body>

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
               <a href="#" data-toggle="modal" data-target="#loginModal"><i class="fa fa-sign-in-alt"></i> Login</a>
              </li>
              {{-- <li>
                <a href="#" data-toggle="modal" data-target="#register"><i class="fa fa-sign-out-alt"></i> Register</a>
              </li> --}}
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
                  <li class="submenu dropdown">
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

      <!-- Navigation Bar Ends -->
    </header>
    <!-- header ends -->

    <!-- Breadcrumb -->
    <section class="breadcrumb-outer text-center">
      <div class="container">
        <div class="breadcrumb-content">
          <h2 class="white">Register</h2>
          <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Register</li>
            </ul>
          </nav>
        </div>
      </div>
      <div class="overlay"></div>
    </section>
    <!-- BreadCrumb Ends -->

<div class="container mt-4">
  <div class="row justify-content-center">
    <div class="col-lg-6 col-md-8 col-sm-12">
      <div class="login-content text-center">
        <h4>Create a Nepayatri Account</h4>

        <!-- Laravel AJAX Registration Form -->
        <form  method="POST" action="{{ route('register') }}">
          @csrf

 <div class="form-group mb-3">
  <input 
    type="text" 
    name="name" 
    class="form-control @error('name') is-invalid @enderror" 
    placeholder="Enter name" 
    value="{{ old('name') }}" 
    
  >
  
  @error('name')
    <small class="text-danger" id="error-name">{{ $message }}</small>
  @enderror
</div>


  <div class="form-group mb-3">
 <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"  placeholder="Enter email"  value="{{ old('email') }}" >
           
  @error('email')
    <small class="text-danger" id="error-name">{{ $message }}</small>
  @enderror
          
   </div>

  <div class="form-group mb-3">
 <input type="password" name="password"  placeholder="Enter password"  class="form-control @error('password') is-invalid @enderror">

  @error('password')
    <small class="text-danger" id="error-name">{{ $message }}</small>
  @enderror
  </div>

 <div class="form-group mb-3">
  <input 
    type="password" 
    name="password_confirmation" 
    class="form-control @error('password_confirmation') is-invalid @enderror" 
    placeholder="Confirm password"
  >

  @error('password_confirmation')
    <small class="text-danger">{{ $message }}</small>
  @enderror
</div>


          <div class="form-btn mar-bottom-20">
            <input type="submit" class="biz-btn biz-btn1" value="sign up">
          </div>
           <ul class="social-links mb-4">
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
        </form>

        <div id="success-message" style="color: green;"></div>
      </div>
    </div>
  </div>
</div>
    </section>
    <!-- Login Ends -->

    <!-- footer starts -->
    <footer>
      <div class="footer-upper pad-bottom-50">
        <div class="container">
          <div class="row">
            <div class="col-lg-4 col-sm-12 col-12">
              <div class="footer-about">
                <div class="footer-about-in mar-bottom-30">
                  <h3 class="white">Need Nepayatri Help?</h3>
                  <div class="footer-phone">
                    <div class="cont-icon"><i class="flaticon-call"></i></div>
                    <div class="cont-content mar-left-20">
                      <p class="mar-0">Got Questions? Call us 24/7!</p>
                      <p class="bold mar-0"><span>Call Us:</span> (888) 1234 56789</p>
                    </div>
                  </div>
                </div>
                <h3 class="white">Contact Info</h3>
                <p>
                  PO Box: +47-252-254-2542<br />
                  Location: Collins Stree, Vicotria 80, Australia
                </p>
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
              </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-12">
              <div class="footer-links">
                <h3 class="white">Company</h3>
                <ul>
                  <li><a href="#">About Us</a></li>
                  <li><a href="#">Careers</a></li>
                  <li><a href="#">Terms of Use</a></li>
                  <li><a href="#">Privacy Statement</a></li>
                  <li><a href="#">Feedbacks</a></li>
                </ul>
              </div>
            </div>
            <div class="col-lg-2 col-sm-6 col-12">
              <div class="footer-links">
                <h3 class="white">Support</h3>
                <ul>
                  <li><a href="#">Account</a></li>
                  <li><a href="#">Legal</a></li>
                  <li><a href="#">Contact</a></li>
                  <li><a href="#">Affiliate Program</a></li>
                  <li><a href="#">Privacy Policy</a></li>
                </ul>
              </div>
            </div>
            <div class="col-lg-3 col-sm-12 col-12">
              <div class="footer-subscribe">
                <h3 class="white">Mailing List</h3>
                <p class="white">Sign up for our mailing list to get latest updates and offers</p>
                <form>
                  <input type="email" placeholder="Your Email" />
                  <a href="#" class="biz-btn mar-top-15">Subscribe</a>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="footer-payment pad-top-30 pad-bottom-30 bg-white">
        <div class="container">
          <div class="pay-main display-flex space-between">
            <div class="footer-logo pull-left">
              <a href="index.html"><img src="{{asset('images/logo-black.png')}}" alt="image" /></a>
            </div>
            <div class="footer-payment-nav pull-right">
              <ul>
                <li><img src="{{asset('images/payment/mastercard.png')}}" alt="image" /></li>
                <li><img src="{{asset('images/payment/paypal.png')}}" alt="image" /></li>
                <li><img src="{{asset('images/payment/skrill.png')}}" alt="image" /></li>
                <li><img src="{{asset('images/payment/visa.png')}}" alt="image" /></li>
                <li>
                  <select>
                    <option>English (United States)</option>
                    <option>English (United States)</option>
                    <option>English (United States)</option>
                    <option>English (United States)</option>
                    <option>English (United States)</option>
                  </select>
                </li>
                <li>
                  <select>
                    <option>$ USD</option>
                    <option>$ USD</option>
                    <option>$ USD</option>
                    <option>$ USD</option>
                    <option>$ USD</option>
                  </select>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div class="footer-copyright">
        <div class="container">
          <div class="copyright-text pull-left">
            <p class="mar-0">2020 Nepayatri. All rights reserved.</p>
          </div>
          <div class="footer-nav pull-right">
            <ul>
              <li><a href="#">Home</a></li>
              <li><a href="#">About Us</a></li>
              <li><a href="#">Services</a></li>
              <li><a href="#">Careers</a></li>
              <li><a href="#">Terms of Use</a></li>
              <li><a href="#">Privacy</a></li>
              <li><a href="#">Contact us</a></li>
            </ul>
          </div>
        </div>
      </div>
    </footer>
    <!-- footer ends -->

    <!-- Back to top start -->
    <div id="back-to-top">
      <a href="#"></a>
    </div>
    <!-- Back to top ends -->

    <!-- search popup -->
    <div id="search1">
      <button type="button" class="close">×</button>
      <form>
        <input type="search" value="" placeholder="type keyword(s) here" />
        <button type="submit" class="btn btn-primary">Search</button>
      </form>
    </div>
<!-- Login Modal -->
<!-- Login Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

      <div class="login-content">
        <div class="login-title section-border">
          <h3>Login</h3>
        </div>

        <div class="login-form section-border">
          <form method="POST" action="{{ route('login') }}" id="login-form">
            @csrf

            <!-- Email Field -->
            <div class="form-group">
              <input 
                type="email" 
                name="email_login" 
                placeholder="Enter email address" 
                class="form-control @error('email_login') is-invalid @enderror"
                value="{{ old('email_login') }}"
              />
              @error('email_login')
                <span class="invalid-feedback d-block" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>

            <!-- Password Field -->
            <div class="form-group">
              <input 
                type="password" 
                name="password" 
                placeholder="Enter password" 
                class="form-control @error('password') is-invalid @enderror"
              />
              @error('password')
                <span class="invalid-feedback d-block" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>

            <div class="form-btn">
              <button type="submit" class="biz-btn biz-btn1">LOGIN</button>
            </div>

            <div class="form-group form-checkbox">
              <input type="checkbox" name="remember"> Remember Me
              <a href="#" class="float-right">Forgot password?</a>
            </div>
          </form>
        </div>

        <div class="login-social section-border">
          <p>or continue with</p>
          <a href="#" class="btn-facebook"><i class="fab fa-facebook" aria-hidden="true"></i> Facebook</a>
          <a href="#" class="btn-twitter"><i class="fab fa-twitter" aria-hidden="true"></i> Twitter</a>
        </div>

        <div class="sign-up text-center">
          <p>Do not have an account? <a href="#">Sign Up</a></p>
        </div>
      </div>

      <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
  </div>
</div>

 <!-- jQuery, Popper.js, and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script src="{{ asset('js/color-switcher.js') }}"></script>
<script src="{{ asset('js/plugin.js') }}"></script>
<script src="{{ asset('js/main.js') }}"></script>
<script src="{{ asset('js/menu.js') }}"></script>
<script src="{{ asset('js/custom-nav.js') }}"></script>
<script>
    
</script>


  </body>
</html>
