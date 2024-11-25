<?php

namespace DaaluPay\Models\Traits;

use Illuminate\Support\Str;

trait UuidTrait {
    protected static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }
}
