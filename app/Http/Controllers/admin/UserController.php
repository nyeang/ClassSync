<?php
// app/Http/Controllers/Admin/UserController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Classes;
use App\Models\PasswordResetRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserController extends Controller
{
    /**
     * Generate secure random password
     */
    private function generateSecurePassword($length = 12): string
    {
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $numbers = '0123456789';
        $symbols = '@#$%&*!?';

        $password = '';
        $password .= $uppercase[random_int(0, strlen($uppercase) - 1)];
        $password .= $lowercase[random_int(0, strlen($lowercase) - 1)];
        $password .= $numbers[random_int(0, strlen($numbers) - 1)];
        $password .= $symbols[random_int(0, strlen($symbols) - 1)];

        $allChars = $uppercase . $lowercase . $numbers . $symbols;
        for ($i = strlen($password); $i < $length; $i++) {
            $password .= $allChars[random_int(0, strlen($allChars) - 1)];
        }

        return str_shuffle($password);
    }

    /**
     * Display admin dashboard with stats
     */
    public function index()
    {
        $users = User::where('role', '!=', 'Admin')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.admin-dashboard', compact('users'));
    }

    public function getAllUser(){
        return view('admin.user-page');
    }

    /**
     * Get dashboard statistics (AJAX)
     */


    /**
     * Get recent users (AJAX)
     */
    public function getRecentUsers(Request $request)
    {
        $limit = $request->get('limit', 5);

        $users = User::where('role', '!=', 'Admin')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'initials' => $user->initials,
                    'is_active' => $user->is_active,
                    'created_at' => $user->created_at->format('M d, Y'),
                    'created_at_human' => $user->created_at->diffForHumans(),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

    /**
     * Get all users with filtering (AJAX)
     */
    public function getUsers(Request $request)
    {
        $query = User::where('role', '!=', 'Admin');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $users = $query->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

    /**
     * Get dashboard statistics with trends
     */
    /**
     * Get dashboard statistics with trends
     */
    public function getStats()
    {
        // Get date ranges
        $now = Carbon::now();
        $currentMonthStart = $now->copy()->startOfMonth();
        $lastMonthStart = $now->copy()->subMonth()->startOfMonth();
        $lastMonthEnd = $now->copy()->subMonth()->endOfMonth();

        // === TOTAL USERS ===
        $totalUsers = User::count();
        $usersCreatedThisMonth = User::where('created_at', '>=', $currentMonthStart)->count();
        $usersCreatedLastMonth = User::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count();
        $usersTrend = $this->calculateTrend($usersCreatedThisMonth, $usersCreatedLastMonth);

        // === ACTIVE CLASSES ===
        $activeClasses = Classes::where('is_archived', false)->count();
        $classesCreatedThisMonth = Classes::where('created_at', '>=', $currentMonthStart)
            ->where('is_archived', false)->count();
        $classesCreatedLastMonth = Classes::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
            ->where('is_archived', false)->count();
        $classesTrend = $this->calculateTrend($classesCreatedThisMonth, $classesCreatedLastMonth);

        // === PENDING PASSWORD REQUESTS ===
        $pendingRequests = PasswordResetRequest::where('status', 'pending')->count();
        $requestsThisMonth = PasswordResetRequest::where('created_at', '>=', $currentMonthStart)->count();
        $requestsLastMonth = PasswordResetRequest::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count();
        $requestsTrend = $this->calculateTrend($requestsThisMonth, $requestsLastMonth);

        // === TOTAL TEACHERS ===
        $totalTeachers = User::where('role', 'Teacher')->where('is_active', true)->count();
        $teachersCreatedThisMonth = User::where('role', 'Teacher')
            ->where('is_active', true)
            ->where('created_at', '>=', $currentMonthStart)->count();
        $teachersCreatedLastMonth = User::where('role', 'Teacher')
            ->where('is_active', true)
            ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count();
        $teachersTrend = $this->calculateTrend($teachersCreatedThisMonth, $teachersCreatedLastMonth);

        return response()->json([
            'success' => true,
            'data' => [
                'total_users' => $totalUsers,
                'users_trend' => $usersTrend,
                'users_this_month' => $usersCreatedThisMonth,
                'users_last_month' => $usersCreatedLastMonth,

                'active_classes' => $activeClasses,
                'classes_trend' => $classesTrend,
                'classes_this_month' => $classesCreatedThisMonth,
                'classes_last_month' => $classesCreatedLastMonth,

                'pending_requests' => $pendingRequests,
                'requests_trend' => $requestsTrend,
                'requests_this_month' => $requestsThisMonth,
                'requests_last_month' => $requestsLastMonth,

                'total_teachers' => $totalTeachers,
                'teachers_trend' => $teachersTrend,
                'teachers_this_month' => $teachersCreatedThisMonth,
                'teachers_last_month' => $teachersCreatedLastMonth,
            ]
        ]);
    }

    /**
     * Calculate percentage trend
     */
    private function calculateTrend($currentMonthNew, $lastMonthNew)
    {
        // If both are 0, return 0
        if ($currentMonthNew == 0 && $lastMonthNew == 0) {
            return 0;
        }

        // If last month was 0 but this month has data
        if ($lastMonthNew == 0 && $currentMonthNew > 0) {
            return 100;
        }

        // If this month is 0 but last month had data (negative 100%)
        if ($currentMonthNew == 0 && $lastMonthNew > 0) {
            return -100;
        }

        // Calculate percentage change
        $difference = $currentMonthNew - $lastMonthNew;
        $percentageChange = ($difference / $lastMonthNew) * 100;

        return round($percentageChange, 1);
    }


    /**
     * Create new user with auto-generated password
     */
public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|max:255',
        'role' => 'required|in:Teacher,Student',
        'gender' => 'nullable|string|max:20',
        'date_of_birth' => 'nullable|date',
        'phone' => 'nullable|string|max:20',
        'department' => 'nullable|string|max:100',
        'academic_year' => 'nullable|string|max:20',
        'address' => 'nullable|string',
    ]);

    // Build full email with domain
    $emailPrefix = $request->email;
    $email = str_contains($emailPrefix, '@')
        ? $emailPrefix
        : $emailPrefix . '@paragon.edu.kh';

    // Validate email domain
    if (!str_ends_with($email, '@paragon.edu.kh')) {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Only @paragon.edu.kh emails are allowed'
            ], 422);
        }

        return back()->withErrors(['email' => 'Only @paragon.edu.kh emails are allowed']);
    }

    // Check if email already exists
    if (User::where('email', $email)->exists()) {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'This email is already registered'
            ], 422);
        }

        return back()->withErrors(['email' => 'This email is already registered']);
    }

    // Generate random password
    $password = $this->generateSecurePassword(12);

    // Create user
    $user = User::create([
        'name' => $request->name,
        'email' => $email,
        'password' => Hash::make($password),
        'role' => $request->role,
        'gender' => $request->gender,
        'date_of_birth' => $request->date_of_birth,
        'phone' => $request->phone,
        'department' => $request->department,
        'academic_year' => $request->academic_year,
        'address' => $request->address,
        'is_active' => true,
    ]);

    // Return JSON for AJAX requests
    if ($request->expectsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'password' => $password, // Plain password for popup display
            ]
        ]);
    }

    // Return redirect for form submissions (with flash data for popup)
    return redirect()->route('admin.dashboard')
        ->with('success', 'User created successfully!')
        ->with('credentials', [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'password' => $password,
        ]);
}

