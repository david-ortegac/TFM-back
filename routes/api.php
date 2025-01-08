<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BranchController;
use App\Http\Controllers\API\BrandController;
use Illuminate\Support\Facades\Route;

if (!defined('MIDDLEWARE_CONST')) {
    define('MIDDLEWARE_CONST', 'auth:api');
}


Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function () {

    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    Route::post('logout', [AuthController::class, 'logout'])->middleware(MIDDLEWARE_CONST);
    Route::post('refresh', [AuthController::class, 'refresh'])->middleware(MIDDLEWARE_CONST);
    Route::post('profile', [AuthController::class, 'profile'])->middleware(MIDDLEWARE_CONST);
    Route::post('update-profile', [AuthController::class, 'updateProfile'])->middleware(MIDDLEWARE_CONST);
    Route::post('find-by-email', [AuthController::class, 'findByEmail'])->middleware(MIDDLEWARE_CONST);
    Route::post('change-user-type', [AuthController::class, 'changeUserType'])->middleware(MIDDLEWARE_CONST);
    Route::post('delete-profile', [AuthController::class, 'deleteProfile'])->middleware(MIDDLEWARE_CONST);
});

Route::apiResource('brands', BrandController::class);
Route::post('brands/intranet', [BrandController::class, 'privateIndex'])->middleware(MIDDLEWARE_CONST);

Route::apiResource('branches', BranchController::class);

