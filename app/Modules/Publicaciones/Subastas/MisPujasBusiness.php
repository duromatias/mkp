<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Modules\Publicaciones\Subastas;

/**
 * Description of MisPujas
 *
 * @author kodear
 */
class MisPujasBusiness extends EnSubastaBusiness {
    
    static public function listar(int $usuarioId, int $page = 1, int $limit = 10, array $filtros = [], array $ordenes = [], array $opciones = []): array {
        $filtros['ofertas_usuario_id'] = $usuarioId;
        $ordenes = array_merge($ordenes, ['updated_at' => 'DESC' ]);
        return static::listarYObtenerFiltros($usuarioId, $page, $limit, $filtros, $ordenes, $opciones);
    }
}
