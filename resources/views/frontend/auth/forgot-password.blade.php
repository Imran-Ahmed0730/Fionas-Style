@extends('frontend.master')

@section('content')
    <!-- Breadcrumb Section Begin -->
    <div class="breacrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-text">
                        <a href="{{ route('home') }}"><i class="ti-home"></i> Home</a>
                        <span>Forgot Password</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Form Section Begin -->

    <!-- Forgot Password Section Begin -->
    <div class="register-login-section spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="login-form">
                        <h2>Forgot Password?</h2>
                        <p class="mb-4 text-muted">No problem. Just let us know your email address and we will email you a
                            password reset link that will allow you to choose a new one.</p>

                        @if (session('status'))
                            <div class="alert alert-success mb-4">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf
                            <div class="group-input">
                                <label for="email">Email address *</label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
                                @error('email')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <button type="submit" class="site-btn login-btn">Email Password Reset Link</button>
                        </form>
                        <div class="switch-login">
                            <a href="{{ route('login') }}" class="or-login">Back to Login</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Forgot Password Section End -->
@endsection