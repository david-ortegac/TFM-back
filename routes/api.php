<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;

const MIDDLEWARE_CONST = "auth:api";

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function () {

    Route::post('register', [AuthController::class,'register']);
    Route::post('login', [AuthController::class,'login']);

    Route::post('logout', [AuthController::class,'logout'])->middleware(MIDDLEWARE_CONST);
    Route::post('refresh', [AuthController::class,'refresh'])->middleware(MIDDLEWARE_CONST);
    Route::post('profile', [AuthController::class,'profile'])->middleware(MIDDLEWARE_CONST);

});

