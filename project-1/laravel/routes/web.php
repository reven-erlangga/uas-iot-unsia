<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SensorMonitoringController;
use App\Http\Controllers\EspSetupController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', function () {
    return Inertia::render('About', [
        'appName' => config('app.name'),
    ]);
})->name('about');

Route::get('/sensor-monitoring', [SensorMonitoringController::class, 'index'])->name('sensor-monitoring');
Route::get('/esp-setup', [EspSetupController::class, 'index'])->name('esp-setup');
