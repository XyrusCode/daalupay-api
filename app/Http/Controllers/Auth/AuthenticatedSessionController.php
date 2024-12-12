<?php

namespace DaaluPay\Http\Controllers\Auth;

use DaaluPay\Http\Controllers\BaseController;
use DaaluPay\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use DaaluPay\Models\User;

class AuthenticatedSessionController extends BaseController
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): JsonResponse
    {
        return $this->process(function () use ($request){
        $request->authenticate();

        $request->session()->regenerate();

        $user = $request->user()->load('wallets', 'transactions');

            return $this->getResponse(
                data: $user,
                message: 'Login Success'
            );
        });
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): Response
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->noContent();
    }

    public function iosToken(Request $request): Response
    {   return $this->process(function () use ($request){
        $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'device_name' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
            ]);
        }

            return $this->getResponse(
                data: $user->createToken($request->device_name)->plainTextToken,
                message: 'Login Success'
            );
            $request->session()->regenerate();

        });
    }
}
