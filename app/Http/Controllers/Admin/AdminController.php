<?php

namespace DaluPay\Http\Controllers\Admin;

use DaluPay\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use DaluPay\Models\User;
use DaluPay\Models\Suspension;

class AdminController extends BaseController
{

    /**
     * Get all users
     * @return JsonResponse
     */
    public function getAllUsers() {
        return $this->process(function() {
            $users = User::all();

            return $this->getResponse('success', $users, 200);
        }, true);
    }

    /**
     * Get a user by ID
     * @param int $user_id
     * @return JsonResponse
     */
    public function getUser($user_id) {
        return $this->process(function() use ($user_id) {
            $user = User::find($user_id);

            return $this->getResponse('success', $user, 200);
        }, true);
    }

    /**
     * Update a user
     * @param Request $request
     * @param int $user_id
     * @return JsonResponse
     */
    public function updateUser(Request $request, $user_id) {
        return $this->process(function() use ($request, $user_id) {
            $user = User::find($user_id);

            return $this->getResponse('success', $user, 200);
        }, true);
    }

    /**
     * Suspend a user and create a suspension record
     * @param Request $request
     * @return JsonResponse
     */
    public function createSuspension(Request $request) {
        return $this->process(function() use ($request) {
            $user = User::find($request->user_id);

            $user->status = 'suspended';
            $user->save();

            $suspension = Suspension::create([
                'user_id' => $user->id,
            ]);

            return $this->getResponse('success', $suspension, 200);
        }, true);
    }
}
