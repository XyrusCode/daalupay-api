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

