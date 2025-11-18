<?php

namespace App\Http\Controllers;

use App\Enums\Roles;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramWebhookController extends Controller
{
//    public function handle1(Request $request)
//    {
////        \Illuminate\Support\Facades\Log::info('Webhook received!', $request->all());
////        \Illuminate\Support\Facades\Log::info('ME!!!', Telegram::bot('mybot')->getMe()->toArray());
//        $this->sendStartMessage();
//    }
//
//    protected function sendStartMessage1(int $chatId, string $firstName)
//    {
//        // 1. Створення клавіатури відповідей
//        $keyboard = \Telegram\Bot\Keyboard\Keyboard::make([
//            'keyboard' => [
//                ['My name 🙋‍♂️'] // Кнопка, яка надсилає текст
//            ],
//            'resize_keyboard' => true,
//        ]);
//
//        // 2. Надсилання повідомлення з клавіатурою
//        $text = "Вітаю, {$firstName}! Натисніть кнопку, щоб дізнатися, як я вас бачу.";
//
//        // Припустимо, ви використовуєте метод sendMessage
//        $this->sendMessage($chatId, $text, $keyboard);
//    }

    /**
     * Головний метод для обробки вебхуків Telegram.
     */
    public function handle(Request $request)
    {
        $update = Telegram::getWebhookUpdate();

        if ($update->isType('callback_query')) {
            $this->handleCallbackQuery($update->getCallbackQuery());
        }

//        \Illuminate\Support\Facades\Log::info('Bot: ', Telegram::bot('mybot')->getMe()->toArray());
//        \Illuminate\Support\Facades\Log::info('ME: ', Telegram::getMe()->toArray());
        \Illuminate\Support\Facades\Log::info('from telegram101:  ', $update->getMessage()->toArray());
//        \Illuminate\Support\Facades\Log::info('from telegram:  ',['aaa' => $update->getMessage()->getText()]);

        // Перевіряємо, чи це повідомлення (message)
        if ($update->getMessage()) {
            $message = $update->getMessage();
            $chatId = $message->getChat()->getId();
            $text = $message->getText();
            $firstName = $message->getChat()->getFirstName() ?? 'friend';
            $user = $this->getUser($message->getFrom()->getId());

            // 2. Обробка команди /start
            if ($text === '/start') {
//                if ($user->hasRole(Roles::ADMIN) ) {
//                    $this->sendStartMessageForAdmin($chatId);
                    $this->sendStartMessage($chatId, $user);
//                } else {
//                    $this->sendStartMessageForUser($chatId, $firstName);
//                }
            } elseif ($text === 'Show Order') {
                $this->showOrder($chatId);
            } elseif (str_contains($text, 'Order_')) {
                $this->findOrderByNumber($chatId, $text, $user);
            } elseif ($text === 'Daily Statistics') {
                $this->showDailyStatistics($chatId);
            } elseif ($text === 'Cancel Order') {
                $this->cancelOrder($chatId, $user);
            }


        elseif ($text === 'Name 🙋‍♂️') {
                $replyText = "Yor name in Telegram is: **{$firstName}**";
                $this->sendMessage($chatId, $replyText);
            } elseif ($text === 'test') {
                $replyText = "Yor name in Telegram is: **{$firstName}**";
                $this->sendMessage($chatId, $replyText);
            }
        }

        return response('OK', 200);
    }

    // ----------------------------------------------------------------

    protected function handleCallbackQuery($callbackQuery)
    {
        $callbackData = $callbackQuery->getData();

        if (str_starts_with($callbackData, 'CANCEL_')) {
            $orderNumber = substr($callbackData, 7);

            $this->sendConfirmationButtons($callbackQuery, $orderNumber);
        }
    }

    protected function sendConfirmationButtons($callbackQuery, string $orderNumber): void
    {
        // ID повідомлення та чату для редагування
        $chatId = $callbackQuery->getMessage()->getChat()->getId();
        $messageId = $callbackQuery->getMessage()->getMessageId();

        // Створюємо нові callback_data з повним контекстом
        $confirmData = 'CONFIRM_CANCEL_' . $orderNumber; // Наприклад: 'CONFIRM_CANCEL_FF12345'
        $abortData = 'ABORT_CANCEL_' . $orderNumber;     // Наприклад: 'ABORT_CANCEL_FF12345'

        $inlineKeyboard = Keyboard::make()->inline()->row(
        // Кнопка YES
            [
                Keyboard::inlineButton(['text' => '✅ Так, скасувати', 'callback_data' => $confirmData]),
                // Кнопка NO
                Keyboard::inlineButton(['text' => '❌ Ні, залишити', 'callback_data' => $abortData]),
            ]
        );

        // Редагуємо поточне повідомлення, щоб замінити старі кнопки на нові
        $this->editMessage($chatId, $messageId, "Ви впевнені, що хочете скасувати замовлення №{$orderNumber}?", $inlineKeyboard);

        // Відповідаємо на callback (щоб прибрати годинник)
        $callbackQuery->answer('Потрібне підтвердження.');
    }

    protected function editMessage(int $chatId, int $messageId, string $text, ?Keyboard $replyMarkup = null): void
    {
        $params = [
            'chat_id' => $chatId,
            'message_id' => $messageId, // Ключовий параметр для ідентифікації
            'text' => $text,
            'parse_mode' => 'MarkdownV2', // Завжди використовуємо для коректного відображення
        ];

        if ($replyMarkup) {
            // Замінюємо розмітку повністю
            $params['reply_markup'] = $replyMarkup;
        } else {
            // Якщо $replyMarkup не передано, відправляємо порожню клавіатуру, щоб видалити старі кнопки.
            $params['reply_markup'] = Keyboard::make()->inline();
        }

        // Викликаємо метод editMessageText з Telegram API
        // (або editMessageReplyMarkup, якщо потрібно змінити лише кнопки)
        Telegram::editMessageText($params);
    }

    protected function getUser(int $telegramId): User
    {
//        return User::where('telegram_id', '=', Telegram::getWebhookUpdate()['message']['from']['id'])->first();
        return User::where('telegram_id', $telegramId)->first();
    }

//    private function sendStartMessageForAdmin(int $chatId): void
//    {
//        $keyboard = Keyboard::make([
//            'keyboard' => [
//                ['Show Order'],
//                ['Daily Statistics'],
//                // Додайте інші кнопки адміністратора, якщо потрібно
//            ],
//            'resize_keyboard' => true,
//            'one_time_keyboard' => false,
//        ]);
//
//        $text = "Select admin action :";
//
//        try {
//            $this->sendMessage($chatId, $text, $keyboard);
//
//            Log::info("Admin start message sent to ChatID: {$chatId}");
//
//        } catch (\Exception $e) {
//            Log::error("Failed to send admin start message to ChatID: {$chatId}", ['error' => $e->getMessage()]);
//            // Обробка помилок
//        }
//    }

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

            Log::info("Admin start message sent to ChatID: {$chatId}");

        } catch (\Exception $e) {
            Log::error("Failed to send admin start message to ChatID: {$chatId}", ['error' => $e->getMessage()]);
        }
    }

    /**
     * Надсилає повідомлення з кнопкою-клавіатурою.
     */
