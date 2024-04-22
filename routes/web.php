<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/admin', [\App\Http\Controllers\Admin\AdminController::class, 'index'])->name('admin');
Route::prefix('admin')->group(function () {
    Route::resource('/categories', CategoryController::class)->except('show');
    Route::resource('/products', ProductController::class);
});
//Route::get('/admin/categories', [\App\Http\Controllers\Admin\CategoryController::class, 'categories'])->name('admin/categories/index');
//Route::post('/admin/categories', [\App\Http\Controllers\Admin\CategoryController::class, 'categories'])->name('admin/categories/add');
//Route::put('/admin/categories/{category}', [\App\Http\Controllers\Admin\CategoryController::class, 'categories'])->name('admin/categories/edit');
//Route::delete('/admin/categories/{category}', [\App\Http\Controllers\Admin\CategoryController::class, 'categories'])->name('admin/categories/delete');
