<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login',    [AuthController::class, 'login'])->middleware('throttle:login');
        Route::post('/logout',   [AuthController::class, 'logout']);
    });

    Route::prefix('users')->group(function () {
        Route::get('/',     [UserController::class, 'index']);
        Route::get('/{id}', [UserController::class, 'show']);
    })->middleware('auth:sanctum');

    Route::prefix('posts')->group(function () {
        Route::get('/',     [PostController::class, 'index']);
        Route::get('/{id}', [PostController::class, 'show']);
        Route::post('/',    [PostController::class, 'store']);
    })->middleware('auth:sanctum');
});