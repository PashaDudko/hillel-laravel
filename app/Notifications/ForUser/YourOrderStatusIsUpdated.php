<?php

namespace App\Notifications\ForUser;

use App\Enums\Order as OrderEnum;
use App\Models\Order;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class YourOrderStatusIsUpdated extends Notification
{
    use Queueable;

    public array $arr = ['mail'];

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
    public function via(User $user): array
    {
        if ($user->telegram_id) {
            $this->arr[] = 'telegram';
        }

        return $this->arr;
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

    public function toTelegram(User $user)
    {
        $url = 'http://laravel.test/profile';

        $telegramMessage = TelegramMessage::create()
            // Optional recipient user id.
            ->to($user->telegram_id)
            // Markdown supported.
            ->content("Dear $user->name")
            ->line("Your order {$this->order->number} status has been changed to {$this->order->status->name}!")
            ->button('See my order', $url)
            ;

//        match ($this->order->status) {
//            OrderEnum::CONFIRMED =>
//            $telegramMessage
//                ->line("Expected delivery date: {$this->order->expected_at}"),
//        }
        if ($this->order->status == OrderEnum::CONFIRMED) {
            $telegramMessage
                ->line("Expected delivery date: {$this->order->estimated_delivery_date}");
        }

//            ->line("Will we inform you when it will be delivered")

        return $telegramMessage;
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
