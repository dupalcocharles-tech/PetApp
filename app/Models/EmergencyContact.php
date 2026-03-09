<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmergencyContact extends Model
{
    use HasFactory;

    protected $fillable = ['pet_owner_id', 'name', 'phone', 'relationship'];

    public function petOwner() {
        return $this->belongsTo(PetOwner::class);
    }
}
