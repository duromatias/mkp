<?php

namespace App\Modules\Publicaciones\Ofertas\Models;

use App\Base\Repository\ModelRepository;
use App\Modules\Publicaciones\Publicacion;
use App\Modules\Users\Models\User;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Oferta extends ModelRepository
{
	protected $guarded = [];

	public static function create(array $data) {
		$oferta = new static();
		$oferta->fill($data);

		return $oferta->insertar();
	}

	public static function aplicarFiltros(Builder $query, array $filtros) {
        parent::aplicarFiltros($query, $filtros);
        
		$table = (new static())->getTable();

		foreach ($filtros as $nombre => $valor) {
			if (in_array($nombre, ['publicacion_id'])) {
				$query->where("{$table}.{$nombre}", '=', $valor);
			}
		}
	}
    
    public function usuario(): BelongsTo {
        return $this->belongsTo(User::class);
    }
    
    public function publicacion(): BelongsTo {
        return $this->belongsTo(Publicacion::class);
    }
}