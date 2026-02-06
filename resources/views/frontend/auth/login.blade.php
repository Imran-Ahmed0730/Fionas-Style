@extends('frontend.master')

@section('content')
    <!-- Breadcrumb Section Begin -->
    <div class="breacrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-text">
                        <a href="{{ route('home') }}"><i class="ti-home"></i> Home</a>
                        <span>Login</span>
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
                    <div class="login-form">
                        <h2>Login</h2>

                        @if (session('status'))
                            <div class="alert alert-success mb-4">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="group-input">
                                <label for="email">Email address *</label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
                                @error('email')
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
                            <div class="group-input gi-check">
                                <div class="gi-more">
                                    <label for="save-pass">
                                        Save Password
                                        <input type="checkbox" id="save-pass" name="remember">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="forget-pass">Forget your Password</a>
                                @endif
                            </div>
                            <button type="submit" class="site-btn login-btn">Sign In</button>
                            <div class="row mt-4">
                                <div class="col-12 text-center">
                                    <p class="mb-2">Or Sign In with</p>
                                    <a href="{{ route('auth.google') }}" class="btn btn-outline-danger w-100">
                                        <i class="fa fa-google me-2"></i> Google
                                    </a>
                                </div>
                            </div>
                        </form>
                        <div class="switch-login">
                            <a href="{{ route('register') }}" class="or-login">Or Create An Account</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Register Form Section End -->
@endsection
