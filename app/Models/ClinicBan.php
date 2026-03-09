<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ClinicBan extends Model
{
    use HasFactory;

    protected $table = 'clinic_bans';

    protected $fillable = [
        'clinic_id',
        'admin_id',
        'reason',
        'banned_until',
        'status',
    ];

    protected $casts = [
        'banned_until' => 'datetime',
    ];

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active')->where(function ($q) {
            $q->whereNull('banned_until')->orWhere('banned_until', '>', now());
        });
    }

    public function daysRemaining(): ?int
    {
        if (!$this->banned_until instanceof Carbon) {
            return null;
        }

        return now()->diffInDays($this->banned_until, false);
    }
}

