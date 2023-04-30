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
use App\Http\Controllers\Port\ProductCategoryController as PortProductCategoryController;
use App\Http\Controllers\Port\ProductController as PortProductController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Middleware\CheckSuperAdmin;
use App\Http\Middleware\CheckUserActiveBlog;
use App\Http\Middleware\LogRequest;
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

Route::group(['middleware' => [LogRequest::class]], function () {
    Auth::routes(['verify' => true]);
    Route::group(['middleware' => ['verified', SetUserActiveBlog::class]], function () {
        Route::get(RouteServiceProvider::HOME, [SiteController::class, 'index'])->name('home');
        Route::resource('blogs', BlogController::class);
        Route::patch('blogs/{blog}/active', [BlogController::class, 'active'])->name('blogs.active');
        Route::group(['middleware' => [CheckUserActiveBlog::class]], function () {
            Route::resource('contacts', ContactController::class);

            Route::get('ports/products/index', [PortProductController::class, 'index'])->name('ports.products.index');
            Route::get('ports/products/export', [PortProductController::class, 'export'])->name('ports.products.export');
            Route::post('ports/products/import', [PortProductController::class, 'import'])->name('ports.products.import');

            Route::get('ports/products-categories/index', [PortProductCategoryController::class, 'index'])->name('ports.products_categories.index');
            Route::get('ports/products-categories/export', [PortProductCategoryController::class, 'export'])->name('ports.products_categories.export');
            Route::post('ports/products-categories/import', [PortProductCategoryController::class, 'import'])->name('ports.products_categories.import');

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
    Route::group(['prefix' => 'superadmin', 'middleware' => ['verified', CheckSuperAdmin::class]], function () {
        Route::get('migrate', [SuperAdminController::class, 'migrate']);
    });
});
