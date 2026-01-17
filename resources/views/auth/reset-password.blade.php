<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Reset Password | {{ getSetting('business_name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @include('backend.include.style')

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

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

        .security-features {
            margin-top: 40px;
            display: grid;
            gap: 18px;
        }

        .security-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 18px;
            background: rgba(255, 255, 255, 0.06);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .security-icon {
            width: 36px;
            height: 36px;
            background: rgba(16, 185, 129, 0.2);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .security-icon i {
            font-size: 16px;
            color: var(--success);
        }

        .security-text {
            font-size: 0.9rem;
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

        /* Password Strength Indicator */
        .password-strength {
            margin-top: 12px;
        }

        .strength-bar {
            height: 4px;
            background: var(--border);
            border-radius: 2px;
            overflow: hidden;
            margin-bottom: 8px;
        }

        .strength-fill {
            height: 100%;
            width: 0%;
            transition: all 0.3s ease;
            border-radius: 2px;
        }

        .strength-fill.weak {
            width: 33%;
            background: var(--danger);
        }

        .strength-fill.medium {
            width: 66%;
            background: var(--warning);
        }

        .strength-fill.strong {
            width: 100%;
            background: var(--success);
        }

        .strength-text {
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        .strength-text.weak { color: var(--danger); }
        .strength-text.medium { color: var(--warning); }
        .strength-text.strong { color: var(--success); }

        /* Password Requirements */
        .password-requirements {
            background: rgba(100, 116, 139, 0.05);
            border-radius: 10px;
            padding: 16px;
            margin-top: 14px;
        }

        .password-requirements p {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 12px;
        }

        .requirement-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-bottom: 8px;
        }

        .requirement-item:last-child {
            margin-bottom: 0;
        }

        .requirement-item i {
            width: 16px;
            font-size: 12px;
        }

        .requirement-item.met {
            color: var(--success);
        }

        .requirement-item.met i {
            color: var(--success);
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
                <i class="fas fa-shield-alt"></i>
            </div>

            <h1>Secure Your Account</h1>
            <p>
                Choose a strong, unique password to protect your account and data. Your security is our top priority.
            </p>

            <div class="security-features">
                <div class="security-item">
                    <div class="security-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                    <span class="security-text">256-bit SSL encryption</span>
                </div>

                <div class="security-item">
                    <div class="security-icon">
                        <i class="fas fa-check-double"></i>
                    </div>
                    <span class="security-text">Two-factor authentication ready</span>
                </div>

                <div class="security-item">
                    <div class="security-icon">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <span class="security-text">Bank-level security standards</span>
                </div>

                <div class="security-item">
                    <div class="security-icon">
                        <i class="fas fa-history"></i>
                    </div>
                    <span class="security-text">Complete activity logs</span>
                </div>
            </div>
        </div>
    </div>

    <!-- RIGHT FORM PANEL -->
    <div class="auth-right">
        <div class="auth-card">
            <div class="auth-header">
                <h2 class="auth-title">Reset Password</h2>
                <p class="auth-subtitle">
                    Enter your new password below to secure your account.
                </p>
            </div>

            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    <i class="fas fa-exclamation-circle"></i>
                    <strong>Error!</strong> There were some problems with your input.
                </div>
            @endif

            <form method="POST" action="{{ route('password.store') }}" id="resetForm">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Address -->
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email', $request->email) }}"
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
                    <label for="password" class="form-label">New Password</label>
                    <div class="password-wrapper">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="Enter new password"
                            required
                            autocomplete="new-password"
                        />
                        <i class="fas fa-eye password-toggle" id="togglePassword"></i>
                    </div>
                    @error('password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror

                    <!-- Password Strength Indicator -->
                    <div class="password-strength" id="passwordStrength" style="display: none;">
                        <div class="strength-bar">
                            <div class="strength-fill" id="strengthFill"></div>
                        </div>
                        <div class="strength-text" id="strengthText">Password strength</div>
                    </div>

                    <!-- Password Requirements -->
                    <div class="password-requirements">
                        <p>Password must contain:</p>
                        <div class="requirement-item" id="req-length">
                            <i class="fas fa-circle"></i>
                            <span>At least 8 characters</span>
                        </div>
                        <div class="requirement-item" id="req-uppercase">
                            <i class="fas fa-circle"></i>
                            <span>One uppercase letter</span>
                        </div>
                        <div class="requirement-item" id="req-lowercase">
                            <i class="fas fa-circle"></i>
                            <span>One lowercase letter</span>
                        </div>
                        <div class="requirement-item" id="req-number">
                            <i class="fas fa-circle"></i>
                            <span>One number</span>
                        </div>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <div class="password-wrapper">
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            class="form-control"
                            placeholder="Re-enter new password"
                            required
                            autocomplete="new-password"
                        />
                        <i class="fas fa-eye password-toggle" id="togglePasswordConfirm"></i>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <span class="btn-text">
                        <i class="fas fa-check-circle me-2"></i>
                        Reset Password
                    </span>
                    <div class="spinner"></div>
                </button>
            </form>
        </div>
    </div>

</div>

<script src="{{ asset('backend/assets/js/core/bootstrap.min.js') }}"></script>
<script>
    // Password toggle for main password
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

    // Password toggle for confirmation
    const togglePasswordConfirm = document.getElementById('togglePasswordConfirm');
    const passwordConfirmInput = document.getElementById('password_confirmation');

    if (togglePasswordConfirm && passwordConfirmInput) {
        togglePasswordConfirm.addEventListener('click', function() {
            const type = passwordConfirmInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordConfirmInput.setAttribute('type', type);

            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    }

    // Password strength checker
    const strengthIndicator = document.getElementById('passwordStrength');
    const strengthFill = document.getElementById('strengthFill');
    const strengthText = document.getElementById('strengthText');

    // Requirement elements
    const reqLength = document.getElementById('req-length');
    const reqUppercase = document.getElementById('req-uppercase');
    const reqLowercase = document.getElementById('req-lowercase');
    const reqNumber = document.getElementById('req-number');

    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            const password = this.value;

            if (password.length > 0) {
                strengthIndicator.style.display = 'block';

                // Check requirements
                const hasLength = password.length >= 8;
                const hasUppercase = /[A-Z]/.test(password);
                const hasLowercase = /[a-z]/.test(password);
                const hasNumber = /[0-9]/.test(password);

                // Update requirement indicators
                updateRequirement(reqLength, hasLength);
                updateRequirement(reqUppercase, hasUppercase);
                updateRequirement(reqLowercase, hasLowercase);
                updateRequirement(reqNumber, hasNumber);

                // Calculate strength
                let strength = 0;
                if (hasLength) strength++;
                if (hasUppercase) strength++;
                if (hasLowercase) strength++;
                if (hasNumber) strength++;

                // Update strength indicator
                strengthFill.className = 'strength-fill';
                strengthText.className = 'strength-text';

                if (strength <= 2) {
                    strengthFill.classList.add('weak');
                    strengthText.classList.add('weak');
                    strengthText.textContent = 'Weak password';
                } else if (strength === 3) {
                    strengthFill.classList.add('medium');
                    strengthText.classList.add('medium');
                    strengthText.textContent = 'Medium password';
                } else if (strength === 4) {
                    strengthFill.classList.add('strong');
                    strengthText.classList.add('strong');
                    strengthText.textContent = 'Strong password';
                }
            } else {
                strengthIndicator.style.display = 'none';
                resetRequirements();
            }
        });
    }

    function updateRequirement(element, met) {
        if (met) {
            element.classList.add('met');
            element.querySelector('i').className = 'fas fa-check-circle';
        } else {
            element.classList.remove('met');
            element.querySelector('i').className = 'fas fa-circle';
        }
    }

    function resetRequirements() {
        [reqLength, reqUppercase, reqLowercase, reqNumber].forEach(req => {
            req.classList.remove('met');
            req.querySelector('i').className = 'fas fa-circle';
        });
    }

    // Form submission loading state
    const resetForm = document.getElementById('resetForm');
    const submitBtn = document.getElementById('submitBtn');

    if (resetForm && submitBtn) {
        resetForm.addEventListener('submit', function() {
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