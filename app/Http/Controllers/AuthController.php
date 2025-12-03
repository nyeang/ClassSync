<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLoginForm(Request $request)
    {
        $role = $request->get('role', 'teacher'); // teacher|student|admin
        return view('auth.login', compact('role'));
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required',       // can be username or email
            'password' => 'required',
            'role'     => 'required|in:Admin,Teacher,Student',
        ]);

        $inputEmail = $request->email;
        $normalizedEmail = $this->normalizeEmail($inputEmail);

        if (!$normalizedEmail) {
            throw ValidationException::withMessages([
                'email' => 'Only @paragoniu.edu.kh accounts are allowed.',
            ]);
        }

        $credentials = [
            'email'     => $normalizedEmail,
            'password'  => $request->password,
            'role'      => $request->role,   // ensure correct role
            'is_active' => true,
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(Auth::user()->getDashboardRoute());
        }

        throw ValidationException::withMessages([
            'email' => 'Invalid credentials or role.',
        ]);
    }

    private function normalizeEmail(string $input): ?string
    {
        $input = trim($input);

        // Already full paragoniu email
        if (str_ends_with($input, '@paragoniu.edu.kh')) {
            return $input;
        }

        // No @ → treat as username, append domain
        if (!str_contains($input, '@')) {
            return $input . '@paragoniu.edu.kh';
        }

        // Has @ but wrong domain → reject
        return null;
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // Redirect to login page
        return redirect()->route('login')->with('success', 'You have been logged out successfully.');
    }
}
