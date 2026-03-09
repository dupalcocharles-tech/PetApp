<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentMedication extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'pet_id',
        'medicine_name',
        'dosage',
        'schedule',
        'treatment_start',
        'treatment_end',
        'vet_id',
        'progress',
    ];
}
