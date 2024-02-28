<?php

use App\Http\Controllers\API\_SiteController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', [_SiteController::class, 'index'])->name('index');

Route::apiResource('/categories', CategoryController::class)->names('categories');
Route::get('/categories/{id}/products', [CategoryController::class, 'categoryWithProducts'])->name('categories.withproducts');
Route::apiResource('/brands', BrandController::class)->names('brands');
Route::get('/brands/{id}/products', [BrandController::class, 'brandsWithProducts'])->name('brands.withproducts');
Route::apiResource('/products', ProductController::class)->names('products');
Route::apiResource('/purchases', PurchaseController::class)->names('purchases');

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});
