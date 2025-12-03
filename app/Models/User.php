<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'gender',
        'date_of_birth',
        'phone',
        'department',
        'academic_year',
        'address',
        'is_active',
        'profile_picture',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    // ========== SCOPES ==========
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeStudents($query)
    {
        return $query->where('role', 'Student');
    }

    public function scopeTeachers($query)
    {
        return $query->where('role', 'Teacher');
    }

    // ========== ROLE HELPERS ==========
    public function isTeacher()
    {
        return $this->role === 'Teacher';
    }

    public function isStudent()
    {
        return $this->role === 'Student';
    }

    public function isAdmin()
    {
        return $this->role === 'Admin';
    }

    // ========== DASHBOARD ROUTE ==========
    public function getDashboardRoute()
    {
        return match ($this->role) {
            'Admin' => route('admin.dashboard'),
            'Teacher' => route('teacher.dashboard'),
            'Student' => route('student.dashboard'),
            default => route('auth.login'),
        };
    }

    // ========== ACCESSORS ==========
    public function getProfilePictureAttribute()
    {
        return $this->attributes['profile_picture']
            ?? 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=4F46E5&color=fff';
    }

    public function getInitialsAttribute(): string
    {
        $words = explode(' ', $this->name);
        $initials = '';
        foreach (array_slice($words, 0, 2) as $word) {
            $initials .= strtoupper(substr($word, 0, 1));
        }
        return $initials;
    }

    // ========== RELATIONSHIPS ==========
    public function classEnrollments()
    {
        return $this->hasMany(ClassEnrollment::class);
    }

    public function passwordResetRequests()
    {
        return $this->hasMany(PasswordResetRequest::class);
    }

    public function createdClasses()
    {
        return $this->hasMany(Classes::class, 'created_by_admin_id');
    }
}
