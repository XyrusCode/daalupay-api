<?php

namespace DaaluPay\Http\Controllers;

use DaaluPay\Exceptions\CustomException;
use DaaluPay\Helpers\StatusCodeHelper;
use DaaluPay\Models\Transfer;
use DaaluPay\Services\FCMService;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Exception\MessagingException;
use OpenApi\Annotations as OA;

use function Sentry\captureException;

/**
 * @OA\Info(
 *     title="DaaluPay API",
 *     version="1.0.0",
 *     description="DaaluPay API Documentation",
 *
 *     @OA\Contact(
 *         email="info@daalupay.com",
 *         name="DaaluPay Support"
 *     )
 * )
 */

/**
 * @OA\PathItem(path="/api")
 */
class BaseController extends Controller
{
    use AuthorizesRequests;
    use ValidatesRequests;

    /**
     * Start a transaction
     */
    protected function transaction(callable $callback)
    {
        try {
            // Start a transaction
            DB::beginTransaction();

            // Execute the callback
            $result = $callback();

            // Commit the transaction
            DB::commit();

            return $result;
        } catch (\Throwable $e) {
            Log::error('Transaction failed: ' . $e->getMessage());
            // Rollback the transaction in case of any error
            DB::rollBack();

            throw $e; // Rethrow the exception to be handled by process
        }
    }

    /**
     * Process a callback
     */
    protected function process(callable $callback, bool $isTransaction = false)
    {
        try {
            if ($isTransaction) {
                return $this->transaction($callback);
            }

            // If not a transaction, simply execute the callback
            return $callback();
        } catch (\Throwable $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Get a response
     */
    public function getResponse($status = 'success', $data = null, $status_code = 200, $message = null): JsonResponse
    {
        if (! StatusCodeHelper::isValid($status_code)) {
            $message ??= 'Invalid HTTP Status Code';
            $status_code = 500;
        }

        $json = [
            'status' => $status,
        ];

        if (! is_null($data)) {
            if (is_array($data) || is_object($data)) {
                $data = $this->convertKeysWithUnderscoresToCamelCases(json_decode(json_encode($data), true));
            }
            $json['data'] = $data;
        }

        if (! is_null($message)) {
            $json['message'] = $message;
        }

        return response()->json($json, $status_code);
    }

    /**
     * Handle an exception
     */
    protected function handleException(\Throwable $e)
    {
        // capture exception to sentry
        captureException($e);

        Log::error('Exception handled: ' . $e->getMessage());

        // Determine the type of throwable
        return match (true) {
            $e instanceof ValidationException => $this->getResponse(
                status: 'error',
                status_code: 422,
                message: 'Incorrect Credentails: ' . $e->getMessage()
            ),
            $e instanceof CustomException => $this->getResponse(
                status: 'error',
                status_code: 404,
                message: 'Model not found: ' . $e->getMessage()
            ),
            $e instanceof QueryException => $this->getResponse(
                status: 'error',
                status_code: 400,
                message: 'Database query error: ' . $e->getMessage()
            ),
            default => $this->getResponse(
                status: 'error',
                status_code: 500,
                message: 'An error occurred: ' . $e->getMessage()
            ),
        };
    }

    /**
     * Convert an object to an array
     */
    public function toArray(object $data)
    {
        if (method_exists($data, 'toArray')) {
            $data = $data->toArray();
        }

        return $data;
    }

    /**
     * Con vert keys with underscores to camel cases
     */
    public function convertKeysWithUnderscoresToCamelCases(array $data): array
    {
        $result = [];
        foreach ($data as $key => $value) {
            if (is_array($value) && array_keys($value) === range(0, count($value) - 1)) {
                // Handle indexed arrays
                $result[$key] = array_map([$this, 'convertKeysWithUnderscoresToCamelCases'], $value);
            } else {
                // Handle associative arrays
                $camelCasedKey = $this->underscoreToCamelCase($key);
                $result[$camelCasedKey] = is_array($value) ? $this->convertKeysWithUnderscoresToCamelCases($value) : $value;
            }
        }

        return $result;
    }

    public function underscoreToCamelCase(string $string): string
    {
        if (! str_contains($string, '_')) {
            return $string;
        }

        $str = str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));
        $str[0] = strtolower($str[0]);

        return $str;
    }

    public function getRequestAttributes(Request $request)
    {
        return $request->getParsedBody() ?? [];
    }

    public function getQueryParams(Request $request)
    {
        return $request->getQueryParams();
    }

    public function getQueryParam(Request $request, string $key, $default = null)
    {
        return $request->getQueryParam($key, $default);
    }

    public function getBlobFromDB(string $table, string $key)
    {
        $transfer = DB::table($table)->where('id', $key)->first();
        $file = $transfer->transfer;
        // check if blob is null
        if (! $file) {
            $this->getResponse(status: 'error', status_code: 404, message: 'File not found');
        }

        // Determine the content type (modify accordingly)
        $mimeType = $file->mime_type ?? 'application/octet-stream';

        return response($file)
            ->header('Content-Type', $mimeType)
            ->header('Content-Disposition', 'inline; filename="' . $file->filename . '"');
    }

    public function sendFCMNotification(Request $request, $messageTitle, $messageBody)
    {
        /** @var FCMService $fcm */
        $fcm = app(FCMService::class);

        $user = $request->user();

        try {
            // Attempt to send the notification
            // Send notifications to all active user device tokens
            $userDeviceTokens = $user->notificationTokens->where('status', 'active');
            foreach ($userDeviceTokens as $userDeviceToken) {
                $fcm->sendNotification(
                    $userDeviceToken->token,
                    $messageTitle,
                    $messageBody
                );
            }

            // Return a success response with the result (if any)
            return $this->getResponse(
                status: 'success',
                message: 'Notification sent successfully',

            );
        } catch (MessagingException $e) {
            // Catches errors specific to messaging (e.g. invalid token, unregistered token, etc.)
            return $this->getResponse(
                status: 'error',
                status_code: 422,
                message: 'Messaging error: ' . $e->getMessage()
            );
        } catch (FirebaseException $e) {
            // Catches general Firebase exceptions
            return $this->getResponse(
                status: 'error',
                status_code: 500,
                message: 'Firebase error: ' . $e->getMessage()
            );
        } catch (\Exception $e) {
            // Catches any other exceptions
            return $this->getResponse(
                status: 'error',
                status_code: 500,
                message: 'Unexpected error: ' . $e->getMessage()
            );
        }
    }
}
