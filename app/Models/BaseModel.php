<?php

namespace DaaluPay\Models;

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

    public function getFillable(): array
    {
        return $this->fillable;
    }

    public function getHidden(): array
    {
        return $this->hidden;
    }

    public function getTable(): string
    {
        return $this->table;
    }

    public function getRelations(): array
    {
        return $this->relations;
    }

    public function getCasts(): array
    {
        return $this->casts;
    }


    public function getKey(): string
    {
        return $this->getKey();
    }

    public function getKeyType(): string
    {
        return $this->keyType;
    }


    public function getIncrementing(): bool
    {
        return $this->incrementing;
    }

    public function getKeyName(): string
    {
        return $this->keyName;
    }

    
}
