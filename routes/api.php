<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Api\InfoController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::auto('/auth', AuthController::class);

Route::prefix('admin')->middleware('auth:sanctum')->group(function () {
    Route::apiResources([
        'categories' => CategoryController::class,
        'products' => ProductController::class,
    ]);
    Route::post('productUpdate/{product}', [ProductController::class, 'productUpdate']);
    Route::get('searchProduct', [ProductController::class, 'search']);
    Route::auto('/order', OrderController::class);
});

Route::auto('/info', InfoController::class) ;

Route::get('/artisan/{name}', function ($name) {
    \Artisan::call($name);
    return 'OK';
});
