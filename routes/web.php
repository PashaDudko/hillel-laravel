<?php

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Auth\GoogleLoginController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
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

//Route::get('/', function () {
//    return view('welcome');
//});

Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/google/redirect', [GoogleLoginController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('/google/callback', [GoogleLoginController::class, 'handleGoogleCallback'])->name('google.callback');

Route::get('/admin', [\App\Http\Controllers\Admin\AdminController::class, 'index'])->name('admin');
Route::prefix('admin')->group(function () {
    Route::resource('/categories', AdminCategoryController::class)->except('show');
    Route::resource('/products', AdminProductController::class);
});
Route::resource('/categories', CategoryController::class);//->except('show');
Route::resource('/products', ProductController::class);//->except('show');
//Route::get('/admin/categories', [\App\Http\Controllers\Admin\CategoryController::class, 'categories'])->name('admin/categories/index');
//Route::post('/admin/categories', [\App\Http\Controllers\Admin\CategoryController::class, 'categories'])->name('admin/categories/add');
//Route::put('/admin/categories/{categories}', [\App\Http\Controllers\Admin\CategoryController::class, 'categories'])->name('admin/categories/edit');
//Route::delete('/admin/categories/{categories}', [\App\Http\Controllers\Admin\CategoryController::class, 'categories'])->name('admin/categories/delete');
