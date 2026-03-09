<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // for authentication
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PetOwner extends Authenticatable
{
    use HasFactory, Notifiable;

    // 🔐 Guard name for authentication (used in config/auth.php)
    protected $guard = 'pet_owner';

    // ✅ Allow mass assignment for editable fields
    protected $fillable = [
        'username',
        'full_name',
        'email',
        'phone',
        'address',
        'password',
        'profile_image', // for uploaded profile pictures
    ];

    // 🔒 Hidden fields for arrays or JSON
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // ✅ Relationships
    public function pets()
    {
        return $this->hasMany(Pet::class, 'pet_owner_id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'pet_owner_id');
    }

    // ✅ Accessor for profile image (returns default if none)
    public function getProfileImageUrlAttribute()
    {
        return $this->profile_image
            ? asset('storage/' . $this->profile_image)
            : asset('images/owner.png');
    }
}
