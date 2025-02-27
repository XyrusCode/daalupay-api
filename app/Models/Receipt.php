<?php

namespace DaaluPay\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
