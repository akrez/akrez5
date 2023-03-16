<?php

use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiteController;
use App\Http\Middleware\CheckUserActiveBlog;
use App\Http\Middleware\SetUserActiveBlog;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\Gallery\ProductGalleryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Property\ProductPropertyController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\TagController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get(RouteServiceProvider::HOME, [SiteController::class, 'index'])->name('home');
Auth::routes(['verify' => true]);
Route::group(['middleware' => ['verified', SetUserActiveBlog::class]], function () {
    Route::resource('blogs', BlogController::class);
    Route::patch('blogs/{blog}/active', [BlogController::class, 'active'])->name('blogs.active');
    Route::group(['middleware' => [CheckUserActiveBlog::class]], function () {
        Route::resource('products', ProductController::class);
        Route::put('products/{product}/active', [ProductController::class, 'active'])->name('products.active');
        Route::get('products/{product}/tags', [TagController::class, 'productForm'])->name('products.tags.form');
        Route::post('products/{product}/tags', [TagController::class, 'productSync'])->name('products.tags.sync');
        Route::resource('products/{product}/properties', ProductPropertyController::class, [
            'as' => 'products',
        ]);
        Route::resource('products/{product}/galleries', ProductGalleryController::class, [
            'as' => 'products',
        ]);
    });
});
