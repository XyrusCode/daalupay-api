<?php

namespace DaaluPay\Http\Controllers;

use DaaluPay\Exceptions\NotFoundException;
use DaaluPay\Http\Requests\Auth\LoginRequest;
use DaaluPay\Mail\NewUser;
use DaaluPay\Mail\PasswordChanged;
use DaaluPay\Mail\PasswordReset as MailPasswordReset;
use DaaluPay\Models\Address;
use DaaluPay\Models\Admin;
use DaaluPay\Models\SuperAdmin;
use DaaluPay\Models\User;
use Daalupay\Models\UserPreference;
use DaaluPay\Models\Wallet;
use DaaluPay\Notifications\OtpNotification;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends BaseController
{
    /**
     * Handle an incoming authentication request.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        return $this->process(function () use ($request) {
            // validate request
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $request->authenticate();

            // handle user not found in db
            if (! $request->user()) {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);
            }

            $request->session()->regenerate();
            $token = $request->user()->createToken('auth_token')->plainTextToken;

            $user = $request->user()->load('wallets', 'transactions');
            $user->token = $token;
            $user->userType = 'user';

            return $this->getResponse(
                data: $user,
                message: 'Login Success'
            );
        });
    }

    public function adminLogin(Request $request)
    {
        return $this->process(function () use ($request) {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $admin = Admin::where('email', $request->email)->first();

            if (! $admin || ! Hash::check($request->password, $admin->password)) {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);
            }

            $token = $admin->createToken('auth_token')->plainTextToken;
            $admin->token = $token;
            $admin->userType = 'admin';

            return $this->getResponse(
                data: $admin,
                message: 'Admin login successful',
                status_code: 200
            );
        });
    }

    public function superAdminLogin(Request $request)
    {
        return $this->process(function () use ($request) {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $superAdmin = SuperAdmin::where('email', $request->email)->first();

            if (! $superAdmin || ! Hash::check($request->password, $superAdmin->password)) {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);
            }

            $token = $superAdmin->createToken('auth_token')->plainTextToken;

            $superAdmin->token = $token;
            $superAdmin->userType = 'super_admin';

            return $this->getResponse(
                data: $superAdmin,
                message: 'Super admin login successful',
                status_code: 200
            );
        });
    }

    /**
     * Register a new user.
     *
     * {
     */
    public function register(Request $request): JsonResponse
    {
        return $this->process(function () use ($request) {
            $request->validate([
                'firstName' => 'required|string|max:255',
                'lastName' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'phoneNumber' => 'required|string|max:255',
                // confirm_password & password must be the same
                'confirmPassword' => 'required|string|same:password',
                'password' => 'required|string|min:8',
                'country' => 'required|string|max:255',
                'gender' => 'required|string|max:255',
                'address' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'zipCode' => 'required|string|max:255',
                'dateOfBirth' => 'required|date',
            ]);

            $user = User::create([
                'uuid' => Str::uuid(),
                'first_name' => $request->firstName,
                'last_name' => $request->lastName,
                'email' => $request->email,
                'phone' => $request->phoneNumber,
                'password' => Hash::make($request->password),
            ]);

            $nairaCurrencyId = DB::table('currencies')->where('code', 'NGN')->first()->id;
            $yuanCurrencyId = DB::table('currencies')->where('code', 'CNY')->first()->id;

            Wallet::create([
                'uuid' => Str::uuid(),
                'user_id' => $user->id,
                'currency_id' => $nairaCurrencyId,
                'balance' => 0,
            ]);

            Wallet::create([
                'uuid' => Str::uuid(),
                'user_id' => $user->id,
                'currency_id' => $yuanCurrencyId,
                'balance' => 0,
            ]);

            Address::create([
                'user_id' => $user->id,
                'town' => $request->city,
                'state' => 'Lagos',
                'country' => $request->country,
                'zip_code' => $request->zipCode,
            ]);

            if (! config('app.test_mode')) {

                UserPreference::create([
                    'user_id' => $user->id,
                    'notify_email' => 'true',
                    'notify_sms' => 'false',
                    'theme' => 'light',
                    'daily_transaction_limit' => '500000',
                    'transaction_total_today' => 0,
                    'last_transaction_date' => now(),
                    'two_fa_enabled' => 'true',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // generate and send otp for user for testing
            $otp = random_int(10000, 99999);
            Cache::put('otp_'.$user->id, $otp, now()->addMinutes(5));

            Mail::to($user->email)->send(new NewUser($user, $otp));

            return $this->getResponse('success', $user, 200);
        });
    }

    public function requestOtp(Request $request)
    {
        return $this->process(function () use ($request) {
            $request->validate([
                'email' => 'required|email',
            ]);

            $user = User::where('email', $request->email)->first();

            if (! $user) {
                throw ValidationException::withMessages([
                    'email' => ['The provided email is incorrect.'],
                ]);
            }

            $otp = random_int(10000, 99999);
            Cache::put('otp_'.$user->id, $otp, now()->addMinutes(5));

            $otpInCache = Cache::get('otp_'.$user->id);

            Mail::to($user->email)->send(new OtpNotification($user, $otp, 5));

            return $this->getResponse(status: 'success', message: 'OTP sent to email: '.$user->email, status_code: 200);
        });
    }

    public function verifyOtp(Request $request)
    {
        return $this->process(function () use ($request) {
            $request->validate([
                'otp' => 'required|string',
                'email' => 'required|email',
            ]);

            $user = User::where('email', $request->email)->first();

            if (! $user) {
                throw ValidationException::withMessages([
                    'email' => ['No user found with the provided email address.'],
                ]);
            }

            $otp = Cache::get('otp_'.$user->id);

            // Ensure the OTP from the cache and request are treated as strings
            $otpFromCache = (string) $otp;  // Cast cache OTP to string
            $otpFromRequest = (string) $request->otp;  // Cast request OTP to string

            // Remove any leading/trailing whitespace from both
            $otpFromCache = trim($otpFromCache);
            $otpFromRequest = trim($otpFromRequest);

            // Check if OTP exists and matches
            if ($otpFromCache !== $otpFromRequest) {
                throw ValidationException::withMessages([
                    'otp' => ['The provided OTP is incorrect or has expired.'],
                ]);
            }

            // Mark the user as active and save
            $user->status = 'active';
            $user->save();

            return $this->getResponse('success', $user, 200);
        });
    }

    /**
     * Destroy an authenticated session.
     */
    public function logout(Request $request): Response
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->noContent();
    }

    public function iosToken(Request $request): JsonResponse
    {
        return $this->process(function () use ($request) {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
                'device_name' => 'required',
            ]);

            $user = User::where('email', $request->email)->first();

            // handle user not found in db
            if (! $user) {
                throw ValidationException::withMessages([
                    'email' => ['User not found in database.'],
                ]);
            }

            $user->load('wallets', 'transactions');

            if (! $user || ! Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);
            }

            $request->session()->regenerate();

            return $this->getResponse(
                data: [
                    'user' => $user,
                    'token' => $user->createToken($request->device_name)->plainTextToken,
                ],
                message: 'Login Success'
            );
        });
    }

    /**
     * Send a new email verification notification.
     */
    public function sendVerificationEmail(Request $request): JsonResponse|RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended('/dashboard');
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->json(['status' => 'verification-link-sent']);
    }

    /**
     * Resend a new verificcation code
     */
    public function resendCode(Request $request)
    {
        return $this->process(function () use ($request) {

            // Generate a new code for the user
            $code = $request->user()->createVerificationCode();

            // Send the new verification code to the user's email
            $request->user()->sendVerificationCode($code);

            return $this->getResponse(
                status: 'success',
                message: 'Verification code has been sent to your email',
                status_code: 200
            );
        });
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'token' => ['required'],
            'user_id' => ['required'],
            'password' => ['required'],
            'password_confirmation' => ['required'],
        ]);

        $userId = $request->user_id;  // Cast request UserID to string
        // remove any leading/trailing whitespace
        $userId = trim($userId);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $user = User::where('uuid', $userId)->first();
        // if user not found return error
        if (! $user) {
            throw new NotFoundException('User not found');
        }

        // confirm token is valid from cache
        $token = Cache::get('password_reset_token_'.$user->id);

        if (! $token) {
            throw new NotFoundException(
                'Token not found for this user, initate another passwword reset request.'
            );
        }

        if ($token !== $request->token) {
            throw new ValidationException([
                'token' => ['Invalid token'],
            ]);
        }

        // if token is valid, send email to user
        if ($token === $request->token) {

            Cache::forget('password_reset_token_'.$user->id);

            $user->password = Hash::make($request->password);
            $user->save();

            Mail::to($user->email)->send(new PasswordChanged($user));
        }

        return $this->getResponse(status: 'success', message: 'Password reset successful', status_code: 200);
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $request->email)->first();

        // if user does not exist
        if (! $user) {
            throw ValidationException::withMessages([
                'email' => ['The provided email is incorrect.'],
            ]);
        }

        // Generate a new password reset token
        $token = Str::random(60);

        // save token to cache
        Cache::put('password_reset_token_'.$user->id, $token, now()->addMinutes(15));

        // Send the password reset link to the user's email
        Mail::to($user->email)->send(new MailPasswordReset($user, $token, 15));

        return $this->getResponse(status: 'success', message: 'Password reset link sent to email: '.$user->email, status_code: 200);
    }

    /**
     * Get a token for a user.
     *
     * @return JsonResponse
     */
    public function getToken(Request $request, string $type)
    {
        $validationRules = [
            'email' => 'required|email',
        ];

        if ($type === 'mobile') {
            $validationRules['password'] = 'required';
        } else {
            $validationRules['password'] = 'nullable';
        }

        $request->validate($validationRules);

        $user = User::where('email', $request->email)->first();

        if (! $user || ($request->password && ! Hash::check($request->password, $user->password))) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return response()->json(['message' => 'Token generated successfully']);
    }

    /**
     * Mark the authenticated user's email address as verified.
     */
    public function verifyEmail(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(
                config('app.frontend_url').'/dashboard?verified=1'
            );
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->intended(
            config('app.frontend_url').'/dashboard?verified=1'
        );
    }
}
