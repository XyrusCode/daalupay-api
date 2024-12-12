<?php

namespace DaaluPay\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSetting extends Model
{
    protected $fillable = [
        'user_id',
        'currency_id',
        'language_id',
        'timezone_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
