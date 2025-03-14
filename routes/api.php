<?php

use DaaluPay\Http\Controllers\Admin\AdminController;
use DaaluPay\Http\Controllers\Admin\SuperAdminController;
use DaaluPay\Http\Controllers\AdminChatController;
use DaaluPay\Http\Controllers\AuthController;
use DaaluPay\Http\Controllers\BaseController;
use DaaluPay\Http\Controllers\BlogPostController;
use DaaluPay\Http\Controllers\ChatController;
use DaaluPay\Http\Controllers\MigrationController;
use DaaluPay\Http\Controllers\MiscController;
use DaaluPay\Http\Controllers\Payment\DepositController;
use DaaluPay\Http\Controllers\Payment\ExchangeController;
use DaaluPay\Http\Controllers\Payment\SwapController;
use DaaluPay\Http\Controllers\Payment\TransactionController;
use DaaluPay\Http\Controllers\Payment\WalletController;
use DaaluPay\Http\Controllers\Payment\TransferController;
use DaaluPay\Http\Controllers\TestController;
use DaaluPay\Http\Controllers\User\AuthenticatedUserController;
use DaaluPay\Http\Controllers\User\UserPreferenceController;
use Illuminate\Support\Facades\Route;

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

// Tests
Route::prefix('/test')->group(function () {
    Route::prefix('/transfers')->group(function () {
        Route::get('/', [AdminController::class, 'getTransfers']);
        Route::get('/{id}', [AdminController::class, 'getTransfer']);
        Route::post('/{id}/approve', [AdminController::class, 'approveTransfer']);
        Route::post('/{id}/deny', [AdminController::class, 'denyTransfer']);
    });
});
Route::get('/test-email', [TestController::class, 'sendEmail']);

Route::get('/test-fcm', [TestController::class, 'testFcm']);

// App Info
Route::get('/', [MiscController::class, 'getAppInfo']);
Route::get('/docs', [MiscController::class, 'getAppDocs']);

Route::get('/artisan/{command}', [MiscController::class, 'runArtisanCommand']);


// Blog Routes
Route::prefix('/blog')->group(function () {
    Route::get('/', [BlogPostController::class, 'getPublicBlogPosts']);
    Route::get('/{id}', [BlogPostController::class, 'getBlogPost']);
});

// Database Routes
Route::prefix('/db')->group(function () {
    Route::get('/', [MigrationController::class, 'index']);

    Route::get('/status', [MigrationController::class, 'getMigrations']);
    Route::post('/migrate', [MigrationController::class, 'runMigrations']);
    Route::post('/rollback', [MigrationController::class, 'rollbackMigrations']);
    Route::post('/seed', [MigrationController::class, 'runSeeds']);
    Route::post('/reset', [MigrationController::class, 'resetDB']);
});

// Token Routes
Route::post('/sanctum/token', [AuthController::class, 'iosToken']);

Route::get('/token', [AuthenticatedUserController::class, 'show']);
Route::get('/token/admin', [AuthenticatedUserController::class, 'showAdmin']);

Route::get('/exchange-rates', [SuperAdminController::class, 'getAllExchangeRates']);

// User Auth Routes
Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login'])
    ->name('login');

Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])
    ->name('forgot-password');

Route::post('/reset-password', [AuthController::class, 'resetPassword']);

Route::get('/verify-email/{id}/{hash}', [AuthController::class, 'verifyEmail'])->middleware(['auth', 'signed', 'throttle:6,1']);

Route::post('/email/verification-notification', [AuthController::class, 'sendVerificationEmail'])
    ->middleware(['auth', 'throttle:6,1']);

Route::post('/logout', [AuthController::class, 'logout']);

