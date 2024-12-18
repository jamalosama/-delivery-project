<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\StoreController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/register',[AuthController::class,'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout']);
Route::put('/profile', [AuthController::class, 'update_profile']);
//--
Route::get('/stores',[StoreController::class,'index']);
Route::get('/products', [ProductController::class, 'show_all']);
Route::get('/products/store/{id}',[ProductController::class,'productByStore']);
//--
Route::post('/search',[SearchController::class,'search']);
Route::get('/product/{id}',[ProductController::class,'product_info']);

Route::post('/cart/product/{id}', [CartController::class, 'addToCart']);
Route::get('/mycart',[CartController::class,'showProducts']);

Route::delete('cart/delete/{product_id}',[CartController::class,'destroy']);
Route::put('cart/update/{cart_id}',[CartController::class,'update']);


Route::put('cart/status/{id}', [CartController::class, 'updateStatus']);



