<?

namespace DaaluPay\Models\Traits;

use Illuminate\Support\Str;

trait UUIDTrait
{
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }
}
