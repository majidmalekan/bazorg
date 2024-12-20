<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\SwaggerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::apiResource('product', ProductController::class)->only(['index', 'show']);
    Route::post('register', [AuthController::class, 'register']);
    Route::get('yaml-convert', [SwaggerController::class, 'yamlConvert']);
    Route::post('login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('me', [AuthController::class, 'me']);
        Route::apiResource('user', AuthController::class)->only(['update']);
        Route::prefix('admin')->group(function () {
            Route::apiResource('product', ProductController::class)->only(['update', 'store', 'destroy']);
        });
    });
});
