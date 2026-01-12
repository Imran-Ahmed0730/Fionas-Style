<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Admin Login | {{ getSetting('business_name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    @include('backend.include.style')

    <style>
        body {
            min-height: 100vh;
            background: #f4f6f9;
        }

        .auth-wrapper {
            min-height: 100vh;
            display: flex;
        }

        .auth-left {
            background: #1a2035;
            color: #fff;
            padding: 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .auth-left h1 {
            font-size: 2.2rem;
            font-weight: 700;
        }

        .auth-left p {
            font-size: 1rem;
            opacity: 0.9;
            max-width: 420px;
        }

        .auth-right {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-card {
            width: 100%;
            max-width: 420px;
            border: none;
            border-radius: 14px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.08);
        }

        .auth-card .card-body {
            padding: 40px;
        }

        .auth-title {
            font-size: 1.6rem;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .auth-subtitle {
            font-size: 0.95rem;
            color: #6c757d;
            margin-bottom: 30px;
        }

        .form-control {
            height: 48px;
            border-radius: 8px;
        }

        .btn-primary {
            height: 48px;
            border-radius: 8px;
            font-weight: 600;
        }

        @media (max-width: 992px) {
            .auth-left {
                display: none;
            }
        }
    </style>
</head>

<body>
<div class="auth-wrapper">

    <!-- LEFT BRAND PANEL -->
    <div class="auth-left col-lg-6 d-none d-lg-flex">
        <h1>{{ getSetting('business_name') }} Admin</h1>
        <p class="mt-3">
            Manage products, orders, customers, and analytics from a single,
            powerful dashboard built for scale and performance.
        </p>

        <div class="mt-5">
            <small class="opacity-75">
                Secure • Fast • Reliable
            </small>
        </div>
    </div>

    <!-- RIGHT LOGIN PANEL -->
    <div class="auth-right col-lg-6 col-12">
        <div class="card auth-card">
            <div class="card-body">
                <div class="mb-4">
                    <div class="auth-title">Welcome Back</div>
                    <div class="auth-subtitle">
                        Sign in to continue to admin panel
                    </div>
                </div>

                <form method="post" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input
                            name="email"
                            type="email"
                            class="form-control"
                            placeholder="admin@example.com"
                            required
                        />
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input
                            name="password"
                            type="password"
                            class="form-control"
                            placeholder="••••••••"
                            required
                        />
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="rememberMe" name="remember">
                            <label class="form-check-label" for="rememberMe">
                                Remember me
                            </label>
                        </div>
                        @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-primary text-decoration-none">
                            Forgot password?
                        </a>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        Login to Dashboard
                    </button>
                </form>

                <div class="text-center mt-4 text-muted" style="font-size: 0.85rem;">
                    © {{ date('Y') }} {{ getSetting('business_name') }}
                </div>
            </div>
        </div>
    </div>

</div>

<script src="{{ asset('backend/assets/js/core/bootstrap.min.js') }}"></script>
</body>
</html>


