<?php

namespace App\Modules\Subastas;

use App\Http\Controllers\Controller;
use App\Modules\Subastas\Business\SubastasBusiness;
use App\Modules\Subastas\Dtos\CreateSubastaDto;
use App\Modules\Subastas\Dtos\UpdateSubastaDto;
use App\Modules\Subastas\Requests\CreateSubastaRequest;
use App\Modules\Subastas\Requests\UpdateSubastaRequest;
use App\Modules\Subastas\Resources\SubastaResource;
use Illuminate\Http\Request;

class SubastasController extends Controller
{
	public function create(CreateSubastaRequest $request) {
		$this->validarAdministrador();

		$subastaDto = CreateSubastaDto::fromRequest($request);

		$subasta = SubastasBusiness::create($subastaDto);

		return new SubastaResource($subasta);
	}

	public function index(Request $request) {
		$subastas = Subasta::listar(
			$request->query('page', 1),
			$request->query('limit', 0),
			$request->query('filtros', []),
			$request->query('ordenes', [])
		);

		return SubastaResource::collection($subastas);
	}
    
    public function show(int $id) {
        $subasta = Subasta::getById($id);
        return new SubastaResource($subasta);
    }

	public function update(int $id, UpdateSubastaRequest $request) {
		$this->validarAdministrador();

		$subastaDto = UpdateSubastaDto::fromRequest($request);

		$subasta = SubastasBusiness::update($id, $subastaDto);

		return new SubastaResource($subasta);

	}

	public function cancelar($id) {
		$this->validarAdministrador();

		$subasta = SubastasBusiness::cancelar($id);

		return new SubastaResource($subasta);
	}
    
    public function obtenerDisponible() {
        $subasta = SubastasBusiness::obtenerDisponible();

        if (!empty($subasta)) {
			$subasta->load('publicaciones');

			return new SubastaResource($subasta);
        }
        
        return $this->json(null);
    }
}