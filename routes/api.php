<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ReviewsController;
use App\Http\Middleware\AuthMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group([
    'middleware' => 'api',
    'prefix' => 'authentication'
], function ($route) {
    Route::post('/register', [AuthenticationController::class, 'register']);
    Route::post('/login', [AuthenticationController::class, 'login']);
    Route::get('/user', [AuthenticationController::class, 'getUser']);
});

Route::group([
    'middleware' => ['api', AuthMiddleware::class],
    'prefix' => 'productos'
], function ($route) {
    Route::get('/all-enabled', [ProductsController::class, 'getAllProductsEnabled']);
    Route::get('/all', [ProductsController::class, 'getAllProducts']);
    Route::get('/{id}', [ProductsController::class, 'getSingleProduct']);
    Route::post('/add', [ProductsController::class, 'addProducts']);
    Route::patch('/update/{id}', [ProductsController::class, 'updateProduct']);
    Route::post('/disable/{id}', [ProductsController::class, 'disableProduct']);
    Route::post('/enable/{id}', [ProductsController::class, 'enableProduct']);
});

Route::group([
    'middleware' => ['api', AuthMiddleware::class],
    'prefix' => 'reviews'
], function ($route) {
    Route::get('/all', [ReviewsController::class, 'showAllReviews']);
    Route::post('/create', [ReviewsController::class, 'createReview']);
    Route::get('/category/{category}', [ReviewsController::class, 'getReviewByproductCategory']);
    Route::get('/product/{name}', [ReviewsController::class, 'getReviewByProductName']);
});

