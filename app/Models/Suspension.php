<?php

namespace DaluPay\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
class Suspension {
    use HasFactory;

    protected $table = 'suspensions';

    protected $fillable = [
        'user_id',
        'reason',
        'start_date',
        'end_date',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
