<?php

namespace DaaluPay\Models;

// use Jenssegers\Mongodb\Eloquent\Model;

/**
 * @OA\Schema(
 *  type="object",
 *  @OA\Property(
 *    type="string",
 *    property="data",
 *  ),
 * )
 * @property string $data
 */


class Hook extends BaseModel
{
	protected $connection = 'mongodb';
	protected $collection = 'hooks';

	protected $fillable = [
		'data',
	];
}
