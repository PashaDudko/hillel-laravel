<?php

namespace App\Observers;

use App\Enums\Cart as CartEnum;
use App\Mail\YourOrderIsCreated;
use App\Models\Cart;
use App\Models\Order;
use App\Models\User;
use App\Notifications\ForAdmin\Telegram\NewOrderCreated;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        //Close cart
        $cart = Cart::getUserCartFromCookies();

        if ($cart) {
            $cart->update(['status' => CartEnum::CLOSED]);
        }

        //Send email
        $email = $order->user->email;
        Mail::to($email)->send(new YourOrderIsCreated($order));

        // Delete cart from cookie
        Cookie::queue(Cookie::forget('cart')); //Todo  what is queue and why deleting throw headers doesn't work?

        // Notify admin
        $admin = User::role('admin')->first();
        Notification::send($admin, new NewOrderCreated($order));
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {

    }

    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "restored" event.
     */
    public function restored(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     */
    public function forceDeleted(Order $order): void
    {
        //
    }
}
