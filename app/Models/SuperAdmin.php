<?php

namespace DaaluPay\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
class SuperAdmin extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use HasApiTokens;

    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'uuid',
        'name',
        'email',
        'password',
        'status',
        'role',
        'phone'
    ];
}
