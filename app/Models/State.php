<?php

namespace DaluPay\Models;

class State extends BaseModel {
	protected $table = 'states';

	protected $fillable = [
		'name',
		'country_id'
	];
}
