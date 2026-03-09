<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordResetOtp extends Model
{
    use HasFactory;

    protected $table = 'password_resets_otp';

    protected $fillable = [
        'user_id',
        'user_type',
        'identifier',
        'otp_code',
        'attempt_count',
        'expires_at',
        'is_verified',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_verified' => 'boolean',
    ];

    public function isExpired()
    {
        return $this->expires_at->isPast();
    }
}
