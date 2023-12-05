<?php

namespace App\Modules\Publicaciones\Home;

use App\Base\BusinessException;
use App\Base\Repository\NotFound;
use App\Modules\Publicaciones\Publicacion;
use App\Modules\Publicaciones\PublicacionesBusiness;
use App\Modules\Publicaciones\Subastas\EnSubastaBusiness;
use App\Modules\Users\Models\Rol;
use App\Modules\Users\Models\User;

class HomeBusiness extends PublicacionesBusiness
{

    static public function listar(?int $usuarioId, int $offset = 0, int $limit = 10, array $filtros = [], array $ordenes = [], array $opciones = []) {
        $filtros = static::agregarFiltrosUsuario($usuarioId, $filtros);
        $ordenes = array_merge($ordenes, ['updated_at' => 'desc' ]);
		$opciones = array_merge_recursive($opciones, static::getOpciones($usuarioId));

        $data = [
            'listado'            => Publicacion::listar($offset, $limit, $filtros, $ordenes, $opciones),
            'filtrosDisponibles' => Publicacion::obtenerFiltrosDisponibles($filtros),
        ];

		if ($usuarioId == null) {
            foreach($data['listado'] as &$row) {
                $row->es_oportunidad = 0;
            }
        }

        return $data;
    }


    static public function obtenerUna(?int $usuarioId, int $id): Publicacion {
        try {
            $filtros = static::agregarFiltrosUsuario($usuarioId, [], false);
            $publicacion = Publicacion::getById($id, $filtros, static::getOpciones($usuarioId));
            return $publicacion;
        } catch (NotFound $e) {

            // No la encuentra con los filtros por usuario,
            // entonces verifica si realmente está

            // Para saber si existe, o no tiene permisos,
            // la solicitamos de nuevo pero sin filtros.
            //
            // Si no existe, el sistema lanzará un not found.
            $publicacion = Publicacion::getById($id, [], static::getOpciones($usuarioId));

            // Si exsiste, verifica que pertenezca al usuario
            // y la muestra
            if ($publicacion && $publicacion->usuario_id === $usuarioId) {
                return $publicacion;
            }
            
            if ($publicacion->subasta_id && !static::esAgencia($usuarioId)) {
                throw new AccesoNoPermitido('No puede acceder a la publicación');
            }

            throw new BusinessException('La publicación no se encuentra disponible');
        }
    }

    static private function agregarFiltrosUsuario(?int $usuarioId, array $filtros = [], bool $verificarSubastas = true): array {
        $filtros['vigente'] = 1;
        
        if ($usuarioId === null) {
            // Revisar esto con Vero
            // $filtros['es_oportunidad'] = 0;
            $filtros['usuario_rol_id'] = [
                Rol::USUARIO_AGENCIA,
            ];
        } else {   
            $usuario = User::getById($usuarioId);
            if ($usuario->esParticular()) {
                $filtros['usuario_rol_id'] = [
                    Rol::USUARIO_AGENCIA,
                ];
            }
        }
        
        if ($usuarioId === null || User::getById($usuarioId)->esParticular()) {
            $filtros['sin_subasta'] = 1;
        } else {
            
            if ($verificarSubastas) {
                $filtros = EnSubastaBusiness::filtrarSegunSubastaDisponible($filtros);
            }
        }


        return $filtros;
    }


	public static function contarClick(int $id) {
    	$publicacion = Publicacion::getById($id);

    	$publicacion->contarClick();
	}
    
    private static function esAgencia(?int $usuarioId): bool {
        if ($usuarioId === null) {
            return false;
        }
        
        return User::getById($usuarioId)->esAgencia();
    }
}
