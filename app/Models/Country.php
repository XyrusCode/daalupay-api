<?php

namespace DaaluPay\Models;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 * type="object",
 *  @OA\Property(
 *      property="name",
 *      type="string",
 *      example="Nigeria"
 *  ),
 *  @OA\Property(
 *      property="calling_code",
 *      type="string",
 *      example="234"
 *  ),
 *  @OA\Property (
 *      property="states",
 *      type="array",
 *      @OA\Items(ref="#/components/schemas/State")
 *   ),
 * )
 * @property string $name
 * @property string $calling_code
 * @property State[] $states
 */
class Country extends BaseModel {
	protected $table = 'countries';
	public $timestamps = false;

	protected $fillable = [
		'name',
		'calling_code'
	];

	protected $hidden = [
		'created_at',
		'updated_at'
	];

	function states() {
		return $this->hasMany(State::class);
	}
}
