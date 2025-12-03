<?php
// app/Models/PasswordResetRequest.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordResetRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'processed_by',
        'processed_at',
        'notes',
    ];

    protected $casts = [
        'processed_at' => 'datetime',
    ];

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function processedByAdmin()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}
