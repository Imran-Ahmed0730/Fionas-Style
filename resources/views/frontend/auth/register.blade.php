@extends('frontend.master')

@section('content')
    <!-- Breadcrumb Section Begin -->
    <div class="breacrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-text">
                        <a href="{{ route('home') }}"><i class="ti-home"></i> Home</a>
                        <span>Register</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Form Section Begin -->

    <!-- Register Section Begin -->
    <div class="register-login-section spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="register-form">
                        <h2>Register</h2>
                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            <div class="group-input">
                                <label for="name">Full Name *</label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus>
                                @error('name')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="group-input">
                                <label for="username">Username *</label>
                                <input type="text" id="username" name="username" value="{{ old('username') }}" required>
                                @error('username')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="group-input">
                                <label for="email">Email address</label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}">
                                @error('email')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="group-input">
                                <label for="phone">Phone Number *</label>
                                <input type="text" id="phone" name="phone" value="{{ old('phone') }}" required>
                                @error('phone')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="group-input">
                                <label for="pass">Password *</label>
                                <input type="password" id="pass" name="password" required>
                                @error('password')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="group-input">
                                <label for="con-pass">Confirm Password *</label>
                                <input type="password" id="con-pass" name="password_confirmation" required>
                            </div>
                            <button type="submit" class="site-btn register-btn">REGISTER</button>
                            <div class="row mt-4">
                                <div class="col-12 text-center">
                                    <p class="mb-2">Or Register with</p>
                                    <a href="{{ route('auth.google') }}" class="btn btn-outline-danger w-100">
                                        <i class="fa fa-google me-2"></i> Google
                                    </a>
                                </div>
                            </div>
                        </form>
                        <div class="switch-login">
                            <a href="{{ route('login') }}" class="or-login">Or Login</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Register Form Section End -->
@endsection