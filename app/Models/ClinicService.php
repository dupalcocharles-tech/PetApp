<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClinicService extends Model
{
    protected $fillable = [
        'clinic_id',
        'service_id',
        'animal_id',  // ✅ already here
        'price',      // ✅ added
    ];

    // Belongs to a clinic
    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    // Belongs to a service
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    // Belongs to an animal
    public function animal()
    {
        return $this->belongsTo(Animal::class);
    }

    // Has many appointments
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
