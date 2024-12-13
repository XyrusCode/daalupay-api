<?php

namespace DaaluPay\Models;

use OpenApi\Annotations as OA;

class Country extends BaseModel
{
    protected $table = 'countries';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'calling_code'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    /**
     * Get the states for the country.
     *
     * @return HasMany
     */
    function states()
    {
        return $this->hasMany(State::class);
    }
}
