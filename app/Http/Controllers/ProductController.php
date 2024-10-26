<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {

    }

    public function show(Product $product)
    {
        return view('products/show', ['product' => $product]);
    }

}
