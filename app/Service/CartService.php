<?php

namespace App\Service;

use App\Enums\Cart as CartEnum;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Support\Facades\Cookie;

class CartService
{
    public function updateProductQuantityInCart(Cart $cart, int $productId, int $newQuantity = 0): string
    {
        $msg = '';
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

    public function attachCartToUser(Cart $cart, User $user): void
    {

    }
}
