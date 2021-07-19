<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Spatie\Tags\HasTags;

class Subscription extends Model
{
    use HasFactory;
    use HasTags;

    public const DAY_INTERVAL = 'day';
    public const WEEK_INTERVAL = 'week';
    public const MONTH_INTERVAL = 'month';
    public const YEAR_INTERVAL = 'year';

    public const CURRENCY_RUB = 'RUB';
    public const CURRENCY_USD = 'USD';
    public const CURRENCY_EUR = 'EUR';

    protected $fillable = [
        'title',
        'first_payment_date',
        'next_payment_date',
        'interval_value',
        'interval_type',
        'payment_amount',
        'currency_code',
        'image',
        'service_id',
    ];

    protected $casts = [
        'first_payment_date' => 'date',
        'next_payment_date' => 'date',
        'interval_value' => 'integer',
        'payment_amount' => 'float',
    ];

    protected static function booted()
    {
        static::deleted(function (Subscription $subscription) {
            if ($subscription->image) {
                Storage::delete($subscription->image);
            }
        });
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
