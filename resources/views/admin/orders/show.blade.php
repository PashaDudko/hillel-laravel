{{--@extends('layouts.app')--}}
@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <h1> Order Details </h1>
            <a class="btn btn-secondary" href="{{route('admin.orders.index')}}">Back</a>

            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Product</th>
                        <th scope="col">Price</th>
                        <th scope="col">Quantity | (In stock)</th>
                        <th scope="col">Total</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($orderItems as $item)
                    <tr>
                        <td>
                            {{$loop->index + 1}}
                        </td>
                        <td>
                            {{$item['product_name']}}
                        </td>
                        <td>
                            <span id="product_price">{{$item['price']}}</span>
                        </td>
                        <td>
                            {{$item['quantity']}} | ({{$item['in_stock']}})
                        </td>
                        <td>
                            <span class="total" id="{{$item['slug']}}_total">{{$item['price'] * $item['quantity']}}</span> $
                        </td>
                    </tr>
                @endforeach
                <th scope="row">Total Price: <span id="total_price">{{$totalPrice}}</span> $</th>
                </tbody>
            </table>
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">Number</th>
                    <th scope="col">Deliver</th>
                    <th scope="col">Payment</th>
                    <th scope="col">Status</th>
                    <th scope="col">Expected delivery date</th>
                    <th scope="col">Last updated at</th>
                    <th scope="col">Action</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                        {{$order->number}}
                    </td>
                    <td>
                        {{$order->deliver}}
                    </td>
                    <td>
                        {{$order->payment}}
                    </td>
                    <td>
                        <span id="current_order_display_status"> {{$order->status}} </span>
                    </td>
                    <td>
                        <span id="delivery_date">{{$order->expected_at}}</span>
                    </td>
                    <td>
                        {{$order->updated_at}}
                    </td>
                    <td>
                        <button id="status_btn">Change status</button>
                        @include('modals.admin.orders.update_status_modal', ['order' => $order, 'statuses' => $statuses])
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
