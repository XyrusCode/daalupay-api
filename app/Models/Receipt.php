<?php

namespace DaaluPay\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Receipt extends Model
{
    use HasFactory;

    protected $table = 'receipts';

    protected $fillable = [
        'user_id',
        'amount',
        'receipt',
        'admin_id',
        'status',
        'notes',
        'created_at',
        'updated_at',
    ];
}
