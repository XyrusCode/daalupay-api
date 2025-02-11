<?php

namespace DaaluPay\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'agent_id', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function agent()
    {
        return $this->belongsTo(Admin::class, 'agent_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
