<?php
namespace App\Modules\Direcciones;

use App\Modules\Prendarios\PrendariosRepository;
use Illuminate\Database\Eloquent\Builder;

class Provincia extends PrendariosRepository {

	public $table = 'Orbis_WS_Localidades';

    static public function generarConsulta(array $filtros = [], array $ordenes = [], array $opciones = []): Builder {
        $query = (new static)->newModelQuery();
        $query->distinct();
        $query->select(['codpro AS id', 'provincia AS nombre']);
        $query->orderBy('codpro');
        return $query;
    }
    
}
