<?php

namespace DaluPay\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
// use DaluPay\Http\Traits\AdminTrait;
use DaluPay\Models\User;

class UserController extends BaseController
{
	// use AdminTrait;

	public function create_user(Request $request) {
		try {
			$request->validate([
				'email' => 'required|email|unique:users',
				'phone_number' => 'required|unique:users',
				'first_name' => 'required',
				'last_name' => 'required',
				'password' => 'required',
			]);
		} catch (\Illuminate\Validation\ValidationException $e) {
			$errors = $e->errors();
			return $this->getResponse('success', ['errors' => $errors], 400);
		}



		$user = User::create([
			'first_name' => $request->first_name,
			'last_name' => $request->last_name,
			'email' => $request->email,
			'phone_number' => $request->phone_number,
			'password' => Hash::make($request->password),
			'role' => $request->role ?? 'vendor'
		]);
		$message = 'User created successfully';
		return $this->getResponse('success', $user, 201, $message);
	}

	public function get_all_users(Request $request) {
		$this->is_admin($request);
		$users = User::all();
		return $this->getResponse('success', $users, 200);
	}

	public function get_user(Request $request) {
		$user = $request->user();
		return $this->getResponse('success', $user, 200);
	}

	public function update_user(Request $request) {

		$user = $request->user();

		$user->first_name = $request->first_name ?? $user->first_name;
		$user->last_name = $request->last_name ?? $user->last_name;
		$user->phone_number = $request->phone_number ?? $user->phone_number;

		$user->save();
		$message = 'User updated successfully';
		return $this->getResponse('success', null, 200, $message);
	}

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
		$user->save();

		return $this->getResponse('success', null, 200, 'Password updated successfully');
	}

	public function get_user_details(Request $request, $user_id) {
		$this->is_admin($request);

		$user = User::find($user_id);

		if (!$user) {
			$message = 'User does not exist';
			return $this->getResponse('failure', null, 404, $message);
		}

		return $this->getResponse('success', $user, 200);
	}

	public function update_user_details(Request $request, $user_id) {
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

	public function delete_user(Request $request, $user_id) {
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
