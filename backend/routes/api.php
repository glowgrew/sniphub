<?php

use App\Http\Controllers\api\v1\AuthController;
use App\Http\Controllers\api\v1\SnippetController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::controller(SnippetController::class)->group(function () {
        Route::get('/snippets', 'index')->prefix('user');
        Route::patch('/snippets/{unique_id}', 'update');
        Route::delete('/snippets/{unique_id}', 'destroy');
    });
});


Route::controller(SnippetController::class)->group(function () {

    Route::get('/snippets/{unique_id}', 'show');
    Route::post('/snippets', 'store');
});

Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::get('/login', 'login');
});