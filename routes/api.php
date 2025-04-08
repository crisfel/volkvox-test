<?php

use App\Http\Controllers\Product\DeleteController;
use App\Http\Controllers\Product\GetAllController;
use App\Http\Controllers\Product\StoreController;
use App\Http\Controllers\Product\UpdateController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/products', GetAllController::class);
Route::post('/products', StoreController::class);
Route::put('/products', UpdateController::class);
Route::delete('/products/{id}', DeleteController::class);
