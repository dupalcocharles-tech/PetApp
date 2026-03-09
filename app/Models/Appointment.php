<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PetOwner;
use App\Models\Pet;
use App\Models\Clinic;
use App\Models\Service;
use App\Models\ClinicReview;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'pet_owner_id',
        'pet_id',
        'clinic_id',
        'service_id',
        'service_location',
        'service_address',
        'service_contact',
        'appointment_date',
        'status',
        'notes',
        'prescriptions',
        'next_appointment',
        'next_notes',
        'reminder_sent',
        'payment_status',
        'payment_method',
        'payment_option',
        'paid_amount',
        'transaction_id',
        'payment_receipt',
    ];

    protected $casts = [
        'appointment_date' => 'datetime',
        'next_appointment' => 'datetime',
        'reminder_sent' => 'boolean',
    ];

    // Relationships
    public function owner()
    {
        return $this->belongsTo(PetOwner::class, 'pet_owner_id');
    }

    public function pet()
    {
        return $this->belongsTo(Pet::class, 'pet_id');
    }

    public function pets()
    {
        return $this->belongsToMany(Pet::class, 'appointment_pet', 'appointment_id', 'pet_id')->withTimestamps();
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class, 'clinic_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function medicalRecord()
    {
        return $this->hasOne(AppointmentMedicalRecord::class, 'appointment_id');
    }

    public function medicalRecords()
    {
        return $this->hasMany(AppointmentMedicalRecord::class, 'appointment_id');
    }

    public function medications()
    {
        return $this->hasMany(AppointmentMedication::class, 'appointment_id');
    }

    // Review
    public function review()
    {
        return $this->hasOne(ClinicReview::class, 'appointment_id');
    }
}
