<?php

namespace App\Models;

use App\Enums\Order as OrderEnum;
use App\Observers\OrderObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ObservedBy([OrderObserver::class])]
class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'user_id',
        'status',
        'data',
        'deliver',
        'payment',
        'comment',
        'is_paid',
        'estimated_delivery_date',
        'delivered_at',
        'received_at',
    ];

    protected $casts = [
      'status' => OrderEnum::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
