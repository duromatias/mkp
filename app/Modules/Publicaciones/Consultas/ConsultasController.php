<?php

namespace App\Modules\Publicaciones\Consultas;

use App\Http\Controllers\Controller;
use App\Modules\Publicaciones\Consultas\Requests\CrearConsultaRequest;
use App\Modules\Publicaciones\Consultas\Requests\ResponderConsultaRequest;
use Illuminate\Http\Request;

class ConsultasController extends Controller
{
	public function index(Request $request) {
		if (!$this->esParticular() && !$this->esAgencia()) {
			abort(403);
		}

		$consultas = ConsultasBusiness::listar(
			$this->getUserId(),
			$request->query('page', 1),
			$request->query('limit', 0),
			$request->query('filtros', []),
			$request->query('ordenes', []),
			$request->query('opciones', []),
		);

		return ConsultaResource::collection($consultas);
	}

	public function store(CrearConsultaRequest $request) {
		$publicacionId   = $request->get('publicacion_id');
        $usuarioOrigenId = $this->getUserIdNull();
        $nombre          = $request->get('nombre');
        $email           = $request->get('email');
        $telefono        = $request->get('telefono');
        $texto           = $request->get('texto');

        $consulta = ConsultasBusiness::crearConsulta($publicacionId, $usuarioOrigenId, $nombre, $email, $telefono, $texto);

		return new ConsultaResource($consulta);
	}

	public function getOne(int $consultaId, Request $request) {
		$userId = $this->getUserId();

		$consulta = ConsultasBusiness::getOne(
			$userId,
			$consultaId,
			$request->query('opciones', [])
		);

		return new ConsultaResource($consulta);
	}

	public function resolver(int $consultaId) {
		$userId = $this->getUserId();

		$consulta = ConsultasBusiness::resolver($userId, $consultaId);

		return new ConsultaResource($consulta);
	}
    
    public function responder(int $consultaId, ResponderConsultaRequest $request) {
		$userId   = $this->getUserId();        
        $texto    = $request->get('texto', '');
        $estado   = $request->get('estado');
        $consulta = ConsultasBusiness::responder($userId, $consultaId, "{$texto}", $estado);

		return new ConsultaResource($consulta);
    }
}