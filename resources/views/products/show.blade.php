@extends('welcome1')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <h1> Product: <b>{{$product->name}}</b> </h1>
        </div>
    </div>
@endsection
