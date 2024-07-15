<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|




*/// Authentication routes
// Public routes for authentication
Route::group(['middleware' => 'api'], function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
});

// Protected routes that require JWT authentication
Route::group(['middleware' => 'jwt.verify'], function () {
    Route::get('me', [AuthController::class, 'me']);
    Route::get('profile', function(Request $request) {
        return response()->json($request->user());
    });
});

//Product routes
Route::middleware(['jwt.auth'])->group(function () {
    Route::get('products', [ProductController::class, 'index']);
    Route::post('products', [ProductController::class, 'store']);
    Route::get('products/{id}', [ProductController::class, 'show']);
    Route::put('products/{id}', [ProductController::class, 'update']);
    Route::delete('products/{id}', [ProductController::class, 'destroy']);
});

//order route
Route::middleware(['jwt.auth'])->group(function () {
    Route::post('orders', [OrderController::class, 'store']);
});
