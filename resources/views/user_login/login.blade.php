@extends('admin_dashboard.layouts.base', ['subtitle' => 'Sign In'])

@section('body-attribuet')
class="authentication-bg"
@endsection

@section('content')
<div class="account-pages py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card border-0 shadow-lg">
                    <div class="card-body p-5">
                        <!-- Logo / Heading -->
                        <div class="text-center">
                            <div class="mx-auto mb-4 text-center auth-logo">
                                <h1 style="font-size: 32px; font-weight: 600; color: #ec7b34; padding-top:10px;">
                                    GoFlyHabibi
                                </h1>
                            </div>
                            <h4 class="fw-bold text-dark mb-2">Welcome Back!</h4>
                            <p class="text-muted">Sign in to your account to continue</p>
                        </div>

                        <!-- Login Form -->
                        <form method="POST" action="{{ route('login_user') }}" class="mt-4">
                            @csrf

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input 
                                    type="email" 
                                    class="form-control @error('email') is-invalid @enderror" 
                                    id="email" 
                                    name="email" 
                                    value="{{ old('email') }}" 
                                    placeholder="Enter your email" 
                                    required 
                                    autofocus>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label for="password" class="form-label">Password</label>
                                    <a href="{{ route('password.request') }}" class="text-decoration-none small text-muted">
                                        Forgot password?
                                    </a>
                                </div>
                                <input 
                                    type="password" 
                                    class="form-control @error('password') is-invalid @enderror" 
                                    id="password" 
                                    name="password" 
                                    placeholder="Enter your password" 
                                    required>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Remember Me -->
                            <div class="form-check mb-3">
                                <input type="checkbox" class="form-check-input" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">Remember me</label>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid">
                                <button class="btn btn-dark btn-lg fw-medium" type="submit">Sign In</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Sign Up Link -->
                <p class="text-center mt-4 text-white text-opacity-50">
                    Don't have an account? 
                    <a href="{{ route('register_user_view') }}" class="text-decoration-none text-white fw-bold">Sign Up</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
