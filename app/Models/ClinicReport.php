<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClinicReport extends Model
{
    use HasFactory;

    protected $table = 'clinic_reports';

    protected $fillable = [
        'appointment_id',
        'pet_owner_id',
        'clinic_id',
        'report_message',
        'proof_image',
        'status',
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function owner()
    {
        return $this->belongsTo(PetOwner::class, 'pet_owner_id');
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }
}

