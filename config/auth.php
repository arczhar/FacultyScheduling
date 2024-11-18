<?php

return [

    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
        'faculty' => [
            'driver' => 'session',
            'provider' => 'faculty',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],
        'faculty' => [
            'driver' => 'eloquent',
            'model' => App\Models\Faculty::class,
        ],
    ],

];
