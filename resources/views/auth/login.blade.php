<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - ClassSync</title>

    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#1a1a2e">
    <link rel="manifest" href="/manifest.json">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
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
        .auth-container { display: flex; min-height: 100vh; }
        .auth-left {
            flex: 1;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .branding { max-width: 500px; }
        .logo-section {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }
        .logo-icon { font-size: 48px; }
        .logo-section h1 { font-size: 36px; font-weight: 700; }
        .tagline { font-size: 18px; opacity: 0.9; margin-bottom: 40px; }
        .features { display: flex; flex-direction: column; gap: 24px; }
        .feature-item { display: flex; gap: 20px; align-items: flex-start; }
        .feature-item i { font-size: 32px; opacity: 0.9; }
        .feature-item h3 { font-size: 18px; margin-bottom: 5px; }
        .feature-item p { font-size: 14px; opacity: 0.8; }

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

        .auth-header { text-align: center; margin-bottom: 32px; }
        .auth-header h2 {
            font-size: 28px;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 8px;
        }
        .auth-header p { color: var(--gray-500); font-size: 15px; }

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

        .form-group { margin-bottom: 20px; }
        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: var(--gray-700);
            margin-bottom: 8px;
        }
        .input-wrapper { position: relative; display: flex; align-items: center; }
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
        .link { color: var(--primary); text-decoration: none; font-size: 14px; font-weight: 500; }
        .link:hover { text-decoration: underline; }

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
        .auth-footer p { color: var(--gray-600); font-size: 14px; }

        @media (max-width: 992px) {
            .auth-container { flex-direction: column; }
            .auth-left { padding: 40px; }
            .features { display: none; }
        }
        @media (max-width: 768px) {
            .auth-right { padding: 20px; }
            .auth-card { padding: 30px 20px; }
        }

        @media (max-width: 640px) {
            .container { padding: 1rem !important; }
            input, select, textarea, button { font-size: 16px !important; }
        }
        html { height: -webkit-fill-available; }
        body {
            min-height: 100vh;
            min-height: -webkit-fill-available;
        }
        button, a { min-height: 44px; min-width: 44px; }
        * { -webkit-overflow-scrolling: touch; }
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
                @php
                    $role = $role ?? 'teacher';
                @endphp

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
                    <button class="role-tab {{ $role === 'admin' ? 'active' : '' }}"
                            onclick="switchRole('admin')">
                        <i class="fas fa-user-shield"></i>
                        <span>Admin</span>
                    </button>
                </div>

                <div class="auth-header">
                    <h2>
                        @if($role === 'teacher') Teacher Login
                        @elseif($role === 'student') Student Login
                        @else Admin Login
                        @endif
                    </h2>
                    <p>
                        @if($role === 'teacher')
                            Sign in to manage your classes
                        @elseif($role === 'student')
                            Sign in to access your classes
                        @else
                            Sign in to manage users and classes
                        @endif
                    </p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $errors->first() }}
                    </div>
                @endif

                <!-- Login Form -->
                <form id="loginForm" method="POST" action="{{ route('login.post') }}">
                    @csrf
                    <input type="hidden" name="role" value="{{ ucfirst($role) }}">

                    <div class="form-group">
                        <label for="email">Username or Email</label>
                        <div class="input-wrapper">
                            <i class="fas fa-envelope input-icon"></i>
                            <input
                                type="text"
                                id="email"
                                name="email"
                                placeholder="Enter username or email (@paragoniu.edu.kh)"
                                value="{{ old('email') }}"
                                required
                                autofocus
                            >
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
                    <p>Accounts are created by the administrator.</p>
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
