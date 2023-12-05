<?php

namespace App\Modules\Publicaciones\Subastas;

use App\Modules\Publicaciones\Publicacion;
use App\Modules\Publicaciones\PublicacionesBusiness;
use App\Modules\Subastas\Business\SubastasBusiness;

class EnSubastaBusiness extends PublicacionesBusiness {
    
    static public function filtrarSegunSubastaDisponible(array $filtros = []): array {
        $subastaDisponible = SubastasBusiness::obtenerDisponible();

        if ($subastaDisponible && $subastaDisponible->abiertaRecepcionOfertas()) {
            $filtros['incluir_subasta_id'] = $subastaDisponible->id;
        } else {
            $filtros['sin_subasta'] = 1;
        }
        
        return $filtros;
    }
    
    static protected function listarYObtenerFiltros(?int $usuarioId, int $page = 1, int $limit = 10, array $filtros = [], array $ordenes = [], array $opciones = []): array {
        
        $opciones = array_merge($opciones, static::getOpciones($usuarioId));
        $ordenes  = array_merge(['subasta_fecha_fin_ofertas' => 'desc'], $ordenes);

		return [
			'listado' => Publicacion::listar($page, $limit, $filtros, $ordenes, $opciones),
			'filtrosDisponibles' => Publicacion::obtenerFiltrosDisponibles($filtros),
		] ;
    }
}
