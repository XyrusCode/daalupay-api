<?php

namespace DaaluPay\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class State extends BaseModel
{
    protected $table = 'states';

    protected $fillable = [
        'name',
        'country_id'
    ];

    /**
     * Get the country that owns the state.
     *
     * @return BelongsTo
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
