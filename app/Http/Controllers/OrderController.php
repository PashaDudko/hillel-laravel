<?php

namespace App\Http\Controllers;

use App\Events\OrderCreated;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Service\CartService;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct(public CartService $cartService)
    {
    }

    public function create()
    {
        if (!$cart = Cart::getUserCartFromCookies()) {
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

        return view('orders.create', ['orderData' => $orderData, 'totalPrice' => $totalPrice]);
    }

    public function store(Request $request)
    {
        if (!$cart = Cart::getUserCartFromCookies()) {
            dd('You did not create a cart yet');
        }

        try {
            $order = Order::create([
                'number' => chr(rand(65, 90)) . chr(rand(65, 90)) . (new \DateTime())->format('Ymd') . rand(1, 1000),
                'user_id' => Auth::id(),
                'data' => $cart->data,
                'deliver' => $request['deliver'],
                'payment' => $request['payment'],
                'comment' => $request['comment'] ?? null,
            ]);

//            OrderCreated::dispatch($order); //reject using this event, because Observer will be used

        } catch (\Exception $exception) {
            dd('Order was not created. ' . $exception->getMessage());
        }

        return response()->view('orders.congratulation', ['order' => $order]);
    }
}
