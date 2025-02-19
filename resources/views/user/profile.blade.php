@extends('welcome1')

@section('content')

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit">LOGOUT!</button>
        </form>

@endsection
