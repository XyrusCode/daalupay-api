<?php

namespace DaluPay\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use DaluPay\Models\Traits\UuidTrait;

class Payment {
	use HasFactory;
	use UuidTrait;

    protected $table = 'payments';

    protected $hidden = [
        'updated_at',
    ];

    protected $fillable = [
    		'name',
		'amount',
		'method',
		'type',
		'status',
    ];

    /**
     * Get the user that made the payment.
     *
     * @return BelongsTo
     */
    public function user() {
        return $this->belongsTo(User::class);
    }
}
