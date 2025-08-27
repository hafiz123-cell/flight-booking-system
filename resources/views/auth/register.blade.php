@extends('layout.layout')
<meta name="csrf-token" content="{{ csrf_token() }}">
@section('content')
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
          <form method="POST" action="{{ route('login_user_page') }}" id="login-form">
            @csrf

            <!-- Email Field -->
            <div class="form-group mb-2">
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
            <div class="form-group mb-2">
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
@endsection