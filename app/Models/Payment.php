<?php

namespace DaaluPay\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use DaaluPay\Models\Traits\UuidTrait;
use DaaluPay\Models\User;
class Payment extends BaseModel {
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
        'channel',
		'status',
    ];

    /**
     * Get the user that made the payment.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
