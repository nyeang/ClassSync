<?php
// app/Http/Controllers/Admin/DashboardController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Classes;
use App\Models\ClassEnrollment;
use App\Models\PasswordResetRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard
     */

    /**
     * Get dashboard statistics (AJAX)
     */
    public function getStats()
    {
        $now = Carbon::now();
        $lastMonth = Carbon::now()->subMonth();

        // Current counts
        $totalUsers = User::where('role', '!=', 'admin')->count();
        $totalTeachers = User::teachers()->count();
        $totalStudents = User::students()->count();
        $activeClasses = Classes::active()->count();
        $pendingRequests = PasswordResetRequest::pending()->count();

        // Last month counts for comparison
        $lastMonthUsers = User::where('role', '!=', 'admin')
                              ->where('created_at', '<', $lastMonth)
                              ->count();
        $lastMonthClasses = Classes::where('created_at', '<', $lastMonth)
                                   ->where('is_archived', false)
                                   ->count();

        // Calculate trends
        $usersTrend = $lastMonthUsers > 0 
            ? round((($totalUsers - $lastMonthUsers) / $lastMonthUsers) * 100, 1)
            : 100;
        $classesTrend = $lastMonthClasses > 0 
            ? round((($activeClasses - $lastMonthClasses) / $lastMonthClasses) * 100, 1)
            : 100;

        return response()->json([
            'success' => true,
            'data' => [
                'total_users' => $totalUsers,
                'total_teachers' => $totalTeachers,
                'total_students' => $totalStudents,
                'active_classes' => $activeClasses,
                'pending_requests' => $pendingRequests,
                'users_trend' => $usersTrend,
                'classes_trend' => $classesTrend,
            ]
        ]);
    }

    /**
     * Get recent users (AJAX)
     */
    public function getRecentUsers(Request $request)
    {
        $limit = $request->get('limit', 5);

        $users = User::where('role', '!=', 'admin')
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
     * Get recent classes (AJAX)
     */
    public function getRecentClasses(Request $request)
    {
        $limit = $request->get('limit', 5);

        $classes = Classes::with(['teachers', 'students'])
                          ->orderBy('created_at', 'desc')
                          ->limit($limit)
                          ->get()
                          ->map(function ($class) {
                              return [
                                  'id' => $class->id,
                                  'name' => $class->name,
                                  'academic_year' => $class->academic_year,
                                  'class_code' => $class->class_code,
                                  'teacher' => $class->teachers->first()?->name ?? 'Unassigned',
                                  'student_count' => $class->students->count(),
                                  'is_archived' => $class->is_archived,
                                  'created_at' => $class->created_at->format('M d, Y'),
                              ];
                          });

        return response()->json([
            'success' => true,
            'data' => $classes
        ]);
    }

    /**
     * Get pending password reset requests (AJAX)
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
    public function processPasswordReset(Request $request, $id)
    {
        $resetRequest = PasswordResetRequest::with('user')->findOrFail($id);

        if ($resetRequest->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'This request has already been processed'
            ], 400);
        }

        // Generate new password
        $newPassword = $this->generateSecurePassword();

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

        // Log the action
        activity()
            ->performedOn($resetRequest->user)
            ->causedBy(auth()->user())
            ->withProperties(['action' => 'password_reset'])
            ->log('Password reset processed');

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
}
