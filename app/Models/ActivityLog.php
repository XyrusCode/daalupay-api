<?php

namespace DaaluPay\Models;

// use Jenssegers\Mongodb\Eloquent\Model as Mongo;


// class ActivityLog extends Mongo
class ActivityLog
{
    protected $connection = 'mongodb'; // Use the MongoDB connection
    protected $collection = 'activity_logs'; // Specify collection name

    protected $primaryKey = 'user_id';

      protected $fillable = [
        'user_id',
        'action',
        'data',
        'ip_address',
        'user_agent',
        'created_at',
    ];

    // Hide sensitive fields
    protected $hidden = [
        'user_id',
        'updated_at',
        'created_at',
        'id',
        '_id'
    ];
}
