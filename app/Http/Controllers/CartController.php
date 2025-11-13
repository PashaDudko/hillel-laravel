<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Service\CartService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CartController extends Controller
{
    public function __construct(public CartService $cartService)
    {
    }

    public function addToCart(Request $request): JsonResponse     //        https://dev.to/codeanddeploy/laravel-model-create-example-4ko
    {
        if (Cart::isCartInCookies() && $cart = Cart::getUserCartFromCookies()) {
            $this->cartService->addToExistingCart($cart, $request);
        } else {
            $this->cartService->openNewCartAndSetCookie($request);
        }
//        return response()->json(['code' => 200, 'status' => 'success'])->withCookie(cookie('cart', $cart->uuid)); // не смог эту куку потом удалить после создания ордера
        return response()->json(['code' => 200, 'status' => 'success']);
    }

    public function show()
    {
        $cart = Cart::getUserCartFromCookies();

        $totalPrice = 0;
        $cartItems = [];

        foreach (unserialize($cart->data) as $productId => $data) {
            $product = Product::find($productId);
            $cartItems[] = [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'slug' => $product->slug,
                'quantity' => $data['q'],
                'price' => $data['p'],
                'in_stock' => $product->in_stock,
            ];
            $totalPrice += $data['p'] * $data['q'];
        }

        return view('cart/show', ['cartItems' => $cartItems, 'totalPrice' => $totalPrice]);
    }

    public function update(Request $request): JsonResponse
    {
        $cart = Cart::getUserCartFromCookies();

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
        $cart = Cart::getUserCartFromCookies();

        $this->cartService->updateProductQuantityInCart($cart, $product->id);

        return redirect()->back(); // если удаляется последний товар, то надо редірект на главную. + сервис возвращает стринг ( см. метод в Сервисе)
    }

    // TODO продумать кейс когда пользователь добавляет товар в корзину (сетится сессия корзины), переходит на страницу корзины и чистит куки-сессию
    public function delete()
    {
        $cart = Cart::getUserCartFromCookies();
        $this->cartService->closeCartAndClearCookie($cart);

        return redirect()->route('home');
    }
}
