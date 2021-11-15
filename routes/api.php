<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('register', [\App\Http\Controllers\API\AuthController::class, 'register'])->name('api.register');
Route::post('login', [\App\Http\Controllers\API\AuthController::class, 'login'])->name('api.login');

Route::group(['middleware' => ['auth:sanctum']], function(){
    Route::post('/logout', [\App\Http\Controllers\API\AuthController::class, 'logout']);
    Route::get('/products/', [\App\Http\Controllers\API\ProductController::class, 'index'])->name('product.all');
    Route::post('/products/', [\App\Http\Controllers\API\ProductController::class, 'store'])->name('product.create');
    Route::put('/products/{key}', [\App\Http\Controllers\API\ProductController::class, 'update'])->name('product.update');
    Route::delete('/products/{key}', [\App\Http\Controllers\API\ProductController::class, 'destroy'])->name('product.delete');
    Route::get('/products/{key}', [\App\Http\Controllers\API\ProductController::class, 'show'])->name('product.show');
});
