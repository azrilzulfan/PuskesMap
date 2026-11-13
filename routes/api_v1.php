<?php

use App\Http\Controllers\Api\V1\AnalysisController;
use App\Http\Controllers\Api\V1\PuskesmasController;
use App\Http\Controllers\Api\V1\RecommendationController;
use Illuminate\Support\Facades\Route;

Route::get('/puskesmas', [PuskesmasController::class, 'index'])->name('puskesmas.index');
Route::get('/puskesmas/nearest', [PuskesmasController::class, 'nearest'])->name('puskesmas.nearest');
Route::get('/puskesmas/radius', [PuskesmasController::class, 'radius'])->name('puskesmas.radius');
Route::get('/puskesmas/{puskesmas}', [PuskesmasController::class, 'show'])
    ->whereNumber('puskesmas')
    ->name('puskesmas.show');

Route::post('/rekomendasi', [RecommendationController::class, 'store'])->name('rekomendasi');

Route::middleware(['auth', 'role:pemerintah,admin'])
    ->prefix('analisis')
    ->name('analisis.')
    ->group(function (): void {
        Route::get('/coverage', [AnalysisController::class, 'coverage'])->name('coverage');
        Route::get('/equity', [AnalysisController::class, 'equity'])->name('equity');
        Route::get('/workload-spectrum', [AnalysisController::class, 'workloadSpectrum'])->name('workload');
        Route::get('/road-access', [AnalysisController::class, 'roadAccess'])->name('road-access');
    });
