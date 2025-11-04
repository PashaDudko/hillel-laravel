<?php

namespace App\Observers;

use App\Enums\Cart as CartEnum;
use App\Models\Cart;
use App\Models\Order;
use App\Models\User;
use App\Notifications\ForAdmin\NewOrderCreated;
use App\Notifications\ForUser\YourOrderIsCreated;
use App\Notifications\ForUser\YourOrderStatusIsUpdated;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Notification;

//use App\Mail\YourOrderIsCreated;

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
//        $email = $order->user->email; //commented, because this is already implemented in user notification
//        Mail::to($email)->send(new YourOrderIsCreated($order));

        // Delete cart from cookie
        Cookie::queue(Cookie::forget('cart')); //Todo  what is queue and why deleting throw headers doesn't work?

        // Notify admin
        $admin = User::role('admin')->first();
        Notification::send($admin, new NewOrderCreated($order));

        //Notify user
        Notification::send($order->user, new YourOrderIsCreated($order));
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        Notification::send($order->user, new YourOrderStatusIsUpdated($order));
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
