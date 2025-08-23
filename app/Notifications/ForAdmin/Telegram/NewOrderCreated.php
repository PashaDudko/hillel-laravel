<?php

namespace App\Notifications\ForAdmin\Telegram;

use App\Models\Order;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class NewOrderCreated extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public readonly Order $order)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
//    public function via(object $notifiable): array
    public function via(User $user): array
    {
//        return ['mail'];
        return ['telegram'];
    }

    public function toTelegram(User $notifiable)
    {
        $url = route('admin.orders.index');

        return TelegramMessage::create()
            // Optional recipient user id.
            ->to($notifiable->telegram_id)

            // Markdown supported.
            ->content("Hello there!")
            ->line("User id: {$this->order->user->id}, name: {$this->order->user->name}")
            ->line("has just created a new order")
            ->line("Please check it in admin panel")
            ->button('Admin Panel', $url)
            ;
//            ->lineIf($notifiable->amount > 0, "Amount paid: {$notifiable->amount}")
//            ->line(sprintf("Thank you, ".TelegramMessage::escapeMarkdown("$user->name!"))

                // (Optional) Blade template for the content.
                // ->view('notification', ['url' => $url])

                // (Optional) Inline Buttons
//                ->button('View Invoice', $url)
//                ->button('Download Invoice', $url);
    }


    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
