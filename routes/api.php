<?php

use App\Http\Controllers\Api\ApiKeyController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CassavaPricesController;
use App\Http\Controllers\Api\CassavaUnitsController;
use App\Http\Controllers\Api\CurrencyController;
use App\Http\Controllers\Api\DefaultPriceController;
use App\Http\Controllers\Api\FertilizerController;
use App\Http\Controllers\Api\FertilizerPriceController;
use App\Http\Controllers\Api\InvestmentAmountController;
use App\Http\Controllers\Api\MaizePricesController;
use App\Http\Controllers\Api\OperationCostController;
use App\Http\Controllers\Api\PotatoPricesController;
use App\Http\Controllers\Api\RecommendationController;
use App\Http\Controllers\Api\StarchFactoryController;
use App\Http\Controllers\Api\StarchPricesController;
use App\Http\Controllers\Api\TranslationController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\UserFeedbackController;
use App\Http\Controllers\Web\HealthCheckController;
use Illuminate\Support\Facades\Route;

// ── Health ────────────────────────────────────────────────────────────────────
Route::prefix('health')->group(function () {
    Route::get('/', [HealthCheckController::class, 'check']);
});

// ── Authentication ────────────────────────────────────────────────────────────
Route::middleware('throttle:10,1')->prefix('v1/auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth.token');
});

// ── Public reference data — cheap reads used by mobile clients on startup ─────
Route::middleware('throttle:120,1')->group(function () {
    Route::prefix('v1/currencies')->group(function () {
        Route::get('/', [CurrencyController::class, 'index']);
    });

    Route::prefix('v1/fertilizers')->group(function () {
        Route::get('/', [FertilizerController::class, 'index']);
        Route::get('/country/{countryCode}', [FertilizerController::class, 'byCountry']);
    });

    Route::prefix('v1/fertilizer-prices')->group(function () {
        Route::get('/', [FertilizerPriceController::class, 'index']);
        Route::get('/{fertilizerKey}', [FertilizerPriceController::class, 'byFertilizerKey']);
        Route::get('/country/{countryCode}', [FertilizerPriceController::class, 'byCountry']);
    });

    Route::prefix('v1/investment-amounts')->group(function () {
        Route::get('/', [InvestmentAmountController::class, 'index']);
        Route::get('/country/{countryCode}', [InvestmentAmountController::class, 'byCountry']);
    });

    Route::prefix('v1/operation-costs')->group(function () {
        Route::get('/', [OperationCostController::class, 'index']);
        Route::get('/country/{countryCode}', [OperationCostController::class, 'byCountry']);
    });

    Route::prefix('v1/starch-factories')->group(function () {
        Route::get('/', [StarchFactoryController::class, 'index']);
        Route::get('/country/{countryCode}', [StarchFactoryController::class, 'byCountry']);
    });

    Route::prefix('v1/starch-prices')->group(function () {
        Route::get('/', [StarchPricesController::class, 'index']);
    });

    Route::prefix('v1/default-prices')->group(function () {
        Route::get('/', [DefaultPriceController::class, 'index']);
    });

    Route::prefix('v1/cassava-units')->group(function () {
        Route::get('/', [CassavaUnitsController::class, 'index']);
    });

    Route::prefix('v1/cassava-prices')->group(function () {
        Route::get('/', [CassavaPricesController::class, 'index']);
        Route::get('/country/{countryCode}', [CassavaPricesController::class, 'byCountry']);
    });

    Route::prefix('v1/potato-prices')->group(function () {
        Route::get('/', [PotatoPricesController::class, 'index']);
        Route::get('/country/{countryCode}', [PotatoPricesController::class, 'byCountry']);
    });

    Route::prefix('v1/maize-prices')->group(function () {
        Route::get('/', [MaizePricesController::class, 'index']);
        Route::get('/country/{countryCode}', [MaizePricesController::class, 'byCountry']);
    });
});

// ── Protected read endpoints ──────────────────────────────────────────────────
Route::middleware(['throttle:120,1', 'auth.token'])->group(function () {
    Route::prefix('v1/recommendations')->group(function () {
        Route::get('/', [RecommendationController::class, 'index']);
    });

    Route::prefix('v1/user-feedback')->group(function () {
        Route::get('/', [UserFeedbackController::class, 'index']);
    });

    Route::prefix('v1/translations')->group(function () {
        Route::get('/', [TranslationController::class, 'index']);
    });
});

// ── Protected API key management ──────────────────────────────────────────────
Route::middleware(['throttle:30,1', 'auth.token'])->prefix('v1/auth')->group(function () {
    Route::get('/api-keys', [ApiKeyController::class, 'index']);
    Route::post('/api-keys', [ApiKeyController::class, 'store']);
    Route::patch('/api-keys/{id}/revoke', [ApiKeyController::class, 'revoke']);
    Route::delete('/api-keys/{id}', [ApiKeyController::class, 'destroy']);
});

// ── Protected mutating endpoints ──────────────────────────────────────────────
Route::middleware(['throttle:30,1', 'auth.token'])->group(function () {
    Route::post('v1/recommendations/compute', [RecommendationController::class, 'computeRecommendations']);
    Route::post('v1/user-feedback', [UserFeedbackController::class, 'store']);
});

// ── Admin — lookup data CRUD (requires write ability) ─────────────────────────
Route::middleware(['throttle:60,1', 'auth.token:write'])->prefix('v1/admin')->group(function () {
    Route::apiResource('fertilizers', FertilizerController::class)->only(['store', 'update', 'destroy']);
    Route::apiResource('fertilizer-prices', FertilizerPriceController::class)->only(['store', 'update', 'destroy']);
    Route::apiResource('maize-prices', MaizePricesController::class)->only(['store', 'update', 'destroy']);
    Route::apiResource('cassava-prices', CassavaPricesController::class)->only(['store', 'update', 'destroy']);
    Route::apiResource('potato-prices', PotatoPricesController::class)->only(['store', 'update', 'destroy']);
    Route::apiResource('starch-prices', StarchPricesController::class)->only(['store', 'update', 'destroy']);
    Route::apiResource('starch-factories', StarchFactoryController::class)->only(['store', 'update', 'destroy']);
    Route::apiResource('investment-amounts', InvestmentAmountController::class)->only(['store', 'update', 'destroy']);
    Route::apiResource('operation-costs', OperationCostController::class)->only(['store', 'update', 'destroy']);
    Route::apiResource('currencies', CurrencyController::class)->only(['store', 'update', 'destroy']);
    Route::apiResource('cassava-units', CassavaUnitsController::class)->only(['store', 'update', 'destroy']);
    Route::apiResource('translations', TranslationController::class)->only(['store', 'update', 'destroy']);

    Route::apiResource('default-prices', DefaultPriceController::class)->only(['store', 'update', 'destroy']);
});

// ── Admin — user management (requires admin ability) ──────────────────────────
Route::middleware(['throttle:30,1', 'auth.token:admin'])->prefix('v1/admin')->group(function () {
    Route::get('users', [UserController::class, 'index']);
    Route::post('users', [UserController::class, 'store']);
    Route::get('users/{id}', [UserController::class, 'show']);
    Route::put('users/{id}', [UserController::class, 'update']);
    Route::delete('users/{id}', [UserController::class, 'destroy']);
});
