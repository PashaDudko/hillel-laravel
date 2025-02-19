<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Models\Cart;
use App\Enums\Cart as CartEnum;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Events\Dispatcher;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;

class CloseCart
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
    public function handle(): void
    {
        $cart = Cart::getUserCartFromCookies();

        if ($cart) {
            $cart->update(['status' => CartEnum::CLOSED]);
        }
    }

    //ToDo разобраться зачем этот метод и на что он влияет
//    public function subscribe(Dispatcher $event): void
//    {
//        $event->listen(
//            OrderCreated::class,
//            CloseCart::class
//        );
//    }
}
//https://laravel.su/docs/11.x/events
