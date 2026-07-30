<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CitizenController;
use App\Http\Controllers\Api\V1\PovertyController;
use App\Http\Controllers\Api\V1\QueueAuthController;
use App\Http\Controllers\Api\V1\QueueController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:sanctum');
        Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    });

    // Queue-specific auth (username-based, for Wails app)
    Route::post('auth/login', [QueueAuthController::class, 'login']);
    Route::post('auth/verify', [QueueAuthController::class, 'verify']);
    Route::post('auth/logout', [QueueAuthController::class, 'logout'])->middleware('auth:sanctum');

    // Public queue data
    Route::get('counters', [QueueController::class, 'counters']);

    Route::middleware('auth:sanctum')->group(function () {
        // Citizens CRUD
        Route::apiResource('citizens', CitizenController::class)->names('api.v1.citizens');

        // Poverty Status & Management
        Route::get('/poverty/status/{nik}', [PovertyController::class, 'status'])->name('api.v1.poverty.status');
        Route::apiResource('poverty', PovertyController::class)->names('api.v1.poverty')->only(['index', 'store']);

        // Queue system
        Route::get('queues', [QueueController::class, 'index']);
        Route::post('queues/call-next', [QueueController::class, 'callNext']);
        Route::post('queues/{queue:number}/recall', [QueueController::class, 'recall']);
        Route::post('queues/{queue:number}/complete', [QueueController::class, 'complete']);
        Route::post('queues/{queue:number}/skip', [QueueController::class, 'skip']);
    });
});
