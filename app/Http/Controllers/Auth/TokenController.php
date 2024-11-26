<?php

namespace DaaluPay\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use DaaluPay\Http\Controllers\Controller;
use DaaluPay\Models\User;

class TokenController extends Controller
{
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
}
