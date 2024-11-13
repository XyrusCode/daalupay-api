<?php

namespace DaluPay\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{

	function toArray() {
		$data = parent::toArray();
		if (isset($data['uuid']) && !isset($data['id'])) {
			$data['id'] = $data['uuid'];
			unset($data['uuid']);
		}

		if (is_array($data)) {
			$data = array_filter($data, fn ($value) => !is_null($value) && $value !== '');
		}
		return $data;
	}
}
