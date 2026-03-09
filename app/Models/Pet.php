<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Pet extends Model
{
    protected $fillable = [
        'name',
        'pet_owner_id',
        'species',
        'breed',
        'age',
        'gender',
        'animal_id', // optional if you also link to Animal
        'image',     // added for pet image
    ];

    // Pet belongs to an animal
    public function animal()
    {
        return $this->belongsTo(Animal::class);
    }

    // Pet belongs to a pet owner
    public function owner()
    {
        return $this->belongsTo(PetOwner::class, 'pet_owner_id');
    }

    // Pet has many appointments
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Get full URL for pet image. Returns default if none.
     */
    public function getImageUrlAttribute()
    {
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            return asset('storage/'.$this->image);
        }

        return asset('images/default_pet.jpg'); // default image in public/images
    }
}
