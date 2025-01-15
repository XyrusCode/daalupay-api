<?php

namespace DaaluPay\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Currency extends BaseModel
{
    use HasFactory;

    protected $table = 'currencies';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'code',
        'name',
        'symbol',
        'exchange_rate',
        'status', // enabled or disabled
    ];

    /**
     * Cast attributes to specific data types.
     */
    protected $casts = [
        'exchange_rate' => 'float',
        'status' => 'string',
    ];

    /**
     * Scope to filter enabled currencies.
     */
    public function scopeEnabled($query)
    {
        return $query->where('status', 'enabled');
    }
}
