<?php

use App\Http\Controllers\LoginsController;
use App\Http\Controllers\UsersController;
use App\Http\Middleware\CheckHeaders;
use App\Http\Middleware\IncomingDataValidation;
use App\Http\Middleware\TokenValidation;
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

Route::middleware([CheckHeaders::class, IncomingDataValidation::class, TokenValidation::class])->prefix("v1")->group(function () {
    //All user endpoints.
    Route::post('/users/create', [UsersController::class, 'create']);
    Route::get('/users/read', [UsersController::class, 'read']);
    Route::get('/users/read_all', [UsersController::class, 'read_all']);
    Route::put('/users/update', [UsersController::class, 'update']);
    Route::delete('/users/delete', [UsersController::class, 'delete']);

    //All login endpoints.
    Route::post('/logins/create', [LoginsController::class, 'create']);
    Route::get('/logins/read', [LoginsController::class, 'read']);
    Route::get('/logins/read_all', [LoginsController::class, 'read_all']);
    Route::put('/logins/update', [LoginsController::class, 'update']);
    Route::delete('/logins/delete', [LoginsController::class, 'delete']);
});
