<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('health')->group(function () {
    Route::get('/', [\App\Http\Controllers\Web\HealthCheckController::class, 'check']);
});
