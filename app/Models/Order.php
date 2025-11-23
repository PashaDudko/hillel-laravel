<?php

namespace App\Models;

use App\Enums\Order as OrderEnum;
use App\Observers\OrderObserver;
use Carbon\Carbon;
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

    public function countTotalPrice(): int
    {
        $totalPrice = 0;

        foreach (unserialize($this->data) as $data) {
            $totalPrice += $data['q'] * $data['p'];
        }

        return $totalPrice;
    }

    public static function countDailyStatistics(): array
    {
        $expectedRevenue = 0;
        $query = Order::query()->whereDate('created_at', Carbon::today());

        foreach ($query->get() as $order) {
            foreach (unserialize($order->data) as $data) {
                if (!in_array($order->status, [OrderEnum::CANCELED, OrderEnum::REJECTED])) {
                    $expectedRevenue += $data['q'] * $data['p'];
                }
            };
        };

        return [
            'today_orders' => $query->get()->count(),
            'canceled' => $query->where('status', OrderEnum::CANCELED)->get()->count(),
            'expected_revenue' => $expectedRevenue,
        ];
    }
}
