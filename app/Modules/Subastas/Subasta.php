<?php

namespace App\Modules\Subastas;

use App\Base\Repository\ModelRepository;
use App\Modules\Publicaciones\Publicacion;
use App\Modules\Subastas\Dtos\CreateSubastaDto;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subasta extends ModelRepository
{
	public const ESTADO_CREADA = 'Creada';
	public const ESTADO_CANCELADA = 'Cancelada';

	public const ESTADOS = [
		self::ESTADO_CREADA,
		self::ESTADO_CANCELADA
	];

	protected $guarded = [];

	public function publicaciones(): HasMany {
		return $this->hasMany(Publicacion::class, 'subasta_id')->whereIn('estado', [Publicacion::ESTADO_ACTIVA]);
	}

	public static function create(CreateSubastaDto $crearSubastaDto) {
		$subasta = new static();

		$subasta->fecha_inicio_inscripcion = $crearSubastaDto->fecha_inicio_inscripcion;
		$subasta->fecha_fin_inscripcion = $crearSubastaDto->fecha_fin_inscripcion;
		$subasta->fecha_inicio_ofertas = $crearSubastaDto->fecha_inicio_ofertas;
		$subasta->fecha_fin_ofertas = $crearSubastaDto->fecha_fin_ofertas;

		$subasta->estado = static::ESTADO_CREADA;

		return $subasta->insertar();
	}


	public static function aplicarFiltros(Builder $query, array $filtros) {
		parent::aplicarFiltros($query, $filtros);

		$table = (new static())->getTable();

		foreach ($filtros as $nombre => $valor) {
			if (in_array($nombre, ['estado', 'fecha_inicio_ofertas', 'fecha_fin_ofertas', 'fecha_inicio_inscripcion'])) {
				$query->where("{$table}.{$nombre}", $valor);
			}
            
            if ($nombre === 'fecha_disponible') {
                $query->whereRaw("(
                    '{$valor}' BETWEEN fecha_inicio_inscripcion AND fecha_fin_inscripcion
                    OR
                    '{$valor}' BETWEEN fecha_inicio_ofertas AND fecha_fin_ofertas
                )");
            }
            
            if ($nombre === 'disponible_entre_fechas') {
                $query->whereRaw("(
                    fecha_fin_ofertas        >= '{$valor['desde']}'
                    AND
                    fecha_inicio_inscripcion <= '{$valor['hasta']}'
                )");
            }
		}
	}


	public static function aplicarOrdenes(Builder $query, array $ordenes) {
		foreach ($ordenes as $columna => $sentido) {
			if (in_array($columna, [
                'id',
                'fecha_inicio_inscripcion', 
                'fecha_fin_inscripcion', 
                'fecha_inicio_ofertas', 
                'fecha_fin_ofertas', 
                'estado'
            ])) {
				$query->orderBy($columna, $sentido);
			}

		}
	}

	public function update(array $attributes = [], array $opciones = []): Subasta {
		$this->fill($attributes);

		return $this->guardar();
	}

	public function abiertaRecepcionOfertas(): bool {
		$today = date('Y-m-d');

		return $this->fecha_inicio_ofertas <= $today && $today <= $this->fecha_fin_ofertas;
	}

	public function abiertaInscripcion(): bool {
		$today = date('Y-m-d');

		return $this->fecha_inicio_inscripcion <= $today && $today <= $this->fecha_fin_inscripcion;
	}

	public function finalizada(): bool {
		$today = date('Y-m-d');

		return $this->fecha_fin_ofertas < $today;
	}

	public function sinOfertas(): bool {
		foreach ($this->publicaciones as $publicacion) {
			if ($publicacion->ofertas) {
				return false;
			}
		}

		return true;
	}

	public static function getSubastasSpaHomePage() {
		return config('app.spa_url') . '/subastas';
	}
}