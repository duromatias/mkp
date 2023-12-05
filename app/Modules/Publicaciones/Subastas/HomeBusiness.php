<?php

namespace App\Modules\Publicaciones\Subastas;

use App\Modules\Publicaciones\Publicacion;
use App\Modules\Subastas\Business\SubastasBusiness;
use App\Modules\Users\Models\User;

class HomeBusiness extends EnSubastaBusiness
{

	public static function listar(int $usuarioId, int $page, int $limit, array $filtros, array $ordenes, array $opciones) {
        $ordenes = array_merge($ordenes, ['updated_at' => 'desc' ]);
        $usuario = User::getById($usuarioId);

        $filtros  = static::agregarFiltrosUsuario($usuario, $filtros);
        $opciones = array_merge($opciones, static::getOpciones($usuarioId));
        
		return [
			'listado' => Publicacion::listar($page, $limit, $filtros, $ordenes, $opciones),
			'filtrosDisponibles' => Publicacion::obtenerFiltrosDisponibles($filtros),
		] ;
	}
    
    static public function agregarFiltrosUsuario(User $usuario, array $filtros = []): array {
		$filtros['vigente'   ] = 1;
        $filtros['en_subasta'] = 1;
        
		$subastaDisponible = SubastasBusiness::obtenerDisponible();

		if (
            $subastaDisponible && 
            $subastaDisponible->abiertaRecepcionOfertas() && 
            !$usuario->esAdministrador()
        ) {
			$filtros['subasta_id'] = $subastaDisponible->id;
		}
        
        return $filtros;
    }
    
    static public function obtenerCantidadPublicaciones(User $usuario, array $filtros = []) {
        $filtros = static::agregarFiltrosUsuario($usuario, $filtros);
        $ordenes = ['updated_at' => 'DESC'];
        return Publicacion::contar($filtros, $ordenes);
    }
}