<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - ClassSync</title>
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
            --warning: #F59E0B;
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

        .auth-container {
            display: flex;
            min-height: 100vh;
        }

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

        .stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            margin: 40px 0;
        }

        .stat-item {
            text-align: center;
        }

        .stat-item h3 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .stat-item p {
            font-size: 14px;
            opacity: 0.8;
        }

        .testimonial {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 24px;
            margin-top: 40px;
        }

        .testimonial i {
            font-size: 24px;
            opacity: 0.5;
            margin-bottom: 10px;
        }

        .testimonial p {
            font-size: 16px;
            margin-bottom: 15px;
            line-height: 1.6;
        }

        .testimonial-author strong {
            font-size: 14px;
        }

        .testimonial-author span {
            font-size: 13px;
            opacity: 0.7;
            display: block;
            margin-top: 4px;
        }

        .auth-right {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            overflow-y: auto;
        }

        .auth-card {
            width: 100%;
            max-width: 480px;
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
            margin: 20px 0;
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

        .alert {
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

        .error-list {
            margin: 8px 0 0 20px;
            list-style: disc;
        }

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

        .form-hint {
            display: block;
            font-size: 13px;
            color: var(--gray-500);
            margin-top: 6px;
        }

        .password-strength {
            margin-top: 10px;
        }

        .strength-bar {
            height: 4px;
            background: var(--gray-200);
            border-radius: 2px;
            overflow: hidden;
            margin-bottom: 6px;
        }

        .strength-fill {
            height: 100%;
            width: 0%;
            transition: all 0.3s;
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
            font-size: 13px;
            color: var(--gray-500);
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: var(--gray-600);
            cursor: pointer;
        }

        .checkbox-label input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .link {
            color: var(--primary);
            text-decoration: none;
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

        @media (max-width: 992px) {
            .auth-container {
                flex-direction: column;
            }
            .auth-left {
                padding: 40px;
            }
            .stats {
                grid-template-columns: repeat(3, 1fr);
                gap: 20px;
            }
            .testimonial {
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
                <p class="tagline">Join thousands of educators and students</p>

                <div class="stats">
                    <div class="stat-item">
                        <h3>500+</h3>
                        <p>Teachers</p>
                    </div>
                    <div class="stat-item">
                        <h3>10,000+</h3>
                        <p>Students</p>
                    </div>
                    <div class="stat-item">
                        <h3>1,000+</h3>
                        <p>Classes</p>
                    </div>
                </div>

                <div class="testimonial">
                    <i class="fas fa-quote-left"></i>
                    <p id="testimonialText">
                        {{ $role === 'teacher' 
                            ? '"ClassSync has transformed how I manage my classes!"' 
                            : '"ClassSync makes it easy to stay organized!"' }}
                    </p>
                    <div class="testimonial-author">
                        <strong>{{ $role === 'teacher' ? 'Prof. Sarah Johnson' : 'Alex Martinez' }}</strong>
                        <span>{{ $role === 'teacher' ? 'Computer Science Dept' : 'CS Student' }}</span>
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
                    <h2>{{ $role === 'teacher' ? 'Create Teacher Account' : 'Create Student Account' }}</h2>
                    <p>{{ $role === 'teacher' ? 'Start managing your classes today' : 'Start your learning journey' }}</p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <ul class="error-list">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif


                <div class="divider"><span>or</span></div>

                <!-- Register Form -->
                <form id="registerForm" method="POST" action="{{ route('register.post') }}">
                    @csrf
                    <input type="hidden" name="role" value="{{ ucfirst($role) }}">

                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <div class="input-wrapper">
                            <i class="fas fa-user input-icon"></i>
                            <input type="text" id="name" name="name" placeholder="Enter your full name" 
                                   value="{{ old('name') }}" required autofocus>
                        </div>
                    </div>

                    <!-- Student ID (Only for Students) -->
                    @if($role === 'student')
                    <div class="form-group">
                        <label for="student_id">Student ID</label>
                        <div class="input-wrapper">
                            <i class="fas fa-id-card input-icon"></i>
                            <input type="text" id="student_id" name="student_id" 
                                   placeholder="Enter your student ID" 
                                   value="{{ old('student_id') }}" required>
                        </div>
                        <small class="form-hint">Your unique student identification number</small>
                    </div>
                    @endif

                    <!-- Department (Only for Teachers) -->
                    @if($role === 'teacher')
                    <div class="form-group">
                        <label for="department">Department (Optional)</label>
                        <div class="input-wrapper">
                            <i class="fas fa-building input-icon"></i>
                            <input type="text" id="department" name="department" 
                                   placeholder="e.g., Computer Science" value="{{ old('department') }}">
                        </div>
                    </div>
                    @endif

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <div class="input-wrapper">
                            <i class="fas fa-envelope input-icon"></i>
                            <input type="email" id="email" name="email" placeholder="Enter your email" 
                                   value="{{ old('email') }}" required>
                        </div>
                        <small class="form-hint">Use your institutional email if available</small>
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone Number (Optional)</label>
                        <div class="input-wrapper">
                            <i class="fas fa-phone input-icon"></i>
                            <input type="tel" id="phone" name="phone" 
                                   placeholder="Enter your phone number" value="{{ old('phone') }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-wrapper">
                            <i class="fas fa-lock input-icon"></i>
                            <input type="password" id="password" name="password" 
                                   placeholder="Create a strong password" required minlength="8">
                            <button type="button" class="toggle-password" onclick="togglePassword('password')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="password-strength">
                            <div class="strength-bar">
                                <div class="strength-fill" id="strengthFill"></div>
                            </div>
                            <span class="strength-text" id="strengthText">Password strength</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Confirm Password</label>
                        <div class="input-wrapper">
                            <i class="fas fa-lock input-icon"></i>
                            <input type="password" id="password_confirmation" name="password_confirmation" 
                                   placeholder="Re-enter your password" required>
                            <button type="button" class="toggle-password" onclick="togglePassword('password_confirmation')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="terms" required>
                            <span>I agree to the <a href="#" class="link">Terms of Service</a> 
                                  and <a href="#" class="link">Privacy Policy</a></span>
                        </label>
                    </div>

                    <button type="submit" class="btn-primary">
                        Create Account
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </form>

                <div class="auth-footer">
                    <p>Already have an account? 
                        <a href="{{ route('login', ['role' => $role]) }}" class="link-bold">Sign In</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function switchRole(role) {
            window.location.href = '{{ route("register") }}?role=' + role;
        }

        function togglePassword(fieldId) {
            const input = document.getElementById(fieldId);
            const button = input.parentElement.querySelector('.toggle-password i');

            if (input.type === 'password') {
                input.type = 'text';
                button.classList.remove('fa-eye');
                button.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                button.classList.remove('fa-eye-slash');
                button.classList.add('fa-eye');
            }
        }

        // Password strength checker
        document.getElementById('password')?.addEventListener('input', function() {
            const password = this.value;
            const strengthFill = document.getElementById('strengthFill');
            const strengthText = document.getElementById('strengthText');

            if (!password) {
                strengthFill.className = 'strength-fill';
                strengthText.textContent = 'Password strength';
                return;
            }

            let strength = 0;
            if (password.length >= 8) strength++;
            if (password.length >= 12) strength++;
            if (/[a-z]/.test(password)) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^a-zA-Z0-9]/.test(password)) strength++;

            strengthFill.className = 'strength-fill';
            if (strength <= 2) {
                strengthFill.classList.add('weak');
                strengthText.textContent = 'Weak password';
            } else if (strength <= 4) {
                strengthFill.classList.add('medium');
                strengthText.textContent = 'Medium password';
            } else {
                strengthFill.classList.add('strong');
                strengthText.textContent = 'Strong password';
            }
        });
    </script>
</body>
</html>
