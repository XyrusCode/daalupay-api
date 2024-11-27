<?php

namespace DaaluPay\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentMethod extends BaseModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'description',
        'status', // enabled or disabled
    ];

    /**
     * Cast attributes to specific data types.
     */
    protected $casts = [
        'status' => 'string',
    ];

    /**
     * Scope to filter enabled payment methods.
     */
    public function scopeEnabled($query)
    {
        return $query->where('status', 'enabled');
    }
}
