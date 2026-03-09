<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Questionnaire extends Model
{
    use HasFactory;

    protected $fillable = ['pet_id', 'questions', 'answers'];

    public function pet() {
        return $this->belongsTo(Pet::class);
    }
}
