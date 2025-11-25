<?php

namespace App\Notifications\ForUser;

use App\Models\Order;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class YourOrderIsCreated extends Notification
{
    use Queueable;

    public array $arr = ['mail'];

    /**
     * Create a new notification instance.
     */
    public function __construct(public readonly Order $order)
    {
        // emulating delay
        sleep(15);
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
    public function toMail(User $user): MailMessage
    {
        return (new MailMessage)
                    ->line("Dear  $user->name!")
                    ->line("Your order  {$this->order->number} has been created!")
                    ->line("We will check and approve it in a minute!")
                    ->action("You can check the status on your profile page", url('http://laravel.test/profile'))
                    ->line("Thank you for using our application!");
    }

    public function toTelegram(User $user)
    {
        $url = 'http://laravel.test/profile';

        return TelegramMessage::create()
            // Optional recipient user id.
            ->to($user->telegram_id)

            // Markdown supported.
            ->content("Dear $user->name")
            ->line("Your order  {$this->order->number} has been created!")
            ->line("We will check and approve it in a minute!")
            ->line("See actual status on your profile page")
            ->line("Thank you for using our application!")
            ->button('See my order', $url)
            ;
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
