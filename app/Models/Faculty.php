<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Faculty extends Authenticatable
{
    protected $fillable = [
        'first_name',
        'middle_initial',
        'last_name',
        'position',
        'semester',
        'id_number',
        'password',
    ];

    protected $table = 'faculty';
}
