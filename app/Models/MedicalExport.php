<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalExport extends Model
{
    use HasFactory;

    protected $fillable = ['pet_id', 'export_data'];

    public function pet() {
        return $this->belongsTo(Pet::class);
    }
}
