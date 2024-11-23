@extends('welcome1')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <h1> Product: <b>{{$product->name}}</b> </h1>
            <img src="{{asset($product->images()->first()?->path)}}" alt="" title="">

        </div>
    </div>
@endsection
