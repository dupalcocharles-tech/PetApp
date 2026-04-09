<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;
use App\Models\Service;
use App\Models\Appointment;
use App\Models\User;
use App\Models\ClinicReview;
use App\Models\ClinicReport;
use App\Models\ClinicBan;
use App\Models\ClinicBanAppeal;

class Clinic extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'clinic_name',
        'description',
        'address',
        'phone',
        'email',
        'password',
        'specializations',
        'is_verified',
        'is_subscribed',
        'documents',
        'profile_image',
        'qr_code',
        'gallery',
        'opening_time',
        'closing_time',
        'is_24_hours',
        'subscription_receipt',
        'subscription_started_at',
        'subscription_expires_at',
        'verification_denied_at',
        'verification_denied_reason',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'is_subscribed' => 'boolean',
        'documents' => 'array',
        'specializations' => 'array',
        'gallery' => 'array',
        'subscription_started_at' => 'datetime',
        'subscription_expires_at' => 'datetime',
        'verification_denied_at' => 'datetime',
    ];

    public function isVerified()
    {
        return $this->is_verified === true;
    }

    public function subscriptionIsExpired(): bool
    {
        return $this->subscription_expires_at instanceof Carbon
            ? $this->subscription_expires_at->isPast()
            : false;
    }

    public function subscriptionDaysLeft(): ?int
    {
        if (!$this->subscription_expires_at instanceof Carbon) {
            return null;
        }

        return now()->diffInDays($this->subscription_expires_at, false);
    }

    public function staff()
    {
        return $this->hasMany(User::class, 'clinic_id');
    }

    public function services()
    {
        return $this->hasMany(Service::class, 'clinic_id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'clinic_id');
    }

    // ⭐ Reviews
    public function reviews()
    {
        return $this->hasMany(ClinicReview::class, 'clinic_id');
    }

    // ⭐ Average rating accessor
    public function getAverageRatingAttribute()
    {
        return round($this->reviews()->avg('rating'), 1);
    }

    /**
     * Accessor for "Open Now" status
     */
    public function getIsOpenAttribute()
    {
        if ($this->is_24_hours) {
            return true;
        }

        if (!$this->opening_time || !$this->closing_time) {
            return false;
        }

        $now = Carbon::now();
        $time = $now->format('H:i:s');

        if ($this->closing_time < $this->opening_time) {
            return $time >= $this->opening_time || $time <= $this->closing_time;
        }

        return $time >= $this->opening_time && $time <= $this->closing_time;
    }

    /**
     * Check if clinic is open at a specific time
     */
    public function isOpenAt($dateTime)
    {
        if ($this->is_24_hours) {
            return true;
        }

        if (!$this->opening_time || !$this->closing_time) {
            return false;
        }

        $time = Carbon::parse($dateTime)->format('H:i:s');

        if ($this->closing_time < $this->opening_time) {
            return $time >= $this->opening_time || $time <= $this->closing_time;
        }

        return $time >= $this->opening_time && $time <= $this->closing_time;
    }

    public function reports()
    {
        return $this->hasMany(ClinicReport::class);
    }

    public function bans()
    {
        return $this->hasMany(ClinicBan::class);
    }

    public function banAppeals()
    {
        return $this->hasMany(ClinicBanAppeal::class);
    }
}
