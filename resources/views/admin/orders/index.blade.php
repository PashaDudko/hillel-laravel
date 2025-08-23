@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <h1> ORDERS </h1>
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">ID</th>
{{--                    <th scope="col">Product</th>--}}
                    <th scope="col">User</th>
                    <th scope="col">Status</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($orders as $order)
                <tr>
                    <td>{{$loop->index+1}}</td>
                    <td>{{$order->id}}</td>
                    <td>{{$order->user->name}} {{$order->user->lastname}}</td>
                    <td>{{$order->status}}</td>
                    <td>
                        some actions
{{--                        <a href="{{route('categories.edit', $category->id)}}"><i class="fa fa-pen"></i></a>,--}}
{{--                        <form method="POST" action="{{route('categories.destroy', $category->id)}}">--}}
{{--                            @csrf--}}
{{--                            @method('DELETE')--}}
{{--                            <button><i class="fa fa-trash"></i></button>--}}
{{--                        </form>--}}
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        {{ $orders->links() }}
    </div>
@endsection
