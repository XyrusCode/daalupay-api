<?php

namespace DaaluPay\Models\Traits;

use Illuminate\Support\Facades\Auth;

trait AdminTrait
{
    /*
        * This prevents regular users from creating or updating records in the database at the admin level
        *
        * @return void
        */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (Auth::user()->role !== 'admin') {
                abort(403, 'You are not authorized to perform this action');
            }
        });

        static::updating(function ($model) {
            if (Auth::user()->role !== 'admin') {
                abort(403, 'You are not authorized to perform this action');
            }
        });

        static::deleting(function ($model) {
            if (Auth::user()->role !== 'admin') {
                abort(403, 'You are not authorized to perform this action');
            }
        });

        static::restoring(function ($model) {
            if (Auth::user()->role !== 'admin') {
                abort(403, 'You are not authorized to perform this action');
            }
        });

        static::forceDeleting(function ($model) {
            if (Auth::user()->role !== 'admin') {
                abort(403, 'You are not authorized to perform this action');
            }
        });

        static::saving(function ($model) {
            if (Auth::user()->role !== 'admin') {
                abort(403, 'You are not authorized to perform this action');
            }
        });

        static::saved(function ($model) {
            if (Auth::user()->role !== 'admin') {
                abort(403, 'You are not authorized to perform this action');
            }
        });
    }
}
