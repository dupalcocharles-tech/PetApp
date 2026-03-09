<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Clinic;

class Animal extends Model
{
    protected $fillable = ['name'];

    // --------------------
    // Get all clinics that specialize in this animal type
    // --------------------
    public function clinics()
    {
        return Clinic::whereJsonContains('specializations', $this->name)->get();
    }
}
