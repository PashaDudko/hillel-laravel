<?php

namespace App\Service;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Support\Facades\Cookie;

class CartService
{
    public function updateProductQuantityInCart($cart, $productId, $newQuantity): string
    {
        $msg = '';
        $data = unserialize($cart->data);

        if ($newQuantity == 0) {
            unset($data[$productId]);

            if (empty($data)) {
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

    public function attachCartToUser(Cart $cart, User $user): void
    {

    }
}
