<?php

namespace App\Observers;

use App\Enums\Cart as CartEnum;
use App\Enums\Order as OrderEnum;
use App\Models\Cart;
use App\Models\Order;
use App\Models\User;
use App\Notifications\ForAdmin\NewOrderCreated;
use App\Notifications\ForAdmin\UserHasCanceledOrder;
use App\Notifications\ForUser\YourOrderDeliveryDateIsUpdated;
use App\Notifications\ForUser\YourOrderIsCreated;
use App\Notifications\ForUser\YourOrderStatusIsUpdated;
use Illuminate\Support\Carbon;
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
//        $changes = $order->getDirty();

        if ($order->isDirty('status')) {
            if ($order->status == OrderEnum::CANCELED) {
                $admin = User::admin()->first();
                Notification::send($admin, new UserHasCanceledOrder($order));
            } else {
                $message = "Your order {$order->number} status has been changed to {$order->status->name}";
                $message .= $order->estimated_delivery_date ? "\n Expected delivery date is: {$order->estimated_delivery_date}" : "";

                Notification::send($order->user, new YourOrderStatusIsUpdated($message));
            }
        } else {
            $messageTemplate = "
                We are %s to inform you that your order {$order->number} will be delivered %s than expected.\n
                New expected delivery date is: {$order->estimated_delivery_date}
            ";

            $originalDate = Carbon::parse($order->getOriginal('estimated_delivery_date'));
            $newDate = Carbon::parse($order->estimated_delivery_date);
            $words = $originalDate->greaterThan($newDate) ? ['HAPPY', 'EARLIER'] : ['REGRET', 'LATER'];

            $message = sprintf($messageTemplate, ...$words);

            Notification::send($order->user, new YourOrderDeliveryDateIsUpdated($message));
        }
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
