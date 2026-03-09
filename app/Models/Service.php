<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'name',
        'description', // ✅ add this so it saves properly
        'price',
        'clinic_id',   // ✅ link directly to clinic
        'animal_type',
        'location_type',
        'images',
        'is_available',
        'home_slots',
    ];

    protected $casts = [
        'images' => 'array',
        'is_available' => 'boolean',
        'home_slots' => 'array',
    ];

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    // If you no longer use animal_id, you can safely remove this:
    // public function animal()
    // {
    //     return $this->belongsTo(Animal::class);
    // }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
