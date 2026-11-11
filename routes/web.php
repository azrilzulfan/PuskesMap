<?php

use App\Http\Controllers\MapController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/peta', [MapController::class, 'index'])->name('peta');
Route::get('/map', function () {
    return redirect()->route('peta');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');
