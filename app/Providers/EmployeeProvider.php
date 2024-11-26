<?php

namespace DaaluPay\Providers;

use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Auth\Authenticatable;
use DaaluPay\Models\Employee;

class EmployeeProvider
{
    // public function retrieveById($identifier)
    // {
    //     //find employee by uuid
    //     return Employee::where('uuid', $identifier)->first();
    // }

    // public function retrieveByToken($identifier, $token)
    // {
    //     return Employee::where('remember_token', $token)->first();
    // }

    // public function updateRememberToken(Authenticatable $user, $token)
    // {
    //     $user->setRememberToken($token);
    //     $user->save();
    // }

    // public function retrieveByCredentials(array $credentials)
    // {
    //     return Employee::where('email', $credentials['email'])->first();
    // }

    // public function validateCredentials(Authenticatable $user, array $credentials)
    // {
    //     return Hash::check($credentials['password'], $user->getAuthPassword());
    // }

    // public function rehashPasswordIfRequired(Authenticatable $user, $password)
    // {
    //     return Hash::needsRehash($password) ? Hash::make($password) : $password;
    // }
}
