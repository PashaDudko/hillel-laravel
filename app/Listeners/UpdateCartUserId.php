<?php

namespace App\Listeners;

use App\Events\UserLogin;
use App\Models\Cart;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Events\Dispatcher;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;

class UpdateCartUserId
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
    public function handle(UserLogin $event): void
    {
        $cart = Cart::getCartFromCookies();

        if ($cart) {
            $cart->update(['user_id' => Auth::id()]);
        }
    }

    public function subscribe(Dispatcher $event): void
    {
        $event->listen(
            UserLogin::class,
            UpdateCartUserId::class
        );
    }
}
//https://laravel.su/docs/11.x/events
