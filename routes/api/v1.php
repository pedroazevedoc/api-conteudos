<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ComentarioController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VideoController;
use Illuminate\Support\Facades\Route;

// Rotas de autenticação
Route::prefix('auth')->name('auth.')->group(function () {
    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::post('login',    [AuthController::class, 'login'])->middleware('throttle:login')->name('login');
    Route::post('logout',   [AuthController::class, 'logout'])->middleware('auth:sanctum')->name('logout');
});

// Rotas protegidas por autenticação
Route::middleware('auth:sanctum')->group(function () {
    // Users
    Route::apiResource('users', UserController::class)
        ->only(['index', 'show'])
        ->names(['index' => 'users.index', 'show' => 'users.show']);

    // Posts
    Route::prefix('posts')->name('posts.')->group(function () {
        Route::post('/',    [PostController::class, 'store'])->middleware('ability:post-store')->name('store');
        Route::get('/',     [PostController::class, 'index'])->name('index');
        Route::get('/{id}', [PostController::class, 'show'])->name('show');
        Route::get('/{id}/comentarios', [PostController::class, 'comentarios'])->name('comentarios');
    });

    // Videos
    Route::prefix('videos')->name('videos.')->group(function () {
        Route::post('/',    [VideoController::class, 'store'])->middleware('ability:video-store')->name('store');
        Route::get('/',     [VideoController::class, 'index'])->name('index');
        Route::get('/{id}', [VideoController::class, 'show'])->name('show');
        Route::get('/{id}/comentarios', [VideoController::class, 'comentarios'])->name('comentarios');
    });
        
    // Comentários
    Route::prefix('comentarios')->name('comentarios.')->group(function () {
        Route::post('/',       [ComentarioController::class, 'store'])->middleware('ability:comment-store')->name('store');
        Route::get('/',        [ComentarioController::class, 'index'])->name('index');
        Route::get('/{id}',    [ComentarioController::class, 'show'])->name('show');
        Route::delete('/{id}', [ComentarioController::class, 'destroy'])->name('destroy');
    });
});