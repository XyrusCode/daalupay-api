<?php

namespace DaaluPay\Models;

// use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

// TODO: [2024-11-24] Change to Eloquent when monogo is implemented

class Activity extends BaseModel
{
    protected $connection = 'mongodb';
    protected $collection = 'activities';
    protected $primaryKey = 'user_id';

    protected $guarded = [];

    // Hide sensitive fields
    protected $hidden = [
        'user_id',
        'updated_at',
        'created_at',
        'id',
        '_id'
    ];
}
