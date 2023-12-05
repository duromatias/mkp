<?php

namespace App\Modules\Publicaciones\Favoritos\Models;

use App\Base\Repository\ModelRepository;
use Illuminate\Database\Eloquent\Builder;


class Favorito extends ModelRepository
{
	public $timestamps = false;
	protected $guarded = [];


	public static function crear(array $data) {
		$favorito = new static();
		$favorito->fill($data);

		return $favorito->insertar();
	}


	public static function aplicarFiltros(Builder $query, array $filtros) {
		foreach ($filtros as $name => $value) {
			if (in_array($name, ['publicacion_id', 'usuario_id'])) {
				$query->where("favoritos.{$name}", '=', $value);
			}
		}
	}
}
