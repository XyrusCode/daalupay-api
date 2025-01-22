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
use Illuminate\Support\Facades\DB;
use DaaluPay\Models\User;
use DaaluPay\Models\Wallet;
use DaaluPay\Mail\NewUser;
use DaaluPay\Models\KYC;
use DaaluPay\Models\Address;
use DaaluPay\Models\Admin;
use DaaluPay\Models\SuperAdmin;
use Illuminate\Support\Facades\Cache;
use DaaluPay\Notifications\OtpNotification;

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

            if (!$admin || !Hash::check($request->password, $admin->password)) {
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

            if (!$superAdmin || !Hash::check($request->password, $superAdmin->password)) {
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
                'documentType' => 'required|string|max:255',
                'documentFile' => 'required|file',
            ]);

            $user = User::create([
                'uuid' => Str::uuid(),
                'first_name' => $request->firstName,
                'last_name' => $request->lastName,
                'email' => $request->email,
                'phone' => $request->phoneNumber,
                'password' => Hash::make($request->password),
            ]);

            $currencyCode = 'NGN';
            $currencyId = DB::table('currencies')->where('code', $currencyCode)->first()->id;


            Wallet::create([
                'uuid' => Str::uuid(),
                'user_id' => $user->id,
                'currency_id' => $currencyId,
                'balance' => 0,
            ]);

            // KYC::create([
            //     'user_id' => $user->id,
            //     // random admin id
            //     'admin_id' => 6, //Admin::inRandomOrder()->first()->id,
            //     'status' => 'pending',
            //     'type' => 'individual',
            //     'document_type' => $request->documentType,
            //     'document_number' => $request->documentNumber,
            //     'document_image' => $request->documentFile,
            // ]);

            Address::create([
                'user_id' => $user->id,
                'town' => $request->city,
                'state' => 'Lagos',
                'country' => $request->country,
                'zip_code' => $request->zipCode,
            ]);

            // Mail::to($user->email)->send(new NewUser($user));

            // generate and send otp for user for testing
            $otp = random_int(100000, 999999);
            Cache::put('otp_' . $user->id, $otp, now()->addMinutes(15));
            $user->otp = $otp;

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

            if (!$user) {
                throw ValidationException::withMessages([
                    'email' => ['The provided email is incorrect.'],
                ]);
            }

            $otp = random_int(100000, 999999);
            Cache::put('otp_' . $user->id, $otp, now()->addMinutes(15));

            $user->notify(new OtpNotification($otp, 15));

            return $this->getResponse('success', 'OTP sent to email', 200);
        });
    }

    public function verifyOtp(Request $request)
    {
        return $this->process(function () use ($request) {
            $request->validate([
                'otp' => 'required|string',
            ]);

            $user = User::find($request->user()?->id);

            $otp = Cache::get('otp_' . $user->id);

            if (!$otp) {
                throw ValidationException::withMessages([
                    'otp' => ['The provided OTP is incorrect.'],
                ]);
            }
            $user->status = 'verified';
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
