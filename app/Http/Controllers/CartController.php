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
            $cart->update(['data' => serialize($arr)]);
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

    public function show()
    {
        $cart = Cart::getCartFromCookies();

        $cartItems = [];

        foreach (unserialize($cart->data) as $productId => $data) {
            $cartItems[] = [
                'product_id' => Product::find($productId)->id,
                'product_name' => Product::find($productId)->name,
                'quantity' => $data['q'],
                'price' => $data['p'], // ToDO или не передавать цену в аякс-запросе, а брать ее у продукта. Надо решить
            ];
        }

        return view('cart/show', ['cartItems' => $cartItems]);
    }

    public function update(Request $request): JsonResponse
    {
        // ToDo добавить валидацию если заказано больше едениц чем есть в наличии. Или если заказано отрицательное число едениц товара (в скрипте уже нельзя счетчик количества прокликать меньше 0)
        $cart = Cart::getCartFromCookies();

        if (!$cart) {
            return response()->json(['code' => 400, 'status' => 'failed']);
        }

        $productId = $request['product_id'];
        $newQuantity = $request['new_quantity'];

        $data = unserialize($cart->data);
        $data[$productId]['q'] = $newQuantity;
        $cart->update(['data' => serialize($data)]);

        return response()->json(['code' => 200, 'status' => 'success']);
    }

    // TODO продумать кейс когда пользователь добавляет товар в корзину (сетится сессия корзины), переходит на страницу корзины и чистит куки-сессию
    public function delete(Cart $cart)
    {

    }
}
