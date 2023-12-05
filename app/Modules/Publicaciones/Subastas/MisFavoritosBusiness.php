<?php

namespace App\Modules\Publicaciones\Subastas;

class MisFavoritosBusiness extends EnSubastaBusiness {
    
    static public function listar(int $usuarioId, int $page = 1, int $limit = 10, array $filtros = [], array $ordenes = [], array $opciones = []) {
        $filtros  = static::obtenerFiltrosUsuario($usuarioId, $filtros);
        $ordenes  = array_merge($ordenes, ['updated_at' => 'desc' ]);
        
        return static::listarYObtenerFiltros($usuarioId, $page, $limit, $filtros, $ordenes, $opciones);
    }
    
    static public function obtenerFiltrosUsuario(int $usuarioId, array $filtros = []): array {
        $filtros['vigente'] = true;
        $filtros['favoritos_usuario_id'] = $usuarioId;

        return static::filtrarSegunSubastaDisponible($filtros);
    }
}
