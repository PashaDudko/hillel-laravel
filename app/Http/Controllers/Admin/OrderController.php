<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Order as OrderEnum;
use App\Events\ProductsQuantityHasChangedInStock;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::orderBy('id', 'DESC')->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $orderItems = [];
        $totalPrice = 0;
        $statuses = array_combine(
            array_column(OrderEnum::cases(), 'name'),
            array_column(OrderEnum::cases(), 'value'),
        );

        unset($statuses[OrderEnum::CANCELED->name]); // only user can cancel his order. Admin can reject order
//dd($statuses);
        foreach (unserialize($order->data) as $productId => $data) {
            $product = Product::find($productId);
            $totalPrice += $data['q'] * $data['p'];
            $orderItems[] = [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'slug' => $product->slug,
                'quantity' => $data['q'],
                'price' => $data['p'],
                'in_stock' => $product->in_stock,
            ];
        }

        return view('admin.orders.show', [
            'order' => $order,
            'orderItems' => $orderItems,
            'totalPrice' => $totalPrice,
            'statuses' => $statuses,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $result = false;
        $valuesForUpdate = [];

        if ($order->status->value != OrderEnum::CONFIRMED->value && OrderEnum::CONFIRMED->value == $request['status']) {
            $result = true;
        }

        $valuesForUpdate['status'] = OrderEnum::from($request['status']);

        if (!empty($request['estimated_delivery_date'])) { // ToDo write and use Request validation
            $valuesForUpdate['estimated_delivery_date'] = $request['estimated_delivery_date'];
        }

        if (OrderEnum::DELIVERED->value == $request['status']) {
            $valuesForUpdate['delivered_at'] = now();
        }

        if (OrderEnum::RECEIVED->value == $request['status']) {
            $valuesForUpdate['received_at'] = now();
        }

        $order->update($valuesForUpdate);

        if ($order->isDirty('status') && OrderEnum::CONFIRMED->value == $request['status']) {
            ProductsQuantityHasChangedInStock::dispatch($order);
        }

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'updated',
            'display_updated_value_in_stock' => $result
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
