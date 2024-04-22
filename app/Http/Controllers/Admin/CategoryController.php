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
        $categories = Category::all();

        return view('admin.categories')->with('categories', $categories);
    }

    public function create(): Category
    {
        dd('ererer');
    }

    public function edit(Category $category)
    {
       dd('nbnbnbnbn');
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
