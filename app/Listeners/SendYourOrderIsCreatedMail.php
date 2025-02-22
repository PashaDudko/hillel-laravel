<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Mail\YourOrderIsCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendYourOrderIsCreatedMail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderCreated $event): void
    {
        $email = $event->order->user->email;
        Mail::to($email)->send(new YourOrderIsCreated($event->order));
    }
}
