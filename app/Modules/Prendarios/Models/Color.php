<?php

namespace App\Modules\Prendarios\Models;

use App\Modules\Prendarios\PrendariosRepository;
use Illuminate\Database\Eloquent\Builder;

class Color extends PrendariosRepository
{
	protected $table = "Orbis_WS_Color";

	static public function aplicarFiltros(Builder $query, array $filtros) {
		parent::aplicarFiltros($query, $filtros);

		foreach ($filtros as $nombre => $valor) {
			if ($nombre === 'Nombre') {
				$query->where("{$nombre}", $valor);
			}
		}
	}

	public static function aplicarOrdenes(Builder $query, array $ordenes) {
		foreach ($ordenes as $nombre => $valor) {
			if (in_array($nombre, ['Order'])) {
				$query->orderBy($nombre, $valor);
			}
		}
	}
}