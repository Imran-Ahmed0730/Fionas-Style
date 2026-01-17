<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Forgot Password | {{ getSetting('business_name') }}</title>
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
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

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

        .icon-wrapper {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.2), rgba(139, 92, 246, 0.2));
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 32px;
            border: 2px solid rgba(59, 130, 246, 0.3);
        }

        .icon-wrapper i {
            font-size: 36px;
            background: linear-gradient(135deg, var(--primary-light), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
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
            color: #cbd5e1;
        }

        .steps-list {
            margin-top: 40px;
            display: grid;
            gap: 20px;
        }

        .step-item {
            display: flex;
            gap: 16px;
            align-items: flex-start;
        }

        .step-number {
            width: 32px;
            height: 32px;
            background: rgba(59, 130, 246, 0.2);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.9rem;
            color: var(--primary-light);
            flex-shrink: 0;
        }

        .step-content h4 {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 6px;
            color: #fff;
        }

        .step-content p {
            font-size: 0.9rem;
            opacity: 0.7;
            margin: 0;
            color: #cbd5e1;
        }

        /* RIGHT FORM PANEL */
        .auth-right {
            width: 50%;
            display: flex;
            align-items: center;
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
            line-height: 1.6;
        }

        .info-box {
            background: rgba(59, 130, 246, 0.08);
            border-left: 4px solid var(--primary);
            border-radius: 10px;
            padding: 16px 18px;
            margin-bottom: 28px;
        }

        .info-box p {
            margin: 0;
            font-size: 0.9rem;
            color: var(--dark);
            line-height: 1.6;
        }

        .info-box i {
            color: var(--primary);
            margin-right: 8px;
        }

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

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.3s ease;
            margin-top: 24px;
        }

        .back-link:hover {
            color: var(--primary);
            transform: translateX(-4px);
        }

        .back-link i {
            transition: transform 0.3s ease;
        }

        .back-link:hover i {
            transform: translateX(-4px);
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
            <div class="icon-wrapper">
                <i class="fas fa-key"></i>
            </div>

            <h1>Password Recovery</h1>
            <p>
                Don't worry if you've forgotten your password. We'll send you a secure link to reset it and regain access to your account.
            </p>

            <div class="steps-list">
                <div class="step-item">
                    <div class="step-number">1</div>
                    <div class="step-content">
                        <h4>Enter Your Email</h4>
                        <p>Provide the email address associated with your account</p>
                    </div>
                </div>

                <div class="step-item">
                    <div class="step-number">2</div>
                    <div class="step-content">
                        <h4>Check Your Inbox</h4>
                        <p>We'll send you a secure password reset link</p>
                    </div>
                </div>

                <div class="step-item">
                    <div class="step-number">3</div>
                    <div class="step-content">
                        <h4>Create New Password</h4>
                        <p>Follow the link to set up your new secure password</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- RIGHT FORM PANEL -->
    <div class="auth-right">
        <div class="auth-card">
            <div class="auth-header">
                <h2 class="auth-title">Forgot Password?</h2>
                <p class="auth-subtitle">
                    No worries! Enter your email and we'll send you reset instructions.
                </p>
            </div>

            <div class="info-box">
                <p>
                    <i class="fas fa-info-circle"></i>
                    Enter your email address and we'll send you instructions to reset your password.
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
                    {{ $errors->first('email') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" id="forgotForm">
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
                    />
                    @error('email')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <span class="btn-text">
                        <i class="fas fa-paper-plane me-2"></i>
                        Send Reset Link
                    </span>
                    <div class="spinner"></div>
                </button>

                <!-- Back to Login -->
                <div class="text-center">
                    <a href="{{ route('login') }}" class="back-link">
                        <i class="fas fa-arrow-left"></i>
                        Back to Login
                    </a>
                </div>
            </form>
        </div>
    </div>

</div>

<script src="{{ asset('backend/assets/js/core/bootstrap.min.js') }}"></script>
<script>
    // Form submission loading state
    const forgotForm = document.getElementById('forgotForm');
    const submitBtn = document.getElementById('submitBtn');

    if (forgotForm && submitBtn) {
        forgotForm.addEventListener('submit', function() {
            submitBtn.classList.add('loading');
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
