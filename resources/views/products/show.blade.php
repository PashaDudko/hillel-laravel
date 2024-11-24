@extends('welcome1')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <h1> Product: <b>{{$product->name}}</b> </h1>
            <i>{{$product->description}}</i>
            <img src="{{asset($product->images()->first()->path)}}" alt="" title="">
            <b>SKU:</b> {{$product->SKU}} , <b>PRICE:</b> {{$product->price}}, <b>AVAILABLE:</b> {{$product->quantity}}
        </div>
        <form>
            <button type="button" class="btn btn-dark">ADD TO CART</button>
        </form>
    </div>
@endsection
