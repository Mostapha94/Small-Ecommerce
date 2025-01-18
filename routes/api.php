<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;


// Fetch products with search functionality
Route::get('/products', [ProductController::class, 'index']);
//show prodcut details
Route::get('/products/{id}', [ProductController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    //ceate product
    Route::post('/products', [ProductController::class, 'store']);
    //update product
    Route::put('/products/{id}', [ProductController::class, 'update']);
});
