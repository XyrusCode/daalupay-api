<?php

namespace DaaluPay\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
// use DaaluPay\Http\Traits\AdminTrait;
use DaaluPay\Models\User;

class UserController extends BaseController
{
    /**
     * Get the authenticated user's details
     * @param Request $request
     * @return JsonResponse
     */
	public function get(Request $request) {
		$user = $request->user();
		$user->load('wallet');
		return $this->getResponse('success', $user, 200);
	}

    /**
     * Update the authenticated user's details
     * @param Request $request
     * @return JsonResponse
     */
	public function update(Request $request) {

		$user = $request->user();

		$user->first_name = $request->first_name ?? $user->first_name;
		$user->last_name = $request->last_name ?? $user->last_name;
		$user->phone_number = $request->phone_number ?? $user->phone_number;

		$user->save();
		$message = 'User updated successfully';
		return $this->getResponse('success', null, 200, $message);
	}

    /**
     * Update the authenticated user's password
     * @param Request $request
     * @return JsonResponse
     */
	public function updatePassword(Request $request) {
		$request->validate([
			'old_password' => ['required', 'string'],
			'new_password' => ['required', 'string', 'min:8'], // TODO: Add more validation rules
		]);

		$user = Auth::user();

		// Check if the old password is correct
		if (!Hash::check($request->old_password, $user->password)) {
			return $this->getResponse('error', null, 400, 'Old password is incorrect');
		}

		// Update the user's password
		$user->password = Hash::make($request->new_password);

		return $this->getResponse('success', null, 200, 'Password updated successfully');
	}

    /**
     * Get a user's details
     * @param Request $request
     * @param int $user_id
     * @return JsonResponse
     */
	public function getDetails(Request $request, $user_id) {
		$this->is_admin($request);

		$user = User::find($user_id);

		if (!$user) {
			$message = 'User does not exist';
			return $this->getResponse('failure', null, 404, $message);
		}

		return $this->getResponse('success', $user, 200);
	}

    /**
     * Update a user's details
     * @param Request $request
     * @param int $user_id
     * @return JsonResponse
     */
	public function updateDetails(Request $request, $user_id) {
		$this->is_admin($request);

		$user = User::find($user_id);

		if (!$user) {
			$message = 'User does not exist';
			return $this->getResponse('failure', null, 404, $message);
		}

		$user->first_name = $request->first_name ?? $user->first_name;
		$user->last_name = $request->last_name ?? $user->last_name;
		$user->phone_number = $request->phone_number ?? $user->phone_number;
		$user->role = $request->role ?? $user->role;

		$user->save();

		$message = 'User updated successfully';
		return $this->getResponse('success', null, 200, $message);
	}

    /**
     * Delete a user
     * @param Request $request
     * @param int $user_id
     * @return JsonResponse
     */
	public function delete(Request $request, $user_id) {
		$this->is_admin($request);

		$user = User::find($user_id);

		if (!$user) {
			$message = 'User does not exist';
			return $this->getResponse('failure', null, 404, $message);
		}

		$user->delete();

		$message = 'User deleted successfully';
		return $this->getResponse('failure', null, 200, $message);
	}
}
