@extends('welcome1')

@section('content')
    <div class="container">
        https://laracasts.com/discuss/channels/laravel/displaying-images-in-blade-file - оформление картинки!
        <img width="300" height="300" src="{{asset($category->thumbnail)}}" alt="" title="">
        <div class="row justify-content-center">

            <h1> Products refer to <b>{{$category->title}}</b> category </h1>
        </div>
        <br>
        <div>
            <div class="text-center text-sm text-gray-500 dark:text-gray-400 sm:text-right sm:ml-0">
                Select product!
            </div>
            <select name="" id="" onchange="window.location.href=this.options[this.selectedIndex].value;">
                <option value="" selected disabled hidden>Choose here</option>
                @foreach($category->products as $product )
                    <option value="{{ route('products.show', [$product->id])}}">{{$product->name}}</option>
                @endforeach
            </select>
        </div>
    </div>
@endsection
