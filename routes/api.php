<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use DaluPay\Http\Controllers\AuthController;
use DaluPay\Http\Controllers\UserController;
use DaluPay\Http\Controllers\ModuleController;
use DaluPay\Http\Controllers\TransactionController;
use DaluPay\Http\Controllers\AccountController;
use DaluPay\Http\Controllers\MigrationController;
use DaluPay\Http\Controllers\MiscController;
use DaluPay\Http\Controllers\PaymentController;
use DaluPay\Http\Controllers\Auth\TokenController;


Route::get('/', [MiscController::class, 'getAppInfo']);

Route::get('/db', [MigrationController::class, 'index']);

    Route::get('/db/status', [MigrationController::class, 'getMigrations']);
    Route::post('/db/migrate', [MigrationController::class, 'runMigrations']);
    Route::post('/db/rollback', [MigrationController::class, 'rollbackMigrations']);
    Route::post('/db/seed', [MigrationController::class, 'runSeeds']);

Route::post('/token', [TokenController::class, 'getMobileToken']);


Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

