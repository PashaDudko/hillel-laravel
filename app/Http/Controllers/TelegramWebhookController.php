<?php

namespace App\Http\Controllers;

use App\Enums\Order as OrderEnum;
use App\Enums\Roles;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramWebhookController extends Controller
{
    public function handle(Request $request): Response
    {
        $update = Telegram::getWebhookUpdate();

        if ($update->isType('callback_query')) {
            $this->handleCallbackQuery($update->getCallbackQuery());
        }
//        \Illuminate\Support\Facades\Log::info('TELEGRAM:  ', $update->getMessage()->toArray());

        if ($update->getMessage()) {
            $message = $update->getMessage();
            $chatId = $message->getChat()->getId();
            $text = $message->getText();
            $user = $this->getUser($message->getChat()->getId());

            if ($text === '/start') {
                $this->sendStartMessage($chatId, $user);
            } elseif ($text === 'Show Order') {
                $this->showOrder($chatId);
            } elseif (str_starts_with($text, 'Order_')) {
                $text = substr($text, 6);
                $this->findOrderByNumber($chatId, $text, $user);
            } elseif ($text === 'Daily Statistics') {
                $this->showDailyStatistics($chatId);
            }
        }

        return response('OK', 200);
    }

    protected function handleCallbackQuery($callbackQuery): void
    {
        $callbackData = $callbackQuery->getData();

        if (str_starts_with($callbackData, 'CANCEL_')) {
            $orderNumber = substr($callbackData, 7);

            $this->sendConfirmationButtons($callbackQuery, $orderNumber);
        }

        if (str_starts_with($callbackData, 'CONFIRM_CANCEL_')) {
            $orderNumber = substr($callbackData, 15);

            $chatId = $callbackQuery->getMessage()->getChat()->getId();
            $messageId = $callbackQuery->getMessage()->getMessageId();
            $user = $this->getUser($chatId);

            $order = Order::where([
                ['number', $orderNumber],
                ['user_id', $user->id],
            ])->first();

            if ($order) {
                $order->update(['status' => OrderEnum::CANCELED]);
            }

            if ($order) {
                $newText = "✅ ** Your order №$orderNumber** is canceled";
            } else {
                $newText = "❌ Some error fails\. Please try again";
            }

            try {
                $this->editMessage($chatId, $messageId, $newText);
            } catch (\Telegram\Bot\Exceptions\TelegramResponseException $e) {
                    \Illuminate\Support\Facades\Log::warning("See error {$e->getMessage()}");
            }

            Telegram::answerCallbackQuery([
                'callback_query_id' => $callbackQuery->getId(),
                'text' => $order ? "Your order was successfully canceled!" : "Some error fails 😔",
                'show_alert' => !$order
            ]);
        }

        if (str_starts_with($callbackData, 'ABORT_CANCEL_')) {
            $orderNumber = substr($callbackData, 13);
            $chatId = $callbackQuery->getMessage()->getChat()->getId();
            $user = $this->getUser($chatId);
            $this->findOrderByNumber($chatId, $orderNumber, $user);
        }
    }

    protected function sendConfirmationButtons($callbackQuery, string $orderNumber): void
    {
        $chatId = $callbackQuery->getMessage()->getChat()->getId();
        $messageId = $callbackQuery->getMessage()->getMessageId();

        $text = "Are you really want to cancel order №{$orderNumber} ?";

        $confirmData = 'CONFIRM_CANCEL_' . $orderNumber;
        $abortData = 'ABORT_CANCEL_' . $orderNumber;

        $inlineKeyboard = Keyboard::make()->inline()->row(
            [
                Keyboard::inlineButton(['text' => '✅ Yes', 'callback_data' => $confirmData]),
                Keyboard::inlineButton(['text' => '❌ No', 'callback_data' => $abortData]),
            ]
        );

        $this->editMessage($chatId, $messageId, $text, $inlineKeyboard);
    }

    protected function editMessage(int $chatId, int $messageId, string $text, ?Keyboard $replyMarkup = null): void
    {
        $params = [
            'chat_id' => $chatId,
            'message_id' => $messageId,
            'text' => $text,
            'parse_mode' => 'MarkdownV2',
        ];

        if ($replyMarkup) {
            $params['reply_markup'] = $replyMarkup;
        } else {
            $params['reply_markup'] = null;
        }

        Telegram::editMessageText($params);
    }

    protected function getUser(int $telegramId): User
    {
        return User::where('telegram_id', $telegramId)->first();
    }

    protected function sendStartMessage(int $chatId, User $user): void
    {
        $keyboard = Keyboard::make([
            'resize_keyboard' => true,
            'one_time_keyboard' => false,
        ]);

        $keyboard->row([
            ['text' => 'Show Order']
        ]);

        if ($user->hasRole(Roles::ADMIN)) {
            $keyboard->row([
                ['text' => 'Daily Statistics']
            ]);
        } else {
            $keyboard->row([
                ['text' => 'Change Avatar']
            ]);
        }

        $text = "Select action: ";

        try {
            $this->sendMessage($chatId, $text, $keyboard);
        } catch (\Exception $e) {
            Log::error("Failed to send admin start message to ChatID: {$chatId}", ['error' => $e->getMessage()]);
        }
    }

    protected function showOrder(int $chatId): void
    {
        $text = "Please provide *number* of order you want to see add add prefix *Order\_* at the beginning";

        $this->sendMessage($chatId, $text);
    }

    protected function findOrderByNumber(int $chatId, string $orderNumber, User $user): void
    {
        $keyboard = null;

        $order = Order::where('number', $orderNumber)->first();

        if (!$order) {
            $text = "Order number {$orderNumber} not found \. Did you add *Order\_* prefix ?";
        } else {
            if ($order->user->isNot($user) && !$user->hasRole(Roles::ADMIN)) {
                $text = "You have no Order with number {$orderNumber}";
            } else {
                $text = "*Order details* №`{$orderNumber}`\n";
                $text .= "\n*Client*\: {$user->name}";
                $text .= "\n*Status*\: _{$order->status->value}_";
                $text .= "\n*Price*\: {$order->countTotalPrice()} $";
                $text .= "\n*Date*\: {$order->updated_at->format("d")}\-{$order->updated_at->format("m")}\-{$order->updated_at->format("Y")}, {$order->updated_at->format("H\:i")}";

                if ($order->user->is($user) && !in_array($order->status,
                        [
                            OrderEnum::REJECTED,
                            OrderEnum::CANCELED,
                            OrderEnum::RECEIVED,
                            OrderEnum::CLOSED,
                        ]
                )) {
                    $callbackData = 'CANCEL_' . $orderNumber;
                    $keyboard = Keyboard::make()->inline();

                    $keyboard->row([
                        Keyboard::inlineButton([
                            'text' => '❌ Cancel Order',
                            'callback_data' => $callbackData
                        ])
                    ]);
                }
            }
        }

        $this->sendMessage($chatId, $text, $keyboard);
    }

    protected function showDailyStatistics(int $chatId): void
    {
        ['today_orders' => $todayOrdersCount, 'canceled' => $canceledCount,'expected_revenue' => $expectedRevenue] = Order::countDailyStatistics();

        $text = "Statistics on " . now()->format('d') ."\-". now()->format('m') ."\-". now()->format('Y') . "\n";
        $text .= "\n*Orders today*\: {$todayOrdersCount}";
        $text .= "\n*Canceled by users*\: {$canceledCount}";
        $text .= "\n*Expected revenue*\: {$expectedRevenue} $";

        $this->sendMessage($chatId, $text);
    }

    protected function sendMessage(int $chatId, string $text, ?Keyboard $replyMarkup = null): void
    {
        $params = [
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => 'MarkdownV2',
        ];

        if ($replyMarkup) {
            $params['reply_markup'] = $replyMarkup;
        }

        Telegram::sendMessage($params);
    }
}
