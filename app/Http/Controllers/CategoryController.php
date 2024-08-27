<?php

namespace App\Http\Controllers;

use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        return view('categories/index')->with('categories', $categories);
    }

    public function show(Category $category)
    {
        return view('categories/show', ['title' => $category->title, 'products' => $category->products]);
    }
}
