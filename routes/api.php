<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CitizenController;
use App\Http\Controllers\Api\V1\PovertyController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:sanctum');
        Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    });

    Route::middleware('auth:sanctum')->group(function () {
        // Citizens CRUD
        Route::apiResource('citizens', CitizenController::class)->names('api.v1.citizens');

        // Poverty Status & Management
        Route::get('/poverty/status/{nik}', [PovertyController::class, 'status'])->name('api.v1.poverty.status');
        Route::apiResource('poverty', PovertyController::class)->names('api.v1.poverty')->only(['index', 'store']);
    });
});