/**
 * Update user
 */
public function update(Request $request, $id)
{
    $user = User::findOrFail($id);
    
    $request->validate([
        'name' => 'sometimes|string|max:255',
        'role' => 'sometimes|in:Teacher,Student',
        'gender' => 'nullable|string|max:20',
        'date_of_birth' => 'nullable|date',
        'phone' => 'nullable|string|max:20',
        'department' => 'nullable|string|max:100',
        'academic_year' => 'nullable|string|max:20',
        'address' => 'nullable|string',
        'is_active' => 'sometimes|boolean',
    ]);

    $user->update($request->only([
        'name', 
        'role', 
        'gender', 
        'date_of_birth', 
        'phone', 
        'department', 
        'academic_year', 
        'address', 
        'is_active'
    ]));

    if ($request->expectsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
            'data' => $user
        ]);
    }

    return redirect()->route('admin.dashboard')->with('success', 'User updated!');
}


    /**
     * Toggle user active status
     */
    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_active' => !$user->is_active]);

        return response()->json([
            'success' => true,
            'message' => $user->is_active ? 'User activated' : 'User deactivated',
            'data' => ['is_active' => $user->is_active]
        ]);
    }

    /**
     * Reset user password (admin initiated)
     */
    public function resetPassword($id)
    {
        $user = User::findOrFail($id);
        $newPassword = $this->generateSecurePassword(12);

        $user->update([
            'password' => Hash::make($newPassword)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password reset successfully',
            'data' => [
                'user_name' => $user->name,
                'user_email' => $user->email,
                'new_password' => $newPassword,
            ]
        ]);
    }

    /**
     * Get pending password reset requests
     */
    public function getPasswordRequests(Request $request)
    {
        $limit = $request->get('limit', 10);

        $requests = PasswordResetRequest::with('user')
            ->pending()
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($req) {
                return [
                    'id' => $req->id,
                    'user_id' => $req->user_id,
                    'user_name' => $req->user->name,
                    'user_email' => $req->user->email,
                    'user_role' => $req->user->role,
                    'user_initials' => $req->user->initials,
                    'requested_at' => $req->created_at->format('M d, Y H:i'),
                    'requested_at_human' => $req->created_at->diffForHumans(),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $requests
        ]);
    }

    /**
     * Process password reset request
     */
    public function processPasswordRequest($id)
    {
        $resetRequest = PasswordResetRequest::with('user')->findOrFail($id);

        if ($resetRequest->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'This request has already been processed'
            ], 400);
        }

        // Generate new password
        $newPassword = $this->generateSecurePassword(12);

        // Update user password
        $resetRequest->user->update([
            'password' => Hash::make($newPassword)
        ]);

        // Mark request as approved
        $resetRequest->update([
            'status' => 'approved',
            'processed_by' => auth()->id(),
            'processed_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password reset successfully',
            'data' => [
                'user_name' => $resetRequest->user->name,
                'user_email' => $resetRequest->user->email,
                'new_password' => $newPassword,
            ]
        ]);
    }

    /**
     * Delete user
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully'
            ]);
        }

        return redirect()->route('admin.dashboard')->with('success', 'User deleted!');
    }
}
