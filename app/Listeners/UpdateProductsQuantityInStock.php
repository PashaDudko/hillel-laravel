<?php

namespace App\Listeners;

use App\Events\ProductsQuantityHasChangedInStock;
use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateProductsQuantityInStock
{
    /**
     * Create the event listener.
     */
    public function __construct() // де задаються параметри які будуть передані сюди?
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ProductsQuantityHasChangedInStock $event): void
    {
        foreach (unserialize($event->order->data) as $productId => $data) {
            $product = Product::find($productId);
            $product->update([
                'in_stock' => $product->in_stock - $data['q']
            ]);
        }
    }
}
