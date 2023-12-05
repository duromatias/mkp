<?php

namespace App\Modules\Publicaciones\Consultas;

use App\Modules\Publicaciones\Consultas\Emails\ConsultaCreada;
use App\Modules\Publicaciones\Publicacion;
use App\Modules\Users\Models\User;
use Illuminate\Support\Facades\Mail;

class ConsultasBusiness
{
	public static function listar(int $userId, int $page = 1, int $limit = 0, array $filtros = [], array $ordenes = [], array $opciones = []) {
		$filtros = array_merge($filtros, ConsultasBusiness::getFiltrosByUserId($userId));

		return Consulta::listar(
			$page,
			$limit,
			$filtros,
			$ordenes,
			$opciones
		);
	}

	public static function crearConsulta(int $publicacionId, ?int $usuarioOrigenId, string $nombre, string $email, int $telefono, string $texto) {
        $publicacion = Publicacion::getById($publicacionId);
        $usuarioDestinoId = $publicacion->usuario->id;
                
		$consulta = Consulta::crearConsulta($publicacionId, $usuarioOrigenId, $usuarioDestinoId, $nombre, $email, $telefono, $texto);
		$userDestino = $consulta->usuarioDestino;

		Mail::to($userDestino->email)->send(new ConsultaCreada($publicacion, $consulta));

		return $consulta;
	}


	public static function resolver(int $userId, int $consultaId) {
		$filtros = ConsultasBusiness::getFiltrosByUserId($userId);

		$consulta = Consulta::getById($consultaId, $filtros)->resolver();

		return $consulta;
	}
    
    public static function responder(int $userId, int $consultaId, string $texto, string $estado): Consulta {
        $consulta = static::getOne($userId, $consultaId);
        
        $consulta->guardarRespuesta($texto);
        
        if ($estado === Consulta::ESTADO_RESUELTA) {
            $consulta->resolver();
        } else {
            $consulta->marcarPendiente();
        }
        
        return $consulta;
    }


	public static function getOne(int $userId, int $consultaId, array $opciones = []) {
		$filtros = ConsultasBusiness::getFiltrosByUserId($userId);

		return Consulta::getById($consultaId, $filtros, $opciones);
	}


	private static function getFiltrosByUserId(int $userId) {
		$user = User::getById($userId);

		if ($user->esParticular()) {
			return ['usuario_destino_id' => $user->id];
		}
		else if ($user->esAgencia()) {
			return ['business_id' => $user->onboardingUser->business_id];
		}

		return [];
	}

	public static function contarPendientes($usuarioDestinoId) {
		return Consulta::contar([
            'usuario_destino_id' => $usuarioDestinoId,
            'estado'             => Consulta::ESTADO_PENDIENTE,
        ]);
	}
}