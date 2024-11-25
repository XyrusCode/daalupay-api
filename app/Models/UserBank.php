<?php

namespace DaaluPay\Models;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *  type="object",
 *    @OA\Property(
 *         type="string",
 *         property="account_number",
 *         example="3169626292"
 *  ),
 *    @OA\Property(
 *         type="string",
 *         property="name",
 *         example="Access Bank"
 *  ),
 *   @OA\Property (
 *        property="user",
 *        type="array",
 *        @OA\Items(ref="#/components/schemas/User")
 *   ),
 * )
 * @property string $status
 * @property string $account_number
 * @property User $user
 */
class UserBank extends BaseModel
{
	protected $table = 'user_bank';

	protected $fillable = [
		'account_number',
		'name',
		'user_id'
	];

	protected $visible = [
		'account_number',
		'name'
	];

	protected $validationRules = [
		'account_number' => [
			'rules' => [
				'required'
			]
		],
		'name' => [
			'rules' => [
				'required'
			]
		]
	];
}
