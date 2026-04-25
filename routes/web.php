<?php

use App\Http\Controllers\Web\CitizenController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\VerificationController;
use App\Http\Controllers\Web\ServiceController;
use App\Http\Controllers\Web\LoginController;
use App\Http\Controllers\Web\ReportController;
use Illuminate\Support\Facades\Route;

// Auth Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected Dashboard Routes
Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/desa', [DashboardController::class, 'desa'])->name('dashboard.desa');

    // Web UI: Citizen & Poverty Management for Operator Desa & Super Admin
    Route::resource('citizens', CitizenController::class)
        ->middleware('permission:citizens.manage');

    Route::get('/verify', [DashboardController::class, 'verify'])->name('dashboard.verify');
    Route::get('/history', [DashboardController::class, 'history'])->name('dashboard.history');
    Route::get('/proof/{nik}', [DashboardController::class, 'proof'])->name('dashboard.proof');

    Route::post('/api/verify-check', [VerificationController::class, 'check'])->name('api.verification.check');
    Route::post('/api/service-report', [ServiceController::class, 'store'])->name('service.store');

    Route::get('/reports/citizens', [ReportController::class, 'exportCitizens'])->name('reports.citizens');
    Route::get('/reports/audit', [ReportController::class, 'exportAuditLogs'])->name('reports.audit');
});
