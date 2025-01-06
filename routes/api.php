<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use DaaluPay\Http\Controllers\Payment\TransactionController;
use DaaluPay\Http\Controllers\MigrationController;
use DaaluPay\Http\Controllers\MiscController;
use DaaluPay\Http\Controllers\AuthController;
use DaaluPay\Http\Controllers\Payment\SwapController;
use DaaluPay\Http\Controllers\Admin\SuperAdminController;
use DaaluPay\Http\Controllers\Admin\AdminController;
use DaaluPay\Http\Controllers\ExchangeRateController;
use DaaluPay\Http\Controllers\User\AuthenticatedUserController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// App Info
Route::get('/', [MiscController::class, 'getAppInfo']);
Route::get('/docs', [MiscController::class, 'getAppDocs']);


// Database Routes
Route::prefix('/db')->group(function () {
    Route::get('/', [MigrationController::class, 'index']);

    Route::get('/status', [MigrationController::class, 'getMigrations']);
    Route::post('/migrate', [MigrationController::class, 'runMigrations']);
    Route::post('/rollback', [MigrationController::class, 'rollbackMigrations']);
    Route::post('/seed', [MigrationController::class, 'runSeeds']);
});

// Token Routes
Route::post('/sanctum/token', [AuthController::class, 'iosToken']);

// User Auth Routes
Route::post('/register', [AuthController::class, 'register'])
    ->middleware('guest')
    ->name('register');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login');

Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])
    ->middleware('guest')
    ->name('password.email');

Route::post('/reset-password', [AuthController::class, 'resetPassword'])
    ->middleware('guest')
    ->name('password.store');

Route::get('/verify-email/{id}/{hash}', [AuthController::class, 'verifyEmail'])
    ->middleware(['auth', 'signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::post('/email/verification-notification', [AuthController::class, 'sendVerificationEmail'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.send');

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');


Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::prefix('/user')->group(function () {
        Route::get('/', [AuthenticatedUserController::class, 'show']);
        Route::post('/', [AuthenticatedUserController::class, 'update']);
        Route::get('/stats', [AuthenticatedUserController::class, 'stats']);
        Route::post('/password', [AuthenticatedUserController::class, 'updatePassword']);
        Route::post('/wallets', [AuthenticatedUserController::class, 'createWallet']);
        Route::get('/wallets', [AuthenticatedUserController::class, 'getWallets']);

        Route::prefix('/transactions')->group(function () {
            Route::get('/', [TransactionController::class, 'index']);
            Route::get('/{id}', [TransactionController::class, 'show']);
            Route::post('/', [TransactionController::class, 'store']);
        });

        Route::prefix('/swaps')->group(function () {
            Route::get('/', [SwapController::class, 'index']);
            Route::get('/{id}', [SwapController::class, 'show']);
            Route::post('/', [SwapController::class, 'store']);
        });
    });
});

Route::prefix('/exchange-rate')->group(function () {
    Route::get('/', [ExchangeRateController::class, 'index']);
    Route::get('/{uuid}', [ExchangeRateController::class, 'show']);
    Route::post('/', [ExchangeRateController::class, 'store']);
    Route::put('/{uuid}', [ExchangeRateController::class, 'update']);
    Route::delete('/{uuid}', [ExchangeRateController::class, 'destroy']);
});


// Admin routes
Route::group(['middleware' => 'auth:sanctum,admin'], function () {
    Route::get('/users', [AdminController::class, 'index']);
    Route::post('/users', [AdminController::class, 'store']);

    // Suspend user
    Route::post('/suspend-user', [AdminController::class, 'suspendUser']);

    // Unsuspend user
    Route::post('/unsuspend-user', [AdminController::class, 'unsuspendUser']);
});

Route::middleware(['auth:sanctum,super_admin, verify.browser'])->group(function () {
    Route::get('/admins', [SuperAdminController::class, 'getAllAdmins']);
    Route::get('/admins/{id}', [SuperAdminController::class, 'getAdmin']);

    // Disable currency from exchange
    Route::post('/disable-currency', [SuperAdminController::class, 'disableCurrency']);

    // Enable currency from exchange
    Route::post('/enable-currency', [SuperAdminController::class, 'enableCurrency']);
});
