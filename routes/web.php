<?php

use App\Http\Controllers\Api\MapApiController;
use App\Http\Controllers\Web\CertificateController;
use App\Http\Controllers\Web\CitizenController;
use App\Http\Controllers\Web\CmsArticleController;
use App\Http\Controllers\Web\CmsBannerController;
use App\Http\Controllers\Web\CmsCategoryController;
use App\Http\Controllers\Web\CmsFaqController;
use App\Http\Controllers\Web\CmsMediaController;
use App\Http\Controllers\Web\CmsPageController;
use App\Http\Controllers\Web\CmsSettingController;
use App\Http\Controllers\Web\CmsTeamController;
use App\Http\Controllers\Web\CmsTestimonialController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\DistrictController;
use App\Http\Controllers\Web\HistoryController;
use App\Http\Controllers\Web\LandingPageController;
use App\Http\Controllers\Web\LoginController;
use App\Http\Controllers\Web\MapLocationCategoryController;
use App\Http\Controllers\Web\MapLocationController;
use App\Http\Controllers\Web\MapRegionController;
use App\Http\Controllers\Web\MapZoneController;
use App\Http\Controllers\Web\MapZoneTypeController;
use App\Http\Controllers\Web\OpdController;
use App\Http\Controllers\Web\PublicVerificationController;
use App\Http\Controllers\Web\ReportController;
use App\Http\Controllers\Web\RoleController;
use App\Http\Controllers\Web\ServiceController;
use App\Http\Controllers\Web\SiberugoController;
use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\Web\VerificationController;
use App\Http\Controllers\Web\VillageController;
use Illuminate\Support\Facades\Route;

// Public Routes (tanpa autentikasi)
Route::get('/cek-surat', [PublicVerificationController::class, 'show'])->name('public.verify');

// SIBERUGO Public Map Routes
Route::get('/', [LandingPageController::class, 'index'])->name('landing.index');
Route::get('/siberugo', [SiberugoController::class, 'index'])->name('siberugo.index');
Route::get('/siberugo/peta', [SiberugoController::class, 'map'])->name('siberugo.map');

// Dynamic Page Routes
Route::get('/{section}/{slug}', [LandingPageController::class, 'showPage'])
    ->whereIn('section', ['profil', 'pelayanan', 'informasi', 'investasi', 'ppid', 'kontak'])
    ->name('landing.page');

// Public Map API Endpoints
Route::prefix('api/map')->name('api.map.')->group(function () {
    Route::get('/regions', [MapApiController::class, 'regions'])->name('regions');
    Route::get('/zones', [MapApiController::class, 'zones'])->name('zones');
    Route::get('/locations', [MapApiController::class, 'locations'])->name('locations');
});

// Auth Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected Dashboard Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard/admin', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/dashboard/desa', [DashboardController::class, 'desa'])->name('dashboard.desa')->middleware('role:operatordesa');

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

    Route::resource('opds', OpdController::class)
        ->middleware('permission:opds.manage');

    // Admin Maps CRUD
    Route::middleware('permission:maps.manage')->group(function () {
        Route::resource('map-regions', MapRegionController::class);
        Route::resource('map-zones', MapZoneController::class);
        Route::resource('map-locations', MapLocationController::class);
        Route::resource('map-zone-types', MapZoneTypeController::class);
        Route::resource('map-location-categories', MapLocationCategoryController::class);
    });

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

    // CMS Routes
    Route::resource('cms-categories', CmsCategoryController::class);
    Route::resource('cms-articles', CmsArticleController::class);
    Route::resource('cms-pages', CmsPageController::class);
    Route::resource('cms-banners', CmsBannerController::class);
    Route::resource('cms-faqs', CmsFaqController::class);
    Route::resource('cms-testimonials', CmsTestimonialController::class);
    Route::resource('cms-teams', CmsTeamController::class);
    Route::get('cms-settings', [CmsSettingController::class, 'index'])->name('cms-settings.index');
    Route::put('cms-settings', [CmsSettingController::class, 'update'])->name('cms-settings.update');
    Route::post('cms-media/upload', [CmsMediaController::class, 'upload'])->name('cms-media.upload');
});
