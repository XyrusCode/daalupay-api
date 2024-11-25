<?php

namespace DaaluPay\Models;

use DaaluPay\Models\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Employee {
    use UuidTrait;
    use HasFactory;

    protected $table = 'employees';

    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'uuid',
        'first_name',
        'last_name',
        'email',
        'phone',
        'photo',
        'password',
        'position',
        'salary',
    ];
}
