@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <h1> CATEGORIES </h1>
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Title</th>
                    <th scope="col">Thumbnail</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($categories as $category)
                <tr>
                    <th scope="row">{{$loop->index}}</th>
                    <td>
                        {{$category->title}}
                    </td>
                    <td>
                        {{$category->thumbnail}}
                    </td>
                </tr>
                <tr>

                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
