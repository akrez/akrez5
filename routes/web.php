<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Gallery\BlogHeroController;
use App\Http\Controllers\Gallery\BlogLogoController;
use App\Http\Controllers\Gallery\ProductImageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Meta\ProductPropertyController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\Meta\BlogKeywordController;
use App\Http\Controllers\Meta\ProductCategoryController;
use App\Http\Middleware\CheckUserActiveBlog;
use App\Http\Middleware\SetUserActiveBlog;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Auth::routes(['verify' => true]);
Route::group(['middleware' => ['verified', SetUserActiveBlog::class]], function () {
    Route::get(RouteServiceProvider::HOME, [SiteController::class, 'index'])->name('home');
    Route::resource('blogs', BlogController::class);
    Route::patch('blogs/{blog}/active', [BlogController::class, 'active'])->name('blogs.active');
    Route::group(['middleware' => [CheckUserActiveBlog::class]], function () {
        Route::resource('contacts', ContactController::class);
        Route::resource('products', ProductController::class);
        Route::resource('keywords', BlogKeywordController::class);
        Route::resource('logos', BlogLogoController::class);
        Route::resource('heroes', BlogHeroController::class);
        Route::resource('products/{product}/categories', ProductCategoryController::class, ['as' => 'products']);
        Route::resource('products/{product}/properties', ProductPropertyController::class, ['as' => 'products']);
        Route::resource('products/{product}/images', ProductImageController::class, ['as' => 'products']);
    });
});
Route::get('api/{blogName}', [ApiController::class, 'index'])->name('api');
