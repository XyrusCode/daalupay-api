<?php

namespace DaaluPay\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use  DaaluPay\Models\Traits\UUIDTrait;
use  DaaluPay\Models\Traits\AdminTrait;

class Admin extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    // use UUIDTrait;
    // use AdminTrait;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'status',
        'role',
        'phone'
    ];

    // public function getAvailableAdmin()
    // {
    // // Define a threshold for what qualifies as "free" (optional)
    //     $freeThreshold = 5;

    // // Find the free admin
    //     $freeAdmin = Admin::where('transactions_assigned', '<', $freeThreshold)
    //     ->orderBy('transactions_assigned', 'asc')
    //     ->first();

    //     if ($freeAdmin) {
    //         return $freeAdmin;
    //     }

    // // If no free admins, find the admin with the least number of transactions
    //     return Admin::orderBy('transactions_assigned', 'asc')->first();
    // }
}
