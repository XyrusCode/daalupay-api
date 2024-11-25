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


Route::get('/', [MiscController::class, 'getAppInfo']);

Route::get('/db', [MigrationController::class, 'index']);

    Route::get('/db/status', [MigrationController::class, 'getMigrations']);
    Route::post('/db/migrate', [MigrationController::class, 'runMigrations']);
    Route::post('/db/rollback', [MigrationController::class, 'rollbackMigrations']);
    Route::post('/db/seed', [MigrationController::class, 'runSeeds']);

Route::post('/token', [TokenController::class, 'getMobileToken']);

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


Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();

});

