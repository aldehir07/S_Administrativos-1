<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as auth;

class Usuario extends auth
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];
}
