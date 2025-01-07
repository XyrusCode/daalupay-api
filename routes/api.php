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


        Route::get('/request-otp', [AuthController::class, 'requestOtp']);
        Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);


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
Route::post('/admin/login', [AuthController::class, 'adminLogin'])
    ->name('admin.login');

Route::group(['middleware' => 'auth:sanctum,admin'], function () {
    Route::get('/stats', [AdminController::class, 'stats']);

    Route::prefix('/users')->group(function () {
        Route::get('/', [AdminController::class, 'getAllUsers']);
        Route::get('/{id}', [AdminController::class, 'getUser']);
        Route::post('/{id}', [AdminController::class, 'updateUser']);
        Route::post('/{id}/approve', [AdminController::class, 'approveUserVerification']);
        Route::post('/{id}/deny', [AdminController::class, 'denyUserVerification']);
        Route::post('/{id}/suspend', [AdminController::class, 'suspendUser']);
        Route::post('/{id}/unsuspend', [AdminController::class, 'unsuspendUser']);
        Route::post('/{id}/delete', [AdminController::class, 'deleteUser']);
    });

    Route::prefix('/transactions')->group(function () {
        Route::get('/', [AdminController::class, 'index']);
        Route::get('/{id}', [AdminController::class, 'show']);
        Route::post('/{id}/approve', [AdminController::class, 'approveTransaction']);
        Route::post('/{id}/deny', [AdminController::class, 'denyTransaction']);
    });

});

// Auth
Route::post('super-admin/login', [AuthController::class, 'superAdminLogin'])
    ->name('super-admin.login');

Route::middleware(['auth:sanctum,super_admin, verify.browser'])->group(function () {

    Route::get('/stats', [SuperAdminController::class, 'stats']);

    Route::prefix('/admins')->group(function () {
        Route::get('/', [SuperAdminController::class, 'getAllAdmins']);
        Route::post('/', [SuperAdminController::class, 'addAdmin']);
        Route::get('/{id}', [SuperAdminController::class, 'getAdmin']);
        Route::post('/{id}/suspend', [SuperAdminController::class, 'suspendAdmin']);
        Route::post('/{id}/unsuspend', [SuperAdminController::class, 'reactivateAdmin']);

        Route::post('/{id}/delete', [SuperAdminController::class, 'deleteAdmin']);
    });

    Route::prefix('/currencies')->group(function () {
        Route::get('/', [SuperAdminController::class, 'getAllCurrencies']);
        Route::post('/disable', [SuperAdminController::class, 'disableCurrency']);
        Route::post('/enable', [SuperAdminController::class, 'enableCurrency']);
    });

    Route::prefix('/payment-methods')->group(function () {
        Route::get('/', [SuperAdminController::class, 'getAllPaymentMethods']);
        Route::post('{id}/disable', [SuperAdminController::class, 'disablePaymentMethod']);
        Route::post('{id}/enable', [SuperAdminController::class, 'enablePaymentMethod']);
    });

    Route::prefix('/exchange-rates')->group(function () {
        Route::get('/', [SuperAdminController::class, 'getAllExchangeRates']);
        Route::post('/', [SuperAdminController::class, 'setExchangeRate']);
    });

});
