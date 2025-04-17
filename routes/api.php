<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('v1/currencies')->group(function () {
    Route::get('/', [\App\Http\Controllers\Api\CurrencyController::class, 'index'])->name('currency.all');
});

Route::prefix('v1/fertilizers')->group(function () {
    Route::get('/', [\App\Http\Controllers\Api\FertilizerController::class, 'index'])->name('fertilizer.all');
});

Route::prefix('v1/fertilizer-prices')->group(function () {
    Route::get('/', [\App\Http\Controllers\Api\FertilizerPriceController::class, 'index'])->name('fertilizer-price.all');
    Route::get('/{fertilizerKey}', [\App\Http\Controllers\Api\FertilizerPriceController::class, 'priceByKey'])->name('fertilizer-price.key');
});
