@extends('welcome1')

@section('content')
    YOUR CART VIEW
    <div class="container">
        <table class="table">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Product</th>
                <th scope="col">Price</th>
                <th scope="col">Quantity</th>
                <th scope="col">Total</th>
            </tr>
            </thead>
            <tbody>
            @foreach($cartItems as $item)
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
                        <form id="cart-update" method="POST" action="{{ route('cart.update') }} ">
                            @csrf
                            <input type="hidden" name="product_id" value="{{$item['product_id']}}">
                            <input type="hidden" name="new_quantity" id="new_quantity" value="">
                            <button id="plus" type="submit" class="btn border-2 btn-outline-dark" onclick="changeProductQuantityInCart('plus');">+</button>
                            <span id="current_quantity">{{$item['quantity']}}</span>
{{--                            <input type="number" id="current_quantity" value="{{$item['quantity']}}" min="0" max="10"> если тип поля намбер - то там уж есть счетчик и есть ограничения--}}
                            <button id="minus" type="submit" class="btn border-2 btn-outline-dark" onclick="changeProductQuantityInCart('minus');">-</button>
                        </form>
                    </td>
                    <td>
                        <span id="total">{{$item['price'] * $item['quantity']}}</span> $
                    </td>
                </tr>
            @endforeach
            <th scope="row">Total Price: <span id="total_price">{{$item['price']}}</span> $</th>
            </tbody>
        </table>
        <button class="btn btn-dark" onclick="window.location.href = '/create-order'">Create Order</button>
    </div>
@endsection
{{--https://ru.wordpress.org/plugins/wc-quantity-plus-minus-button/--}}
