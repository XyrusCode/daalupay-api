<?php

namespace DaaluPay\Models;

use DaaluPay\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use DaaluPay\Models\Traits\UuidTrait;
use DaaluPay\Models\Payment;
use DaaluPay\Models\Employee;
use DaaluPay\Models\User;

class Transaction extends BaseModel
{
	use HasFactory;
    use UuidTrait;

	protected $hidden = [
		'id',
		'updated_at'
	];

	protected $fillable = [
		'uuid',
		'reference_number',
		'channel',
		'amount',
        'send_currency',
        'receive_currency',
        'rate',
        'fee',
		'transaction_date',
		'status',
        'user_id',
        'admin_id',
		'payment_id',
	];

    /**
     * Get the payment associated with the transaction.
     */
    public function payment() {
        return $this->belongsTo(Payment::class, 'payment_id', 'id');
    }

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
