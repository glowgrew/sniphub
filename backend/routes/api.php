<?php

use App\Http\Controllers\api\v1\AuthController;
use App\Http\Controllers\api\v1\CategoryController;
use App\Http\Controllers\api\v1\SnippetController;
use App\Http\Controllers\api\v1\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::get('/login', 'login');
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::controller(SnippetController::class)->group(function () {
        Route::patch('/snippets/{unique_id}', 'update');
        Route::delete('/snippets/{unique_id}', 'destroy');
    });
});

Route::controller(SnippetController::class)->group(function () {
    Route::post('/snippets', 'store');
    Route::get('/snippets/{unique_id}', 'show');
});

Route::controller(UserController::class)->group(function () {
    Route::get('u/{user}', 'show');
});

Route::controller(CategoryController::class)->group(function () {
    Route::get('categories', 'index');
});