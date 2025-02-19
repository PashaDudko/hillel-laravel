<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;

class GoogleLoginController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();
        $user = User::where('email', $googleUser->email)->first();

        if (!$user) {
            $user = User::create([
                'name' => $googleUser->name,
                'lastname' => $googleUser->name,
                'email' => $googleUser->email,
                'phone' => '1234567',
                'birthday' => (new \DateTime('-40 years'))->format('Y-m-d'),
                'password' => \Hash::make(rand(100000,999999)), // змінити генерування паролю
                'remember_token' => Str::random(10),
            ]);
        }

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
