<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminCategoryStoreRequest;
use App\Http\Requests\AdminCategoryEditRequest;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::paginate(5);

        return view('admin/categories/index')->with('categories', $categories);
    }

    public function create(): Category
    {
        dd('ererer');
    }

    public function edit(Category $category)
    {
       return view('admin/categories/edit')->with('category', $category);
    }

    public function update(AdminCategoryEditRequest $adminCategoryEditRequest): Category
    {
        dd('nbn234234bn');
    }

    public function store(AdminCategoryStoreRequest $adminCategoryStoreRequest)
    {
        dd('nererererreerer');
    }

    public function destroy(Category $category)
    {
        dd('mfheie473');
        $category->delete();
    }
}
