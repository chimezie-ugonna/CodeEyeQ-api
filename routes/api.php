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
    Route::controller(UsersController::class)->group(function () {
        Route::post("/users/create", "create");
        Route::get("/users/read", "read");
        Route::get("/users/read_all", "read_all");
        Route::put("/users/update", "update");
        Route::delete("/users/delete", "delete");
    });

    //All login endpoints.
    Route::controller(LoginsController::class)->group(function () {
        Route::post("/logins/create", "create");
        Route::get("/logins/read", "read");
        Route::get("/logins/read_all", "read_all");
        Route::put("/logins/update", "update");
        Route::delete("/logins/delete", "delete");
    });
});
