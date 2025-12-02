<?php

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
        'google_id',
        'student_id',
        'department',
        'phone',
        'is_active',
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

    /**
     * Check if user is a teacher
     */
    public function isTeacher()
    {
        return $this->role === 'Teacher';
    }

    /**
     * Check if user is a student
     */
    public function isStudent()
    {
        return $this->role === 'Student';
    }

    /**
     * Get dashboard route based on role
     */
    public function getDashboardRoute()
    {
        return $this->isTeacher() 
            ? route('teacher.dashboard') 
            : route('student.dashboard');
    }

    /**
     * Get profile picture URL
     */
    public function getProfilePictureAttribute()
    {
        // Return default avatar or user's profile picture
        return $this->attributes['profile_picture'] ?? 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=4F46E5&color=fff';
    }
}
