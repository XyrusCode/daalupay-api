<?php

namespace DaaluPay\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_id',
        'sent_from',
        'sender_id',
        'message'];

    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }
}
