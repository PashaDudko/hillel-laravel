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
        Mail::to('moderator@moderato.com')->send(new YourOrderIsCreated());

        сообщение после отправки почты : "You did not create a cart yet"
    создать заказ и посомтреть что там не так
    }
}
