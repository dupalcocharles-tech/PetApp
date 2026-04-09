<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClinicVerificationDenial extends Model
{
    use HasFactory;

    protected $fillable = [
        'clinic_name',
        'email',
        'reason',
        'denied_at',
    ];

    protected $casts = [
        'denied_at' => 'datetime',
    ];
}

