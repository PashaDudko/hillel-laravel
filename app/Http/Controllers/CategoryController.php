<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::all();

        return view('categories/index')->with('categories', $categories);
    }

    public function show(Category $category): View
    {
        return view('categories/show', ['category' => $category]);
    }
}
