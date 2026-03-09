<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'user';       // Your ERD main user table
    protected $primaryKey = 'user_id';
    public $timestamps = false;      // change to true if you have created_at/updated_at

    protected $fillable = [
        'username',
        'email',
        'password',
        'user_type',               // Admin / Clinic Staff / Pet Owner
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
