<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Show unified login form with role selection
     */
    public function showLoginForm(Request $request)
    {
        // Check if already logged in
        if (Auth::check()) {
            return redirect(Auth::user()->getDashboardRoute());
        }

        $role = $request->get('role', 'teacher'); // default to teacher
        return view('auth.login', compact('role'));
    }

    /**
     * Handle login for both Teacher and Student
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'role' => ['required', 'in:Teacher,Student'],
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return back()->withErrors($validator)->withInput($request->only('email', 'role'));
        }

        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
            'role' => $request->role,
            'is_active' => true,
        ];

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();
            $redirectRoute = $user->getDashboardRoute();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Login successful!',
                    'redirect' => $redirectRoute
                ]);
            }

            return redirect()->intended($redirectRoute);
        }

        $errorMessage = $request->role === 'Teacher' 
            ? 'Invalid credentials or you do not have teacher access.' 
            : 'Invalid credentials or you do not have student access.';

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => $errorMessage
            ], 401);
        }

        throw ValidationException::withMessages([
            'email' => [$errorMessage],
        ]);
    }

    /**
     * Show unified registration form with role selection
     */
    public function showRegisterForm(Request $request)
    {
        // Check if already logged in
        if (Auth::check()) {
            return redirect(Auth::user()->getDashboardRoute());
        }

        $role = $request->get('role', 'teacher'); // default to teacher
        return view('auth.register', compact('role'));
    }

    /**
     * Handle registration for both Teacher and Student
     */
    public function register(Request $request)
    {
        // Base validation rules
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:Teacher,Student'],
            'phone' => ['nullable', 'string', 'max:20'],
        ];

        // Add role-specific validation
        if ($request->role === 'Student') {
            $rules['student_id'] = ['required', 'string', 'max:50', 'unique:users,student_id'];
        } else {
            $rules['department'] = ['nullable', 'string', 'max:100'];
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        // Create user with role-specific data
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'is_active' => true,
        ];

        if ($request->role === 'Student') {
            $userData['student_id'] = $request->student_id;
        } else {
            $userData['department'] = $request->department;
        }

        $user = User::create($userData);

        Auth::login($user);

        $redirectRoute = $user->getDashboardRoute();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Registration successful!',
                'redirect' => $redirectRoute
            ]);
        }

        return redirect($redirectRoute)->with('success', 'Welcome to ClassSync!');
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        $role = Auth::user()->role;

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect to appropriate login page
        $loginRoute = $role === 'Teacher' ? 'login' : 'login';
        return redirect()->route($loginRoute, ['role' => strtolower($role)])
            ->with('success', 'Logged out successfully.');
    }

    /**
     * Check email availability (AJAX)
     */
    public function checkEmail(Request $request)
    {
        $exists = User::where('email', $request->email)->exists();
        return response()->json(['exists' => $exists]);
    }

    /**
     * Check student ID availability (AJAX)
     */
    public function checkStudentId(Request $request)
    {
        $exists = User::where('student_id', $request->student_id)->exists();
        return response()->json(['exists' => $exists]);
    }
}
