<?php

use App\Http\Controllers\Web\HealthCheckController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('health')->group(function () {
    Route::get('/', [HealthCheckController::class, 'check']);
});
