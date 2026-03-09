<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PetOwner;
use App\Models\Clinic;
use App\Models\Appointment;

class ClinicReview extends Model
{
    use HasFactory;

    protected $table = 'clinic_reviews';

    protected $fillable = [
        'clinic_id',
        'pet_owner_id', // Updated column name
        'appointment_id',
        'rating',
        'review',
        'images',
    ];

    protected $casts = [
        'images' => 'array',
    ];

    /**
     * Clinic this review belongs to
     */
    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    /**
     * Pet owner who created the review
     */
    public function owner()
    {
        return $this->belongsTo(PetOwner::class, 'pet_owner_id');
    }

    /**
     * Appointment associated with this review
     */
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
