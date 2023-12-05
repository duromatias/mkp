<?php

namespace App\Modules\Prendarios\Models;

use App\Modules\Prendarios\PrendariosRepository;
use Illuminate\Database\Eloquent\Builder;
use function in_array;

class EstadoCivil extends PrendariosRepository
{
	protected $table = "TiposEstadoCivil";

	public static function aplicarFiltros(Builder $query, array $filtros) {
		$table = (new static())->getTable();

		foreach ($filtros as $key => $value) {
			if (in_array($key, ['Codigo', 'inactivo'])) {
				$query->where("{$table}.{$key}", '=', $value);
			}
		}
	}
}