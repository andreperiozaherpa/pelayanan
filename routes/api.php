<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CitizenController;
use App\Http\Controllers\Api\V1\DisplayController;
use App\Http\Controllers\Api\V1\MppQueueOperationController;
use App\Http\Controllers\Api\V1\MppRequestController;
use App\Http\Controllers\Api\V1\MppServiceController;
use App\Http\Controllers\Api\V1\MppSkmController;
use App\Http\Controllers\Api\V1\MppTicketController;
use App\Http\Controllers\Api\V1\PovertyController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
        Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    });

    // Publik — dipakai kiosk (Gerai) tanpa login
    Route::get('/services', [MppServiceController::class, 'index']);
    Route::get('/services/{service}', [MppServiceController::class, 'show'])->whereNumber('service');
    Route::post('/services/{service}/requests', [MppRequestController::class, 'store'])->whereNumber('service');
    Route::post('/tickets', [MppTicketController::class, 'store']);

    // Publik — data layar display (riwayat antrian hari ini)
    Route::get('/display/history', [DisplayController::class, 'history']);

    // Publik — Survei Kepuasan Masyarakat (SKM)
    Route::get('/opd/{opd}/skm', [MppSkmController::class, 'questions'])->whereNumber('opd');
    Route::post('/opd/{opd}/skm', [MppSkmController::class, 'store'])->whereNumber('opd');

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/gerai', [MppServiceController::class, 'geraiIndex']);

        Route::prefix('fo')->group(function () {
            Route::get('/waiting', [MppQueueOperationController::class, 'foWaiting']);
            Route::get('/calling', [MppQueueOperationController::class, 'foCalling']);
            Route::post('/call', [MppQueueOperationController::class, 'foCall']);
            Route::post('/forward', [MppQueueOperationController::class, 'foForward']);
            Route::post('/reject', [MppQueueOperationController::class, 'foReject']);
            Route::post('/recall', [MppQueueOperationController::class, 'foRecall']);
            Route::post('/skip', [MppQueueOperationController::class, 'foSkip']);
        });

        Route::prefix('gerai')->group(function () {
            Route::get('/{gerai}/waiting', [MppQueueOperationController::class, 'geraiWaiting']);
            Route::get('/{gerai}/calling', [MppQueueOperationController::class, 'geraiCalling']);
            Route::post('/call', [MppQueueOperationController::class, 'geraiCall']);
            Route::post('/complete', [MppQueueOperationController::class, 'geraiComplete']);
            Route::post('/recall', [MppQueueOperationController::class, 'geraiRecall']);
        });

        Route::apiResource('citizens', CitizenController::class)->names('api.v1.citizens');

        Route::get('/poverty/status/{nik}', [PovertyController::class, 'status'])->name('api.v1.poverty.status');
        Route::apiResource('poverty', PovertyController::class)->names('api.v1.poverty')->only(['index', 'store']);
    });
});
