<?php

namespace App\Modules\Parametros;

use App\Modules\Shared\Repositories\ModelRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class Parametro extends ModelRepository {

    public $timestamps = false;

	protected $table = 'parametros';

    const ID_PORCENTAJE_OPORTUNIDAD        	 = 1;
    const ID_PRECIO_MINIMO_ARS_PUBLICACION 	 = 2;
    const ID_PRECIO_MINIMO_USD_PUBLICACION 	 = 3;
    const ID_SUBASTAS_OFERTAS_INCREMENTO_USD = 4;
    const ID_SUBASTAS_OFERTAS_INCREMENTO_ARS = 5;
	const ID_VIGENCIA_PUBLICACIÓN 			 = 6;
	const ID_PUBLICACION_PROXIMA_VENCER 	 = 7;
	const ID_FACEBOOK						 = 8;
	const ID_INSTAGRAM						 = 9;
	const ID_LINKEDIN						 = 10;
	const ID_FINANCIACION_ANTIGUEDAD_MAXIMA  = 14;


	public static function crear(string $descripcion, string $valor) {
		$row = new static();

        $row->descripcion = $descripcion;
        $row->valor       = $valor;

        return $row->guardar();
	}

    static public function valorPorcentajeOportinidad() {
        return static::getById(static::ID_PORCENTAJE_OPORTUNIDAD)->valor;
    }

    static public function valorSubastasOfertasIncrementoUsd(): float {
        return static::getById(static::ID_SUBASTAS_OFERTAS_INCREMENTO_USD);
    }

    static public function valorSubastasOfertasIncrementoArs(): float {
        return static::getById(static::ID_SUBASTAS_OFERTAS_INCREMENTO_ARS);
    }

    static public function valorPrecioMinimoPublicacionPesos(): float {
        return (float) static::getById(static::ID_PRECIO_MINIMO_ARS_PUBLICACION)->valor;
    }

    static public function valorPrecioMinimoPublicacionDolares(): float {
        return (float) static::getById(static::ID_PRECIO_MINIMO_USD_PUBLICACION)->valor;
    }

    static public function massUpdate(array $parametros) {
		foreach ($parametros as $parametro) {
			$id = $parametro['id'];
			$data = Arr::except($parametro, 'id');

			Parametro::query()->where('id', $id)->update($data);
		}

		return $parametros;
	}

    static public function filtrosEq(): array {
        return [];
    }

    static public function aplicarFiltros(Builder $query, array $filtros) {
        parent::aplicarFiltros($query, $filtros);
        foreach($filtros as $nombre => $valor) {
            if (in_array($nombre, ['id'])) {
                $valor = is_array($valor) ? $valor : [$valor];
                $query->whereIn($nombre, $valor);
            }
        }
    }

    public function obtenerNombreParaExposicion(): string {
        if ($this->nombre) {
            return $this->nombre;
        }
        $partes = explode(' ', $this->descripcion);
        $nombre = '';
        foreach($partes as $parte) {
            $nombre .= ucfirst(strtolower($parte));
        }

        return lcfirst($nombre);
    }

    public static function obtenerAntiguedadMaximaFinanciacion() : int {
        return static::getById(static::ID_FINANCIACION_ANTIGUEDAD_MAXIMA)['valor'];
    }
}
