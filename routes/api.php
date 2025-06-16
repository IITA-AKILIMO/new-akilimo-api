<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1/currencies')->group(function () {
    Route::get('/', [\App\Http\Controllers\Api\CurrencyController::class, 'index']);
});

Route::prefix('v1/fertilizers')->group(function () {
    Route::get('/', [\App\Http\Controllers\Api\FertilizerController::class, 'index']);
    Route::get('/country/{countryCode}', [\App\Http\Controllers\Api\FertilizerController::class, 'byCountry']);
});

Route::prefix('v1/fertilizer-prices')->group(function () {
    Route::get('/', [\App\Http\Controllers\Api\FertilizerPriceController::class, 'index']);
    Route::get('/{fertilizerKey}', [\App\Http\Controllers\Api\FertilizerPriceController::class, 'byFertilizerKey']);
    Route::get('/country/{countryCode}', [\App\Http\Controllers\Api\FertilizerPriceController::class, 'byCountry']);
});


Route::prefix('v1/investment-amounts')->group(function () {
    Route::get('/country/{countryCode}', [\App\Http\Controllers\Api\InvestmentAmountController::class, 'byCountry']);
});

Route::prefix('v1/operation-costs')->group(function () {
    Route::get('/country/{countryCode}', [\App\Http\Controllers\Api\OperationCostController::class, 'byCountry']);
});

Route::prefix('v1/recommendations')->group(function () {
    Route::post('/compute', [\App\Http\Controllers\Api\RecommendationController::class, 'computeRecommendations']);
});

Route::prefix('v1/starch-factories')->group(function () {
    Route::get('/country/{countryCode}', [\App\Http\Controllers\Api\StarchFactoryController::class, 'byCountry']);
});

Route::prefix('v1/cassava-prices')->group(function () {
    Route::get('/country/{countryCode}', [\App\Http\Controllers\Api\CassavaPricesController::class, 'byCountry']);
});

Route::prefix('v1/potato-prices')->group(function () {
    Route::get('/country/{countryCode}', [\App\Http\Controllers\Api\PotatoPricesController::class, 'byCountry']);
});

Route::prefix('v1/maize-prices')->group(function () {
    Route::get('/country/{countryCode}', [\App\Http\Controllers\Api\MaizePricesController::class, 'byCountry']);
});
