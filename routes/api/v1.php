<?php

use Illuminate\Support\Facades\Route;

// API v1
Route::prefix('v1')->as('api.v1.')->group(function () {
    Route::prefix('auth/otp')->as('auth.otp')->group(function () {
        Route::post('/', [App\Http\Controllers\Auth\OTPController::class, 'sendCode'])->name('.send');
        Route::post('/verify', [App\Http\Controllers\Auth\OTPController::class, 'verifyCode'])->name('.verify');
    });

    // Auth required routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::prefix('prices')->as('prices')->group(function () {
            Route::get('/', App\Http\Controllers\CryptoLastUpdatesController::class)->name('.get');
        });
    });
});
