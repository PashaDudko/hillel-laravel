@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <h1> ADMIN PANEL </h1>
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Items</th>
                    <th scope="col">Description</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th scope="row">1</th>
                    <td>
{{--                        <a class="nav-link" href="{{ route('categories.index') }}"><b>Categories</b></a>--}}
                        <a class="nav-link" href="/admin/categories"><b>Categories</b></a>
                    </td>
                    <td>
                        <i> Go to the list of categories. Create new, modify existing, delete unused </i>
                    </td>
                </tr>
                <tr>
                    <th scope="row">2</th>
                    <td>
                        <a class="nav-link" href="{{ route('products.index') }}"><b>Products</b></a>
                    </td>
                    <td>
                        <i> Go to the list of products. Create new, modify existing, delete unused </i>
                    </td>
                </tr>
                <tr>
                    <th scope="row">3</th>
                    <td><b>Orders</b></td>
                    <td>
                        <i> List of created orders </i>
                    </td>
                </tr>
                <tr>
                    <th scope="row">4</th>
                    <td><b>Users</b></td>
                    <td>
                        <i> List of site users</i>
                    </td>
                </tr>
                <tr>
                    <th scope="row">5</th>
                    <td><b>Schedule Commands</b></td>
                    <td>
                        <i> List of schedule commands and results of their executions</i>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
