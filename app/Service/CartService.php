<?php

namespace App\Service;

use App\Enums\Cart as CartEnum;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class CartService
{
    public function openNewCartAndSetCookie(Request $request): void
    {
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

        Cart::setCartCookie($cart->uuid);
    }

    public function addToExistingCart(Cart $cart, Request $request): void
    {
        $currentDataArr = unserialize($cart->data);
        $newDataArr = [];

        if (array_key_exists($request['product_id'], $currentDataArr)) {
            $currentDataArr[$request['product_id']]['q']++;
        } else {
            $newDataArr = [
                $request['product_id'] => [
                    'q' => $request['quantity'],
                    'p' => $request['price'],
                ]
            ];
        }

        $arr = $currentDataArr + $newDataArr; // https://foxminded.ua/ru/php-add-array-to-array/ !!!!!!

        $cart->update(['data' => serialize($arr)]);
    }

    public function updateProductQuantityInCart(Cart $cart, int $productId, int $newQuantity = 0): string
    {
        $data = unserialize($cart->data);

        if ($newQuantity == 0) {
            unset($data[$productId]);

            if (empty($data)) {
                $cart->update(['status' => CartEnum::CLOSED]);
                Cookie::queue(Cookie::forget('cart'));
                return 'empty';
            }

            $msg = 'removed';
        } else {
            $data[$productId]['q'] = $newQuantity;
            $msg = 'updated';
        }

        $cart->update(['data' => serialize($data)]);

        return $msg;
    }

    public function closeCartAndClearCookie(Cart $cart): void
    {
        try {
            $cart->update(['status' => CartEnum::CLOSED]);
            Cookie::queue(Cookie::forget('cart'));
        } catch (\Exception $exception) {
            dd('Some error during deleting the cart: ' . $exception->getMessage());
        }

    }
}
