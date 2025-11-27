<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\User;
use App\Notifications\ForAdmin\NewOrderCreated;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;

class SendOrderNotificationsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected readonly  Order $order, protected readonly User $user)
    {
        $this->onQueue('order-notifications');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Notification::send($this->user, new NewOrderCreated($this->order));
    }
}