Route::post('/user/request-otp', [AuthController::class, 'requestOtp']);
Route::post('/user/verify-otp', [AuthController::class, 'verifyOtp']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::prefix('/user')->group(function () {
        Route::get('/', [AuthenticatedUserController::class, 'show']);
        Route::post('/', [AuthenticatedUserController::class, 'update']);
        Route::get('/stats', [AuthenticatedUserController::class, 'stats']);
        Route::post('/password', [AuthenticatedUserController::class, 'updatePassword']);
        Route::post('/pin', [AuthenticatedUserController::class, 'updatePin']);
        Route::post('/pin/verify', [AuthenticatedUserController::class, 'verifyPin']);

        Route::prefix('/notification-tokens')->group(function () {
            Route::post('/', [AuthenticatedUserController::class, 'storeNotificationToken']);
            Route::delete('/{id}', [AuthenticatedUserController::class, 'deleteNotificationToken']);
        });

        Route::get('/payment-methods', [WalletController::class, 'getPaymentMethods']);

        Route::prefix('/wallets')->group(function () {
            Route::post('/', [WalletController::class, 'store']);
            Route::get('/', [WalletController::class, 'index']);
            Route::delete('/{id}', [WalletController::class, 'delete']);
            Route::get('/{uuid}', [TransferController::class, 'show']);
        });

        Route::prefix('/transfers')->group(function () {
            Route::get('/', [TransferController::class, 'index']);
            Route::get('/{id}', [TransferController::class, 'show']);
            Route::post('/send', [TransferController::class, 'store']);
            Route::post('/verify', [TransferController::class, 'verify']);
        });

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

        Route::prefix('/deposits')->group(function () {
            Route::post('/', [DepositController::class, 'store']);
        });

        Route::prefix('/kyc')->group(function () {
            Route::post('/', [AuthenticatedUserController::class, 'createKyc']);
        });

        // Set bank account
        Route::prefix('/bank-account')->group(function () {
            Route::post('/', [AuthenticatedUserController::class, 'setBankAccount']);
            Route::get('/', [AuthenticatedUserController::class, 'getBankAccount']);
        });

        // Withdraw
        Route::prefix('/withdraw')->group(function () {
            Route::post('/', [AuthenticatedUserController::class, 'withdraw']);
            Route::get('/', [AuthenticatedUserController::class, 'getWithdrawals']);
        });

        // Routes for user preferences
        Route::get('/preferences', [UserPreferenceController::class, 'show']);
        Route::post('/preferences', [UserPreferenceController::class, 'update']);

        // transfer fee
        Route::get('/transfer-fee', [ExchangeController::class, 'transferFee']);

        Route::post('/chats', [ChatController::class, 'createChat']);
        Route::get('/chats/{chatId}/messages', [ChatController::class, 'getMessages']);
        Route::post('/chats/{chatId}/messages', [ChatController::class, 'sendMessage']);
    });
});

// Admin routes
Route::post('/admin/login', [AuthController::class, 'adminLogin']);

