<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use DaaluPay\Http\Controllers\AuthController;
use DaaluPay\Http\Controllers\UserController;
use DaaluPay\Http\Controllers\ModuleController;
use DaaluPay\Http\Controllers\TransactionController;
use DaaluPay\Http\Controllers\AccountController;
use DaaluPay\Http\Controllers\MigrationController;
use DaaluPay\Http\Controllers\MiscController;
use DaaluPay\Http\Controllers\PaymentController;
use DaaluPay\Http\Controllers\Auth\TokenController;
use DaaluPay\Http\Controllers\Auth\AuthenticatedSessionController;
use DaaluPay\Http\Controllers\Auth\EmailVerificationNotificationController;
use DaaluPay\Http\Controllers\Auth\NewPasswordController;
use DaaluPay\Http\Controllers\Auth\PasswordResetLinkController;
use DaaluPay\Http\Controllers\Auth\RegisteredUserController;
use DaaluPay\Http\Controllers\Auth\VerifyEmailController;

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
Route::get('/docs', [MiscController::class, 'getApiDocs']);

// Database Routes
Route::prefix('/db')->group(function () {
    Route::get('/', [MigrationController::class, 'index']);

    Route::get('/status', [MigrationController::class, 'getMigrations']);
    Route::post('/migrate', [MigrationController::class, 'runMigrations']);
    Route::post('/rollback', [MigrationController::class, 'rollbackMigrations']);
    Route::post('/seed', [MigrationController::class, 'runSeeds']);
});

// Token Routes
Route::post('/token', [TokenController::class, 'getMobileToken']);

// User Auth Routes
Route::post('/register', [RegisteredUserController::class, 'store'])
    ->middleware('guest')
    ->name('register');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest')
    ->name('login');

Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->middleware('guest')
    ->name('password.email');

Route::post('/reset-password', [NewPasswordController::class, 'store'])
    ->middleware('guest')
    ->name('password.store');

Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
    ->middleware(['auth', 'signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.send');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');


Route::group(['middleware' => 'auth:sanctum'], function () {

    Route::prefix('/user')->group(function () {
        Route::get(
            '/',
            function (Request $request) {
                return $request->user();
            }
        );
        Route::get('/{id}', [TransactionController::class, 'show']);
        Route::post('/', [TransactionController::class, 'store']);
        Route::delete('/{id}', [TransactionController::class, 'destroy']);
    });

    Route::prefix('/transactions')->group(function () {
        Route::get('/{id}', [TransactionController::class, 'show']);
        Route::post('/', [TransactionController::class, 'store']);
        Route::delete('/{id}', [TransactionController::class, 'destroy']);
    });
});

// SuperAdmin routes
Route::group(['middleware' => 'auth:sanctum,super_admin'], function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);

    // Disable currency from exchange
    // Route::post('/disable-currency', [CurrencyController::class, 'disableCurrency']);

    // Enable currency from exchange
    // Route::post('/enable-currency', [CurrencyController::class, 'enableCurrency']);
});

// Admin routes
Route::group(['middleware' => 'auth:sanctum,admin'], function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);

    // Suspend user
    // Route::post('/suspend-user', [UserController::class, 'suspendUser']);

    // Unsuspend user
    // Route::post('/unsuspend-user', [UserController::class, 'unsuspendUser']);
});
