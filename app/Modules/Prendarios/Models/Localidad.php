<?php

namespace App\Modules\Prendarios\Models;

use Illuminate\Database\Eloquent\Builder;

class Localidad extends \App\Modules\Prendarios\PrendariosRepository
{
	public $table = 'Orbis_WS_Localidades';

	public static function aplicarFiltros(Builder $query, array $filtros) {
		$table = (new static())->getTable();

		foreach ($filtros as $nombre => $valor) {
			if (in_array($nombre, ['codpos'])) {
				$query->where("{$table}.{$nombre}", '=', $valor);
			}
            
            if ($nombre === 'codpro') {
                $query->where("{$table}.codpro", $valor);
            }

			if (in_array($nombre, ['busqueda'])) {
                if (is_numeric($valor)) {
                    $query->where("{$table}.codpos", $valor);
                } else {
                    $query->where(function($query) use($valor, $table) {
                        $query->where  ("{$table}.codpos",    'LIKE', "%{$valor}%");
                        $query->orWhere("{$table}.localidad", 'LIKE', "%{$valor}%");
                    });
                }
			}
		}
	}
}