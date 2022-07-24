<?php

use Illuminate\Http\Request;
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
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

Route::prefix('auth')->controller(AuthController::class)->group(function() {
    Route::post('login', 'login');
    Route::post('register','register');
});

Route::middleware('auth:sanctum')->prefix('users')->controller(UserController::class)->group(function() {
    Route::get('/', 'index');
    Route::post('/','create');
    Route::get('/{id}','show');
    Route::put('/{id}','update');
    Route::delete('/{id}','destroy');
});