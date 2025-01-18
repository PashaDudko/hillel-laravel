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
{{--                <th scope="col">Add/Remove</th>--}}
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
                         {{$item['product']}}
                    </td>
                    <td>
                        {{$item['price']}}
                    </td>
                    <td>
{{--                        <a href="sdfsdfsdf"><i class="fa fa-minus"></i></a> {{$item['quantity']}} <a href="sdfsdfsdf"><i class="fa fa-plus"></i></a>--}}
                        <button type="button" class="btn border-2 bg-opacity-25">+</button> {{$item['quantity']}} <button type="button">-</button>
                    </td>
                    <td>
                        {{$item['price'] * $item['quantity']}} $
                    </td>
                </tr>
            @endforeach
            <th scope="row">Total Proce: </th>
            </tbody>
        </table>
        <button type="submit" class="btn btn-dark">Order</button>
    </div>
@endsection

