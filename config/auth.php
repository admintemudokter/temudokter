<?php

use App\Models\Admin;
use App\Models\Doctor;
use App\Models\User;

return [

    'defaults' => [
        'guard'     => 'web',
        'passwords' => 'users',
    ],

    'guards' => [
        'web' => [
            'driver'   => 'session',
            'provider' => 'users',
        ],

        'admin' => [
            'driver'   => 'session',
            'provider' => 'admins',
        ],

        'doctor' => [
            'driver'   => 'session',
            'provider' => 'doctors',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model'  => User::class,
        ],

        'admins' => [
            'driver' => 'eloquent',
            'model'  => Admin::class,
        ],

        'doctors' => [
            'driver' => 'eloquent',
            'model'  => Doctor::class,
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table'    => 'password_reset_tokens',
            'expire'   => 60,
            'throttle' => 60,
        ],
        'admins' => [
            'provider' => 'admins',
            'table'    => 'password_reset_tokens',
            'expire'   => 60,
            'throttle' => 60,
        ],
        'doctors' => [
            'provider' => 'doctors',
            'table'    => 'password_reset_tokens',
            'expire'   => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];
