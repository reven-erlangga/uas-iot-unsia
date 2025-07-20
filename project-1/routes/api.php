<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TelemetryController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Telemetry routes
Route::prefix('telemetry')->name('telemetry.')->group(function () {
    Route::get('/', [TelemetryController::class, 'index'])->name('index');
    Route::post('/', [TelemetryController::class, 'store'])->name('store');
    Route::get('/latest', [TelemetryController::class, 'latest'])->name('latest');
    Route::get('/{id}', [TelemetryController::class, 'show'])->name('show');
    Route::put('/{id}', [TelemetryController::class, 'update'])->name('update');
    Route::delete('/{id}', [TelemetryController::class, 'destroy'])->name('destroy');
});

// Alternative: You can also use resource routes
// Route::apiResource('telemetry', TelemetryController::class);
// Route::get('telemetry/latest', [TelemetryController::class, 'latest']);
