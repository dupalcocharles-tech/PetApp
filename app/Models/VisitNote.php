<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Appointment; // Add this line

class VisitNote extends Model
{
    use HasFactory;

    protected $fillable = ['appointment_id', 'notes'];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
