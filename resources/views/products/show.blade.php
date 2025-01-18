@extends('welcome1')

@section('content')
    <div class="container">
        <img width="300" height="300" src="{{asset($product->images()->first()->path)}}" alt="" title="">
        <div class="row justify-content-center">
            <div id="res"></div>
            <br>
            <br>
            <h1> Product: <b>{{$product->name}}</b> </h1>
            <i>{{$product->description}}</i>
            <ul>
                <li><b>SKU:</b> {{$product->SKU}}</li>
                <li><b>PRICE:</b> {{$product->price}}</li>
                <li><b>AVAILABLE:</b> {{$product->quantity}}</li>
            </ul>
        </div>
        <form id="cart" method="POST" action="{{ route('cart.add') }} ">
            @csrf
            <input type="number" class="form-control" hidden name="product_id" value="{{$product->id}}">
            <input type="number" class="form-control" hidden name="price" value="{{$product->price}}">
            <input type="number" class="form-control" hidden name="quantity" value=1> {{--Подумать, как пользовталеь может сразу заказывать больше 1 шт --}}
            <button type="submit" class="btn btn-dark" id="cart_submit_btn">ADD TO CART</button>
        </form>
    </div>
@endsection