Route::group(['middleware' => 'auth:sanctum,admin'], function () {

    Route::prefix('/admin')->group(function () {
        Route::get('/stats', [AdminController::class, 'stats']);

        Route::prefix('/users')->group(function () {
            Route::get('/', [AdminController::class, 'getAllUsers']);
            Route::get('/{id}', [AdminController::class, 'getUser']);
            Route::post('/{id}', [AdminController::class, 'updateUser']);
            Route::post('/{id}/approve', [AdminController::class, 'approveUserVerification']);
            Route::post('/{id}/deny', [AdminController::class, 'denyUserVerification']);
            Route::post('/{id}/suspend', [AdminController::class, 'suspendUser']);
            Route::post('/{id}/unsuspend', [AdminController::class, 'reactivateUser']);
            Route::post('/{id}/delete', [AdminController::class, 'deleteUser']);
        });

        Route::prefix('/transactions')->group(function () {
            Route::get('/', [AdminController::class, 'getTransactions']);
            Route::get('/{id}', [AdminController::class, 'getTransaction']);
        });

        Route::prefix('/withdrawals')->group(function () {
            Route::get('/', [AdminController::class, 'getWithdrawals']);
            Route::get('/{id}', [AdminController::class, 'getWithdrawal']);
            Route::post('/{id}/approve', [AdminController::class, 'approveWithdrawal']);
            Route::post('/{id}/deny', [AdminController::class, 'denyWithdrawal']);
        });

        Route::prefix('/transfers')->group(function () {
            Route::get('/', [AdminController::class, 'getTransfers']);
            Route::get('/{id}', [AdminController::class, 'getTransfer']);
            Route::post('/{id}/approve', [AdminController::class, 'approveTransfer']);
            Route::post('/{id}/deny', [AdminController::class, 'denyTransfer']);
        });

        Route::prefix('/blog-posts')->group(function () {
            Route::get('/', [BlogPostController::class, 'getBlogPosts']);
            Route::get('/{id}', [BlogPostController::class, 'getBlogPost']);
            Route::post('/', [BlogPostController::class, 'createBlogPost']);
            Route::post('/{id}', [BlogPostController::class, 'updateBlogPost']);
            Route::patch('/{id}/toggle', [BlogPostController::class, 'updateStatus']);
            Route::delete('/{id}', [BlogPostController::class, 'deleteBlogPost']);
        });

        // Route::get('/chats/pending', [AdminChatController::class, 'getPendingChats']);
        // Route::post('/chats/{chatId}/assign', [AdminChatController::class, 'assignChat']);
        // Route::post('/chats/{chatId}/messages', [AdminChatController::class, 'sendAdminMessage']);
        // Route::post('/chats/{chatId}/close', [AdminChatController::class, 'closeChat']);
    });
});

// Auth
Route::post('/super-admin/login', [AuthController::class, 'superAdminLogin']);

Route::middleware(['auth:sanctum,super_admin'])->group(function () {

    Route::prefix('/super-admin')->group(function () {

        Route::get('/stats', [SuperAdminController::class, 'stats']);

        Route::prefix('/admins')->group(function () {
            Route::get('/', [SuperAdminController::class, 'getAllAdmins']);
            Route::post('/', [SuperAdminController::class, 'addAdmin']);
            Route::get('/{id}', [SuperAdminController::class, 'getAdmin']);
            Route::post('/{id}/update', [SuperAdminController::class, 'updateAdmin']);
            Route::post('/{id}/suspend', [SuperAdminController::class, 'suspendAdmin']);
            Route::post('/{id}/unsuspend', [SuperAdminController::class, 'reactivateAdmin']);

            Route::post('/{id}/delete', [SuperAdminController::class, 'deleteAdmin']);
        });

        Route::prefix('/currencies')->group(function () {
            Route::get('/', [SuperAdminController::class, 'getAllCurrencies']);
            Route::post('/{id}/disable', [SuperAdminController::class, 'disableCurrency']);
            Route::post('/{id}/enable', [SuperAdminController::class, 'enableCurrency']);
        });

        Route::prefix('/payment-methods')->group(function () {
            Route::get('/', [SuperAdminController::class, 'getAllPaymentMethods']);
            Route::post('/{id}/disable', [SuperAdminController::class, 'disablePaymentMethod']);
            Route::post('/{id}/enable', [SuperAdminController::class, 'enablePaymentMethod']);
        });

        Route::prefix('/exchange-rates')->group(function () {
            Route::get('/', [SuperAdminController::class, 'getAllExchangeRates']);
            Route::post('/', [SuperAdminController::class, 'setExchangeRate']);
            Route::post('/{id}', [SuperAdminController::class, 'updateExchangeRate']);
            Route::delete('/{id}', [SuperAdminController::class, 'deleteExchangeRate']);
        });

        Route::prefix('/transfer-fees')->group(function () {
            Route::get('/', [SuperAdminController::class, 'getTransferFees']);
            Route::put('/', [SuperAdminController::class, 'setTransferFee']);
        });
    });
});
