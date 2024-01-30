<?php

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


Route::controller(SnippetController::class)->group(function () {
   Route::post('/snippets', 'store');
   Route::get('/snippets/{unique_id}', 'show');
   Route::patch('/snippets/{unique_id}', 'update');
});