<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Order as OrderEnum;
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
        $orders = Order::paginate(10);

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
        $order->update([
            'status' => OrderEnum::from($request['status'])
        ]);

        if (!empty($request['estimated_delivery_date'])) { // ToDo write and use Request validation
            $order->update([
                'expected_at' => $request['estimated_delivery_date']
            ]);
        }

        OrderCreated::dispatch($order);

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'updated']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
