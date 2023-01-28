<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\PasswrodController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('auth')->group(function () {
    Route::post('register', [RegisteredUserController::class, 'store']);
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

// 要認証
Route::middleware('auth')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::get('user', [RegisteredUserController::class, 'show']);
        Route::post('logout', [AuthenticatedSessionController::class, 'destroy']);
        Route::post('confirm-password', [PasswrodController::class, 'confirm']);
        Route::put('password', [PasswrodController::class, 'update']);
    });

    Route::prefix('articles')->group(function () {
        Route::post('', [ArticleController::class, 'store']);
        Route::post('query', [ArticleController::class, 'query']);
        Route::get('{article}', [ArticleController::class, 'show']);
        Route::put('{article}', [ArticleController::class, 'update']);
        Route::delete('{article}', [ArticleController::class, 'destroy']);
    });
});
