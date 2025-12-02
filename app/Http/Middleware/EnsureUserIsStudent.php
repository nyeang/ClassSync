<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsStudent
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login', ['role' => 'student'])
                ->with('error', 'Please login to access this page.');
        }
        
        // Check if user is a student
        if (!Auth::user()->isStudent()) {
            Auth::logout();
            return redirect()->route('login', ['role' => 'student'])
                ->with('error', 'Access denied. Student account required.');
        }
        
        // Check if user is active
        if (!Auth::user()->is_active) {
            Auth::logout();
            return redirect()->route('login', ['role' => 'student'])
                ->with('error', 'Your account has been deactivated.');
        }
        
        return $next($request);
    }
}
