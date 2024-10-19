@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <h1> CATEGORIES </h1>
            <a class="btn btn-secondary" href="{{route('categories.create')}}">Add new</a>

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
                    <th scope="row">{{$category->id}}</th>
                    <td>
                        {{$category->title}}
                    </td>
                    <td>
                        <img src="{{asset($category->thumbnail)}}" alt="" title="" height="100" width="100">
                    </td>
                    <td>
                        <a href="{{route('categories.edit', $category->id)}}"><i class="fa fa-eye"></i>edit</a> ,
                        <form method="POST" action="{{route('categories.destroy', $category->id)}}">
                            @csrf
                            @method('DELETE')
                            <button>Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        {{ $categories->links() }}
    </div>
@endsection
