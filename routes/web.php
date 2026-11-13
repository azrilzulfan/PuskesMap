<?php

use App\Http\Controllers\Admin\OperationalMetricsController;
use App\Http\Controllers\Admin\PuskesmasController as AdminPuskesmasController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MapController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/peta', [MapController::class, 'index'])->name('peta');
Route::get('/map', function () {
    return redirect()->route('peta');
});

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:login')
        ->name('login.attempt');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware(['auth', 'role:pemerintah,admin'])->group(function (): void {
    Route::get('/analitik', [AnalyticsController::class, 'index'])->name('analitik.index');
    Route::get('/analitik/ekspor/csv', [AnalyticsController::class, 'exportCsv'])->name('analitik.export.csv');
    Route::get('/analitik/ekspor/pdf', [AnalyticsController::class, 'exportPdf'])->name('analitik.export.pdf');
});

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function (): void {
        Route::resource('puskesmas', AdminPuskesmasController::class)
            ->parameters(['puskesmas' => 'puskesmas'])
            ->except(['show']);

        Route::get('/puskesmas/{puskesmas}/operasional', [OperationalMetricsController::class, 'edit'])
            ->name('operasional.edit');
        Route::put('/puskesmas/{puskesmas}/operasional', [OperationalMetricsController::class, 'updateOperational'])
            ->name('operasional.update');
        Route::put('/puskesmas/{puskesmas}/tenaga-medis', [OperationalMetricsController::class, 'updateTenagaMedis'])
            ->name('tenaga-medis.update');
        Route::get('/puskesmas/{puskesmas}/kunjungan', [OperationalMetricsController::class, 'kunjunganIndex'])
            ->name('kunjungan.index');
        Route::post('/puskesmas/{puskesmas}/kunjungan', [OperationalMetricsController::class, 'storeKunjungan'])
            ->name('kunjungan.store');

        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
        Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
        Route::patch('/users/{user}/deactivate', [AdminUserController::class, 'deactivate'])->name('users.deactivate');

        Route::get('/audit-log', [AdminUserController::class, 'auditLogs'])->name('audit-log.index');
    });
