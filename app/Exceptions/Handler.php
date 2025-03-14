<?php

namespace DaaluPay\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Sentry\Laravel\Integration;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        // Stop ignoring HttpException
        $this->stopIgnoring(HttpException::class);

        // Capture unhandled exceptions with Sentry
        $this->reportable(function (Throwable $e) {
            Integration::captureUnhandledException($e);
        });

        // Renderable callback for NotFoundHttpException
        $this->renderable(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('*')) {
                return response()->json([
                    'message' => 'Route not found.',
                    'code' => 404,
                    'status' => 'error',
                ], 404);
            }
        });
    }
}