//    protected function sendStartMessageForUser(int $chatId, string $firstName): void
//    {
//        // 1. Створення клавіатури відповідей
//        $keyboard = Keyboard::make([
//            'keyboard' => [
//                ['Name 🙋‍♂️'] // Кнопка, яка надсилає текст
//            ],
//            'resize_keyboard' => true,
//        ]);
//
//        // 2. Надсилання повідомлення з клавіатурою
//        $text = "Hi, {$firstName}! Click on button";
//
//        $this->sendMessage($chatId, $text, $keyboard);
//    }

    protected function showOrder(int $chatId): void
    {
        $text = "Please provide string\: Order\_*order number* you want to see";

        $this->sendMessage($chatId, $text);
    }

    protected function findOrderByNumber(int $chatId, string $value, User $user): void
    {
        $keyboard = null;

        $orderNumber = substr($value, 6);

        $order = Order::where('number', $orderNumber)->first();

        if (!$order) {
            $text = "Order number {$orderNumber} not found";
        } else {
            if ($order->user->isNot($user) && !$user->hasRole(Roles::ADMIN)) {
                $text = "You have no Order with number {$orderNumber}";
            } else {
                $text = "*Order details* №`{$orderNumber}`\n";
                $text .= "\n*Client*\: {$user->name}";
                $text .= "\n*Status*\: _{$order->status->value}_";
                $text .= "\n*Price*\: {$order->countTotalPrice()} $";
                $text .= "\n*Date*\: {$order->updated_at->format("d")}\-{$order->updated_at->format("m")}\-{$order->updated_at->format("Y")}, {$order->updated_at->format("H\:i")}";

                if ($order->user->is($user)) {
//                    $keyboard = Keyboard::make([
//                        'keyboard' => [
//                            ['Cancel Order'],
//                        ],
//                        'resize_keyboard' => true,
//                        'one_time_keyboard' => false,
//                    ]);
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
        ['today_orders' => $todayOrdersCount, 'rejected' => $rejectedCount,'income' => $totalIncome] = Order::countDailyStatistics();

        $text = "Statistics on " . now()->format('d') ."\-". now()->format('m') ."\-". now()->format('Y') . "\n";
        $text .= "\n*Orders total*\: {$todayOrdersCount}";
        $text .= "\n*Canceled by user*\: {$rejectedCount}";
        $text .= "\n*Income*\: {$totalIncome} $";

        $this->sendMessage($chatId, $text);
    }

    protected function cancelOrder(int $chatId, User $user): void
    {

    }

    /**
     * Обгортка для надсилання повідомлення, використовує Telegram Фасад.
     */
    protected function sendMessage(int $chatId, string $text, ?Keyboard $replyMarkup = null)
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
