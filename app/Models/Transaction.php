<?php

namespace DaluPay\Models;

use DaluPay\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use DaluPay\Models\Traits\UuidTrait;

class Transaction extends BaseModel
{
	use HasFactory;
    use UuidTrait;

	protected $hidden = [
		'id',
		'created_at',
		'updated_at'
	];

	protected $fillable = [
		'uuid',
		'reference_number',
		'channel',
		'amount',
		'description',
		'transaction_date',
		'status',
        'user_id',
        'admin_id',
		'payment_id',
	];

	/**
	 * Get the user associated with the transaction.
	 */
	public function user() {
		return $this->belongsTo(User::class, 'user_id', 'id');
	}

    /**
     * Get the admin who approved the transaction.
     */
    public function admin() {
        return $this->belongsTo(Employee::class, 'admin_id', 'id');
    }
}
