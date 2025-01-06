<?php

namespace DaaluPay\Http\Controllers;

use DaaluPay\Http\Controllers\BaseController;
use DaaluPay\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use DaaluPay\Models\User;
use DaaluPay\Models\Wallet;
use DaaluPay\Mail\NewUser;
use DaaluPay\Models\KYC;
use DaaluPay\Models\Address;

class AuthController extends BaseController
{
    /**
     * Handle an incoming authentication request.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        return $this->process(function () use ($request) {
            $request->authenticate();

            $request->session()->regenerate();
            $token = $request->user()->createToken('auth_token')->plainTextToken;

            $user = $request->user()->load('wallets', 'transactions');
            $user->token = $token;

            return $this->getResponse(
                data: $user,
                message: 'Login Success'
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
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'phone_number' => 'required|string|max:255',
                // confirm_password & password must be the same
                'confirm_password' => 'required|string|same:password',
                'password' => 'required|string|min:8',
                'country' => 'required|string|max:255',
                'gender' => 'required|string|max:255',
                'address' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'zip_code' => 'required|string|max:255',
                'date_of_birth' => 'required|date',
                'document_type' => 'required|string|max:255',
                // 'document_file' => 'required|file',
            ]);

            $user = User::create([
                'uuid' => Str::uuid(),
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone_number,
                'password' => Hash::make($request->password),
            ]);



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
                    'token' => $user->createToken($request->device_name)->plainTextToken
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
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->string('password')),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status != Password::PASSWORD_RESET) {
            throw ValidationException::withMessages([
                'email' => [__($status)],
            ]);
        }

        return response()->json(['status' => __($status)]);
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function sendResetLinkEmail(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            throw ValidationException::withMessages([
                'email' => [__($status)],
            ]);
        }

        return response()->json(['status' => __($status)]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): Response
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->string('password')),
        ]);

        KYC::create([
            'user_id' => $user->id,
            'status' => 'pending',
            'type' => 'individual',
            'document_type' => 'passport',
            'document_number' => $request->document_number,
            'document_image' => $request->document_image,
        ]);

        Address::create([
            'user_id' => $user->id,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
            'zip_code' => $request->zip_code,
        ]);

        //Create a naira wallet for the user
        Wallet::create([
            'user_id' => $user->id,
            'currency' => 'NGN',
            'balance' => 0,
        ]);

        Mail::to($user->email)->send(new NewUser($user));

        event(new Registered($user));

        Auth::login($user);

        return response()->noContent();
    }

    /**
     * Get a token for a user.
     *
     * @param Request $request
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

        if (!$user || ($request->password && !Hash::check($request->password, $user->password))) {
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
                config('app.frontend_url') . '/dashboard?verified=1'
            );
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->intended(
            config('app.frontend_url') . '/dashboard?verified=1'
        );
    }
}
