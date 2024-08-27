@extends('welcome1')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <h1> Products refer to <b>{{$title}}</b> category </h1>
        </div>
        <br>
        <div>
            <div class="text-center text-sm text-gray-500 dark:text-gray-400 sm:text-right sm:ml-0">
                Select product!
            </div>
            <select name="" id="" onchange="window.location.href=this.options[this.selectedIndex].value;">
                <option value="" selected disabled hidden>Choose here</option>
                @foreach($products as $product )
                    <option value="{{ route('products.show', [$product->id])}}">{{$product->name}}</option>
                @endforeach
            </select>
        </div>
    </div>
@endsection
