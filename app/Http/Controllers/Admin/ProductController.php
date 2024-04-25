<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        dd('tyt74urhreu');
        $products = Product::all();

        return view('admin.products')->with('products', $products);
    }
}
