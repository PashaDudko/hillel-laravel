<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public static function openCart(): Cart
    {
//        https://dev.to/codeanddeploy/laravel-model-create-example-4ko
        $cart = Cart::create([
            'uuid' => 123,
            'user_id' => Auth::id(),
            'product_id' => null,
        ]);

        return $cart;
    }

    public static function getCart(): ?Cart
    {
        return Cart::findOrFail('uuid', session('cart_uuid'));
    }

    public function addToCart(Request $request)
    {

    }

    public function showCart()
    {

    }

    public function updateCart(Product $product, Request $request)
    {

    }

    // TODO продумать кейс когда пользователь добавляет товар в корзину (сетится сессия корзины), переходит на страницу корзины и чистит куки-сессию
    public function deleteCart(Request $request)
    {

    }
}
