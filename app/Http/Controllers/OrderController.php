<?php

namespace App\Http\Controllers;

use App\Events\UserLogin;
use App\Models\Cart;
use App\Models\Product;
use App\Service\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct(public CartService $cartService)
    {
    }

    public function create()
    {
//        UserLogin::dispatch();
        if (!$cart = Cart::getCartFromCookies()) {
            dd('You did not create cart yet'); // ToDo подумать, что бі сообщение біло одинаковим во всех местах, где проверяется корзина.  И може добавить логирование?
        }

        $orderData = [];

        if (!$cart->user) {
           $cart->update(['user_id' => Auth::id()]);   //ToDo или использовать метод из сервиса? $this->cartService->attachCartToUser($cart, $user);
        }

        $totalPrice = 0;
        foreach (unserialize($cart->data) as $k => $value) {
            $product = Product::findOrFail($k);
            $totalPrice += $value['q'] * $value['p'];
            $orderData[$k]['name'] = $product->name;
            $orderData[$k]['img'] = $product->images()->first()->path;
            $orderData[$k]['quantity'] = $value['q'];
            $orderData[$k]['total'] = $value['q'] * $value['p'];
        }

//        dd($orderData);

        return view('orders.create', ['orderData' => $orderData, 'totalPrice' => $totalPrice]);
    }

    public function store(Request $request)
    {
        dd('nbnbnb');
    }
}
