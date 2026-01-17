<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Admin Login | {{ getSetting('business_name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @include('backend.include.style')

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link
        rel="icon"
        href="{{ asset(getSetting('site_favicon')) }}"
        type="image/x-icon"
    />

    <style>
        :root {
            --primary: #3b82f6;
            --primary-dark: #2563eb;
            --primary-light: #60a5fa;
            --secondary: #8b5cf6;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --dark: #0f172a;
            --dark-secondary: #1e293b;
            --light: #f8fafc;
            --border: #e2e8f0;
            --text-muted: #64748b;
            --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-dark: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            background: var(--light);
            overflow-x: hidden;
        }

        .auth-wrapper {
            min-height: 100vh;
            display: flex;
            position: relative;
        }

        /* LEFT BRAND PANEL */
        .auth-left {
            width: 50%;
            background: var(--gradient-dark);
            color: #fff;
            padding: 80px 60px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }

        /* Animated Background Pattern */
        .auth-left::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.15) 0%, transparent 70%);
            border-radius: 50%;
            animation: float 20s ease-in-out infinite;
        }

        .auth-left::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(139, 92, 246, 0.12) 0%, transparent 70%);
            border-radius: 50%;
            animation: float 15s ease-in-out infinite reverse;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(30px, -30px) scale(1.1); }
        }

        .auth-left-content {
            position: relative;
            z-index: 2;
        }

        .brand-logo {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 48px;
            box-shadow: 0 8px 24px rgba(59, 130, 246, 0.3);
        }

        .brand-logo i {
            font-size: 26px;
            color: white;
        }

        .auth-left h1 {
            font-size: 2.5rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 20px;
            letter-spacing: -0.5px;
        }

        .auth-left p {
            font-size: 1.05rem;
            opacity: 0.85;
            line-height: 1.7;
            max-width: 480px;
            margin-bottom: 40px;
            color: #cbd5e1;
        }

        .feature-list {
            display: grid;
            gap: 16px;
            margin-bottom: 40px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 16px 20px;
            background: rgba(255, 255, 255, 0.06);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .feature-item:hover {
            background: rgba(255, 255, 255, 0.08);
            transform: translateX(4px);
        }

        .feature-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.2), rgba(139, 92, 246, 0.2));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .feature-icon i {
            font-size: 18px;
            color: var(--primary-light);
        }

        .feature-text {
            flex: 1;
        }

        .feature-text h4 {
            font-size: 0.95rem;
            font-weight: 600;
            margin-bottom: 4px;
            color: #fff;
        }

        .feature-text p {
            font-size: 0.85rem;
            opacity: 0.7;
            margin: 0;
            color: #cbd5e1;
        }

        .auth-footer {
            position: relative;
            z-index: 2;
            display: flex;
            align-items: center;
            gap: 24px;
            padding-top: 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .footer-badge {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.7);
        }

        .footer-badge i {
            color: var(--success);
        }

        /* RIGHT LOGIN PANEL */
        .auth-right {
            width: 50%;
            display: flex;
            justify-content: center;
            padding: 40px;
            background: #ffffff;
        }

        .auth-card {
            width: 100%;
            max-width: 460px;
        }

        .auth-header {
            margin-bottom: 36px;
        }

        .auth-title {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 8px;
            color: var(--dark);
            letter-spacing: -0.3px;
        }

        .auth-subtitle {
            font-size: 0.95rem;
            color: var(--text-muted);
            font-weight: 400;
        }

        /* Form Styles */
        .form-label {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 10px;
            display: block;
        }

        .form-group {
            margin-bottom: 24px;
            position: relative;
        }

        .form-control {
            height: 50px;
            border: 2px solid var(--border);
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 0.95rem;
            font-weight: 400;
            transition: all 0.3s ease;
            background: #fff;
            width: 100%;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
            outline: none;
            background: #fff;
        }

        .form-control::placeholder {
            color: #94a3b8;
        }

        .form-check {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-check-input {
            width: 18px;
            height: 18px;
            border: 2px solid var(--border);
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .form-check-input:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .form-check-label {
            font-size: 0.9rem;
            color: var(--dark);
            cursor: pointer;
            user-select: none;
        }

        .forgot-link {
            color: var(--primary);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .forgot-link:hover {
            color: var(--primary-dark);
        }

        .btn-primary {
            width: 100%;
            height: 50px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border: none;
            border-radius: 10px;
            color: white;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 14px rgba(59, 130, 246, 0.3);
            position: relative;
            overflow: hidden;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .alert {
            padding: 14px 18px;
            border-radius: 10px;
            margin-bottom: 24px;
            border: none;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }

        .copyright-text {
            text-align: center;
            margin-top: 32px;
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        /* Password Toggle */
        .password-wrapper {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--text-muted);
            font-size: 16px;
            transition: color 0.3s ease;
            z-index: 10;
        }

        .password-toggle:hover {
            color: var(--primary);
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .auth-left h1 {
                font-size: 2.2rem;
            }

            .auth-left p {
                font-size: 1rem;
            }
        }

        @media (max-width: 992px) {
            .auth-left {
                display: none;
            }

            .auth-right {
                width: 100%;
            }
        }

        @media (max-width: 576px) {
            .auth-right {
                padding: 30px 20px;
            }

            .auth-card {
                max-width: 100%;
            }

            .auth-title {
                font-size: 1.5rem;
            }

            .form-control, .btn-primary {
                height: 48px;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 375px) {
            .auth-right {
                padding: 25px 16px;
            }

            .auth-title {
                font-size: 1.35rem;
            }

            .form-control, .btn-primary {
                height: 46px;
            }
        }

        /* Loading State */
        .btn-primary.loading {
            pointer-events: none;
            opacity: 0.7;
        }

        .btn-primary .spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
            margin: 0 auto;
        }

        .btn-primary.loading .spinner {
            display: block;
        }

        .btn-primary.loading .btn-text {
            display: none;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>

<body>
<div class="auth-wrapper">

    <!-- LEFT BRAND PANEL -->
    <div class="auth-left">
        <div class="auth-left-content">
            <div class="brand-logo">
                <i class="fas fa-shopping-bag"></i>
            </div>

            <h1>{{ getSetting('business_name') }}<br>Admin Portal</h1>
            <p>
                Powerful admin dashboard designed for modern ecommerce.
                Manage your entire business operations with advanced analytics and real-time insights.
            </p>

            <div class="feature-list">
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="feature-text">
                        <h4>Real-time Analytics</h4>
                        <p>Monitor sales, revenue, and performance metrics instantly</p>
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div class="feature-text">
                        <h4>Enterprise Security</h4>
                        <p>Bank-level encryption with role-based access control</p>
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <div class="feature-text">
                        <h4>Lightning Fast</h4>
                        <p>Optimized performance for seamless workflow</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="auth-footer">
            <div class="footer-badge">
                <i class="fas fa-check-circle"></i>
                <span>Secure Connection</span>
            </div>
            <div class="footer-badge">
                <i class="fas fa-check-circle"></i>
                <span>24/7 Support</span>
            </div>
        </div>
    </div>

    <!-- RIGHT LOGIN PANEL -->
    <div class="auth-right">
        <div class="auth-card">
            <div class="auth-header">
                <h2 class="auth-title">Welcome Back</h2>
                <p class="auth-subtitle">
                    Sign in to continue to your admin dashboard
                </p>
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    <i class="fas fa-check-circle"></i>
                    {{ session('status') }}
                </div>
            @endif

            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    <i class="fas fa-exclamation-circle"></i>
                    <strong>Error!</strong> {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf

                <!-- Email Address -->
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email') }}"
                        placeholder="admin@example.com"
                        required
                        autofocus
                        autocomplete="username"
                    />
                    @error('email')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="password-wrapper">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="••••••••"
                            required
                            autocomplete="current-password"
                        />
                        <i class="fas fa-eye password-toggle" id="togglePassword"></i>
                    </div>
                    @error('password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input
                            type="checkbox"
                            class="form-check-input"
                            id="remember_me"
                            name="remember"
                        />
                        <label class="form-check-label" for="remember_me">
                            Remember me
                        </label>
                    </div>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-link">
                            Forgot Password?
                        </a>
                    @endif
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary" id="loginBtn">
                    <span class="btn-text">Login to Dashboard</span>
                    <div class="spinner"></div>
                </button>
            </form>

            <div class="copyright-text">
                © {{ date('Y') }} {{ getSetting('business_name') }}. All rights reserved.
            </div>
        </div>
    </div>

</div>

<script src="{{ asset('backend/assets/js/core/bootstrap.min.js') }}"></script>
<script>
    // Password toggle
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    if (togglePassword && passwordInput) {
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    }

    // Form submission loading state
    const loginForm = document.getElementById('loginForm');
    const loginBtn = document.getElementById('loginBtn');

    if (loginForm && loginBtn) {
        loginForm.addEventListener('submit', function() {
            loginBtn.classList.add('loading');
        });
    }

    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.3s ease';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });
</script>
</body>
</html>
