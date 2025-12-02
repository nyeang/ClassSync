<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - ClassSync</title>

    <!-- PWA Meta Tags -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes, viewport-fit=cover">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#1a1a2e">
    <link rel="manifest" href="/manifest.json">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Reset & Variables */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #4F46E5;
            --primary-dark: #4338CA;
            --success: #10B981;
            --danger: #EF4444;
            --gray-50: #F9FAFB;
            --gray-100: #F3F4F6;
            --gray-200: #E5E7EB;
            --gray-400: #9CA3AF;
            --gray-500: #6B7280;
            --gray-600: #4B5563;
            --gray-700: #374151;
            --gray-900: #111827;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: var(--gray-50);
            min-height: 100vh;
        }

        /* Auth Container */
        .auth-container {
            display: flex;
            min-height: 100vh;
        }

        /* Left Side */
        .auth-left {
            flex: 1;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .branding {
            max-width: 500px;
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        .logo-icon {
            font-size: 48px;
        }

        .logo-section h1 {
            font-size: 36px;
            font-weight: 700;
        }

        .tagline {
            font-size: 18px;
            opacity: 0.9;
            margin-bottom: 40px;
        }

        .features {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .feature-item {
            display: flex;
            gap: 20px;
            align-items: flex-start;
        }

        .feature-item i {
            font-size: 32px;
            opacity: 0.9;
        }

        .feature-item h3 {
            font-size: 18px;
            margin-bottom: 5px;
        }

        .feature-item p {
            font-size: 14px;
            opacity: 0.8;
        }

        /* Right Side */
        .auth-right {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }

        .auth-card {
            width: 100%;
            max-width: 480px;
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
        }

        /* Role Tabs */
        .role-tabs {
            display: flex;
            gap: 12px;
            margin-bottom: 24px;
            padding: 4px;
            background: var(--gray-100);
            border-radius: 12px;
        }

        .role-tab {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 16px;
            background: transparent;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 500;
            color: var(--gray-600);
            cursor: pointer;
            transition: all 0.2s;
        }

        .role-tab:hover {
            color: var(--gray-900);
            background: var(--gray-200);
        }

        .role-tab.active {
            background: white;
            color: var(--primary);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .auth-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .auth-header h2 {
            font-size: 28px;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 8px;
        }

        .auth-header p {
            color: var(--gray-500);
            font-size: 15px;
        }

        /* Alert */
        .alert {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-error {
            background: #FEE2E2;
            color: #991B1B;
            border: 1px solid #EF4444;
        }

        .alert-success {
            background: #D1FAE5;
            color: #065F46;
            border: 1px solid #10B981;
        }

        /* Google Button */
        .btn-google {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            width: 100%;
            padding: 14px 24px;
            background: white;
            border: 2px solid var(--gray-200);
            border-radius: 12px;
            font-size: 15px;
            font-weight: 500;
            color: var(--gray-700);
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
        }

        .btn-google:hover {
            background: var(--gray-50);
            border-color: var(--gray-300);
        }

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            margin: 24px 0;
            color: var(--gray-400);
            font-size: 14px;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--gray-200);
        }

        .divider span {
            padding: 0 16px;
        }

        /* Form */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: var(--gray-700);
            margin-bottom: 8px;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 16px;
            color: var(--gray-400);
            font-size: 16px;
        }

        .input-wrapper input {
            width: 100%;
            padding: 14px 16px 14px 48px;
            border: 2px solid var(--gray-200);
            border-radius: 12px;
            font-size: 15px;
            transition: all 0.2s;
            background: var(--gray-50);
        }

        .input-wrapper input:focus {
            outline: none;
            border-color: var(--primary);
            background: white;
        }

        .toggle-password {
            position: absolute;
            right: 16px;
            background: none;
            border: none;
            color: var(--gray-400);
            cursor: pointer;
            font-size: 16px;
            padding: 5px;
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: var(--gray-600);
            cursor: pointer;
        }

        .link {
            color: var(--primary);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
        }

        .link:hover {
            text-decoration: underline;
        }

        .btn-primary {
            width: 100%;
            padding: 14px 24px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(79, 70, 229, 0.3);
        }

        .auth-footer {
            text-align: center;
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px solid var(--gray-200);
        }

        .auth-footer p {
            color: var(--gray-600);
            font-size: 14px;
        }

        .link-bold {
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
        }

        .link-bold:hover {
            text-decoration: underline;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .auth-container {
                flex-direction: column;
            }
            .auth-left {
                padding: 40px;
            }
            .features {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .auth-right {
                padding: 20px;
            }
            .auth-card {
                padding: 30px 20px;
            }
        }
    </style>


    <style>
        /* Mobile Responsive Improvements */
        @media (max-width: 640px) {
            .container {
                padding: 1rem !important;
            }

            /* Prevent zoom on iOS */
            input, select, textarea, button {
                font-size: 16px !important;
            }
        }

        /* Fix for viewport height on mobile browsers */
        html {
            height: -webkit-fill-available;
        }

        body {
            min-height: 100vh;
            min-height: -webkit-fill-available;
        }

        /* Better touch targets for mobile */
        button, a {
            min-height: 44px;
            min-width: 44px;
        }

        /* Smooth scrolling */
        * {
            -webkit-overflow-scrolling: touch;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <!-- Left Side -->
        <div class="auth-left">
            <div class="branding">
                <div class="logo-section">
                    <i class="fas fa-graduation-cap logo-icon"></i>
                    <h1>ClassSync</h1>
                </div>
                <p class="tagline">Empowering education through seamless collaboration</p>

                <div class="features">
                    <div class="feature-item">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <div>
                            <h3>For Teachers</h3>
                            <p>Manage classes and track student progress</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-user-graduate"></i>
                        <div>
                            <h3>For Students</h3>
                            <p>Join classes and collaborate with peers</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-qrcode"></i>
                        <div>
                            <h3>QR Code Access</h3>
                            <p>Quick and easy class enrollment</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side -->
        <div class="auth-right">
            <div class="auth-card">
                <!-- Role Tabs -->
                <div class="role-tabs">
                    <button class="role-tab {{ $role === 'teacher' ? 'active' : '' }}" 
                            onclick="switchRole('teacher')">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <span>Teacher</span>
                    </button>
                    <button class="role-tab {{ $role === 'student' ? 'active' : '' }}" 
                            onclick="switchRole('student')">
                        <i class="fas fa-user-graduate"></i>
                        <span>Student</span>
                    </button>
                </div>

                <div class="auth-header">
                    <h2>{{ $role === 'teacher' ? 'Teacher Login' : 'Student Login' }}</h2>
                    <p>{{ $role === 'teacher' ? 'Sign in to manage your classes' : 'Sign in to access your classes' }}</p>
                </div>

                <!-- Alerts -->
                @if ($errors->any())
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $errors->first() }}
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Google Button -->
                <a href="" class="btn-google">
                    <svg class="google-icon" viewBox="0 0 24 24" width="18" height="18">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    Continue with Google
                </a>

                <div class="divider"><span>or</span></div>

                <!-- Login Form -->
                <form id="loginForm" method="POST" action="{{ route('login.post') }}">
                    @csrf
                    <input type="hidden" name="role" value="{{ ucfirst($role) }}">

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <div class="input-wrapper">
                            <i class="fas fa-envelope input-icon"></i>
                            <input type="email" id="email" name="email" placeholder="Enter your email" 
                                   value="{{ old('email') }}" required autofocus>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-wrapper">
                            <i class="fas fa-lock input-icon"></i>
                            <input type="password" id="password" name="password" 
                                   placeholder="Enter your password" required>
                            <button type="button" class="toggle-password" onclick="togglePassword()">
                                <i class="fas fa-eye" id="toggleIcon"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-options">
                        <label class="checkbox-label">
                            <input type="checkbox" name="remember">
                            <span>Remember me</span>
                        </label>
                        <a href="#" class="link">Forgot password?</a>
                    </div>

                    <button type="submit" class="btn-primary">
                        Sign In
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </form>

                <div class="auth-footer">
                    <p>Don't have an account? 
                        <a href="{{ route('register', ['role' => $role]) }}" class="link-bold">Create Account</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function switchRole(role) {
            window.location.href = '{{ route("login") }}?role=' + role;
        }

        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
