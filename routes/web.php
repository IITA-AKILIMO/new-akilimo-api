<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\CassavaPriceController;
use App\Http\Controllers\Admin\CassavaUnitController;
use App\Http\Controllers\Admin\CurrencyController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DefaultPriceController;
use App\Http\Controllers\Admin\FertilizerController;
use App\Http\Controllers\Admin\FertilizerPriceController;
use App\Http\Controllers\Admin\InvestmentAmountController;
use App\Http\Controllers\Admin\MaizePriceController;
use App\Http\Controllers\Admin\OperationCostController;
use App\Http\Controllers\Admin\PotatoPriceController;
use App\Http\Controllers\Admin\StarchFactoryController;
use App\Http\Controllers\Admin\StarchPriceController;
use App\Http\Controllers\Admin\TranslationController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Web\HealthCheckController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('health')->group(function () {
    Route::get('/', [HealthCheckController::class, 'check']);
});

// ── Admin — public ─────────────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
});

// ── Admin — authenticated ──────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth:web'])->group(function () {
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Users
    Route::resource('users', UserController::class)
        ->except(['show'])
        ->parameters(['users' => 'user']);

    // Management
    Route::resource('fertilizers', FertilizerController::class)
        ->except(['show'])
        ->parameters(['fertilizers' => 'id']);

    Route::resource('fertilizer-prices', FertilizerPriceController::class)
        ->except(['show'])
        ->parameters(['fertilizer-prices' => 'id']);

    // Commodity prices
    Route::resource('maize-prices', MaizePriceController::class)
        ->except(['show'])
        ->parameters(['maize-prices' => 'id']);

    Route::resource('cassava-prices', CassavaPriceController::class)
        ->except(['show'])
        ->parameters(['cassava-prices' => 'id']);

    Route::resource('potato-prices', PotatoPriceController::class)
        ->except(['show'])
        ->parameters(['potato-prices' => 'id']);

    Route::resource('starch-prices', StarchPriceController::class)
        ->except(['show'])
        ->parameters(['starch-prices' => 'id']);

    Route::resource('default-prices', DefaultPriceController::class)
        ->except(['show'])
        ->parameters(['default-prices' => 'id']);

    // Supporting data
    Route::resource('starch-factories', StarchFactoryController::class)
        ->except(['show'])
        ->parameters(['starch-factories' => 'id']);

    Route::resource('investment-amounts', InvestmentAmountController::class)
        ->except(['show'])
        ->parameters(['investment-amounts' => 'id']);

    Route::resource('operation-costs', OperationCostController::class)
        ->except(['show'])
        ->parameters(['operation-costs' => 'id']);

    Route::resource('currencies', CurrencyController::class)
        ->except(['show'])
        ->parameters(['currencies' => 'id']);

    Route::resource('cassava-units', CassavaUnitController::class)
        ->except(['show'])
        ->parameters(['cassava-units' => 'id']);

    Route::resource('translations', TranslationController::class)
        ->except(['show'])
        ->parameters(['translations' => 'id']);
});
