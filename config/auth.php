<?php

return [

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'pet_owner'), // Default guard set to pet_owner
        'passwords' => env('AUTH_PASSWORD_BROKER', 'pet_owners'),
    ],

    'guards' => [
        'admin' => [
            'driver' => 'session',
            'provider' => 'admins',
        ],

        'clinic' => [
            'driver' => 'session',
            'provider' => 'clinics',
        ],

        'pet_owner' => [
            'driver' => 'session',
            'provider' => 'pet_owners',
        ],
    ],

    'providers' => [
        'admins' => [
            'driver' => 'eloquent',
            'model' => App\Models\Admin::class,
        ],

        'clinics' => [
            'driver' => 'eloquent',
            'model' => App\Models\Clinic::class,
        ],

        'pet_owners' => [
            'driver' => 'eloquent',
            'model' => App\Models\PetOwner::class,
        ],
    ],

    'passwords' => [
        'admins' => [
            'provider' => 'admins',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],

        'clinics' => [
            'provider' => 'clinics',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],

        'pet_owners' => [
            'provider' => 'pet_owners',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,

];
