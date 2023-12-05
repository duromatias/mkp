<?php

namespace App\Modules\Publicaciones\Subastas;

use App\Modules\Publicaciones\Publicacion;
use App\Modules\Shared\Exceptions\BusinessException;
use App\Modules\Users\Models\User;

class MisPublicacionesBusiness extends EnSubastaBusiness
{
    
	public static function listar(?int $usuarioId, int $page, int $limit, array $filtros, array $ordenes, array $opciones) {
        
		$ordenes = array_merge($ordenes, ['updated_at' => 'DESC' ]);
        $filtros['vigente'    ] = 1;
        $filtros['en_subasta' ] = 1;
        $filtros['business_id'] = static::obtenerBusinessId($usuarioId);
        
        $opciones = array_merge($opciones, static::getOpciones($usuarioId));

		return [
			'listado' => Publicacion::listar($page, $limit, $filtros, $ordenes, $opciones),
			'filtrosDisponibles' => Publicacion::obtenerFiltrosDisponibles($filtros),
		] ;
	}
    
    public static function obtenerBusinessId(int $usuarioId) {
        
        $user = User::getById($usuarioId);
        $user->load('onboardingUser.business');
        $businessId = $user->onboardingUser->business->id;

        if (empty($businessId)) {
            throw new BusinessException('Agencia incompleta');
        }
        
        return $businessId;        
    }
}