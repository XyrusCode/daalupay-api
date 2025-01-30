<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Sentry\Laravel\Integration;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/api.php',
        // api: __DIR__.'/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        channels: __DIR__ . '/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->validateCsrfTokens(except: [
            '/transactions/*',
            '/login',
            '/logout',
            '/forgot-password',
            '/reset-password',
            '/verify-email',
            '/email/verification-notification',
            '/request-otp',
            '/verify-otp',
            '/user/*',
            '/user',
            '/register',
            '/sanctum/*',
            '/admin/login',
            '/super-admin/admins',
            '/super-admin/login',
            '/admin/*',
            '/super-admin/admins/*',
            '/super-admin/currencies/*',
            '/super-admin/payment-methods/*',
            '/super-admin/exchange-rates/*',
            '/super-admin/exchange-rates',
            '/super-admin/transfer-fees',
            '/super-admin/receipts',
            '/super-admin/swaps',
        ]);

        $middleware->trustHosts(at: ['daalupay.internal', 'daalupay.com']);

        // Ensure frontend requests are stateful (Sanctum middleware)
        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);

        // $middleware->api(prepend: [
        //  \DaaluPay\Http\Middleware\LogUserActivity::class,
        // ]);

        // // Force HTTPS for all routes (HSTS)
        // $middleware->prepend(\Illuminate\Http\Middleware\TrustProxies::class);

        // Rate limiting: apply to API routes globally
        // $middleware->throttle('api')->prepend();

        // // Content Security Policy (CSP) headers
        // $middleware->after(function ($request, $response) {
        //     $response->headers->set('Content-Security-Policy', 'default-src https://yourdomain.com;');
        // });

        // // Enforce HTTPS with Strict-Transport-Security (HSTS)
        // $middleware->after(function ($request, $response) {
        //     $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        // });

        // Aliases for verified email middleware
        $middleware->alias([
            'verified' => \DaaluPay\Http\Middleware\EnsureEmailIsVerified::class,
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            // 'logActivity' => \DaaluPay\Http\Middleware\LogUserActivity::class,
            //  'verify.browser' => \DaaluPay\Http\Middleware\VerifyBrowserId::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Integrating Sentry for error handling
        Integration::handles($exceptions);
    })
    ->create();
