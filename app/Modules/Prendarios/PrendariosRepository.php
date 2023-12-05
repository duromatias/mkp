<?php

namespace App\Modules\Prendarios;

use App\Base\Repository\ModelRepository;

class PrendariosRepository extends ModelRepository
{

	public function __construct(array $attributes = []) {
		parent::__construct($attributes);
		$this->table = "{$this->getDatabaseName()}.{$this->table}";
	}


	protected function getDatabaseName() {
		return config('prendarios.db_name');
	}
}