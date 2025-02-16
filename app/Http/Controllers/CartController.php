<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Service\CartService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class CartController extends Controller
{
    public function __construct(public CartService $cartService)
    {
    }

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

        $minutes = 60;
        Cookie::queue(Cookie::make('cart', $cart->uuid, $minutes));

//        return response()->json(['code' => 200, 'status' => 'success'])->withCookie(cookie('cart', $cart->uuid)); // не смог эту куку потом удалить после создания ордера
        return response()->json(['code' => 200, 'status' => 'success']);
    }

    public function show()
    {
        $cart = Cart::getCartFromCookies();

        $cartItems = [];

        foreach (unserialize($cart->data) as $productId => $data) {
            $product = Product::find($productId);
            $cartItems[] = [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'slug' => $product->slug,
                'quantity' => $data['q'],
                'price' => $data['p'], // ToDO или не передавать цену в аякс-запросе, а брать ее у продукта. Надо решить
                'in_stock' => $product->in_stock,
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

        $message = $this->cartService->updateProductQuantityInCart($cart, $productId, $newQuantity);

        return response()->json(['code' => 200, 'status' => 'success', 'message' => $message]);
    }

    public function removeProductFromCart(Product $product)
    {
        $cart = Cart::getCartFromCookies();

        $this->cartService->updateProductQuantityInCart($cart, $product->id);

        return redirect()->back(); // если удаляется последний товар, то надо редірект на главную. + сервис возвращает стринг ( см. метод в Сервисе)
    }

    // TODO продумать кейс когда пользователь добавляет товар в корзину (сетится сессия корзины), переходит на страницу корзины и чистит куки-сессию
    public function delete()
    {
        $cart = Cart::getCartFromCookies();
        $this->cartService->closeCartAndClearCookie($cart);

        return redirect()->route('home');
    }
}
