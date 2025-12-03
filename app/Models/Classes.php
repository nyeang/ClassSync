<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    protected $fillable = [
        'name',
        'subject',
        'academic_year',
        'semester',
        'schedule',
        'room',
        'max_students',
        'credits',
        'description',
        'class_code',
        'qr_code',
        'qr_code_image_url',
        'is_archived',
        'created_by_admin_id',
        'created_by_teacher_id'
    ];

    protected $casts = [
        'is_archived' => 'boolean',
        'max_students' => 'integer',
        'credits' => 'decimal:2',
    ];

    // ========== SCOPES ==========
    
    public function scopeActive($query)
    {
        return $query->where('is_archived', false);
    }

    public function scopeArchived($query)
    {
        return $query->where('is_archived', true);
    }

    public function scopeBySubject($query, $subject)
    {
        return $query->where('subject', $subject);
    }

    public function scopeByAcademicYear($query, $year)
    {
        return $query->where('academic_year', $year);
    }

    public function scopeBySemester($query, $semester)
    {
        return $query->where('semester', $semester);
    }

    // ========== RELATIONSHIPS ==========
    
    public function createdByAdmin()
    {
        return $this->belongsTo(User::class, 'created_by_admin_id');
    }

    public function createdByTeacher()
    {
        return $this->belongsTo(User::class, 'created_by_teacher_id');
    }

    public function enrollments()
    {
        return $this->hasMany(ClassEnrollment::class, 'class_id');
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'class_enrollments', 'class_id', 'user_id')
            ->wherePivot('role', 'student')
            ->wherePivot('is_active', true);
    }

    public function teachers()
    {
        return $this->belongsToMany(User::class, 'class_enrollments', 'class_id', 'user_id')
            ->wherePivot('role', 'teacher')
            ->wherePivot('is_active', true);
    }

    // ========== HELPER METHODS ==========
    
    public function getEnrollmentCount()
    {
        return $this->students()->count();
    }

    public function isFull()
    {
        if (!$this->max_students) {
            return false;
        }
        return $this->getEnrollmentCount() >= $this->max_students;
    }

    public function canEnroll()
    {
        return !$this->is_archived && !$this->isFull();
    }

    public function getAvailableSeats()
    {
        if (!$this->max_students) {
            return null;
        }
        return $this->max_students - $this->getEnrollmentCount();
    }

    public function getEnrollmentPercentage()
    {
        if (!$this->max_students) {
            return 0;
        }
        return round(($this->getEnrollmentCount() / $this->max_students) * 100, 1);
    }
}
