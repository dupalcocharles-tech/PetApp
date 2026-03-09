<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClinicBanAppeal extends Model
{
    use HasFactory;

    protected $table = 'clinic_ban_appeals';

    protected $fillable = [
        'clinic_ban_id',
        'clinic_id',
        'message',
        'status',
        'reviewed_by_admin_id',
        'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function ban()
    {
        return $this->belongsTo(ClinicBan::class, 'clinic_ban_id');
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(Admin::class, 'reviewed_by_admin_id');
    }
}

