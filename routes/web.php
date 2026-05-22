<?php

use App\Http\Controllers\Web\CertificateController;
use App\Http\Controllers\Web\CitizenController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\DistrictController;
use App\Http\Controllers\Web\HistoryController;
use App\Http\Controllers\Web\LoginController;
use App\Http\Controllers\Web\ReportController;
use App\Http\Controllers\Web\RoleController;
use App\Http\Controllers\Web\ServiceController;
use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\Web\VerificationController;
use App\Http\Controllers\Web\VillageController;
use Illuminate\Support\Facades\Route;

// Auth Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected Dashboard Routes
Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/desa', [DashboardController::class, 'desa'])->name('dashboard.desa')->middleware('role:operatordesa');

    // Web UI: Citizen & Poverty Management for Operator Desa & Super Admin
    Route::resource('citizens', CitizenController::class)
        ->middleware('permission:citizens.manage');

    Route::resource('users', UserController::class)
        ->middleware('permission:users.manage');

    Route::resource('roles', RoleController::class)
        ->middleware('permission:roles.manage');

    Route::resource('villages', VillageController::class)
        ->middleware('permission:villages.manage');

    Route::resource('districts', DistrictController::class)
        ->middleware('permission:districts.manage');

    // Super Admin - Certificate Management
    Route::get('/admin/certificates', [CertificateController::class, 'index'])->name('admin.certificates.index');
    Route::post('/admin/certificates/{user_id}/generate', [CertificateController::class, 'generate'])->name('admin.certificates.generate');

    // Services & Verification (Layanan & Verifikasi)
    Route::prefix('layanan')->name('services.')->group(function () {
        Route::get('/verifikasi', [VerificationController::class, 'index'])->name('verification');
        Route::post('/verifikasi/check', [VerificationController::class, 'check'])->name('verification.check');
        Route::get('/permohonan', [ServiceController::class, 'index'])->name('requests.index');
        Route::post('/permohonan/store', [ServiceController::class, 'store'])->name('requests.store');
        Route::post('/permohonan/{serviceRequest}/approve', [ServiceController::class, 'approve'])->name('requests.approve');
        Route::post('/permohonan/{serviceRequest}/reject', [ServiceController::class, 'reject'])->name('requests.reject');
        Route::get('/riwayat', [HistoryController::class, 'index'])->name('history');
        Route::get('/lapor-datang/buat', [ServiceController::class, 'arrivalCreate'])->name('arrival.create');
        Route::post('/lapor-datang', [ServiceController::class, 'arrivalStore'])->name('arrival.store');
    });

    Route::get('/proof/{nik}/{type}', [VerificationController::class, 'proof'])->where('nik', '[0-9]{16}')->name('verification.proof');

    Route::get('/reports/citizens', [ReportController::class, 'exportCitizens'])->name('reports.citizens');
    Route::get('/reports/audit', [ReportController::class, 'exportAuditLogs'])->name('reports.audit');
});
