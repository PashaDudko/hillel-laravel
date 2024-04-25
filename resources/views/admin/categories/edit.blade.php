@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <h1> EDIT CATEGORY </h1>
            <a class="btn btn-secondary" href="{{route('categories.index')}}">Back</a>

            <form method="POST" action="{{route('categories.update', $category->id)}}">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="category_title" class="form-label">Category title</label>
                    <input type="text" class="form-control" id="category_title" value="{{$category->title}}">
                </div>
                <div class="mb-3">
                    <label for="category_thumbnail" class="form-label">Thumbnail</label>
                    <input type="file" class="form-control" id="category_thumbnail">
                </div>

                <button type="submit" class="btn btn-primary">Отправить</button>
            </form>


        </div>
    </div>
@endsection
