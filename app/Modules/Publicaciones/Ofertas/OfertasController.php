<?php

namespace App\Modules\Publicaciones\Ofertas;

use App\Http\Controllers\Controller;
use App\Modules\Publicaciones\Ofertas\Businesses\OfertaBusiness;
use App\Modules\Publicaciones\Ofertas\Dtos\CreateOfertaDto;
use App\Modules\Publicaciones\Ofertas\Requests\CreateOfertaRequest;
use App\Modules\Publicaciones\Ofertas\Resources\OfertaResource;
use Illuminate\Http\Request;

class OfertasController extends Controller
{
	public function index(int $publicacionId, Request $request) {
		$this->validarAgencia();

		$filtros = array_merge(
			$request->input('filtros', []),
			[ 'publicacion_id' => $publicacionId ]
		);

		$ofertas = OfertaBusiness::listar(
			1,
			0,
			$filtros,
			$request->input('ordenes', []),
			$request->input('opciones', [])
		);

		return OfertaResource::collection($ofertas);
	}

	public function create(int $publicacionId, CreateOfertaRequest $request) {
		$this->validarAgencia();

		$userId = $this->getUserId();
		$createOfertaDto = CreateOfertaDto::fromRequest($request);

		$createOfertaDto->publicacion_id = $publicacionId;
		$createOfertaDto->usuario_id = $userId;

		$oferta = OfertaBusiness::create($createOfertaDto);

		return new OfertaResource($oferta);
	}
}