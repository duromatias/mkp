<?php

namespace App\Modules\Prendarios\Models;

use Illuminate\Database\Eloquent\Builder;
use function is_array;

class CodigoPostal extends \App\Modules\Prendarios\PrendariosRepository
{
	public $table = 'CodigosPostales';

	public function provincia() {
		return $this->belongsTo(Provincia::class, 'CodProvincia', 'Codigo');
	}

	public static function aplicarFiltros(Builder $query, array $filtros) {
		$table = (new static())->getTable();

		foreach ($filtros as $key => $value) {
			if (in_array($key, ['CodigoPostal'])) {
				if (is_array($value)) {
					$query->whereIn("{$table}.{$key}", $value);
				}
				else {
					$query->where("{$table}.{$key}", '=', $value);
				}
			}
		}
	}
}