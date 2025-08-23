<?php

namespace App\Http\Controllers\Auth\Telegram;

use App\Http\Controllers\Controller;
use Azate\LaravelTelegramLoginAuth\Contracts\Telegram\NotAllRequiredAttributesException;
use Azate\LaravelTelegramLoginAuth\Contracts\Validation\Rules\ResponseOutdatedException;
use Azate\LaravelTelegramLoginAuth\Contracts\Validation\Rules\SignatureException;
use Azate\LaravelTelegramLoginAuth\TelegramLoginAuth;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __invoke(TelegramLoginAuth $telegramLoginAuth, Request $request)
    {
        try {
            $data = $telegramLoginAuth->validateWithError($request);
        } catch(NotAllRequiredAttributesException $e) {
            dd('telegram login error 1');
        } catch(SignatureException $e) {
            dd('telegram login error 2');
        } catch(ResponseOutdatedException $e) {
            dd('telegram login error 3');
        } catch(Exception $e) {
            dd('telegram login error 4');
        }

        Auth::user()->update(['telegram_id' => $data->getId()]);
        session()->flash('success', 'You just join our telegram bot!');

        return redirect()->route('admin.dashboard');
    }
}
