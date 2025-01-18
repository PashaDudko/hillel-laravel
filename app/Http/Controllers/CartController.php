<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function addToCart(Request $request): JsonResponse     //        https://dev.to/codeanddeploy/laravel-model-create-example-4ko
    {
        //ToDo добавить валидацию данніх форми
        if (Cart::isCartInCookies() && $cart = Cart::getCartFromCookies()) {
            $previousDataArr = unserialize($cart->data);
            //TODO в корзине может біть нестклько продуктов. Должно біть что-то вроде foreach($request['data' as $item или $k=>$v]
            $newDataArr = [
                $request['product_id'] => [
                    'q' => $request['quantity'], //Подумать, как пользовталеь может сразу заказывать больше 1 шт. См. этот же комент в файле блейд
                    'p' => $request['price'],
                ]
            ];
            $arr = $previousDataArr + $newDataArr; // https://foxminded.ua/ru/php-add-array-to-array/ !!!!!!
            $cart->data = serialize($arr);
            $cart->save();
        } else {
            $cart = Cart::create([
                'uuid' => bin2hex(random_bytes(3)),
                'user_id' => Auth::id(),
                'data' => serialize([
                    $request['product_id'] => [
                        'q'=> $request['quantity'],
                        'p' => $request['price'],
                    ]
                ]),
            ]);
        }
//        Session::flash('add_to_cart', 'Product is added to cart!'); флеш месседж будет отображаться после отправки формі на фронте

        return response()->json(['code' => 200, 'status' => 'success'])->withCookie(cookie('cart', $cart->uuid));
    }

    public function showCart()
    {
        $cart = Cart::getCartFromCookies();

        $cartItems = [];

        foreach (unserialize($cart->data) as $productId => $data) {
            $cartItems[] = [
                'product' => Product::find($productId)->name,
                'quantity' => $data['q'],
                'price' => $data['p'], // ToDO или не передавать цену в аякс-запросе, а брать ее у продукта. Надо решить
            ];
        }

        return view('cart/show', ['cartItems' => $cartItems]);
    }

    public function updateCart(Product $product, Request $request)
    {

    }

    // TODO продумать кейс когда пользователь добавляет товар в корзину (сетится сессия корзины), переходит на страницу корзины и чистит куки-сессию
    public function deleteCart(Cart $cart)
    {

    }
}
